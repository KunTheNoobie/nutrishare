<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationDocument;
use App\Helpers\SecurityHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for NGO Verification endpoints (Module 2).
 *
 * WEB SERVICE - Exposure: POST /api/user/verify-ngo
 *
 * All responses follow the strict IFA (Interface Agreement):
 *   Request:  { requestID, timestamp, user_id }
 *   Response: { status: S|F|E, timestamp, data }
 */
class UserVerificationApiController extends Controller
{
    /**
     * WEB SERVICE — EXPOSE: POST /api/user/verify-ngo
     *
     * Validates if an NGO user is approved and has a valid license.
     * Called by Module 1 before allowing donations to be claimed.
     *
     * IFA Request:
     *   { "requestID": "REQ-xxx", "timestamp": "2024-...", "user_id": 5 }
     *
     * IFA Response:
     *   { "status": "S", "timestamp": "2024-...", "data": { "is_verified": true, ... } }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyNgo(Request $request): JsonResponse
    {
        try {
            // Validate IFA request
            $ifa = SecurityHelper::validateIfaRequest($request->all());

            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validated['user_id']);

            // Check if user is an NGO
            if (!$user || $user->role !== 'ngo') {
                return response()->json(
                    SecurityHelper::ifaResponse('F', [
                        'requestID' => $ifa['requestID'],
                        'is_verified' => false,
                        'reason' => 'User is not registered as an NGO.',
                    ]),
                    200
                );
            }

            // Check verification status
            $isVerified = $user->verification_status === 'approved';

            // Check if they have approved license documents
            $hasValidLicense = VerificationDocument::where('user_id', $user->id)
                ->where('status', 'approved')
                ->where('document_type', 'license')
                ->exists();

            return response()->json(
                SecurityHelper::ifaResponse('S', [
                    'requestID' => $ifa['requestID'],
                    'is_verified' => $isVerified && $hasValidLicense,
                    'verification_status' => $user->verification_status,
                    'has_valid_license' => $hasValidLicense,
                    'organization_name' => $user->organization_name,
                    'trust_rating' => $user->averageRating(),
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
}
