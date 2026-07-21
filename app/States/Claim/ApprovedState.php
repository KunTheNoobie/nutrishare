<?php

namespace App\States\Claim;

use App\Models\SystemLog;
use App\Models\Notification;

/**
 * DESIGN PATTERN: State Pattern — ApprovedState (Module 3)
 *
 * Claim has been approved. NGO can now collect the donation.
 * Allowed transition: collect -> CollectedState
 */
class ApprovedState extends ClaimState
{
    public function getStateName(): string
    {
        return 'approved';
    }

    public function allowedActions(): array
    {
        return ['collect'];
    }

    public function handle(string $action): bool
    {
        if (!$this->canPerform($action)) {
            return false;
        }

        return match ($action) {
            'collect' => $this->collect(),
            default   => false,
        };
    }

    private function collect(): bool
    {
        $this->claim->update(['status' => 'collected']);

        // Update donation status
        $this->claim->donation->update(['status' => 'collected']);

        // Notify the donor that their donation was collected
        Notification::create([
            'user_id' => $this->claim->donation->user_id,
            'donation_id' => $this->claim->donation_id,
            'title' => 'Donation Collected',
            'message' => "Your donation '{$this->claim->donation->title}' has been collected by {$this->claim->user->organization_name}.",
            'channel' => 'email',
            'sent_at' => now(),
        ]);

        SystemLog::create([
            'user_id' => $this->claim->user_id,
            'action' => 'claim.collected',
            'description' => "Claim #{$this->claim->id} - Donation '{$this->claim->donation->title}' collected.",
            'level' => 'info',
        ]);

        return true;
    }
}
