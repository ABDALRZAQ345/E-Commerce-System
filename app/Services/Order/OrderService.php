<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Product;
use App\Models\SubOrder;
use App\Services\InterestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @throws \Throwable
     */
    public function createOrder(int $userId, array $products, $location_id): Order
    {

        try {
            DB::beginTransaction();
            // Create the main order
            $order = Order::create([
                'user_id' => $userId,
                'total' => 0, // Will calculate later
                'location_id' => $location_id,
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
                    'location_id' => $location_id,
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
            $this->EditInterests($products);
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
            'name' => Product::find($product->id)->name,
            'quantity' => $productData['quantity'],
            'price' => $product->price,
            'total' => $product->price * $productData['quantity'],
        ]);

        $product->update([
            'sales' => $product->sales + $productData['quantity'],
            'quantity' => $product->quantity - $productData['quantity'],
        ]);
    }

    public function EditInterests($products)
    {
        $interestService = new InterestService;
        foreach ($products as $product) {
            $product = Product::findOrFail($product['id']);
            $category = $product->category_id;
            if (! $interestService->CheckUserInterest(Auth::id(), $category)) {
                $interestService->CreateUserInterest(Auth::id(), $category);
            }
            $interestService->increaseInterestLevel(Auth::id(), $category, 1);
        }
    }
}
