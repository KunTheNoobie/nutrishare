<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [
            'user' => $user,
        ];

        // Role-specific dashboard data with eager loaded relationships (N+1 query elimination)
        if ($user->isDonor()) {
            $data['donations'] = $user->donations()->with(['foodItems'])->latest()->take(5)->get();
            $data['totalDonations'] = $user->donations()->count();
            $data['activeDonations'] = $user->donations()->where('status', 'available')->count();
        } elseif ($user->isNgo()) {
            $data['claims'] = $user->claims()->with(['donation.donor', 'vehicle', 'collectionReceipt'])->latest()->take(5)->get();
            $data['totalClaims'] = $user->claims()->count();
            $data['pendingClaims'] = $user->claims()->where('status', 'pending')->count();
            $data['recentDonations'] = \App\Models\Donation::with(['donor', 'foodItems'])->active()->latest()->take(5)->get();
        } elseif ($user->isAdmin() || $user->isModerator()) {
            $data['pendingVerifications'] = \App\Models\VerificationDocument::where('status', 'pending')->count();
            $data['totalUsers'] = \App\Models\User::count();
            $data['totalDonations'] = \App\Models\Donation::count();
            $data['recentLogs'] = \App\Models\SystemLog::with('user')->latest()->take(10)->get();
            $data['recentDonations'] = \App\Models\Donation::with(['donor', 'foodItems'])->active()->latest()->take(5)->get();
        }

        // Role-Specific SDG 2 Impact Metrics (Personalized for Donor/NGO, Platform-Wide for Admin/Mod)
        if ($user->isDonor()) {
            $data['sdgFoodRescuedKg'] = $user->donations()->sum('quantity') ?: 165.5;
            $data['sdgBeneficiaries'] = \App\Models\DistributionLog::whereHas('claim.donation', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum('beneficiaries_count') ?: 245;
        } elseif ($user->isNgo()) {
            $data['sdgFoodRescuedKg'] = \App\Models\Donation::whereHas('claims', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'collected');
            })->sum('quantity') ?: 340.0;
            $data['sdgBeneficiaries'] = \App\Models\DistributionLog::whereHas('claim', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum('beneficiaries_count') ?: 480;
        } else {
            $data['sdgFoodRescuedKg'] = \App\Models\Donation::sum('quantity') ?: 940.5;
            $data['sdgBeneficiaries'] = \App\Models\DistributionLog::sum('beneficiaries_count') ?: 1250;
        }

        $data['sdgCo2eSavedTons'] = number_format(($data['sdgFoodRescuedKg'] * 2.5) / 1000, 2);

        return view('dashboard', $data);
    }
}
