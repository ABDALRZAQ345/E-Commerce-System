<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles,Notifiable, HasRoles;

    protected  $guarded=['id'];
    protected $fillable = ['first_name', 'last_name', 'phone_number', 'password', 'photo'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function locations(): MorphMany
    {
        return $this->morphMany(Location::class,'object');
    }
    public function favoriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'favorite_products','user_id','product_id');
    }
    public function favoriteStores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class,'favorite_stores','user_id','store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    public function promotions(): HasOne
    {
        return $this->hasOne(Promotion::class);
    }


}
