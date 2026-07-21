<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Helpers\SecurityHelper;
use App\Repositories\DonationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * API Controller for Donation endpoints (Module 1).
 *
 * WEB SERVICE - Exposure:  GET /api/donations/active
 * WEB SERVICE - Consumption: Calls Module 2's POST /api/user/verify-ngo
 *
 * All responses follow the strict IFA (Interface Agreement):
 *   Request:  { requestID, timestamp, ... }
 *   Response: { status: S|F|E, timestamp, data|message }
 */
class DonationApiController extends Controller
{
    private DonationRepository $repository;

    public function __construct(DonationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * WEB SERVICE — EXPOSE: GET /api/donations/active
     *
     * Returns all active (unclaimed, non-expired) donations.
     * IFA-compliant response format.
     *
     * @param Request $request Must include requestID and timestamp
     * @return JsonResponse IFA response with status S/F/E
     */
    public function active(Request $request): JsonResponse
    {
        try {
            // Validate IFA request format
            $ifa = SecurityHelper::validateIfaRequest($request->all());

            $donations = Donation::with(['donor:id,name,organization_name', 'foodItems'])
                ->active() // Scope: available + not expired
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($donation) {
                    return [
                        'id' => $donation->id,
                        'title' => $donation->title,
                        'description' => $donation->description,
                        'quantity' => $donation->quantity,
                        'unit' => $donation->unit,
                        'pickup_address' => $donation->pickup_address,
                        'expiry_date' => $donation->expiry_date->toIso8601String(),
                        'status' => $donation->status,
                        'donor' => [
                            'name' => $donation->donor->name,
                            'organization' => $donation->donor->organization_name,
                        ],
                        'food_items_count' => $donation->foodItems->count(),
                        'created_at' => $donation->created_at->toIso8601String(),
                    ];
                });

            return response()->json(
                SecurityHelper::ifaResponse('S', [
                    'requestID' => $ifa['requestID'],
                    'donations' => $donations,
                    'total' => $donations->count(),
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
     * WEB SERVICE — CONSUME: Verify NGO status via Module 2's API.
     *
     * Before allowing an NGO to claim a donation, this method calls
     * Module 2's POST /api/user/verify-ngo endpoint to validate the
     * NGO's approval status and license validity.
     *
     * @param int $ngoUserId The NGO user's ID to verify
     * @return bool Whether the NGO is verified and can claim
     */
    public function verifyNgoBeforeClaim(int $ngoUserId): bool
    {
        try {
            // CONSUME Module 2's API endpoint
            $response = Http::timeout(10)->post(
                config('app.url') . '/api/user/verify-ngo',
                [
                    'requestID' => uniqid('VERIFY-'),
                    'timestamp' => now()->toIso8601String(),
                    'user_id' => $ngoUserId,
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] === 'S' && ($data['data']['is_verified'] ?? false);
            }

            return false;
        } catch (\Exception $e) {
            // Log the error and fail closed (deny access)
            \Log::error('NGO verification API call failed', [
                'ngo_user_id' => $ngoUserId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
