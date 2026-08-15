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
}
