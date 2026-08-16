<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Donation;
use App\Models\Category;
use App\Models\Claim;
use App\Models\InventoryLocation;
use App\Models\SystemLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NutriShareComprehensiveSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $moderator;
    protected User $ngo;
    protected User $donor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@nutrishare.com',
            'password' => bcrypt('Password1!'),
            'role' => 'admin',
            'verification_status' => 'approved',
        ]);

        $this->moderator = User::create([
            'name' => 'Platform Moderator',
            'email' => 'moderator@nutrishare.com',
            'password' => bcrypt('Password1!'),
            'role' => 'moderator',
            'verification_status' => 'approved',
        ]);

        $this->ngo = User::create([
            'name' => 'Food Rescue Foundation',
            'email' => 'ngo@nutrishare.com',
            'password' => bcrypt('Password1!'),
            'role' => 'ngo',
            'organization_name' => 'Food Rescue Foundation',
            'verification_status' => 'approved',
        ]);

        $this->donor = User::create([
            'name' => 'Sunway Grocer',
            'email' => 'donor@nutrishare.com',
            'password' => bcrypt('Password1!'),
            'role' => 'donor',
            'organization_name' => 'Sunway Grocer',
            'verification_status' => 'approved',
        ]);
    }

    public function test_demo_login_switcher_for_all_roles(): void
    {
        foreach (['admin', 'moderator', 'ngo', 'donor'] as $role) {
            $response = $this->get(route('demo.login', $role));
            $response->assertRedirect(route('dashboard'));
            $this->assertAuthenticated();
            $this->assertEquals($role, auth()->user()->role);
        }
    }

    public function test_dashboard_renders_for_all_authenticated_roles(): void
    {
        foreach ([$this->admin, $this->moderator, $this->ngo, $this->donor] as $user) {
            $response = $this->actingAs($user)->get(route('dashboard'));
            $response->assertStatus(200);
            $response->assertSee('UN SDG 2 Impact Tracker');
        }
    }

    public function test_donations_catalog_and_csv_export(): void
    {
        $donation = Donation::create([
            'user_id' => $this->donor->id,
            'title' => 'Organic Fresh Apples',
            'description' => 'Crisp organic apples surplus',
            'quantity' => 50,
            'unit' => 'kg',
            'pickup_address' => 'Sunway Pyramid, Petaling Jaya',
            'expiry_date' => now()->addDays(3),
            'status' => 'available',
        ]);

        // Donor view
        $response = $this->actingAs($this->donor)->get(route('donations.index'));
        $response->assertStatus(200);
        $response->assertSee('Organic Fresh Apples');

        // CSV export
        $csvResponse = $this->actingAs($this->donor)->get(route('donations.export.csv'));
        $csvResponse->assertStatus(200);
        $csvResponse->assertHeader('content-type', 'text/csv; charset=utf-8');
    }

    public function test_inventory_access_and_rbac_restrictions(): void
    {
        $location = InventoryLocation::create([
            'user_id' => $this->ngo->id,
            'name' => 'Central Cold Hub',
            'address' => 'Jalan Ampang, KL',
            'storage_type' => 'cold',
            'capacity' => 1000,
            'current_occupancy' => 200,
        ]);

        // NGO has access
        $responseNgo = $this->actingAs($this->ngo)->get(route('inventory.index'));
        $responseNgo->assertStatus(200);
        $responseNgo->assertSee('Central Cold Hub');

        // NGO CSV export
        $csvResponse = $this->actingAs($this->ngo)->get(route('inventory.export.csv'));
        $csvResponse->assertStatus(200);

        // Donor is FORBIDDEN from accessing inventory (RBAC check)
        $responseDonor = $this->actingAs($this->donor)->get(route('inventory.index'));
        $responseDonor->assertStatus(403);
    }

    public function test_system_logs_and_reports_rbac_security(): void
    {
        SystemLog::create([
            'action' => 'security.audit',
            'description' => 'Automated test security log.',
            'level' => 'info',
        ]);

        // Admin has access to logs and reports
        $this->actingAs($this->admin)->get(route('logs.index'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('logs.export.csv'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('reports.index'))->assertStatus(200);

        // Moderator has access to logs and reports
        $this->actingAs($this->moderator)->get(route('logs.index'))->assertStatus(200);
        $this->actingAs($this->moderator)->get(route('reports.index'))->assertStatus(200);

        // Donor is FORBIDDEN from accessing logs (RBAC check)
        $this->actingAs($this->donor)->get(route('logs.index'))->assertStatus(403);
        $this->actingAs($this->donor)->get(route('logs.export.csv'))->assertStatus(403);

        // NGO is FORBIDDEN from accessing reports (RBAC check)
        $this->actingAs($this->ngo)->get(route('reports.index'))->assertStatus(403);
    }

    public function test_ngo_verification_queue_rbac_security(): void
    {
        // Admin & Moderator have access
        $this->actingAs($this->admin)->get(route('verification.index'))->assertStatus(200);
        $this->actingAs($this->moderator)->get(route('verification.index'))->assertStatus(200);

        // Donor & NGO are FORBIDDEN from viewing the verification queue (RBAC check)
        $this->actingAs($this->donor)->get(route('verification.index'))->assertStatus(403);
        $this->actingAs($this->ngo)->get(route('verification.index'))->assertStatus(403);
    }

    public function test_moderator_cannot_delete_donation(): void
    {
        $donation = Donation::create([
            'user_id' => $this->donor->id,
            'title' => 'Bakery Surplus Croissants',
            'description' => 'Fresh butter croissants',
            'quantity' => 20,
            'unit' => 'items',
            'pickup_address' => 'Bandar Sunway',
            'expiry_date' => now()->addDay(),
            'status' => 'available',
        ]);

        // Moderator cannot delete donations (Moderator safeguard policy check)
        $response = $this->actingAs($this->moderator)->delete(route('donations.destroy', $donation));
        $response->assertStatus(403);
    }

    public function test_form_request_vehicle_assignment_validation(): void
    {
        $donation = Donation::create([
            'user_id' => $this->donor->id,
            'title' => 'Fresh Bread Loaves',
            'description' => 'Wholemeal bread surplus',
            'quantity' => 10,
            'unit' => 'boxes',
            'pickup_address' => 'Subang Jaya',
            'expiry_date' => now()->addDays(2),
            'status' => 'claimed',
        ]);

        $claim = Claim::create([
            'user_id' => $this->ngo->id,
            'donation_id' => $donation->id,
            'justification' => 'Food distribution for shelter',
            'pickup_scheduled_at' => now()->addDay(),
            'status' => 'approved',
        ]);

        // Assign vehicle with invalid short phone number should fail form validation
        $response = $this->actingAs($this->ngo)->post(route('claims.vehicle', $claim), [
            'plate_number' => 'W1234A',
            'vehicle_type' => 'van',
            'driver_name' => 'John Driver',
            'driver_phone' => '123', // Invalid short phone number
        ]);

        $response->assertSessionHasErrors('driver_phone');

        // Valid vehicle assignment
        $validResponse = $this->actingAs($this->ngo)->post(route('claims.vehicle', $claim), [
            'plate_number' => 'W1234A',
            'vehicle_type' => 'van',
            'driver_name' => 'John Driver',
            'driver_phone' => '012-3456789',
        ]);

        $validResponse->assertRedirect(route('claims.show', $claim));
        $this->assertDatabaseHas('vehicles', [
            'claim_id' => $claim->id,
            'plate_number' => 'W1234A',
        ]);
    }

    public function test_inventory_web_service_status_and_food_safety(): void
    {
        $location = InventoryLocation::create([
            'user_id' => $this->ngo->id,
            'name' => 'Petaling Jaya Cold Warehouse',
            'address' => 'SS2 Petaling Jaya',
            'storage_type' => 'cold',
            'capacity' => 800,
            'current_occupancy' => 150,
        ]);

        $item = \App\Models\FoodItem::create([
            'user_id' => $this->ngo->id,
            'name' => 'Pasteurized Fresh Milk',
            'quantity' => 50,
            'unit' => 'litres',
            'expiry_date' => now()->addDays(5),
            'inventory_location_id' => $location->id,
        ]);

        // Test GET /api/inventory/status (IFA standard)
        $statusResponse = $this->getJson(route('api.inventory.status') . '?requestID=REQ-INV-001&timestamp=' . now()->toIso8601String());
        $statusResponse->assertStatus(200);
        $statusResponse->assertJsonPath('status', 'S');
        $statusResponse->assertJsonPath('data.requestID', 'REQ-INV-001');

        // Test POST /api/inventory/food-safety-check (IFA standard)
        $safetyResponse = $this->postJson(route('api.inventory.safety-check'), [
            'requestID' => 'REQ-SAFE-002',
            'timestamp' => now()->toIso8601String(),
            'food_item_id' => $item->id,
        ]);

        $safetyResponse->assertStatus(200);
        $safetyResponse->assertJsonPath('status', 'S');
        $safetyResponse->assertJsonPath('data.food_item.safety_status', 'SAFE');
    }

    public function test_inventory_location_store_form_request(): void
    {
        $response = $this->actingAs($this->ngo)->post(route('inventory.store'), [
            'name' => 'Klang Ambient Facility',
            'address' => 'Port Klang Warehouse Zone',
            'storage_type' => 'ambient',
            'capacity' => 1200,
        ]);

        $response->assertRedirect(route('inventory.index'));
        $this->assertDatabaseHas('inventory_locations', [
            'name' => 'Klang Ambient Facility',
            'storage_type' => 'ambient',
        ]);
    }

    public function test_report_generation_form_request(): void
    {
        $response = $this->actingAs($this->admin)->post(route('reports.store'), [
            'type' => 'sdg_impact',
            'title' => 'Q3 2026 UN SDG 2 Impact Comprehensive Audit',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reports', [
            'title' => 'Q3 2026 UN SDG 2 Impact Comprehensive Audit',
            'type' => 'sdg_impact',
        ]);
    }

    public function test_submit_user_review_form_request(): void
    {
        $response = $this->actingAs($this->donor)->post(route('reviews.submit', $this->ngo), [
            'rating' => 5,
            'comment' => 'Outstanding punctuality, hygienic handling, and smooth collection process!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'reviewer_id' => $this->donor->id,
            'reviewee_id' => $this->ngo->id,
            'rating' => 5,
        ]);
    }

    public function test_claims_show_page_renders_successfully(): void
    {
        $donation = Donation::create([
            'user_id' => $this->donor->id,
            'category_id' => 1,
            'title' => 'Fresh Artisan Sourdough Bread',
            'description' => 'Surplus freshly baked sourdough loaves.',
            'quantity' => 20,
            'unit' => 'items',
            'pickup_address' => 'Sunway Pyramid Shopping Mall',
            'expiry_date' => now()->addDays(2),
            'status' => 'claimed',
        ]);

        $claim = Claim::create([
            'user_id' => $this->ngo->id,
            'donation_id' => $donation->id,
            'justification' => 'Food distribution for urban community center.',
            'pickup_scheduled_at' => now()->addDay(),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->ngo)->get(route('claims.show', $claim));
        $response->assertStatus(200);
        $response->assertSee('Claim #' . $claim->id);
        $response->assertSee('Fresh Artisan Sourdough Bread');
    }
}

