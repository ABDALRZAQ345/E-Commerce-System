<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreOrderRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{

    public function createPaymentIntent(StoreOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $products = $validated['products'];
        try {

            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $totalAmount = 0;

            foreach ($products as $product) {
                $totalAmount += Product::find($product['id'])->price * $product['quantity'];
            }
            $totalAmount *= 100;

            // إنشاء PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount, // المبلغ بالـ "سنت"
                'currency' => 'usd', // العملة
                'payment_method_types' => ['card'], // نوع طريقة الدفع
            ]);

            // إرجاع Client Secret
            return response()->json([
                'status' => true,
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            // معالجة الخطأ
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
