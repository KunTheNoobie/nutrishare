<?php

namespace App\Repositories;

use App\Models\Claim;

/**
 * Author: Yap Zhing Shuen
 * Module 3: Claims & Logistics Distribution
 *
 * ClaimRepository — Repository Pattern Implementation (Module 3)
 *
 * Encapsulates parameterized Eloquent query logic for claims.
 */
class ClaimRepository
{
    public function getClaimsForUser($user, int $perPage = 20)
    {
        $query = Claim::with(['donation', 'vehicle', 'collectionReceipt', 'user']);

        if ($user->isDonor()) {
            $query->whereHas('donation', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($user->isNgo()) {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getStatisticsForUser($user): array
    {
        if ($user->isNgo()) {
            return [
                'total' => Claim::where('user_id', $user->id)->count(),
                'pending' => Claim::where('user_id', $user->id)->where('status', 'pending')->count(),
                'approved' => Claim::where('user_id', $user->id)->where('status', 'approved')->count(),
                'collected' => Claim::where('user_id', $user->id)->where('status', 'collected')->count(),
            ];
        }

        return [
            'total' => Claim::count(),
            'pending' => Claim::where('status', 'pending')->count(),
            'approved' => Claim::where('status', 'approved')->count(),
            'collected' => Claim::where('status', 'collected')->count(),
        ];
    }
}
