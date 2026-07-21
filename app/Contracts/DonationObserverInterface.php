<?php

namespace App\Contracts;

/**
 * Observer Pattern — Observer Interface (Module 1).
 *
 * Defines the contract for objects that should be notified
 * when a Donation (Subject) undergoes a state change.
 */
interface DonationObserverInterface
{
    /**
     * Called when a new donation is created.
     *
     * @param \App\Models\Donation $donation The newly created donation
     */
    public function onDonationCreated(\App\Models\Donation $donation): void;

    /**
     * Called when a donation's status changes.
     *
     * @param \App\Models\Donation $donation The updated donation
     * @param string $oldStatus The previous status
     */
    public function onDonationStatusChanged(\App\Models\Donation $donation, string $oldStatus): void;
}
