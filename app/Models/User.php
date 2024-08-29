<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Cart;
use App\Models\Role;
use App\Models\Review;
use App\Models\Product;
use App\Models\Business;
use App\Models\Transaction;
use App\Models\SellerApplication;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [
        'id'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function sellerApplication(): HasMany
    {
        return $this->hasMany(SellerApplication::class, 'user_id');
    }

    public function business(): HasOne
    {
        return $this->hasOne(Business::class, 'user_id');
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, Business::class, 'user_id', 'business_id');
    }

    // Define the relationship with the Cart model
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    // Relationship with review model
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    // Relationship with transaction
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}
