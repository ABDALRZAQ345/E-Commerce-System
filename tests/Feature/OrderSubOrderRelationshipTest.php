<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\SubOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderSubOrderRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_has_many_sub_orders()
    {
        $order = Order::factory()->create();
        $subOrders = SubOrder::factory(2)->create(['order_id' => $order->id]);

        $this->assertCount(2, $order->subOrders);
        $this->assertTrue($order->subOrders->contains($subOrders->first()));
    }

    public function test_sub_order_belongs_to_order()
    {
        $order = Order::factory()->create();
        $subOrder = SubOrder::factory()->create(['order_id' => $order->id]);

        $this->assertTrue($subOrder->order->is($order));
    }
}
