<?php

namespace App\Observers;

use App\Models\Donation;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\SystemLog;
use App\Models\User;
use App\Contracts\DonationObserverInterface;

use App\Jobs\SendDonationNotificationJob;

/**
 * DESIGN PATTERN: Observer Pattern (Module 1 — Donation & Notification Management)
 *
 * This class acts as the Observer that is notified when a Donation (Subject) is
 * created or updated. It implements the DonationObserverInterface contract.
 *
 * When a Donation is created:
 *   1. All verified NGO users are notified of the new donation asynchronously
 *   2. A system log entry is recorded
 *   3. Notifications are dispatched via background queue jobs
 *
 * Laravel's built-in model observer hooks (created, updated) serve as the
 * Subject's notification mechanism, while this class serves as the concrete Observer.
 */
class DonationObserver implements DonationObserverInterface
{
    /**
     * Handle the Donation "created" event.
     * Implements Observer Pattern: Subject (Donation) notifies this Observer.
     */
    public function created(Donation $donation): void
    {
        $this->onDonationCreated($donation);
    }

    /**
     * Handle the Donation "updated" event.
     */
    public function updated(Donation $donation): void
    {
        $oldStatus = $donation->getOriginal('status');
        $newStatus = $donation->status;

        if ($oldStatus !== $newStatus) {
            $this->onDonationStatusChanged($donation, $oldStatus);
        }
    }

    /**
     * Observer callback: New donation created.
     * Dispatches async job to notify all verified NGO users.
     */
    public function onDonationCreated(Donation $donation): void
    {
        // Dispatch asynchronous queue job for instant HTTP performance
        SendDonationNotificationJob::dispatch($donation);
    }

    /**
     * Observer callback: Donation status changed.
     */
    public function onDonationStatusChanged(Donation $donation, string $oldStatus): void
    {
        SystemLog::create([
            'user_id' => $donation->user_id,
            'action' => 'donation.status_changed',
            'description' => "Donation '{$donation->title}' status changed from '{$oldStatus}' to '{$donation->status}'.",
            'level' => 'info',
        ]);

        // If donation is claimed, notify the donor
        if ($donation->status === 'claimed') {
            Notification::create([
                'user_id' => $donation->user_id,
                'donation_id' => $donation->id,
                'title' => 'Donation Claimed',
                'message' => "Your donation '{$donation->title}' has been claimed by an NGO.",
                'channel' => 'email',
                'sent_at' => now(),
            ]);
        }
    }
}
