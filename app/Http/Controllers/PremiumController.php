<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Stripe\StripeClient;

class PremiumController extends Controller
{
    public function index(): View
    {
        return view('premium.index');
    }

    public function checkout(): View
    {
        return view('premium.checkout');
    }

    public function createCheckoutSession(): RedirectResponse
    {
        $user = auth()->user();
        if ($user->hasPremiumAccess()) {
            return redirect()->route('premium.success');
        }

        $secret = config('services.stripe.secret');
        $priceId = config('services.stripe.price_id');

        if (!$secret || !$priceId) {
            abort(500, 'Stripe price is not configured.');
        }

        $stripe = new StripeClient($secret);

        $successUrl = config('services.stripe.success_url')
            ?: route('premium.success') . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = config('services.stripe.cancel_url') ?? route('premium.cancel');

        $sessionParams = [
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => (string) $user->id,
            'metadata' => [
                'user_id' => (string) $user->id,
                'email' => $user->email,
            ],
            'allow_promotion_codes' => true,
        ];

        if ($user->stripe_customer_id) {
            $sessionParams['customer'] = $user->stripe_customer_id;
        } else {
            $sessionParams['customer_email'] = $user->email;
        }

        $session = $stripe->checkout->sessions->create($sessionParams);

        return redirect()->away($session->url);
    }

    public function success(): View
    {
        return view('premium.success');
    }

    public function cancel(): View
    {
        return view('premium.cancel');
    }
}
