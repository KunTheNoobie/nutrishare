<?php

namespace App\Http\Controllers;

use App\Models\InventoryLocation;
use App\Models\FoodItem;
use App\Models\Category;
use App\Models\AllergenTag;
use App\Http\Requests\StoreFoodItemRequest;
use App\Helpers\SecurityHelper;
use App\Strategies\Notification\NotificationDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

/**
 * InventoryController — Module 4: Inventory & Food Safety (Wong Men Jing)
 *
 * SECURITY (Module 4):
 * - Log Injection Prevention: CRLF sanitization via SecurityHelper (OWASP A09)
 * - Parameter Tampering Prevention: HMAC signed routes (OWASP A04)
 *
 * DESIGN PATTERN: Strategy Pattern used for notification dispatch.
 */
class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->isDonor()) {
                abort(403, 'Unauthorized action. Inventory is managed by NGOs and Admins.');
            }
            return $next($request);
        });
    }
    /** List inventory locations. */
    public function index()
    {
        if (Auth::user()->isAdmin() || Auth::user()->isModerator()) {
            $locations = InventoryLocation::with('user')->withCount('foodItems')->paginate(20);
        } else {
            $locations = Auth::user()->inventoryLocations()->withCount('foodItems')->paginate(20);
        }
        return view('inventory.index', compact('locations'));
    }

    /** Show create form. */
    public function create()
    {
        if (!Auth::user()->isNgo()) {
            abort(403, 'Unauthorized. Inventory locations are managed exclusively by NGOs.');
        }
        return view('inventory.create');
    }

    /** Store a new inventory location. */
    public function store(Request $request)
    {
        if (!Auth::user()->isNgo()) {
            abort(403, 'Unauthorized. Inventory locations can only be registered by NGOs.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'storage_type' => 'required|in:cold,dry,frozen,ambient',
            'capacity' => 'required|numeric|min:0.01',
        ]);

        $validated['user_id'] = Auth::id();

        // SECURITY (Module 4): Log injection prevention via sanitized SystemLog
        \App\Models\SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'inventory.created',
            'description' => "Inventory location '{$validated['name']}' created.", // Sanitized by model mutator
            'level' => 'info',
        ]);

        InventoryLocation::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory location registered successfully.');
    }

    /** Show inventory location details with food items. */
    public function show(InventoryLocation $inventoryLocation)
    {
        $inventoryLocation->load(['foodItems.category', 'foodItems.allergenTags', 'user']);
        $categories = Category::all();
        $allergenTags = AllergenTag::all();
        $donations = Auth::user()->isNgo() ? Auth::user()->claims()->with('donation')->get() : collect();

        return view('inventory.show', compact('inventoryLocation', 'categories', 'allergenTags', 'donations'));
    }

    /** Add a food item to inventory. */
    public function addFoodItem(StoreFoodItemRequest $request)
    {
        if (!Auth::user()->isNgo()) {
            abort(403, 'Unauthorized. Food items in inventory are managed exclusively by NGOs.');
        }
        $validated = $request->validated();

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('food-items', 'public');
                    $imagePaths[] = $path;
                }
            }
        }
        if (!empty($validated['image_url'])) {
            $imagePaths[] = trim($validated['image_url']);
        }
        if (!empty($validated['image_urls']) && is_array($validated['image_urls'])) {
            foreach ($validated['image_urls'] as $url) {
                if (!empty($url)) {
                    $imagePaths[] = trim($url);
                }
            }
        }

        if (!empty($imagePaths)) {
            $validated['image_paths'] = array_slice($imagePaths, 0, 5);
        }

        $foodItem = FoodItem::create($validated);

        if (!empty($validated['inventory_location_id'])) {
            $location = \App\Models\InventoryLocation::find($validated['inventory_location_id']);
            if ($location) {
                $location->increment('current_occupancy', $validated['quantity']);
            }
        }

        // Attach allergen tags (Many-to-Many)
        if ($request->has('allergen_tags')) {
            $foodItem->allergenTags()->sync($request->input('allergen_tags'));
        }

        // DESIGN PATTERN: Strategy Pattern — Dispatch notification using user's preferred channel
        $dispatcher = new NotificationDispatcher();
        $dispatcher->dispatch(
            Auth::user(),
            'Food Item Added',
            "Food item '{$foodItem->name}' has been added to inventory."
        );

        return redirect()->back()
            ->with('success', 'Food item added successfully with allergen tags.');
    }

    /**
     * SECURITY (Module 4): Parameter Tampering Prevention — Signed URL.
     *
     * Generate a signed URL for a quick-claim action.
     * The URL contains an HMAC signature that prevents tampering.
     */
    public function generateSignedClaimLink(int $donationId)
    {
        // SECURITY: Laravel's URL::signedRoute creates a URL with HMAC signature
        // Any modification to the URL parameters will invalidate the signature
        $signedUrl = URL::signedRoute('inventory.quick-claim', [
            'donation' => $donationId,
            'user' => Auth::id(),
        ]);

        return response()->json([
            'signed_url' => $signedUrl,
            'message' => 'Use this signed URL to quickly claim the donation. URL expires and is tamper-proof.',
        ]);
    }

    /**
     * SECURITY (Module 4): Handle quick-claim via signed URL.
     * The signature is validated by Laravel's middleware automatically.
     */
    public function quickClaim(Request $request, int $donationId)
    {
        // Laravel validates the signature automatically via 'signed' middleware
        // If the URL was tampered with, it returns 403 Forbidden

        return redirect()->route('claims.browse')
            ->with('success', "Signed claim link verified for donation #{$donationId}. Proceed to claim.");
    }
}
