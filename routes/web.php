<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;

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

    // Password Reset Routes (OTP-based)
    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'sendOtp'])->name('password.email');
    Route::get('/forgot-password/otp', [\App\Http\Controllers\PasswordResetController::class, 'otpForm'])->name('password.otp.form');
    Route::post('/forgot-password/otp/verify', [\App\Http\Controllers\PasswordResetController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::post('/forgot-password/otp/resend', [\App\Http\Controllers\PasswordResetController::class, 'resendOtp'])->name('password.otp.resend');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Presentation Demo Account Quick Switcher
Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated Routes ──
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ── Notifications ──
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // ─── Module 1: Donation Management (Liew Yi Ler) ───
    Route::get('/donations/export/csv', [DonationController::class, 'exportCsv'])->name('donations.export.csv');
    Route::resource('donations', DonationController::class);

    // ─── Module 2: NGO Verification & Trust (Cheon Jie Han) ───
    Route::middleware('role:admin,moderator')->group(function () {
        Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
        Route::post('/verification/{document}/review', [VerificationController::class, 'review'])->name('verification.review');
        Route::get('/verification/{document}/file', [VerificationController::class, 'showFile'])->name('verification.file');
        Route::get('/verification/{document}/download', [VerificationController::class, 'download'])->name('verification.download');

        // ─── Platform Reports (Admin/Moderator) ───
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/export', [ReportController::class, 'export'])->name('reports.export');
        Route::post('/reports/{report}/refresh', [ReportController::class, 'refresh'])->name('reports.refresh');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // ─── System Activity Logs (Admin/Moderator) ───
        Route::get('/logs/export/csv', [\App\Http\Controllers\SystemLogController::class, 'exportCsv'])->name('logs.export.csv');
        Route::get('/logs', [\App\Http\Controllers\SystemLogController::class, 'index'])->name('logs.index');
        Route::get('/logs/{log}', [\App\Http\Controllers\SystemLogController::class, 'show'])->name('logs.show');
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
    Route::get('/inventory/export/csv', [InventoryController::class, 'exportCsv'])->name('inventory.export.csv');
    Route::resource('inventory', InventoryController::class)->parameters(['inventory' => 'inventoryLocation']);
    Route::post('/inventory/food-items', [InventoryController::class, 'addFoodItem'])->name('inventory.add-food-item');
    Route::get('/inventory/signed-link/{donation}', [InventoryController::class, 'generateSignedClaimLink'])->name('inventory.signed-link');

    // SECURITY (Module 4): Signed route for parameter tampering prevention
    Route::get('/inventory/quick-claim/{donation}', [InventoryController::class, 'quickClaim'])
        ->name('inventory.quick-claim')
        ->middleware('signed');
});
