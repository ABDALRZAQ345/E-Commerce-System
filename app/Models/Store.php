<?php

namespace App\Models;

use App\Services\Interest\InterestService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

class Store extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use Searchable;
    use SoftDeletes;

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'pivot'];

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

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'object');
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

    public function scopeFilter($query, $filter): void
    {

        if ($filter === 'latest') {
            $query->orderBy('created_at', 'desc');
        }
        // filter as top rated stores
        elseif ($filter === 'top_rated') {
            $query->orderBy('rate', 'desc');
        } elseif ($filter === 'recommended') {
            $interestService = new InterestService;
            $ids = collect($interestService->recommendStores(Auth::id()))->pluck('id');
            $query->wherein('id', $ids);
        } elseif (Category::where('name', $filter)->exists()) {
            $query->whereHas('categories', function ($query) use ($filter) {
                $query->where('name', $filter);
            });
        }

    }
}
