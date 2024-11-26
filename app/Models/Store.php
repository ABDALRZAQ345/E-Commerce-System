<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Scout\Searchable;
class Store extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use Searchable;
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'user_id'];

    public function resolveUser(): User|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        // Return the user from Sanctum's guard explicitly
        return Auth::user();
    }

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
