<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;

/**
 * Donation Policy — Access control for donations.
 */
class DonationPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view donations
    }

    public function view(User $user, Donation $donation): bool
    {
        return true; // All authenticated users can view individual donations
    }

    public function create(User $user): bool
    {
        return $user->isDonor() || $user->isAdmin();
    }

    public function update(User $user, Donation $donation): bool
    {
        if ($user->isAdmin()) return true;
        return $user->isDonor() && $donation->user_id === $user->id;
    }

    public function delete(User $user, Donation $donation): bool
    {
        if ($user->isAdmin()) return true;
        return $user->isDonor() && $donation->user_id === $user->id && $donation->status === 'available';
    }
}
