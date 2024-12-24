<?php

namespace Database\Seeders;

use App\Models\SubOrder;
use Illuminate\Database\Seeder;

class SubOrderSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Order::all()->each(function ($order) {
            SubOrder::factory()->count(rand(1, 3))->create(['order_id' => $order->id]);
        });
    }
}
