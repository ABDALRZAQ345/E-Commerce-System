<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['pivot'];

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'category_store');
    }
}
