<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmNewsletterSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;

    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }

   public function build()
   {
    return $this->subject('Confirm Your Newsletter Subscription')
                ->view('emails.confirm-newsletter-subscription');
   }
}
