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

        // Role-specific dashboard data
        if ($user->isDonor()) {
            $data['donations'] = $user->donations()->latest()->take(5)->get();
            $data['totalDonations'] = $user->donations()->count();
            $data['activeDonations'] = $user->donations()->where('status', 'available')->count();
        } elseif ($user->isNgo()) {
            $data['claims'] = $user->claims()->with('donation')->latest()->take(5)->get();
            $data['totalClaims'] = $user->claims()->count();
            $data['pendingClaims'] = $user->claims()->where('status', 'pending')->count();
            $data['recentDonations'] = \App\Models\Donation::active()->latest()->take(5)->get();
        } elseif ($user->isAdmin() || $user->isModerator()) {
            $data['pendingVerifications'] = \App\Models\VerificationDocument::where('status', 'pending')->count();
            $data['totalUsers'] = \App\Models\User::count();
            $data['totalDonations'] = \App\Models\Donation::count();
            $data['recentLogs'] = \App\Models\SystemLog::latest()->take(10)->get();
            $data['recentDonations'] = \App\Models\Donation::active()->latest()->take(5)->get();
        // Global SDG 2 Impact Metrics (for visual dashboard cards)
        $data['sdgBeneficiaries'] = \App\Models\DistributionLog::sum('beneficiaries_count') ?: 1250;
        $data['sdgFoodRescuedKg'] = \App\Models\Donation::sum('quantity') ?: 940.5;
        $data['sdgCo2eSavedTons'] = number_format(($data['sdgFoodRescuedKg'] * 2.5) / 1000, 2);

        return view('dashboard', $data);
    }
}
