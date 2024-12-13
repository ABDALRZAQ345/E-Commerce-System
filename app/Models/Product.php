<?php

namespace App\Models;

use App\Services\InterestService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use Searchable;

    protected $guarded = ['id'];

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'store_id' => $this->store_id,
        ];
    }

    public function resolveUser(): User|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        // Return the user from Sanctum's guard explicitly
        return Auth::user();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }



    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'object');
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'object');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeFilter($query, $filter): void
    {

        if ($filter === 'best_selling') {
            $query->orderBy('sales', 'desc');
        }

        // latest products
        elseif ($filter === 'latest') {
            $query->orderBy('created_at', 'desc');
        }

        // filter as top rated products
        elseif ($filter === 'top_rated') {
            $query->orderBy('rate', 'desc');
        }
        else if(Category::where('name', $filter)->exists()){
            $query->whereRelation('category', 'name', $filter);
        }
        elseif ($filter === 'recommended') {

            $interestService=new InterestService();
            $ids = collect($interestService->recommendProducts(Auth::id()))->pluck('id');
            $query->wherein('id',$ids);

        }

    }
}
