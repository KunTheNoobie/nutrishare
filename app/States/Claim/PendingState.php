<?php

namespace App\States\Claim;

use App\Models\SystemLog;
use App\Models\Notification;

/**
 * DESIGN PATTERN: State Pattern — PendingState (Module 3)
 *
 * Initial state when an NGO submits a claim.
 * Allowed transitions: approve -> ApprovedState, reject -> (terminal)
 */
class PendingState extends ClaimState
{
    public function getStateName(): string
    {
        return 'pending';
    }

    public function allowedActions(): array
    {
        return ['approve', 'reject', 'cancel'];
    }

    public function handle(string $action): bool
    {
        if (!$this->canPerform($action)) {
            return false;
        }

        return match ($action) {
            'approve' => $this->approve(),
            'reject'  => $this->reject(),
            'cancel'  => $this->cancel(),
            default   => false,
        };
    }

    private function approve(): bool
    {
        $this->claim->update(['status' => 'approved']);

        // Update the related donation status
        $this->claim->donation->update(['status' => 'claimed']);

        // Notify the NGO
        Notification::create([
            'user_id' => $this->claim->user_id,
            'donation_id' => $this->claim->donation_id,
            'title' => 'Claim Approved',
            'message' => "Your claim for '{$this->claim->donation->title}' has been approved. Please schedule a pickup.",
            'channel' => 'email',
            'sent_at' => now(),
        ]);

        SystemLog::create([
            'user_id' => $this->claim->user_id,
            'action' => 'claim.approved',
            'description' => "Claim #{$this->claim->id} approved for donation '{$this->claim->donation->title}'.",
            'level' => 'info',
        ]);

        return true;
    }

    private function reject(): bool
    {
        $this->claim->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $this->claim->user_id,
            'donation_id' => $this->claim->donation_id,
            'title' => 'Claim Rejected',
            'message' => "Your claim for '{$this->claim->donation->title}' has been rejected.",
            'channel' => 'email',
            'sent_at' => now(),
        ]);

        SystemLog::create([
            'user_id' => $this->claim->user_id,
            'action' => 'claim.rejected',
            'description' => "Claim #{$this->claim->id} rejected.",
            'level' => 'info',
        ]);

        return true;
    }

    private function cancel(): bool
    {
        $this->claim->update(['status' => 'cancelled']);

        SystemLog::create([
            'user_id' => $this->claim->user_id,
            'action' => 'claim.cancelled',
            'description' => "Claim #{$this->claim->id} cancelled by NGO.",
            'level' => 'info',
        ]);

        return true;
    }
}
