<?php

namespace App\Http\Controllers;

use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // التحقق من توقيع الطلب
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        $signature = $request->header('Stripe-Signature');
        try {
            // تحقق من التوقيع وصحة الطلب
            $event = Webhook::constructEvent(
                $request->getContent(),
                $signature,
                $endpointSecret
            );

            // التعامل مع أنواع الأحداث
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object; // تفاصيل الدفع
                    Log::info('Payment abd  succeeded:', ['id' => $paymentIntent->id]);

                    // هنا يتم إنشاء الطلب بعد نجاح الدفع
                    $this->createOrderAfterPayment($paymentIntent);

                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object; // تفاصيل الدفع
                    Log::warning('Payment failed:', ['id' => $paymentIntent->id]);
                    break;

                default:
                    Log::info('Unhandled event type:', ['type' => $event->type]);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed:', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Webhook handling error:', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Webhook handling error'], 500);
        }
    }

    private function createOrderAfterPayment($paymentIntent)
    {

        $userId = $paymentIntent->metadata->user_id ?? null;
        $products = json_decode($paymentIntent->metadata->products, true) ?? [];
        $locationId = $paymentIntent->metadata->location_id ?? null;
        $orderService = new OrderService;
        // استدعاء خدمة الطلب
        $orderService->createOrder($userId, $products, $locationId);
    }
}
