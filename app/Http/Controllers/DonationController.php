<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Http\Requests\StoreDonationRequest;
use App\Repositories\DonationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DonationController — Module 1: Donation & Notification Management (Liew Yi Ler)
 *
 * SECURITY (Module 1):
 * - SQLi Prevention: Uses Eloquent parameterized queries (OWASP A03)
 * - Stored XSS Prevention: Blade {{ }} escaping in views (OWASP A07)
 */
class DonationController extends Controller
{
    private DonationRepository $repository;

    public function __construct(DonationRepository $repository)
    {
        $this->repository = $repository;
    }

    /** Display all donations (with search/filter). */
    public function index(Request $request)
    {
        // SECURITY (Module 1): Eloquent parameterized queries prevent SQLi
        $donations = $this->repository->findActiveDonations($request->all());

        return view('donations.index', compact('donations'));
    }

    /** Show donation creation form. */
    public function create()
    {
        return view('donations.create');
    }

    /**
     * Store a new donation.
     * Observer Pattern: DonationObserver::created() fires automatically.
     */
    public function store(StoreDonationRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('donations', 'public');
        }

        // Creating the donation triggers the Observer Pattern (DonationObserver)
        $donation = Donation::create($validated);

        return redirect()->route('donations.show', $donation)
            ->with('success', 'Donation published successfully! NGOs have been notified.');
    }

    /** Show donation details. */
    public function show(Donation $donation)
    {
        $donation->load(['donor', 'foodItems.category', 'foodItems.allergenTags', 'claims.user']);
        return view('donations.show', compact('donation'));
    }

    /** Show edit form. */
    public function edit(Donation $donation)
    {
        $this->authorize('update', $donation);
        return view('donations.edit', compact('donation'));
    }

    /** Update donation. */
    public function update(StoreDonationRequest $request, Donation $donation)
    {
        $this->authorize('update', $donation);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('donations', 'public');
        }

        $donation->update($validated);

        return redirect()->route('donations.show', $donation)
            ->with('success', 'Donation updated successfully.');
    }

    /** Delete donation. */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);
        $donation->delete();

        return redirect()->route('donations.index')
            ->with('success', 'Donation deleted successfully.');
    }
}
