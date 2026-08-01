<?php

namespace App\States\Claim;

use App\Models\SystemLog;
use App\Models\Notification;

/**
 * DESIGN PATTERN: State Pattern — ApprovedState (Module 3)
 *
 * Claim has been approved. NGO can now collect the donation.
 * Allowed transition: collect -> CollectedState
 */
class ApprovedState extends ClaimState
{
    public function getStateName(): string
    {
        return 'approved';
    }

    public function allowedActions(): array
    {
        return ['collect'];
    }

    public function handle(string $action): bool
    {
        if (!$this->canPerform($action)) {
            return false;
        }

        return match ($action) {
            'collect' => $this->collect(),
            default   => false,
        };
    }

    private function collect(): bool
    {
        // Enforce vehicle assignment business rule before collection
        if (!$this->claim->vehicle_id) {
            return false;
        }

        $this->claim->update(['status' => 'collected']);

        // Update donation status
        $this->claim->donation->update(['status' => 'collected']);

        // AUTOMATED INVENTORY INFLOW (Modules 1, 3, 4 Integration)
        // Auto-stock collected food donation into NGO's target Inventory Warehouse
        $ngo = $this->claim->user;
        $location = null;

        if ($this->claim->inventory_location_id) {
            $location = \App\Models\InventoryLocation::where('id', $this->claim->inventory_location_id)
                ->where('user_id', $ngo->id)
                ->first();
        }

        if (!$location) {
            $location = $ngo->inventoryLocations()->first();
        }

        // Auto-create an Inventory Warehouse if the NGO does not have one yet
        if (!$location) {
            $locationName = ($ngo->organization_name ?: $ngo->name) . ' Central Storage';
            $location = \App\Models\InventoryLocation::create([
                'user_id' => $ngo->id,
                'name' => $locationName,
                'address' => $this->claim->donation->pickup_address ?: 'NGO Central Facility',
                'storage_type' => 'ambient',
                'capacity' => 5000,
                'current_occupancy' => 0,
            ]);
        }

        // Create FoodItem entry in the NGO's Inventory
        \App\Models\FoodItem::create([
            'donation_id' => $this->claim->donation_id,
            'inventory_location_id' => $location->id,
            'category_id' => $this->claim->donation->category_id,
            'name' => $this->claim->donation->title,
            'description' => $this->claim->donation->description,
            'quantity' => $this->claim->donation->quantity,
            'unit' => $this->claim->donation->unit,
            'expiry_date' => $this->claim->donation->expiry_date,
            'storage_requirements' => 'ambient',
            'is_perishable' => false,
        ]);

        $location->increment('current_occupancy', $this->claim->donation->quantity);

        // Notify the donor that their donation was collected
        Notification::create([
            'user_id' => $this->claim->donation->user_id,
            'donation_id' => $this->claim->donation_id,
            'title' => 'Donation Collected',
            'message' => "Your donation '{$this->claim->donation->title}' has been collected by {$this->claim->user->organization_name} and stocked into inventory.",
            'channel' => 'email',
            'sent_at' => now(),
        ]);

        SystemLog::create([
            'user_id' => $this->claim->user_id,
            'action' => 'claim.collected',
            'description' => "Claim #{$this->claim->id} - Donation '{$this->claim->donation->title}' collected and auto-stocked into '{$location->name}'.",
            'level' => 'info',
        ]);

        return true;
    }
}
