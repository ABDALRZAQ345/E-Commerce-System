<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['object_id', 'object_type'];

    public function object(): MorphTo
    {
        return $this->morphTo();
    }
}
