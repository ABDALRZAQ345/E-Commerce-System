<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Meilisearch\Client;

class RunServerCommand extends Command
{
    protected $signature = 'run-server';

    protected $description = 'command that run the server and MeiliSearch ';
    public function handle(): void
    {

        $client = new Client('http://127.0.0.1:7700'); // Replace with your Meilisearch server URL
        $index = $client->index('products'); // Ensure 'products' is your Meilisearch index

        // Update filterable attributes
        $this->info('Updating filterable attributes...');
        $index->updateFilterableAttributes(['id', 'store_id']);
        $this->info('Filterable attributes updated.');

        // Re-import the products into Meilisearch
        $this->info('Re-importing products...');
        $this->call('scout:import', ['model' => 'App\Models\Product']);
        $this->call('scout:import', ['model' => 'App\Models\Store']);

        $this->info('Products re-imported.');

        // Run php artisan serve (optional)
        $this->info('Starting Laravel development server...');
        exec('php artisan serve');
    }
}
