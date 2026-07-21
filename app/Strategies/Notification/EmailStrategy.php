<?php

namespace App\Strategies\Notification;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * DESIGN PATTERN: Strategy Pattern — EmailStrategy (Module 4)
 *
 * Concrete strategy that dispatches notifications via email.
 * Uses Laravel's Mail facade for delivery.
 */
class EmailStrategy implements NotificationStrategyInterface
{
    /**
     * Send notification via email.
     */
    public function send(string $recipient, string $subject, string $message): bool
    {
        try {
            Mail::raw($message, function ($mail) use ($recipient, $subject) {
                $mail->to($recipient)
                     ->subject("[NutriShare] {$subject}");
            });

            Log::info("EmailStrategy: Notification sent to {$recipient}", [
                'subject' => $subject,
                'channel' => 'email',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("EmailStrategy: Failed to send to {$recipient}", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function getChannelName(): string
    {
        return 'email';
    }
}
