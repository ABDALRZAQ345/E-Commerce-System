<?php

namespace App\Observers;

use App\Models\Store;

class StoreObserver
{
    /**
     * Handle the Store "created" event.
     */
    public function created(Store $store): void
    {
        \Artisan::queue('update-mili-search');
    }

    /**
     * Handle the Store "updated" event.
     */
    public function updated(Store $store): void
    {
        \Artisan::queue('update-mili-search');

    }

    /**
     * Handle the Store "deleted" event.
     */
    public function deleted(Store $store): void
    {
        \Artisan::queue('update-mili-search');
    }

    /**
     * Handle the Store "restored" event.
     */
    public function restored(Store $store): void
    {
        \Artisan::queue('update-mili-search');
    }

    /**
     * Handle the Store "force deleted" event.
     */
    public function forceDeleted(Store $store): void
    {
        //
    }
}
