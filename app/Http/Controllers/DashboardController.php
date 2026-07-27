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
            $data['recentDonations'] = \App\Models\Donation::where('status', 'available')->where('expiry_date', '>', now())->latest()->take(5)->get();
        } elseif ($user->isAdmin()) {
            $data['pendingVerifications'] = \App\Models\VerificationDocument::where('status', 'pending')->count();
            $data['totalUsers'] = \App\Models\User::count();
            $data['totalDonations'] = \App\Models\Donation::count();
            $data['recentLogs'] = \App\Models\SystemLog::latest()->take(10)->get();
            $data['recentDonations'] = \App\Models\Donation::where('status', 'available')->where('expiry_date', '>', now())->latest()->take(5)->get();
        }

        return view('dashboard', $data);
    }
}
