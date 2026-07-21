<?php

namespace App\Policies;

use App\Models\Claim;
use App\Models\User;

/**
 * SECURITY (Module 3): IDOR Prevention — Claim Policy
 *
 * OWASP Reference: A01 Broken Access Control (Insecure Direct Object Reference)
 *
 * Implements strict ownership checks via Laravel Policies/Gates to ensure
 * an NGO can only view/edit their own claims. This prevents IDOR attacks
 * where a user might manipulate claim IDs in URLs to access other users' data.
 */
class ClaimPolicy
{
    /**
     * Determine if the user can view any claims.
     * Admins can view all; NGOs only see their own.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'ngo', 'donor']);
    }

    /**
     * Determine if the user can view the specific claim.
     * IDOR Prevention: User must own the claim or be admin/donor of the donation.
     */
    public function view(User $user, Claim $claim): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // NGO can only view their own claims
        if ($user->isNgo()) {
            return $claim->user_id === $user->id;
        }

        // Donor can view claims on their donations
        if ($user->isDonor()) {
            return $claim->donation->user_id === $user->id;
        }

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
     * IDOR Prevention: Only the claim owner (NGO) or admin can update.
     */
    public function update(User $user, Claim $claim): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isNgo() && $claim->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the claim.
     */
    public function delete(User $user, Claim $claim): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // NGO can cancel their own pending claims
        return $user->isNgo() && $claim->user_id === $user->id && $claim->status === 'pending';
    }
}
