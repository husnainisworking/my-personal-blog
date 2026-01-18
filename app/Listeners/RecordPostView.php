<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PostViewed;
use App\Models\PostEvent;

class RecordPostView
{

    /**
     * Handle the event.
     */
    public function handle(PostViewed $event)
    {

        PostEvent::create([
            'event_type' => 'viewed',
            'post_id' => $event->post->id,
            'user_id' => $event->user?->id,
            'ip_address' => $event->metadata['ip'] ?? null,
            'user_agent' => $event->metadata['user_agent'] ?? null,
            'referrer' => $event->metadata['referrer'] ?? null,
            'data' => $event->metadata['extra'] ?? null,
            'created_at' => now(),
        ]);
    }
}
