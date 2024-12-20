<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        \App\Models\SubOrder::all()->each(function ($subOrder) {
            OrderItem::factory()->count(rand(1, 5))->create(['sub_order_id' => $subOrder->id]);
        });
    }
}
