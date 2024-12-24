<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        \App\Models\SubOrder::all()->each(function ($subOrder) {
            OrderItem::factory()->count(rand(1, 5))->create(['sub_order_id' => $subOrder->id]);
        });
    }
}
