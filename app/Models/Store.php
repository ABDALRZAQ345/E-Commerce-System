<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_store', 'store_id', 'category_id');
    }

    public function locations(): MorphMany
    {
        return $this->morphMany(Location::class, 'object');
    }

    public function subOrders(): HasMany
    {
        return $this->hasMany(SubOrder::class);
    }

    public function rates(): MorphMany
    {
        return $this->morphMany(Rate::class, 'object');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'object');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
