<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;

/**
 * Donation Policy — Access control for donations.
 *
 * RBAC Rules:
 * - Admin: Full CRUD (create, read, update, delete)
 * - Moderator: Create, Read, Update (NO delete)
 * - Donor: Full CRUD on own donations only
 * - NGO: Read only (+ claim functionality)
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
        return $user->isDonor() || $user->isAdmin() || $user->isModerator();
    }

    public function update(User $user, Donation $donation): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isModerator()) return true;
        return $user->isDonor() && $donation->user_id === $user->id;
    }

    public function delete(User $user, Donation $donation): bool
    {
        // Only Admin or the owning Donor can delete. Moderators CANNOT delete.
        if ($user->isAdmin()) return true;
        return $user->isDonor() && $donation->user_id === $user->id;
    }
}
