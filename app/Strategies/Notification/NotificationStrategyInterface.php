<?php

namespace App\Strategies\Notification;

/**
 * DESIGN PATTERN: Strategy Pattern — Strategy Interface (Module 4)
 *
 * Defines the contract for notification dispatch strategies.
 * Concrete strategies (EmailStrategy, SMSStrategy) implement
 * different delivery mechanisms that can be swapped at runtime
 * based on user preference or urgency level.
 */
interface NotificationStrategyInterface
{
    /**
     * Send a notification using this strategy's channel.
     *
     * @param string $recipient The recipient identifier (email/phone)
     * @param string $subject The notification subject
     * @param string $message The notification body
     * @return bool Whether the notification was sent successfully
     */
    public function send(string $recipient, string $subject, string $message): bool;

    /**
     * Get the name of this strategy's channel.
     */
    public function getChannelName(): string;
}
