<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Strategies\Notification\NotificationStrategyInterface;
use App\Strategies\Notification\EmailStrategy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the default notification strategy (Strategy Pattern - Module 4)
        $this->app->bind(NotificationStrategyInterface::class, EmailStrategy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
