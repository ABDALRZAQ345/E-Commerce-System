<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Meilisearch\Client;

class RunServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Connect to Meilisearch
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
