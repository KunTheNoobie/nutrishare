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
        // Donors see their own donations, NGOs see active available donations
        if (Auth::user()->isDonor()) {
            $query = Donation::with(['foodItems'])->where('user_id', Auth::id());
            
            if (!empty($request->search)) {
                $query->where('title', 'LIKE', '%' . $request->search . '%');
            }
            
            $donations = $query->orderBy('created_at', 'desc')->paginate(15);
        } else {
            // SECURITY (Module 1): Eloquent parameterized queries prevent SQLi
            $donations = $this->repository->findActiveDonations($request->all());
        }

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

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('donations', 'public');
            }
        }
        
        if ($request->filled('image_url')) {
            $imagePaths[] = $request->image_url;
        }

        $validated['image_paths'] = empty($imagePaths) ? null : $imagePaths;

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

        $imagePaths = $donation->image_paths ?? [];

        // Handle image removals
        if ($request->has('remove_images') && is_array($request->input('remove_images'))) {
            $toRemove = $request->input('remove_images');
            foreach ($toRemove as $path) {
                if (!str_starts_with($path, 'http')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
                $imagePaths = array_diff($imagePaths, [$path]);
            }
            $imagePaths = array_values($imagePaths); // Re-index array
        }

        // Add new uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('donations', 'public');
            }
        }
        
        // Add new URL image
        if ($request->filled('image_url')) {
            $newUrl = $request->image_url;
            $toRemove = is_array($request->input('remove_images')) ? $request->input('remove_images') : [];
            
            // Only add if we aren't simultaneously removing it
            if (!in_array($newUrl, $toRemove)) {
                $imagePaths[] = $newUrl;
            }
        }

        // Ensure no duplicate URLs
        $imagePaths = array_values(array_unique($imagePaths));

        $validated['image_paths'] = empty($imagePaths) ? null : $imagePaths;

        $donation->update($validated);

        return redirect()->route('donations.show', $donation)
            ->with('success', 'Donation updated successfully.');
    }

    /** Delete donation (soft delete). */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);
        $donation->delete();

        return redirect()->route('donations.index')
            ->with('success', 'Donation deleted successfully.');
    }
}
