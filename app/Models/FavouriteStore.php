<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavouriteStore extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'store_id'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
