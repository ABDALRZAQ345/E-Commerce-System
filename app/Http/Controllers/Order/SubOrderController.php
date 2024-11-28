<?php

namespace App\Http\Controllers\Order;

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
        // Ensure the order belongs to the given user
        $order = $user->orders()->findOrFail($order->id);

        // Valid statuses
        $validStatuses = Order::$validStatuses;



        // Get sub-orders query
        $subOrders = $order->subOrders(); // Ensure the `subOrders()` relationship exists in the Order model

        // Filter by status
        if ($request->filled('status') && in_array($request->status, $validStatuses) ) {
            $subOrders->where('status', $request->status);
        }




        // Paginate results
        $paginatedSubOrders = $subOrders->paginate(20);

        // Return paginated sub-orders as JSON
        return response()->json($paginatedSubOrders);
    }

    public function show(User $user, Order $order, SubOrder $subOrder): JsonResponse
    {
        $order = $user->orders()->findOrFail($order->id);
        $sub_order = $order->subOrders()->findOrFail($subOrder->id);

        return response()->json([
            'sub_order' => $sub_order,
        ]);
    }
}
