<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles,Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function locations(): MorphMany
    {
        return $this->morphMany(Location::class, 'object');
    }

    public function favouriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favourite_products', 'user_id', 'product_id');
    }

    public function favouriteStores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'favourite_stores', 'user_id', 'store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'user_category_interests')
            ->withPivot('interest_level');
    }

    public function subOrders(): HasManyThrough
    {
        return $this->hasManyThrough(SubOrder::class, Order::class);
    }
}
