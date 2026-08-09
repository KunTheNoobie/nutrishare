<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Donation;
use App\Models\Category;
use App\Models\AllergenTag;
use App\Models\Claim;
use App\Models\InventoryLocation;
use App\Models\SystemLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NutriShareSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_creation_and_roles(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test@nutrishare.com',
            'password' => bcrypt('Password1!'),
            'role' => 'admin'
        ]);

        $this->assertEquals('admin', $admin->role);
        $this->assertTrue($admin->isAdmin());
    }

    public function test_donation_and_category_relationship(): void
    {
        $donor = User::create([
            'name' => 'Test Donor',
            'email' => 'donor_test@nutrishare.com',
            'password' => bcrypt('Password1!'),
            'role' => 'donor'
        ]);

        $category = Category::create([
            'name' => 'Fresh Bakery',
            'description' => 'Bakery goods'
        ]);

        $donation = Donation::create([
            'user_id' => $donor->id,
            'title' => 'Sourdough Bread',
            'description' => 'Fresh bread',
            'quantity' => 10,
            'unit' => 'items',
            'pickup_address' => 'Test Address',
            'expiry_date' => now()->addDays(2),
            'status' => 'available'
        ]);

        $this->assertEquals('available', $donation->status);
        $this->assertEquals($donor->id, $donation->user_id);
    }

    public function test_system_log_auto_population(): void
    {
        $log = SystemLog::create([
            'action' => 'test.action',
            'description' => 'Test system action log.',
            'level' => 'info'
        ]);

        $this->assertEquals('127.0.0.1', $log->ip_address);
        $this->assertNotEmpty($log->user_agent);
    }
}
