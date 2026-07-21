<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Helpers\SecurityHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for Claim endpoints (Module 3).
 *
 * WEB SERVICE - Exposure: GET /api/claim/details
 *
 * All responses follow the strict IFA (Interface Agreement).
 */
class ClaimApiController extends Controller
{
    /**
     * WEB SERVICE — EXPOSE: GET /api/claim/details
     *
     * Returns the current status and details of a claim.
     *
     * IFA Request (query params):
     *   ?requestID=REQ-xxx&timestamp=2024-...&claim_id=1
     *
     * IFA Response:
     *   { "status": "S", "timestamp": "2024-...", "data": { ... } }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function details(Request $request): JsonResponse
    {
        try {
            $ifa = SecurityHelper::validateIfaRequest($request->all());

            $validated = $request->validate([
                'claim_id' => 'required|integer|exists:claims,id',
            ]);

            $claim = Claim::with([
                'donation:id,title,quantity,unit,status',
                'user:id,name,organization_name',
                'vehicle',
                'collectionReceipt',
            ])->find($validated['claim_id']);

            // Get allowed actions from State Pattern
            $stateObject = $claim->getStateObject();

            return response()->json(
                SecurityHelper::ifaResponse('S', [
                    'requestID' => $ifa['requestID'],
                    'claim' => [
                        'id' => $claim->id,
                        'status' => $claim->status,
                        'current_state' => $stateObject->getStateName(),
                        'allowed_actions' => $stateObject->allowedActions(),
                        'justification' => $claim->justification,
                        'pickup_scheduled_at' => $claim->pickup_scheduled_at?->toIso8601String(),
                        'donation' => $claim->donation,
                        'ngo' => [
                            'name' => $claim->user->name,
                            'organization' => $claim->user->organization_name,
                        ],
                        'vehicle' => $claim->vehicle,
                        'collection_receipt' => $claim->collectionReceipt,
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
}
