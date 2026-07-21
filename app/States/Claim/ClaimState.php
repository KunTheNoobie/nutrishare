<?php

namespace App\States\Claim;

use App\Models\Claim;

/**
 * DESIGN PATTERN: State Pattern — Abstract State (Module 3)
 *
 * Manages the lifecycle of a Claim through discrete states:
 *   PendingState -> ApprovedState -> CollectedState
 *
 * Each concrete state defines which transitions are valid and
 * what side-effects occur during state transitions.
 */
abstract class ClaimState
{
    protected Claim $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    /**
     * Handle a state transition action.
     *
     * @param string $action The action to perform (e.g., 'approve', 'collect', 'reject')
     * @return bool Whether the transition was successful
     */
    abstract public function handle(string $action): bool;

    /**
     * Get the name of the current state.
     */
    abstract public function getStateName(): string;

    /**
     * Get the list of valid actions from this state.
     *
     * @return array<string>
     */
    abstract public function allowedActions(): array;

    /**
     * Check if a given action is allowed in the current state.
     */
    public function canPerform(string $action): bool
    {
        return in_array($action, $this->allowedActions());
    }
}
