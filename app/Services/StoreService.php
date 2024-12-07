<?php

namespace App\Services;

use App\Models\Store;

class StoreService
{
    public function get_the_user_info_for_store(Store $store, \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null $user): void
    {
        $your_rate = $store->rates()->where('user_id', $user->id)->first();
        $your_rate = $your_rate != null ? $your_rate->rate : 0;
        $your_favourite = $user->favouriteStores()->find($store->id);
        $store->your_rate = $your_rate;
        $store->your_favourite = $your_favourite != null;
    }
}
