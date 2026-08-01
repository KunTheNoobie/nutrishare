<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Donation;
use App\Models\Claim;
use App\Models\User;
use App\Models\DistributionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ReportController — Platform Analytics & SDG Impact Reports
 *
 * Provides Admin/Moderator users with the ability to generate,
 * view, and manage analytics reports from the `reports` table.
 */
class ReportController extends Controller
{
    /** List all generated reports. */
    public function index()
    {
        $reports = Report::with('user')->latest('report_date')->paginate(15);
        return view('reports.index', compact('reports'));
    }

    /** Show report generation form. */
    public function create()
    {
        return view('reports.create');
    }

    /** Generate and store a new report. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:sdg_impact,donation_summary,user_activity',
            'title' => 'required|string|max:255',
        ]);

        $content = match ($validated['type']) {
            'sdg_impact' => $this->generateSdgImpactReport(),
            'donation_summary' => $this->generateDonationSummaryReport(),
            'user_activity' => $this->generateUserActivityReport(),
        };

        $report = Report::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => json_encode($content),
            'type' => $validated['type'],
            'report_date' => now(),
        ]);

        \App\Models\SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'report.generated',
            'description' => "Report '{$report->title}' ({$validated['type']}) generated.",
            'level' => 'info',
        ]);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Report generated successfully.');
    }

    /** Display a specific report. */
    public function show(Report $report)
    {
        $report->load('user');
        $content = json_decode($report->content, true);
        return view('reports.show', compact('report', 'content'));
    }

    /** Delete a report. */
    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    // ──────────── Report Generators ────────────

    private function generateSdgImpactReport(): array
    {
        $totalBeneficiaries = DistributionLog::sum('beneficiaries_count');
        $totalQuantityDistributed = DistributionLog::sum('quantity_distributed');
        $totalDistributions = DistributionLog::count();
        $totalDonationsCompleted = Donation::where('status', 'completed')->count();
        $totalDonationsCollected = Donation::where('status', 'collected')->count();
        $totalFoodSaved = Donation::whereIn('status', ['claimed', 'collected', 'completed'])->sum('quantity');

        $distributionsByLocation = DistributionLog::selectRaw('distribution_location, SUM(beneficiaries_count) as total_beneficiaries, SUM(quantity_distributed) as total_quantity, COUNT(*) as distribution_count')
            ->groupBy('distribution_location')
            ->orderByDesc('total_beneficiaries')
            ->limit(10)
            ->get()
            ->toArray();

        return [
            'total_beneficiaries' => $totalBeneficiaries,
            'total_quantity_distributed' => $totalQuantityDistributed,
            'total_distributions' => $totalDistributions,
            'total_donations_completed' => $totalDonationsCompleted,
            'total_donations_collected' => $totalDonationsCollected,
            'total_food_saved_kg' => $totalFoodSaved,
            'top_distribution_locations' => $distributionsByLocation,
        ];
    }

    private function generateDonationSummaryReport(): array
    {
        $totalDonations = Donation::count();
        $byStatus = Donation::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalClaims = Claim::count();
        $claimsByStatus = Claim::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $topDonors = User::where('role', 'donor')
            ->withCount('donations')
            ->orderByDesc('donations_count')
            ->limit(10)
            ->get()
            ->map(fn($u) => ['name' => $u->name, 'donations_count' => $u->donations_count])
            ->toArray();

        $topNgos = User::where('role', 'ngo')
            ->withCount('claims')
            ->orderByDesc('claims_count')
            ->limit(10)
            ->get()
            ->map(fn($u) => ['name' => $u->organization_name ?? $u->name, 'claims_count' => $u->claims_count])
            ->toArray();

        return [
            'total_donations' => $totalDonations,
            'donations_by_status' => $byStatus,
            'total_claims' => $totalClaims,
            'claims_by_status' => $claimsByStatus,
            'top_donors' => $topDonors,
            'top_ngos' => $topNgos,
        ];
    }

    private function generateUserActivityReport(): array
    {
        $totalUsers = User::count();
        $usersByRole = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        $verifiedNgos = User::where('role', 'ngo')->where('verification_status', 'approved')->count();
        $pendingNgos = User::where('role', 'ngo')->where('verification_status', 'pending')->count();

        $recentUsers = User::latest()
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'name' => $u->name,
                'role' => $u->role,
                'joined' => $u->created_at->format('d M Y'),
            ])
            ->toArray();

        $activeUsers = User::withCount(['donations', 'claims'])
            ->orderByDesc('donations_count')
            ->orderByDesc('claims_count')
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'name' => $u->name,
                'role' => $u->role,
                'donations' => $u->donations_count,
                'claims' => $u->claims_count,
            ])
            ->toArray();

        return [
            'total_users' => $totalUsers,
            'users_by_role' => $usersByRole,
            'verified_ngos' => $verifiedNgos,
            'pending_ngos' => $pendingNgos,
            'recent_users' => $recentUsers,
            'most_active_users' => $activeUsers,
        ];
    }
}
