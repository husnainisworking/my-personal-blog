<?php

namespace App\Providers;

use App\Models\Post;
use App\Observers\PostObserver;
use App\Policies\DashboardPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Dashboard policy manually (not a model)
        Gate::policy('Dashboard', DashboardPolicy::class);

        Post::observe(PostObserver::class);

    }
}
