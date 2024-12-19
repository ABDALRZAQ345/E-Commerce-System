<?php

namespace App\Services;

use App\Models\Store;

class StoreService
{
    public function get_the_user_info_for_store(Store $store, \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null $user): void
    {
        $your_review = $store->reviews()->where('user_id', $user->id)->first();
        $your_favourite = $user->favouriteStores()->find($store->id);
        $store->your_review = $your_review;
        $store->your_favourite = $your_favourite != null;
    }
}
