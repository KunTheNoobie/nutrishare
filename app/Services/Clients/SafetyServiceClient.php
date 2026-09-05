<?php

namespace App\Services\Clients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Service Client for consuming Module 4's Food Safety Web Service (Module 3).
 *
 * Author: Hiew Li Wei (25WMR09728)
 * Module: Module 3 — Claims & Logistics Distribution
 */
class SafetyServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.safety_module.url', config('app.url'));
    }

    /**
     * Consumes Module 4's Food Safety Verification Web Service:
     * POST /api/inventory/food-safety-check
     *
     * @param int $foodItemId
     * @return bool
     */
    public function verifySafetyCompliance(int $foodItemId): bool
    {
        $requestId = 'REQ-SAFE-' . bin2hex(random_bytes(4));

        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/api/inventory/food-safety-check", [
                'requestID'    => $requestId,
                'timestamp'    => now()->toIso8601String(),
                'food_item_id' => $foodItemId,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return ($data['status'] === 'S') && 
                       (($data['data']['food_item']['safety_status'] ?? '') === 'SAFE' || ($data['data']['is_safe'] ?? false));
            }

            Log::warning("Food safety verification rejected by Module 4 for item ID: {$foodItemId}");
            return false;
        } catch (Exception $e) {
            Log::error("Failed to connect to Module 4 Food Safety Web Service", [
                'error'        => $e->getMessage(),
                'food_item_id' => $foodItemId,
            ]);

            // Fail-secure: reject dispatch if safety check service is unavailable
            return false;
        }
    }
}
