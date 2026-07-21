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

        // 1. Seed Categories & Allergen Tags
        $categories = [
            'Fresh Produce' => 'Fresh fruits and vegetables.',
            'Bakery & Bread' => 'Baked goods, breads, and pastries.',
            'Dairy & Eggs' => 'Milk, cheese, eggs, and dairy alternatives.',
            'Meat & Seafood' => 'Fresh or frozen meat, poultry, and fish.',
            'Pantry & Canned Goods' => 'Non-perishable items, canned foods, pasta.',
            'Prepared Meals' => 'Ready-to-eat meals and cooked catering left-overs.',
        ];
        foreach ($categories as $name => $desc) {
            \App\Models\Category::updateOrCreate(['name' => $name], ['description' => $desc]);
        }

        $allergens = ['Contains Nuts', 'Gluten', 'Dairy', 'Soy', 'Egg', 'Shellfish'];
        foreach ($allergens as $name) {
            \App\Models\AllergenTag::updateOrCreate(['name' => $name]);
        }
        $catProduce = \App\Models\Category::where('name', 'Fresh Produce')->first();
        $catBakery = \App\Models\Category::where('name', 'Bakery & Bread')->first();
        $catPantry = \App\Models\Category::where('name', 'Pantry & Canned Goods')->first();
        $catMeals = \App\Models\Category::where('name', 'Prepared Meals')->first();

        // 2. Create Professional NGOs
        $ngo1 = User::updateOrCreate(['email' => 'soupkitchen@nutrishare.com'], [
            'name' => 'Community Hope Foundation',
            'password' => $password,
            'role' => 'ngo',
            'organization_name' => 'Community Hope Foundation',
            'phone' => '+1 (555) 019-2001',
            'verification_status' => 'approved',
            'email_verified_at' => now(),
            'address' => '100 Community Way, Metro City',
        ]);

        $ngo2 = User::updateOrCreate(['email' => 'shelter@nutrishare.com'], [
            'name' => 'Global Relief Initiative',
            'password' => $password,
            'role' => 'ngo',
            'organization_name' => 'Global Relief Inc.',
            'phone' => '+1 (555) 021-3992',
            'verification_status' => 'approved',
            'email_verified_at' => now(),
            'address' => '450 Safety Blvd, Metro City',
        ]);

        // 3. Create Professional Donors
        $donor1 = User::updateOrCreate(['email' => 'freshmart@nutrishare.com'], [
            'name' => 'Whole Foods Market',
            'password' => $password,
            'role' => 'donor',
            'phone' => '+1 (555) 100-2000',
            'address' => '789 Organic Ave, Metro City',
        ]);

        $donor2 = User::updateOrCreate(['email' => 'bistro@nutrishare.com'], [
            'name' => 'The Ritz-Carlton Culinary',
            'password' => $password,
            'role' => 'donor',
            'phone' => '+1 (555) 220-4000',
            'address' => '1 Luxury Plaza, Metro City',
        ]);
        
        $donor3 = User::updateOrCreate(['email' => 'bakery@nutrishare.com'], [
            'name' => 'Artisan Bakery Co.',
            'password' => $password,
            'role' => 'donor',
            'phone' => '+1 (555) 330-5000',
            'address' => '22 Sourdough Lane, Metro City',
        ]);

        // 4. Create High-Quality Donations
        $donationsData = [
            [
                'user_id' => $donor1->id,
                'title' => 'Premium Organic Produce Surplus',
                'description' => 'A large collection of completely fresh organic vegetables and fruits (apples, kale, carrots, and avocados) that were slightly overstocked. Excellent quality and perfectly safe for consumption.',
                'quantity' => 120.5,
                'unit' => 'kg',
                'pickup_address' => '789 Organic Ave, Loading Dock C, Metro City',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'expiry_date' => Carbon::now()->addDays(3),
                'status' => 'available',
                'image_path' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?q=80&w=2070&auto=format&fit=crop'
            ],
            [
                'user_id' => $donor1->id,
                'title' => 'Assorted Canned Soups and Pasta',
                'description' => 'Pallet of non-perishable canned tomato soups, beans, and dried pasta boxes. Approaching best-before dates but good for another 8 months.',
                'quantity' => 500,
                'unit' => 'items',
                'pickup_address' => '789 Organic Ave, Storage Facility B, Metro City',
                'latitude' => 40.7138,
                'longitude' => -74.0050,
                'expiry_date' => Carbon::now()->addMonths(8),
                'status' => 'available',
                'image_path' => 'https://images.unsplash.com/photo-1584473457406-6240486418e9?q=80&w=2072&auto=format&fit=crop'
            ],
            [
                'user_id' => $donor2->id,
                'title' => 'Gourmet Catering Trays (Unserved)',
                'description' => 'High-end prepared meals from yesterday\'s corporate gala. Includes 10 trays of roasted chicken and vegetables, and 5 trays of vegetarian lasagna. Stored immediately in cold blast freezers.',
                'quantity' => 15,
                'unit' => 'boxes',
                'pickup_address' => '1 Luxury Plaza, Kitchen Entrance, Metro City',
                'latitude' => 40.7580,
                'longitude' => -73.9855,
                'expiry_date' => Carbon::now()->addDays(2),
                'status' => 'available',
                'image_path' => 'https://images.unsplash.com/photo-1555244162-833832eb1cce?q=80&w=2070&auto=format&fit=crop'
            ],
            [
                'user_id' => $donor3->id,
                'title' => 'Artisan Sourdough and Baguettes',
                'description' => 'A massive batch of day-old artisan bread. Still soft and incredibly delicious, perfect for soup kitchens or sandwich drives.',
                'quantity' => 45,
                'unit' => 'items',
                'pickup_address' => '22 Sourdough Lane, Metro City',
                'latitude' => 40.7282,
                'longitude' => -73.9943,
                'expiry_date' => Carbon::now()->addDays(2),
                'status' => 'available',
                'image_path' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop'
            ],
            [
                'user_id' => $donor3->id,
                'title' => 'Assorted Breakfast Pastries',
                'description' => 'Croissants, muffins, and danishes left over from the morning rush. Must be consumed soon.',
                'quantity' => 60,
                'unit' => 'items',
                'pickup_address' => '22 Sourdough Lane, Metro City',
                'latitude' => 40.7282,
                'longitude' => -73.9943,
                'expiry_date' => Carbon::now()->addHours(24),
                'status' => 'claimed',
                'image_path' => 'https://images.unsplash.com/photo-1495147466023-ac5c588e2e40?q=80&w=2070&auto=format&fit=crop'
            ]
        ];

        foreach ($donationsData as $data) {
            $donation = Donation::create($data);
            
            // Add some food items to each donation
            if ($donation->title === 'Premium Organic Produce Surplus') {
                \App\Models\FoodItem::create([
                    'donation_id' => $donation->id,
                    'category_id' => $catProduce->id,
                    'name' => 'Organic Honeycrisp Apples',
                    'quantity' => 50,
                    'unit' => 'kg',
                    'expiry_date' => $donation->expiry_date,
                    'storage_requirements' => 'ambient',
                    'is_perishable' => true
                ]);
                \App\Models\FoodItem::create([
                    'donation_id' => $donation->id,
                    'category_id' => $catProduce->id,
                    'name' => 'Fresh Kale Bunches',
                    'quantity' => 70.5,
                    'unit' => 'kg',
                    'expiry_date' => $donation->expiry_date,
                    'storage_requirements' => 'cold',
                    'is_perishable' => true
                ]);
            }
        }

        // 5. Create Realistic Claims
        $claimedDonation = Donation::where('title', 'Assorted Breakfast Pastries')->first();
        if ($claimedDonation) {
            Claim::create([
                'donation_id' => $claimedDonation->id,
                'user_id' => $ngo1->id,
                'status' => 'approved',
                'pickup_scheduled_at' => Carbon::now()->addHours(2),
                'justification' => 'We will distribute these pastries immediately at our morning shelter breakfast service.'
            ]);
        }

        // 6. Create Premium Inventory Locations for NGOs
        InventoryLocation::create([
            'user_id' => $ngo1->id,
            'name' => 'Metro City Main Pantry',
            'address' => '100 Community Way, Metro City',
            'storage_type' => 'ambient',
            'capacity' => 1500.00,
            'current_occupancy' => 450.00
        ]);

        InventoryLocation::create([
            'user_id' => $ngo1->id,
            'name' => 'Commercial Blast Freezer',
            'address' => '100 Community Way (Facility B), Metro City',
            'storage_type' => 'frozen',
            'capacity' => 500.00,
            'current_occupancy' => 200.00
        ]);

        InventoryLocation::create([
            'user_id' => $ngo2->id,
            'name' => 'Downtown Shelter Coolers',
            'address' => '450 Safety Blvd, Metro City',
            'storage_type' => 'cold',
            'capacity' => 300.00,
            'current_occupancy' => 85.00
        ]);
    }
}
