<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Donation;
use App\Models\Vehicle;
use App\Models\CollectionReceipt;
use App\Models\DistributionLog;
use App\Http\Requests\StoreClaimRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ClaimController — Module 3: Claim & Logistics Distribution (Hiew Li Wei)
 *
 * SECURITY (Module 3):
 * - IDOR Prevention: Laravel Policies enforce ownership checks (OWASP A01)
 * - CSRF Enforcement: All forms include @csrf directive (OWASP A05)
 *
 * DESIGN PATTERN: State Pattern used for claim lifecycle transitions.
 */
class ClaimController extends Controller
{
    /** List user's claims or all claims if Admin/Moderator. */
    public function index()
    {
        $query = Claim::with(['donation', 'vehicle', 'collectionReceipt', 'user']);

        // SECURITY (Module 3): Role-based claim scoping
        if (Auth::user()->isDonor()) {
            // Donors view claims placed by NGOs on their published donations
            $query->whereHas('donation', function ($q) {
                $q->where('user_id', Auth::id());
            });
        } elseif (Auth::user()->isNgo()) {
            // NGOs view claims created by their organization
            $query->where('user_id', Auth::id());
        }
        // Admins & Moderators view all platform claims

        $claims = $query->latest()->paginate(20);

        return view('claims.index', compact('claims'));
    }

    /** Submit a new claim. CSRF token validated automatically. */
    public function store(StoreClaimRequest $request)
    {
        $validated = $request->validated();

        $donation = Donation::findOrFail($validated['donation_id']);

        if ($donation->status !== 'available') {
            return back()->with('error', 'This donation is no longer available for claiming.');
        }

        // Prevent duplicate pending or approved claims from the same NGO
        $existingClaim = Claim::where('donation_id', $donation->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'collected'])
            ->first();

        if ($existingClaim) {
            return back()->with('error', 'You have already submitted a claim for this donation.');
        }

        $validated['user_id'] = Auth::id();

        $claim = Claim::create($validated);

        return redirect()->route('claims.show', $claim)
            ->with('success', 'Claim submitted successfully. Awaiting donor approval.');
    }

    /** Show claim details. */
    public function show(Claim $claim)
    {
        // SECURITY (Module 3): IDOR check via Policy
        $this->authorize('view', $claim);

        $claim->load(['donation.donor', 'vehicle', 'collectionReceipt', 'distributionLogs']);
        $stateObject = $claim->getStateObject();

        return view('claims.show', compact('claim', 'stateObject'));
    }

    /**
     * Transition claim state (State Pattern).
     * Actions: approve, reject, collect, cancel
     */
    public function transition(Request $request, Claim $claim)
    {
        $this->authorize('update', $claim);

        $action = $request->validate(['action' => 'required|in:approve,reject,collect,cancel'])['action'];

        $user = Auth::user();

        // Enforce strict role authorization per transition action
        if (in_array($action, ['approve', 'reject'])) {
            // ONLY Admin, Moderator, or the donor who created the donation can approve/reject
            if (!$user->isAdmin() && !$user->isModerator() && $claim->donation->user_id !== $user->id) {
                abort(403, 'Unauthorized. Only an Admin, Moderator, or the Donor can approve or reject claims.');
            }
        } elseif ($action === 'collect') {
            // Admin, Moderator, or the NGO who claimed this donation can collect it
            if (!$user->isAdmin() && !$user->isModerator() && (!$user->isNgo() || $claim->user_id !== $user->id)) {
                abort(403, 'Unauthorized. Only the claiming NGO, Moderator, or Admin can mark this donation as collected.');
            }
        } elseif ($action === 'cancel') {
            // ONLY the NGO who submitted the claim (or Admin/Moderator) can cancel
            if (!$user->isAdmin() && !$user->isModerator() && $claim->user_id !== $user->id) {
                abort(403, 'Unauthorized. Only the NGO who submitted this claim can cancel it.');
            }
        }

        $success = $claim->transitionTo($action);

        if ($success) {
            return redirect()->route('claims.show', $claim)
                ->with('success', "Claim {$action}d successfully.");
        }

        return redirect()->route('claims.show', $claim)
            ->with('error', "Cannot {$action} this claim in its current state.");
    }

    /** Assign a vehicle to a claim. */
    public function assignVehicle(Request $request, Claim $claim)
    {
        $this->authorize('update', $claim);

        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'vehicle_type' => 'required|in:van,truck,car,motorcycle',
            'driver_name' => 'required|string|max:255',
            'driver_phone' => ['required', 'string', 'min:9', 'max:20', 'regex:/^(\+?[\d\s\-\(\)]){9,20}$/'],
            'capacity_kg' => 'nullable|numeric|min:0',
        ], [
            'driver_phone.min' => 'Please enter a valid driver phone number (at least 9 digits).',
            'driver_phone.regex' => 'Please enter a valid driver phone number (e.g. 012-3456789 or +60123456789).',
        ]);

        $validated['claim_id'] = $claim->id;

        Vehicle::updateOrCreate(
            ['claim_id' => $claim->id],
            $validated
        );

        return redirect()->route('claims.show', $claim)
            ->with('success', 'Vehicle assigned successfully.');
    }

    /** Generate collection receipt. */
    public function generateReceipt(Request $request, Claim $claim)
    {
        $this->authorize('update', $claim);

        $validated = $request->validate([
            'quantity_collected' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,litres,items,boxes',
            'collected_by' => 'required|string|max:255',
            'condition_notes' => 'nullable|string|max:500',
        ]);

        $validated['claim_id'] = $claim->id;
        $validated['receipt_number'] = CollectionReceipt::generateReceiptNumber();
        $validated['collected_at'] = now();

        CollectionReceipt::create($validated);

        // Transition to collected state
        $claim->transitionTo('collect');

        return redirect()->route('claims.show', $claim)
            ->with('success', 'Collection receipt generated. Donation marked as collected.');
    }

    /** Submit distribution log (SDG tracking). */
    public function logDistribution(Request $request, Claim $claim)
    {
        $this->authorize('update', $claim);

        $validated = $request->validate([
            'beneficiaries_count' => 'required|integer|min:1',
            'distribution_location' => 'required|string|max:500',
            'quantity_distributed' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,litres,items,boxes',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['claim_id'] = $claim->id;
        $validated['distributed_at'] = now();

        DistributionLog::create($validated);

        return redirect()->route('claims.show', $claim)
            ->with('success', 'Distribution log recorded for SDG impact tracking.');
    }
}
