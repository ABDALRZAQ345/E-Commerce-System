<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        \Artisan::queue('update-mili-search');
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        \Artisan::queue('update-mili-search');
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        \Artisan::queue('update-mili-search');
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        \Artisan::queue('update-mili-search');
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
