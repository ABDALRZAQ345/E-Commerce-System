<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubOrderController extends Controller
{
    //

    public function index(Request $request, User $user, Order $order): JsonResponse
    {

        $order = $user->orders()->findOrFail($order->id);

        $validStatuses = OrderStatusEnum::getAllStatus();

        $subOrders = $order->subOrders();

        if ($request->filled('status') && in_array($request->status, $validStatuses)) {
            $subOrders->where('status', $request->status);
        }

        $paginatedSubOrders = $subOrders->paginate(20);

        return response()->json([
            'status' => true,
            'message' => 'orders retrieved successfully',
            'orders' => $paginatedSubOrders,
        ]);
    }

    public function show(User $user, Order $order, SubOrder $subOrder): JsonResponse
    {
        $order = $user->orders()->findOrFail($order->id);
        $sub_order = $order->subOrders()->findOrFail($subOrder->id);

        return response()->json([
            'status' => true,
            'message' => 'order retrieved successfully',
            'sub_order' => $sub_order,
        ]);
    }
}
