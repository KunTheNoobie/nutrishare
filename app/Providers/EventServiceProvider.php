<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * The event to listener mappings for the application.
     * Observer Pattern registrations are done in boot() method.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        //
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Observer Pattern (Module 1): Register Donation Observer
        \App\Models\Donation::observe(\App\Observers\DonationObserver::class);
    }
}
