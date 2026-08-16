<?php

namespace App\Jobs;

use App\Models\Donation;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Author: Liew Yi Ler
 * Module 1: Donation & Notification Management
 *
 * SendDonationNotificationJob — Queueable Job for Asynchronous Notifications
 *
 * Dispatches notification alerts to NGOs in background queues without blocking HTTP requests.
 */
class SendDonationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function handle(): void
    {
        $donation = $this->donation;

        $ngoUsers = User::where('role', 'ngo')
            ->where('verification_status', 'approved')
            ->get();

        $template = NotificationTemplate::where('name', 'donation_created')->first();

        foreach ($ngoUsers as $ngo) {
            $message = $template
                ? $template->render([
                    'donor_name' => $donation->donor->name ?? 'A donor',
                    'donation_title' => $donation->title,
                    'quantity' => $donation->quantity . ' ' . $donation->unit,
                    'expiry_date' => $donation->expiry_date?->format('d M Y') ?? 'N/A',
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

        SystemLog::create([
            'user_id' => $donation->user_id,
            'action' => 'donation.created',
            'description' => "Donation '{$donation->title}' created. {$ngoUsers->count()} NGOs notified via async job.",
            'level' => 'info',
        ]);
    }
}
