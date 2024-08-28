<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Business extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'business_id');
    }

    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Product::class, 'business_id', 'product_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'business_id');
    }

    public function getPendingTransactionsAttribute()
    {
        return $this->transactions()
                    ->where('status', 'pending')
                    ->count();
    }

    public function getTotalEarningsAttribute()
    {
        return $this->products()
                    ->whereHas('orders.transaction', function ($query) {
                        $query->where('status', 'completed');
                    })
                    ->with('orders')
                    ->get()
                    ->pluck('orders')
                    ->flatten()
                    ->sum('total_price');
    }

    public function getTotalTransactionsAttribute()
    {
        return $this->transactions()
                    ->count();
    }

    public function getCurrentMonthEarningsAttribute()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return $this->products()
                    ->whereHas('orders.transaction', function ($query) use ($currentMonth, $currentYear) {
                        $query->where('status', 'completed')
                              ->whereMonth('created_at', $currentMonth)
                              ->whereYear('created_at', $currentYear);
                    })
                    ->with('orders')
                    ->get()
                    ->pluck('orders')
                    ->flatten()
                    ->sum('total_price');
    }

    public function topSoldProducts($limit = 5)
    {
        return $this->products()
                    ->withSum(['orders as sold_quantity' => function ($query) {
                        $query->whereHas('transaction', function ($query) {
                            $query->where('status', 'completed');
                        });
                    }], 'quantity')
                    ->orderBy('sold_quantity', 'desc')
                    ->take($limit)
                    ->get();
    }
}
