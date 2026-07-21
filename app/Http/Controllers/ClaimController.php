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
    /** List user's claims. */
    public function index()
    {
        // SECURITY (Module 3): IDOR Prevention — Only show user's own claims
        $claims = Auth::user()->claims()
            ->with(['donation', 'vehicle', 'collectionReceipt'])
            ->latest()
            ->paginate(15);

        return view('claims.index', compact('claims'));
    }

    /** Submit a new claim. CSRF token validated automatically. */
    public function store(StoreClaimRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $claim = Claim::create($validated);

        return redirect()->route('claims.show', $claim)
            ->with('success', 'Claim submitted successfully. Awaiting approval.');
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
     * Actions: approve, reject, collect
     */
    public function transition(Request $request, Claim $claim)
    {
        $this->authorize('update', $claim);

        $action = $request->validate(['action' => 'required|in:approve,reject,collect,cancel'])['action'];

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
            'driver_phone' => 'required|string|max:20',
            'capacity_kg' => 'nullable|numeric|min:0',
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
