<?php

namespace App\Strategies\Notification;

use App\Models\User;

/**
 * DESIGN PATTERN: Strategy Pattern — Context/Dispatcher (Module 4)
 *
 * Resolves and invokes the appropriate notification strategy
 * based on user preference or urgency level.
 *
 * Usage:
 *   $dispatcher = new NotificationDispatcher();
 *   $dispatcher->dispatch($user, 'Subject', 'Message body');
 *   $dispatcher->dispatchWithStrategy(new SMSStrategy(), $phone, 'Subject', 'Body');
 */
class NotificationDispatcher
{
    /**
     * Dispatch a notification to a user using their preferred strategy.
     *
     * @param User $user The recipient user
     * @param string $subject Notification subject
     * @param string $message Notification body
     * @return bool Whether the notification was sent successfully
     */
    public function dispatch(User $user, string $subject, string $message): bool
    {
        $strategy = $this->resolveStrategy($user->notification_preference);
        $recipient = $this->getRecipient($user, $strategy);

        return $strategy->send($recipient, $subject, $message);
    }

    /**
     * Dispatch using a specific strategy (override user preference).
     * Useful for urgent notifications that must use a specific channel.
     */
    public function dispatchWithStrategy(
        NotificationStrategyInterface $strategy,
        string $recipient,
        string $subject,
        string $message
    ): bool {
        return $strategy->send($recipient, $subject, $message);
    }

    /**
     * Resolve the notification strategy based on user preference.
     */
    private function resolveStrategy(string $preference): NotificationStrategyInterface
    {
        return match ($preference) {
            'sms'  => new SMSStrategy(),
            'both' => new EmailStrategy(), // Default to email for 'both', SMS sent separately
            default => new EmailStrategy(),
        };
    }

    /**
     * Get the appropriate recipient identifier for the strategy.
     */
    private function getRecipient(User $user, NotificationStrategyInterface $strategy): string
    {
        return match ($strategy->getChannelName()) {
            'sms'  => $user->phone ?? $user->email,
            default => $user->email,
        };
    }
}
