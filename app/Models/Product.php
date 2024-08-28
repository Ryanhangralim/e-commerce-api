<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    // Define the relationship with the Cart model
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    // Relationship with review model
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    public function sold()
    {
        return $this->orders()
                    ->whereHas('transaction', function ($query) {
                        $query->where('status', 'completed');
                    })
                    ->sum('quantity');
    }

    //scope query
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where(function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }
    public function scopeFilterBusiness($query, array $filters){
        $query->when($filters['business-search'] ?? false, function($query, $search) {
            return $query->where(function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }
}
