<?php

namespace App\States\Claim;

use App\Models\SystemLog;

/**
 * DESIGN PATTERN: State Pattern — CollectedState (Module 3)
 *
 * Terminal state: donation has been collected by the NGO.
 * No further transitions allowed. Distribution logs can be added.
 */
class CollectedState extends ClaimState
{
    public function getStateName(): string
    {
        return 'collected';
    }

    public function allowedActions(): array
    {
        return []; // Terminal state — no further transitions
    }

    public function handle(string $action): bool
    {
        // No transitions from collected state
        SystemLog::create([
            'user_id' => $this->claim->user_id,
            'action' => 'claim.invalid_transition',
            'description' => "Attempted action '{$action}' on claim #{$this->claim->id} which is already in 'collected' state.",
            'level' => 'warning',
        ]);

        return false;
    }
}
