<?php

namespace App\Http\Controllers;

use App\Http\Requests\Newsletter\StoreSubscriberRequest;
use App\Models\NewsletterSubscriber;
use App\Mail\ConfirmNewsletterSubscriptionMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(StoreSubscriberRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['confirmation_token'] = Str::random(64);

        try {

            $subscriber = NewsletterSubscriber::create($validated);

            Mail::to($subscriber->email)->queue(new ConfirmNewsletterSubscriptionMail($subscriber));

            return back()->with('newsletter_success', 
            'Check your email to confirm your subscription!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('newsletter_error',
                'Error subscribing. Please try again later.');
        }
    }

    public function confirm(string $token): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)->firstOrFail();

        $subscriber->update([
            'confirmed_at' => now(),
            'confirmation_token' => null,
        ]);

        return redirect()->route('home')
                        ->with('newsletter_success', 'Successfully subscribed to newsletter!');

    }

    public function unsubscribe(string $token): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->firstOrFail();

        $subscriber->update(['subscribed' => false]);

        return redirect()->route('home')
                ->with('newsletter_success' , 'Unsubscribed successfully.');
    }
    
}
