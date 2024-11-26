<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SubOrderController extends Controller
{
    //
    public function index(User $user, Order $order): JsonResponse
    {
        $order=$user->orders()->findOrFail($order->id);
        $sub_orders=$order->subOrders()->paginate(20);
        return response()->json($sub_orders);
    }

    public function show(User $user, Order $order, SubOrder $subOrder): JsonResponse
    {
        $order=$user->orders()->findOrFail($order->id);
        $sub_order=$order->subOrders()->findOrFail($subOrder->id);
        return response()->json([
            'sub_order'=>$sub_order,
        ]);
    }

}
