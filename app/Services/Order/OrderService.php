<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Product;
use App\Models\Suborder;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @throws \Throwable
     */
    public function createOrder(int $userId, array $products): Order
    {


        try {
            DB::beginTransaction();
            // Create the main order
            $order = Order::create([
                'user_id' => $userId,
                'total' => 0, // Will calculate later
            ]);

            $suborders = [];
            $totalPrice = 0;

            foreach ($products as $productData) {
                $product = Product::findOrFail($productData['id']);
                $this->validateStock($product, $productData['quantity']);

                $storeId = $product->store_id;

                // Find or create suborder for the store
                $suborders[$storeId] = $suborders[$storeId] ?? Suborder::create([
                    'order_id' => $order->id,
                    'store_id' => $storeId,
                    'total' => 0,
                ]);

                $suborder = $suborders[$storeId];

                // Add product to suborder
                $this->addProductToSuborder($suborder, $product, $productData);

                $productPrice = $product->price * $productData['quantity'];
                $suborder->total += $productPrice;
                $suborder->save();

                $totalPrice += $productPrice;
            }

            // Update the main order price
            $order->total = $totalPrice;
            $order->save();

            DB::commit();
            // Todo Create Invoice

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    protected function validateStock(Product $product, int $quantity): void
    {
        if ($quantity > $product->quantity) {
            throw new \Exception("Product with id {$product->id} is out of stock");
        }
    }

    protected function addProductToSuborder(Suborder $suborder, Product $product, array $productData): void
    {
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
    }
}
