<?php

namespace App\Http\Controllers\Store;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function index(Request $request, Store $store): JsonResponse
    {

        $subOrders = $store->subOrders();

        $validStatuses = OrderStatusEnum::getAllStatus();
        if ($request->filled('status') && in_array($request->status, $validStatuses)) {
            $subOrders->where('status', $request->status);
        }
        if ($request->filled('date') && in_array($request->date, ['asc', 'desc'])) {
            $subOrders->OrderBy('created_at', $request->date);
        }

        $subOrders = $subOrders->paginate(20);

        return response()->json($subOrders);

    }

    public function update(Request $request, Store $store, Order $order): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', OrderStatusEnum::getAllStatus())],
        ]);
        $order = $store->subOrders()->findOrFail($order->id);
        $current_status = -1;
        $nex_status = -1;
        $index = 0;
        foreach (OrderStatusEnum::getAllStatus() as $status) {
            if ($request->input('status') == $status) {
                $nex_status = $index;
            }
            if ($order->status == $status) {
                $current_status = $index;
            }
            $index++;
        }
        if ($current_status >= $nex_status) {
            return response()->json([
                'message' => 'Invalid status transition. Status cannot move backward.',
            ], 400); // Bad Request
        }
        $order->status = $request->input('status');
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully.',
            'order' => $order,
        ]);
    }
}
