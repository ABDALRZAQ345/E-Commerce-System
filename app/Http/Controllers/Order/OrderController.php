<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function index(User $user): JsonResponse
    {

        $orders = $user->orders()->paginate(20);
        return response()->json($orders);
    }

    public function show(User $user, Order $order): JsonResponse
    {

        $order=$user->orders()->findOrFail($order->id);
        return response()->json([
            'order'=>$order,
        ]);

    }

    /**
     * @throws \Throwable
     */
    public function store(StoreOrderRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();
        $products = $validated['products'];
        DB::beginTransaction();

        try {
            // Create the main order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => 0, // Will calculate later
            ]);

            $suborders = [];
            $totalPrice = 0;

            foreach ($products as $productData) {
                $product = Product::find($productData['id']);
                $storeId = $product->store_id; // Assuming each product belongs to a store

                // Find or create suborder for the store
                if (!isset($suborders[$storeId])) {
                    $suborders[$storeId] = Suborder::create([
                        'order_id' => $order->id,
                        'store_id' => $storeId,
                        'total' => 0, // Will calculate later
                    ]);
                }

                // Add product to suborder
                $suborder = $suborders[$storeId];
                if($productData['quantity']> $product->quantity){
                    DB::rollBack();
                    return response()->json([
                       'message' => 'product with id '.$productData['id'].' is out of stock',
                    ],400);
                }
                $suborder->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'price' => $product->price,
                    'total' => $product->price * $productData['quantity'],
                ]);
                $product->update([
                    'sales' => $product->sales + $productData['quantity'],
                    'quantity' => $product->quantity - $productData['quantity'],
                ]);
                $productPrice = $product->price * $productData['quantity'];
                $suborder->total += $productPrice;
                $suborder->save();

                $totalPrice += $productPrice;
            }

            // Update the main order price
            $order->total = $totalPrice;
            $order->save();

            DB::commit();
            // Todo Create word for فاتورة
            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
                'sub_orders' => $order->subOrders()->with('items')->get(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create order', 'error' => $e->getMessage()], 500);
        }


    }

}
