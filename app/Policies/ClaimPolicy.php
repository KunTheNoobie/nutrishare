<?php

namespace App\Policies;

use App\Models\Claim;
use App\Models\User;

/**
 * SECURITY (Module 3): IDOR Prevention — Claim Policy
 *
 * OWASP Reference: A01 Broken Access Control (Insecure Direct Object Reference)
 *
 * RBAC Rules:
 * - Admin: Full CRUD (view all, update all, delete all)
 * - Moderator: View all, Update all, but CANNOT delete
 * - Donor: View/update claims on their own donations
 * - NGO: View/update own claims, cancel own pending claims
 */
class ClaimPolicy
{
    /**
     * Determine if the user can view any claims.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'moderator', 'ngo', 'donor']);
    }

    /**
     * Determine if the user can view the specific claim.
     * IDOR Prevention: User must own the claim or be admin/moderator/donor of the donation.
     */
    public function view(User $user, Claim $claim): bool
    {
        if ($user->isAdmin() || $user->isModerator()) return true;
        if ($user->isNgo()) return $claim->user_id === $user->id;
        if ($user->isDonor()) return $claim->donation->user_id === $user->id;
        return false;
    }

    /**
     * Determine if the user can create claims.
     * Only verified NGOs can create claims.
     */
    public function create(User $user): bool
    {
        return $user->isNgo() && $user->isVerified();
    }

    /**
     * Determine if the user can update the claim.
     */
    public function update(User $user, Claim $claim): bool
    {
        if ($user->isAdmin() || $user->isModerator()) return true;
        return ($user->isDonor() && $claim->donation->user_id === $user->id) 
            || ($user->isNgo() && $claim->user_id === $user->id);
    }

    /**
     * Determine if the user can delete the claim.
     * Only Admin or the owning NGO (pending only) can delete. Moderators CANNOT delete.
     */
    public function delete(User $user, Claim $claim): bool
    {
        if ($user->isAdmin()) return true;
        // Moderators CANNOT delete claims
        return $user->isNgo() && $claim->user_id === $user->id && $claim->status === 'pending';
    }
}
