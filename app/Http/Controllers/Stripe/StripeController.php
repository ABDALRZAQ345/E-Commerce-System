<?php

namespace App\Http\Controllers\Stripe;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreOrderRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function createPaymentIntent(StoreOrderRequest $request): JsonResponse
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

            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'user_id' => Auth::id(),
                    'products' => json_encode($products),
                    'location_id' => $validated['location_id'],
                ],
            ]);

            return response()->json([
                'status' => true,
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException('Some Thing Went Wrong');
        }
    }
}
