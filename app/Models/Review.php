<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['object_id', 'object_type'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function object(): MorphTo
    {
        return $this->morphTo();
    }
}
