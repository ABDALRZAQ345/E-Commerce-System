<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateMiliSearch extends Command
{
    protected $signature = 'update-meili-search';

    protected $description = 'Update meilisearch data';

    public function handle(): void
    {
        $this->call('scout:import', ['model' => 'App\Models\Product']);
        $this->call('scout:import', ['model' => 'App\Models\Store']);

    }
}
