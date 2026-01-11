<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\NewsletterSubscriber;
use App\Mail\NewPostNotificationMail;
use Illuminate\Support\Facades\Mail;


class PostObserver
{
    public function updated(Post $post)
    {
        // Check if post was just published ( status changed to 'published')
        if ($post->isDirty('status') && $post->status === 'published') {
            $this->sendNewPostNotifications($post);
        }
    }

    public function created(Post $post) {
        // Send notifications when post is created with published status
        if ($post->status === 'published') {
            $this->sendNewPostNotifications($post);
        }
    }

    protected function sendNewPostNotifications(Post $post)
    {
        // Send emails to all confirmed subscribers in batches
        NewsletterSubscriber::confirmed()
        ->chunk(100, function ($subscribers) use ($post) {
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)
                ->queue(new NewPostNotificationMail($post, $subscriber));
            }
        });
    }
    

}
