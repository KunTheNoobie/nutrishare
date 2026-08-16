<?php

namespace App\Repositories;

use App\Models\InventoryLocation;

/**
 * Author: Wong Men Jing
 * Module 4: Inventory & Food Safety Compliance
 *
 * InventoryRepository — Repository Pattern Implementation (Module 4)
 *
 * Encapsulates parameterized query logic for NGO storage facilities and inventory items.
 */
class InventoryRepository
{
    public function getLocationsForUser($user, int $perPage = 20)
    {
        $query = InventoryLocation::with(['foodItems.category', 'foodItems.allergenTags']);

        if (!$user->isAdmin() && !$user->isModerator()) {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getTotalStorageCapacity($userId): array
    {
        $locations = InventoryLocation::where('user_id', $userId)->get();

        return [
            'total_capacity' => $locations->sum('capacity'),
            'current_occupancy' => $locations->sum('current_occupancy'),
            'utilization_rate' => $locations->sum('capacity') > 0
                ? round(($locations->sum('current_occupancy') / $locations->sum('capacity')) * 100, 1)
                : 0,
        ];
    }
}
