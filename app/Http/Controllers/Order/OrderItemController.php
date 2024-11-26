<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\User;

class OrderItemController extends Controller
{
    public function index(User $user, Order $order,SubOrder $subOrder): \Illuminate\Http\JsonResponse
    {
        $order=$user->orders()->findOrFail($order->id);
        $subOrder=$order->subOrders()->findOrFail($subOrder->id);
        $items=$subOrder->items()->paginate(20);
        return response()->json($items);

    }
}
