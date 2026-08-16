<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryLocation;
use App\Models\FoodItem;
use App\Helpers\SecurityHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Author: Wong Men Jing
 * Module 4: Inventory & Food Safety Compliance
 *
 * WEB SERVICE - Exposure: GET /api/inventory/status
 * WEB SERVICE - Exposure: POST /api/inventory/food-safety-check
 * WEB SERVICE - Consumption: Consumes Module 1 active donations endpoint
 *
 * All responses strictly adhere to the Interface Agreement (IFA):
 *   Request:  { requestID, timestamp, ... }
 *   Response: { status: S|F|E, timestamp, data|message }
 */
class InventoryApiController extends Controller
{
    /**
     * WEB SERVICE — EXPOSE: GET /api/inventory/status
     *
     * Returns total facility capacities, current occupancy, and available storage by storage type.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function status(Request $request): JsonResponse
    {
        try {
            $ifa = SecurityHelper::validateIfaRequest($request->all());

            $locations = InventoryLocation::withCount('foodItems')->get()->map(function ($loc) {
                return [
                    'id' => $loc->id,
                    'name' => $loc->name,
                    'address' => $loc->address,
                    'storage_type' => $loc->storage_type,
                    'capacity' => (float) $loc->capacity,
                    'current_occupancy' => (float) $loc->current_occupancy,
                    'available_space' => max(0, (float) ($loc->capacity - $loc->current_occupancy)),
                    'utilization_rate' => $loc->capacity > 0 ? round(($loc->current_occupancy / $loc->capacity) * 100, 1) . '%' : '0%',
                    'items_count' => $loc->food_items_count,
                ];
            });

            return response()->json(
                SecurityHelper::ifaResponse('S', [
                    'requestID' => $ifa['requestID'],
                    'facilities' => $locations,
                    'total_capacity' => $locations->sum('capacity'),
                    'total_occupancy' => $locations->sum('current_occupancy'),
                ]),
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                SecurityHelper::ifaResponse('E', null, 'Internal server error: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * WEB SERVICE — EXPOSE: POST /api/inventory/food-safety-check
     *
     * Verifies food safety compliance (checks expiry dates and allergen tags).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkSafety(Request $request): JsonResponse
    {
        try {
            $ifa = SecurityHelper::validateIfaRequest($request->all());

            $validated = $request->validate([
                'food_item_id' => 'required|integer|exists:food_items,id',
            ]);

            $item = FoodItem::with(['category', 'allergenTags'])->find($validated['food_item_id']);

            $isExpired = $item->expiry_date ? $item->expiry_date->isPast() : false;
            $daysToExpiry = $item->expiry_date ? now()->diffInDays($item->expiry_date, false) : null;
            $isSafeForConsumption = !$isExpired && ($daysToExpiry === null || $daysToExpiry >= 0);

            return response()->json(
                SecurityHelper::ifaResponse('S', [
                    'requestID' => $ifa['requestID'],
                    'food_item' => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'category' => $item->category?->name ?? 'General',
                        'allergens' => $item->allergenTags->pluck('name'),
                        'expiry_date' => $item->expiry_date?->toIso8601String(),
                        'days_remaining' => $daysToExpiry,
                        'is_expired' => $isExpired,
                        'safety_status' => $isSafeForConsumption ? 'SAFE' : 'EXPIRED / AT RISK',
                    ],
                ]),
                200
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                SecurityHelper::ifaResponse('F', null, 'Validation failed: ' . $e->getMessage()),
                422
            );
        } catch (\Exception $e) {
            return response()->json(
                SecurityHelper::ifaResponse('E', null, 'Internal server error.'),
                500
            );
        }
    }

    /**
     * WEB SERVICE — CONSUME: Queries Module 1's active donations catalog.
     *
     * Allows inventory managers to preview incoming surplus donations.
     */
    public function fetchActiveDonationsForWarehouse(): array
    {
        try {
            $response = Http::timeout(10)->get(
                config('app.url') . '/api/donations/active',
                [
                    'requestID' => uniqid('INV-PREVIEW-'),
                    'timestamp' => now()->toIso8601String(),
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] === 'S' ? ($data['data']['donations'] ?? []) : [];
            }
            return [];
        } catch (\Exception $e) {
            \Log::error('Inventory API failed to fetch active donations', ['error' => $e->getMessage()]);
            return [];
        }
    }
}
