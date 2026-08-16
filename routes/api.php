<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\UserVerificationApiController;
use App\Http\Controllers\Api\ClaimApiController;
use App\Http\Controllers\Api\InventoryApiController;

/*
|--------------------------------------------------------------------------
| API Routes — NutriShare
|--------------------------------------------------------------------------
| All API endpoints follow the Interface Agreement (IFA):
|   Request:  { requestID, timestamp, ... }
|   Response: { status: S|F|E, timestamp, data|message }
|
| API Routes are stateless and excluded from CSRF verification.
*/

// ─────────────────────────────────────────────────────────────
// Module 1: Donation & Notification Management (Liew Yi Ler)
// WEB SERVICE — EXPOSE: Returns active unclaimed donations
// ─────────────────────────────────────────────────────────────
Route::get('/donations/active', [DonationApiController::class, 'active'])
    ->name('api.donations.active');

// ─────────────────────────────────────────────────────────────
// Module 2: NGO Verification & Trust Management (Cheon Jie Han)
// WEB SERVICE — EXPOSE: Validates NGO approval and license status
// ─────────────────────────────────────────────────────────────
Route::post('/user/verify-ngo', [UserVerificationApiController::class, 'verifyNgo'])
    ->name('api.user.verify-ngo');

// ─────────────────────────────────────────────────────────────
// Module 3: Claim & Logistics Distribution (Hiew Li Wei)
// WEB SERVICE — EXPOSE: Returns claim status and details
// ─────────────────────────────────────────────────────────────
Route::get('/claim/details', [ClaimApiController::class, 'details'])
    ->name('api.claim.details');

// ─────────────────────────────────────────────────────────────
// Module 4: Inventory & Food Safety Compliance (Wong Men Jing)
// WEB SERVICE — EXPOSE: Returns facility storage status & safety check
// ─────────────────────────────────────────────────────────────
Route::get('/inventory/status', [InventoryApiController::class, 'status'])
    ->name('api.inventory.status');
Route::post('/inventory/food-safety-check', [InventoryApiController::class, 'checkSafety'])
    ->name('api.inventory.safety-check');
