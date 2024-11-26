<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateMiliSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-mili-search';

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
        $this->call('scout:import', ['model' => 'App\Models\Product']);
        $this->call('scout:import', ['model' => 'App\Models\Store']);

    }
}
