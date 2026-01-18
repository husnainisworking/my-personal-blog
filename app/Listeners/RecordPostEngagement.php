<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PostEngaged;
use App\Models\PostEvent;

class RecordPostEngagement
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostEngaged $event): void
    {
        PostEvent::create([
            'event_type' => 'engaged',
            'post_id' => $event->post->id,
            'user_id' => $event->user?->id,
            'ip_address' => request()->ip(),
            'data' => $event->engagementData,
            'created_at' => now(),
        ]);
    }
}
