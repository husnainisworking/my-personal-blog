<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use App\Models\User;


class PostViewed 
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
    
        public Post $post,
        public ?User $user,
        public array $metadata
    ) {}
    

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
