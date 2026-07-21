<?php

namespace App\Observers;

use App\Models\Donation;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\SystemLog;
use App\Models\User;
use App\Contracts\DonationObserverInterface;

/**
 * DESIGN PATTERN: Observer Pattern (Module 1 — Donation & Notification Management)
 *
 * This class acts as the Observer that is notified when a Donation (Subject) is
 * created or updated. It implements the DonationObserverInterface contract.
 *
 * When a Donation is created:
 *   1. All verified NGO users are notified of the new donation
 *   2. A system log entry is recorded
 *   3. Notifications are dispatched via the appropriate channel
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
     * Notifies all verified NGO users about the available donation.
     */
    public function onDonationCreated(Donation $donation): void
    {
        // Fetch all verified NGO users to notify
        $ngoUsers = User::where('role', 'ngo')
            ->where('verification_status', 'approved')
            ->get();

        // Try to load the notification template
        $template = NotificationTemplate::where('name', 'donation_created')->first();

        foreach ($ngoUsers as $ngo) {
            $message = $template
                ? $template->render([
                    'donor_name' => $donation->donor->name ?? 'A donor',
                    'donation_title' => $donation->title,
                    'quantity' => $donation->quantity . ' ' . $donation->unit,
                    'expiry_date' => $donation->expiry_date->format('d M Y'),
                ])
                : "New donation available: {$donation->title} ({$donation->quantity} {$donation->unit})";

            Notification::create([
                'user_id' => $ngo->id,
                'notification_template_id' => $template?->id,
                'donation_id' => $donation->id,
                'title' => 'New Donation Available',
                'message' => $message,
                'channel' => $ngo->notification_preference === 'sms' ? 'sms' : 'email',
                'sent_at' => now(),
            ]);
        }

        // Log the event
        SystemLog::create([
            'user_id' => $donation->user_id,
            'action' => 'donation.created',
            'description' => "Donation '{$donation->title}' created. {$ngoUsers->count()} NGOs notified.",
            'level' => 'info',
        ]);
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
