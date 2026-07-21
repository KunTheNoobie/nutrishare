<?php

namespace App\Strategies\Notification;

use Illuminate\Support\Facades\Log;

/**
 * DESIGN PATTERN: Strategy Pattern — SMSStrategy (Module 4)
 *
 * Concrete strategy that dispatches notifications via SMS.
 * In production, this would integrate with an SMS gateway (e.g., Twilio).
 * For this implementation, it logs the SMS for demonstration.
 */
class SMSStrategy implements NotificationStrategyInterface
{
    /**
     * Send notification via SMS.
     * Simulated via logging for demonstration purposes.
     */
    public function send(string $recipient, string $subject, string $message): bool
    {
        try {
            // In production, integrate with SMS gateway API here:
            // $smsGateway->send($recipient, "[NutriShare] {$subject}: {$message}");

            Log::info("SMSStrategy: SMS notification sent to {$recipient}", [
                'subject' => $subject,
                'message' => $message,
                'channel' => 'sms',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("SMSStrategy: Failed to send SMS to {$recipient}", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function getChannelName(): string
    {
        return 'sms';
    }
}
