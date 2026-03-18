<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        if (!$secret) {
            Log::warning('Stripe webhook secret is not configured.');
            return response('Webhook secret missing', 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        }

        $stripeSecret = config('services.stripe.secret');
        if (!$stripeSecret) {
            Log::warning('Stripe secret is not configured.');
            return response('Stripe secret missing', 500);
        }

        $stripe = new StripeClient($stripeSecret);

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutCompleted($stripe, $session);
                break;

            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $this->handleSubscriptionChange($subscription);
                break;

            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                $this->handleInvoiceSucceeded($stripe, $invoice);
                break;
        }

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(StripeClient $stripe, $session): void
    {
        if (!isset($session->customer, $session->subscription)) {
            return;
        }

        $userId = $session->metadata->user_id ?? null;
        $user = $userId ? User::find($userId) : User::where('stripe_customer_id', $session->customer)->first();

        if (!$user) {
            return;
        }

        $subscription = $stripe->subscriptions->retrieve($session->subscription, []);
        $periodEnd = $subscription->current_period_end ?? null;

        $user->forceFill([
            'stripe_customer_id' => $session->customer,
            'stripe_subscription_id' => $session->subscription,
            'stripe_price_id' => $subscription->items->data[0]->price->id ?? null,
            'is_premium' => true,
            'premium_expires_at' => $periodEnd ? now()->setTimestamp($periodEnd) : null,
        ])->save();
    }

    private function handleSubscriptionChange($subscription): void
    {
        $status = $subscription->status ?? null;
        $isActive = in_array($status, ['active', 'trialing'], true);

        $user = User::where('stripe_subscription_id', $subscription->id)
            ->orWhere('stripe_customer_id', $subscription->customer ?? '')
            ->first();

        if (!$user) {
            return;
        }

        $periodEnd = $subscription->current_period_end ?? null;

        $user->forceFill([
            'stripe_subscription_id' => $subscription->id,
            'stripe_customer_id' => $subscription->customer ?? $user->stripe_customer_id,
            'stripe_price_id' => $subscription->items->data[0]->price->id ?? $user->stripe_price_id,
            'is_premium' => $isActive,
            'premium_expires_at' => $periodEnd ? now()->setTimestamp($periodEnd) : null,
        ])->save();
    }

    private function handleInvoiceSucceeded(StripeClient $stripe, $invoice): void
    {
        if (!isset($invoice->subscription)) {
            return;
        }

        $subscription = $stripe->subscriptions->retrieve($invoice->subscription, []);
        $this->handleSubscriptionChange($subscription);
    }
}
