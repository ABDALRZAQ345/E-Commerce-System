<?php

namespace App\Services;

use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function createPaymentIntent($amount, $currency, $paymentMethodId)
    {
        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'payment_method' => $paymentMethodId,
            'confirmation_method' => 'automatic',
            'confirm' => true,
        ]);
    }
}
