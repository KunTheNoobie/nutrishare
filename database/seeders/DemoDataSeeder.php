<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Donation;
use App\Models\Claim;
use App\Models\InventoryLocation;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Password1!');

        // 1. Create Extra NGOs
        $ngo1 = User::updateOrCreate(['email' => 'soupkitchen@nutrishare.com'], [
            'name' => 'Downtown Soup Kitchen',
            'password' => $password,
            'role' => 'ngo',
            'organization_name' => 'City Hope Foundation',
            'phone' => '555-0101',
            'verification_status' => 'approved',
            'email_verified_at' => now(),
        ]);

        $ngo2 = User::updateOrCreate(['email' => 'shelter@nutrishare.com'], [
            'name' => 'Safe Haven Shelter',
            'password' => $password,
            'role' => 'ngo',
            'organization_name' => 'Safe Haven Inc',
            'phone' => '555-0102',
            'verification_status' => 'approved',
            'email_verified_at' => now(),
        ]);

        // 2. Create Extra Donors
        $donor1 = User::updateOrCreate(['email' => 'freshmart@nutrishare.com'], [
            'name' => 'FreshMart Supermarket',
            'password' => $password,
            'role' => 'donor',
            'phone' => '555-0201',
        ]);

        $donor2 = User::updateOrCreate(['email' => 'bistro@nutrishare.com'], [
            'name' => 'Le Petit Bistro',
            'password' => $password,
            'role' => 'donor',
            'phone' => '555-0202',
        ]);
        
        $donor3 = User::updateOrCreate(['email' => 'bakery@nutrishare.com'], [
            'name' => 'Morning Fresh Bakery',
            'password' => $password,
            'role' => 'donor',
            'phone' => '555-0203',
        ]);

        // 3. Create Donations
        $donationsData = [
            [
                'user_id' => $donor1->id,
                'title' => 'Assorted Fresh Produce',
                'description' => 'A mix of slightly bruised but perfectly edible apples, bananas, and carrots.',
                'quantity' => 15.5,
                'unit' => 'kg',
                'pickup_address' => '123 Market St, Loading Bay A',
                'expiry_date' => Carbon::now()->addDays(2),
                'status' => 'available'
            ],
            [
                'user_id' => $donor1->id,
                'title' => 'Canned Beans and Soups',
                'description' => 'Overstocked items nearing their best-before dates. Safe to consume for another 6 months.',
                'quantity' => 50,
                'unit' => 'items',
                'pickup_address' => '123 Market St, Loading Bay B',
                'expiry_date' => Carbon::now()->addMonths(6),
                'status' => 'available'
            ],
            [
                'user_id' => $donor2->id,
                'title' => 'Prepared Pasta Trays',
                'description' => 'Unserved, untouched baked ziti and lasagna trays from yesterday\'s catering event. Kept frozen.',
                'quantity' => 10,
                'unit' => 'boxes',
                'pickup_address' => '45 Culinary Blvd, Kitchen Entrance',
                'expiry_date' => Carbon::now()->addDays(5),
                'status' => 'available'
            ],
            [
                'user_id' => $donor3->id,
                'title' => 'Day-old Bagels and Bread',
                'description' => 'A large bag of assorted bagels and sourdough loaves baked yesterday.',
                'quantity' => 25,
                'unit' => 'items',
                'pickup_address' => '88 Baker Avenue',
                'expiry_date' => Carbon::now()->addDays(1),
                'status' => 'available'
            ],
            [
                'user_id' => $donor3->id,
                'title' => 'Croissants and Pastries',
                'description' => 'Assorted sweet pastries. Best consumed immediately.',
                'quantity' => 12,
                'unit' => 'items',
                'pickup_address' => '88 Baker Avenue',
                'expiry_date' => Carbon::now()->addHours(12),
                'status' => 'claimed'
            ]
        ];

        foreach ($donationsData as $data) {
            Donation::create($data);
        }

        // Get the claimed donation for a claim record
        $claimedDonation = Donation::where('title', 'Croissants and Pastries')->first();

        // 4. Create Claims
        if ($claimedDonation) {
            Claim::create([
                'donation_id' => $claimedDonation->id,
                'user_id' => $ngo1->id,
                'status' => 'approved',
                'pickup_scheduled_at' => Carbon::now()->addHours(2),
            ]);
        }

        // 5. Create Inventory Locations for NGOs
        InventoryLocation::create([
            'user_id' => $ngo1->id,
            'name' => 'Main Pantry',
            'address' => '100 Charity Lane',
            'storage_type' => 'ambient',
            'capacity' => 500.00,
            'current_occupancy' => 150.00
        ]);

        InventoryLocation::create([
            'user_id' => $ngo1->id,
            'name' => 'Walk-in Freezer',
            'address' => '100 Charity Lane (Back Unit)',
            'storage_type' => 'frozen',
            'capacity' => 200.00,
            'current_occupancy' => 50.00
        ]);

        InventoryLocation::create([
            'user_id' => $ngo2->id,
            'name' => 'Shelter Fridge',
            'address' => '200 Safe Street',
            'storage_type' => 'cold',
            'capacity' => 100.00,
            'current_occupancy' => 20.00
        ]);
    }
}
