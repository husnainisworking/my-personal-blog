<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPostNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $subscriber;
    


    public function __construct($post, $subscriber)
    {
        $this->post = $post;
        $this->subscriber = $subscriber;
    }

   public function build()
   {
    return $this->subject('New Post: ' . $this->post->title)
        ->view('emails.new-post-notification');
   }
}
