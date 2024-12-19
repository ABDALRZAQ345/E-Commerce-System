<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubOrder;

class SubOrderSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Order::all()->each(function ($order) {
            SubOrder::factory()->count(rand(1, 3))->create(['order_id' => $order->id]);
        });
    }
}
