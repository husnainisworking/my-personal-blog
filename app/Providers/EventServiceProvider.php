<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\RecordPostView;
use App\Listeners\RecordPostEngagement;
use App\Events\PostViewed;
use App\Events\PostEngaged;


class EventServiceProvider extends ServiceProvider
{
   protected $listen = [
        PostViewed::class => [
                RecordPostView::class,
            ],
        PostEngaged::class => [
                RecordPostEngagement::class,
            ],
    ];


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();
    }

    
} 
