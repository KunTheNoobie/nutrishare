<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes — NutriShare
|--------------------------------------------------------------------------
*/

// ── Public Routes ──
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ── Authentication Routes ──
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Password Reset Routes
    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated Routes ──
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ─── Module 1: Donation Management (Liew Yi Ler) ───
    Route::resource('donations', DonationController::class);

    // ─── Module 2: NGO Verification & Trust (Cheon Jie Han) ───
    Route::middleware('role:admin')->group(function () {
        Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
        Route::post('/verification/{document}/review', [VerificationController::class, 'review'])->name('verification.review');
    });

    Route::middleware('role:ngo')->group(function () {
        Route::post('/verification/upload', [VerificationController::class, 'upload'])->name('verification.upload');
    });

    Route::get('/users/{user}/reviews', [VerificationController::class, 'reviews'])->name('reviews.show');
    Route::post('/users/{user}/reviews', [VerificationController::class, 'submitReview'])->name('reviews.submit');

    // ─── Module 3: Claims & Logistics (Hiew Li Wei) ───
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');
    Route::post('/claims/{claim}/transition', [ClaimController::class, 'transition'])->name('claims.transition');
    Route::post('/claims/{claim}/vehicle', [ClaimController::class, 'assignVehicle'])->name('claims.vehicle');
    Route::post('/claims/{claim}/receipt', [ClaimController::class, 'generateReceipt'])->name('claims.receipt');
    Route::post('/claims/{claim}/distribution', [ClaimController::class, 'logDistribution'])->name('claims.distribution');

    // ─── Module 4: Inventory & Food Safety (Wong Men Jing) ───
    Route::resource('inventory', InventoryController::class)->parameters(['inventory' => 'inventoryLocation']);
    Route::post('/inventory/food-items', [InventoryController::class, 'addFoodItem'])->name('inventory.add-food-item');
    Route::get('/inventory/signed-link/{donation}', [InventoryController::class, 'generateSignedClaimLink'])->name('inventory.signed-link');

    // SECURITY (Module 4): Signed route for parameter tampering prevention
    Route::get('/inventory/quick-claim/{donation}', [InventoryController::class, 'quickClaim'])
        ->name('inventory.quick-claim')
        ->middleware('signed');
});
