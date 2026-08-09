<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Donation;
use App\Models\Claim;
use App\Models\FoodItem;
use App\Models\Category;
use App\Models\AllergenTag;
use App\Models\InventoryLocation;
use App\Models\Vehicle;
use App\Models\CollectionReceipt;
use App\Models\DistributionLog;
use App\Models\VerificationDocument;
use App\Models\Review;
use App\Models\Report;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\SystemLog;
use App\Models\PasswordResetOtp;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Password1!');

        // ──────────────────────────────────────────────────────────
        // 1. Categories (10 Records)
        // ──────────────────────────────────────────────────────────
        $categoriesData = [
            'Fresh Produce' => 'Fresh organic fruits and vegetables from local farms and markets.',
            'Bakery & Pastry' => 'Freshly baked artisanal breads, rolls, and morning pastries.',
            'Dairy & Eggs' => 'Fresh milk, artisan cheeses, farm eggs, and plant-based alternatives.',
            'Meat & Poultry' => 'Quality meat cuts, poultry, chicken breast, and beef.',
            'Seafood & Fish' => 'Fresh or frozen fish fillets, salmon, and seafood.',
            'Pantry & Canned Goods' => 'Non-perishable canned goods, pasta, rice, and pantry staples.',
            'Prepared Meals' => 'Ready-to-eat gourmet cooked meals and catering surplus.',
            'Beverages & Juices' => 'Fresh fruit juices, milk tea, mineral water, and healthy drinks.',
            'Frozen Foods' => 'Frozen vegetables, frozen ready meals, and blast-frozen items.',
            'Baby Food & Formula' => 'Nutritious infant formula, cereal packs, and fruit purée pouches.',
        ];
        $catModels = [];
        foreach ($categoriesData as $name => $desc) {
            $catModels[$name] = Category::updateOrCreate(['name' => $name], ['description' => $desc]);
        }

        // ──────────────────────────────────────────────────────────
        // 2. Allergen Tags (10 Records)
        // ──────────────────────────────────────────────────────────
        $allergensData = [
            'Gluten', 'Dairy', 'Contains Nuts', 'Soy', 'Egg', 
            'Seafood', 'Peanuts', 'Sesame', 'Mustard', 'Sulfites'
        ];
        $tagModels = [];
        foreach ($allergensData as $name) {
            $tagModels[$name] = AllergenTag::updateOrCreate(['name' => $name]);
        }

        // ──────────────────────────────────────────────────────────
        // 3. User Accounts (12 Records: Admin, Mods, Donors, NGOs)
        // ──────────────────────────────────────────────────────────
        $usersData = [
            // Admin & Mods
            ['email' => 'admin@nutrishare.com', 'name' => 'System Admin', 'role' => 'admin', 'org' => 'NutriShare HQ', 'phone' => '+60100000001', 'status' => 'approved'],
            ['email' => 'moderator@nutrishare.com', 'name' => 'Platform Moderator', 'role' => 'moderator', 'org' => 'NutriShare Compliance', 'phone' => '+60100000002', 'status' => 'approved'],
            ['email' => 'mod2@nutrishare.com', 'name' => 'Sarah Lin (Mod)', 'role' => 'moderator', 'org' => 'NutriShare Safety', 'phone' => '+60100000003', 'status' => 'approved'],
            
            // NGOs
            ['email' => 'ngo@nutrishare.com', 'name' => 'Food Rescue Foundation', 'role' => 'ngo', 'org' => 'Food Rescue Foundation (BBB)', 'phone' => '+60123456789', 'status' => 'approved', 'addr' => '100 Community Way, KL'],
            ['email' => 'kechara@nutrishare.com', 'name' => 'Kechara Soup Kitchen', 'role' => 'ngo', 'org' => 'Kechara Soup Kitchen Society', 'phone' => '+60169876543', 'status' => 'approved', 'addr' => '17 Jalan Barat, KL'],
            ['email' => 'mykasih@nutrishare.com', 'name' => 'MyKasih Foundation', 'role' => 'ngo', 'org' => 'MyKasih Charity Trust', 'phone' => '+60134445555', 'status' => 'approved', 'addr' => 'Level 8, Menara LGB, TTDI, KL'],
            ['email' => 'pichaeats@nutrishare.com', 'name' => 'PichaEats Social Enterprise', 'role' => 'ngo', 'org' => 'PichaEats Relief', 'phone' => '+60178889999', 'status' => 'pending', 'addr' => '25 Jalan Bangsar, KL'],
            
            // Donors
            ['email' => 'donor@nutrishare.com', 'name' => 'Sunway Bakery & Grocer', 'role' => 'donor', 'org' => 'Sunway Bakery & Grocer AAA', 'phone' => '+60198887777', 'status' => 'approved', 'addr' => '789 Sunway Ave, PJ'],
            ['email' => 'jayagrocer@nutrishare.com', 'name' => 'Jaya Grocer Supermarket', 'role' => 'donor', 'org' => 'Jaya Grocer Outlets', 'phone' => '+60123334444', 'status' => 'approved', 'addr' => '12 Plaza Damansara, KL'],
            ['email' => 'lotus@nutrishare.com', 'name' => 'Lotus Hypermarket Malaysia', 'role' => 'donor', 'org' => 'Lotus Stores Malaysia', 'phone' => '+60187776666', 'status' => 'approved', 'addr' => '3 Jalan Kepong, KL'],
            ['email' => 'shangrila@nutrishare.com', 'name' => 'Shangri-La Hotel Catering', 'role' => 'donor', 'org' => 'Shangri-La Executive Kitchen', 'phone' => '+60112223333', 'status' => 'approved', 'addr' => '11 Jalan Sultan Ismail, KL'],
            ['email' => 'bens@nutrishare.com', 'name' => 'Ben Independent Grocer', 'role' => 'donor', 'org' => 'BIG Retail Sdn Bhd', 'phone' => '+60156667777', 'status' => 'approved', 'addr' => 'Publika Shopping Gallery, KL'],
        ];

        $userModels = [];
        foreach ($usersData as $u) {
            $userModels[$u['email']] = User::updateOrCreate(['email' => $u['email']], [
                'name' => $u['name'],
                'password' => $password,
                'role' => $u['role'],
                'organization_name' => $u['org'],
                'phone' => $u['phone'],
                'verification_status' => $u['status'],
                'email_verified_at' => now(),
                'address' => $u['addr'] ?? 'Kuala Lumpur, Malaysia',
                'notification_preference' => 'email',
            ]);
        }

        $donor1 = $userModels['donor@nutrishare.com'];
        $donor2 = $userModels['jayagrocer@nutrishare.com'];
        $donor3 = $userModels['lotus@nutrishare.com'];
        $donor4 = $userModels['shangrila@nutrishare.com'];
        $donor5 = $userModels['bens@nutrishare.com'];

        $ngo1 = $userModels['ngo@nutrishare.com'];
        $ngo2 = $userModels['kechara@nutrishare.com'];
        $ngo3 = $userModels['mykasih@nutrishare.com'];
        $ngo4 = $userModels['pichaeats@nutrishare.com'];

        // ──────────────────────────────────────────────────────────
        // 4. Surplus Food Donations (12 Records)
        // ──────────────────────────────────────────────────────────
        $donationsList = [
            [
                'user_id' => $donor1->id,
                'title' => 'Fresh Organic Fruits & Veggies Pack',
                'description' => 'Surplus organic honeycrisp apples, fresh kale, carrots, and avocados from morning stock. Excellent quality.',
                'quantity' => 120.50, 'unit' => 'kg', 'pickup_address' => '789 Sunway Ave, Loading Bay C, PJ',
                'latitude' => 3.0738, 'longitude' => 101.6074, 'expiry_date' => Carbon::now()->addDays(4), 'status' => 'available',
                'image_paths' => ['https://images.unsplash.com/photo-1610832958506-aa56368176cf?q=80&w=2070&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor1->id,
                'title' => 'Artisan Sourdough Breads & Pastries',
                'description' => 'Collection of fresh sourdough loaves, French baguettes, and croissants baked fresh this morning.',
                'quantity' => 45.00, 'unit' => 'items', 'pickup_address' => '789 Sunway Ave, Bakery Counter, PJ',
                'latitude' => 3.0738, 'longitude' => 101.6074, 'expiry_date' => Carbon::now()->addDays(2), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor2->id,
                'title' => 'Assorted Canned Soups & Pantry Boxes',
                'description' => 'Pallet of canned tomato soups, beans, and whole wheat pasta boxes. Long shelf life, ideal for pantry storage.',
                'quantity' => 250.00, 'unit' => 'items', 'pickup_address' => '12 Plaza Damansara, Storage B, KL',
                'latitude' => 3.1517, 'longitude' => 101.6558, 'expiry_date' => Carbon::now()->addMonths(6), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1584473457406-6240486418e9?q=80&w=2072&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor2->id,
                'title' => 'Grade A Farm Fresh Eggs & Dairy Milk',
                'description' => 'Cartons of pasteurized whole milk and fresh farm chicken eggs. Stored in cold chillers.',
                'quantity' => 80.00, 'unit' => 'boxes', 'pickup_address' => '12 Plaza Damansara, Cold Room, KL',
                'latitude' => 3.1517, 'longitude' => 101.6558, 'expiry_date' => Carbon::now()->addDays(5), 'status' => 'available',
                'image_paths' => ['https://images.unsplash.com/photo-1516467508483-a7212febe31a?q=80&w=2072&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor3->id,
                'title' => 'Cooked Gourmet Buffet Trays (Unserved)',
                'description' => 'Unserved hotel gala dinner trays: roasted chicken breast, mixed vegetables, and butter rice. Blast frozen.',
                'quantity' => 20.00, 'unit' => 'boxes', 'pickup_address' => '3 Jalan Kepong, Loading Bay 1, KL',
                'latitude' => 3.2100, 'longitude' => 101.6300, 'expiry_date' => Carbon::now()->addDays(2), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1555244162-833832eb1cce?q=80&w=2070&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor3->id,
                'title' => 'Fresh Salmon Fillets & Seafood Pack',
                'description' => 'Chilled Atlantic salmon fillets and tiger prawns. Packed in ice crates ready for immediate pick up.',
                'quantity' => 35.00, 'unit' => 'kg', 'pickup_address' => '3 Jalan Kepong, Fish Counter, KL',
                'latitude' => 3.2100, 'longitude' => 101.6300, 'expiry_date' => Carbon::now()->addDays(1), 'status' => 'available',
                'image_paths' => ['https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=2070&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor4->id,
                'title' => 'Cold Pressed Orange & Apple Juices',
                'description' => 'Bottles of 100% natural cold pressed orange and green apple juices. Refrigerated at 4°C.',
                'quantity' => 150.00, 'unit' => 'litres', 'pickup_address' => '11 Jalan Sultan Ismail, Kitchen, KL',
                'latitude' => 3.1530, 'longitude' => 101.7080, 'expiry_date' => Carbon::now()->addDays(3), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?q=80&w=2070&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor4->id,
                'title' => 'Premix Cereal & Breakfast Packs',
                'description' => 'Nutritious oats, cornflakes, and cereal grain boxes for breakfast shelter distribution.',
                'quantity' => 90.00, 'unit' => 'items', 'pickup_address' => '11 Jalan Sultan Ismail, Pantry, KL',
                'latitude' => 3.1530, 'longitude' => 101.7080, 'expiry_date' => Carbon::now()->addMonths(4), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1521483451569-e33803c0330c?q=80&w=2070&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor5->id,
                'title' => 'Infant Organic Cereal & Fruit Pouches',
                'description' => 'Organic baby rice cereal and puréed banana fruit pouches. Safe and sealed.',
                'quantity' => 110.00, 'unit' => 'items', 'pickup_address' => 'Publika Shopping Gallery, B.I.G., KL',
                'latitude' => 3.1708, 'longitude' => 101.6660, 'expiry_date' => Carbon::now()->addMonths(5), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1596797882870-8c33deeac224?q=80&w=2070&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor5->id,
                'title' => 'Frozen Mixed Peas & Corn Vegetables',
                'description' => 'Blast frozen green peas, sweet corn, and diced carrots in 1kg commercial packs.',
                'quantity' => 60.00, 'unit' => 'kg', 'pickup_address' => 'Publika Shopping Gallery, Freezer 4, KL',
                'latitude' => 3.1708, 'longitude' => 101.6660, 'expiry_date' => Carbon::now()->addMonths(3), 'status' => 'available',
                'image_paths' => ['https://images.unsplash.com/photo-1574316071802-0d684efa7bf5?q=80&w=2070&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor1->id,
                'title' => 'Whole Roasted Chicken Meal Trays',
                'description' => 'Roasted chicken meals served with gravy and mashed potatoes. Ready for immediate dinner service.',
                'quantity' => 30.00, 'unit' => 'boxes', 'pickup_address' => '789 Sunway Ave, Kitchen C, PJ',
                'latitude' => 3.0738, 'longitude' => 101.6074, 'expiry_date' => Carbon::now()->addHours(36), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?q=80&w=2070&auto=format&fit=crop']
            ],
            [
                'user_id' => $donor2->id,
                'title' => 'Surplus Mineral Water Crate Packs',
                'description' => '500ml mineral water bottles packaged in shrink-wrapped 24-bottle crates.',
                'quantity' => 40.00, 'unit' => 'boxes', 'pickup_address' => '12 Plaza Damansara, Bay 3, KL',
                'latitude' => 3.1517, 'longitude' => 101.6558, 'expiry_date' => Carbon::now()->addYear(), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1548839140-29a749e1bc4e?q=80&w=2070&auto=format&fit=crop']
            ]
        ];

        $donationModels = [];
        foreach ($donationsList as $d) {
            $donationModels[] = Donation::create($d);
        }

        // ──────────────────────────────────────────────────────────
        // 5. Inventory Locations (10 Records)
        // ──────────────────────────────────────────────────────────
        $locationsData = [
            ['user' => $ngo1, 'name' => 'Central Storage Facility (NGO Central Facility)', 'addr' => '100 Community Way, KL', 'type' => 'dry', 'cap' => 2000.0],
            ['user' => $ngo1, 'name' => 'Cold Storage Blast Freezer', 'addr' => '100 Community Way (Block B), KL', 'type' => 'frozen', 'cap' => 800.0],
            ['user' => $ngo1, 'name' => 'Pantry Storage Room A', 'addr' => '100 Community Way (Room A), KL', 'type' => 'ambient', 'cap' => 1200.0],
            ['user' => $ngo2, 'name' => 'Kechara Main Shelter Depot', 'addr' => '17 Jalan Barat, KL', 'type' => 'dry', 'cap' => 1500.0],
            ['user' => $ngo2, 'name' => 'Kechara Walk-in Cooler', 'addr' => '17 Jalan Barat (Kitchen), KL', 'type' => 'cold', 'cap' => 600.0],
            ['user' => $ngo3, 'name' => 'MyKasih Central Distribution Hub', 'addr' => 'Menara LGB, TTDI, KL', 'type' => 'dry', 'cap' => 3000.0],
            ['user' => $ngo3, 'name' => 'MyKasih Chilled Warehouse', 'addr' => 'Section 13, Petaling Jaya', 'type' => 'cold', 'cap' => 1000.0],
            ['user' => $ngo4, 'name' => 'PichaEats Kitchen Pantry', 'addr' => '25 Jalan Bangsar, KL', 'type' => 'ambient', 'cap' => 500.0],
            ['user' => $ngo4, 'name' => 'PichaEats Cold Storage Unit', 'addr' => '25 Jalan Bangsar (Unit 2), KL', 'type' => 'cold', 'cap' => 400.0],
            ['user' => $ngo1, 'name' => 'Emergency Relief Food Bank', 'addr' => 'Cheras Community Center, KL', 'type' => 'ambient', 'cap' => 2500.0],
        ];

        $invLocationModels = [];
        foreach ($locationsData as $loc) {
            $invLocationModels[] = InventoryLocation::create([
                'user_id' => $loc['user']->id,
                'name' => $loc['name'],
                'address' => $loc['addr'],
                'storage_type' => $loc['type'],
                'capacity' => $loc['cap'],
                'current_occupancy' => rand(50, (int)($loc['cap'] * 0.4))
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 6. Food Items (Linked to Donations & Inventory Locations)
        // ──────────────────────────────────────────────────────────
        $foodItemsList = [
            ['d' => $donationModels[0], 'loc' => $invLocationModels[0], 'cat' => $catModels['Fresh Produce'], 'name' => 'Organic Honeycrisp Apples', 'qty' => 50.0, 'unit' => 'kg', 'storage' => 'ambient', 'perish' => true],
            ['d' => $donationModels[0], 'loc' => $invLocationModels[1], 'cat' => $catModels['Fresh Produce'], 'name' => 'Fresh Kale & Spinach Bunches', 'qty' => 70.5, 'unit' => 'kg', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[1], 'loc' => $invLocationModels[3], 'cat' => $catModels['Bakery & Pastry'], 'name' => 'Artisan Sourdough Loaf', 'qty' => 45.0, 'unit' => 'items', 'storage' => 'dry', 'perish' => true],
            ['d' => $donationModels[2], 'loc' => $invLocationModels[3], 'cat' => $catModels['Pantry & Canned Goods'], 'name' => 'Tomato Soup Cans (400g)', 'qty' => 250.0, 'unit' => 'items', 'storage' => 'dry', 'perish' => false],
            ['d' => $donationModels[3], 'loc' => $invLocationModels[4], 'cat' => $catModels['Dairy & Eggs'], 'name' => 'Pasteurized Whole Milk Cartons', 'qty' => 80.0, 'unit' => 'boxes', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[4], 'loc' => $invLocationModels[4], 'cat' => $catModels['Prepared Meals'], 'name' => 'Roasted Chicken & Rice Trays', 'qty' => 20.0, 'unit' => 'boxes', 'storage' => 'frozen', 'perish' => true],
            ['d' => $donationModels[5], 'loc' => $invLocationModels[6], 'cat' => $catModels['Seafood & Fish'], 'name' => 'Fresh Atlantic Salmon Fillets', 'qty' => 35.0, 'unit' => 'kg', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[6], 'loc' => $invLocationModels[6], 'cat' => $catModels['Beverages & Juices'], 'name' => 'Cold Pressed Orange Juice (1L)', 'qty' => 150.0, 'unit' => 'litres', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[7], 'loc' => $invLocationModels[5], 'cat' => $catModels['Pantry & Canned Goods'], 'name' => 'Oats & Cornflakes Cereal Packs', 'qty' => 90.0, 'unit' => 'items', 'storage' => 'dry', 'perish' => false],
            ['d' => $donationModels[8], 'loc' => $invLocationModels[5], 'cat' => $catModels['Baby Food & Formula'], 'name' => 'Infant Purée Banana Pouches', 'qty' => 110.0, 'unit' => 'items', 'storage' => 'ambient', 'perish' => false],
            ['d' => $donationModels[9], 'loc' => $invLocationModels[1], 'cat' => $catModels['Frozen Foods'], 'name' => 'Frozen Sweet Corn & Green Peas', 'qty' => 60.0, 'unit' => 'kg', 'storage' => 'frozen', 'perish' => true],
            ['d' => $donationModels[10], 'loc' => $invLocationModels[3], 'cat' => $catModels['Meat & Poultry'], 'name' => 'Roasted Chicken Meal Boxes', 'qty' => 30.0, 'unit' => 'boxes', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[11], 'loc' => $invLocationModels[2], 'cat' => $catModels['Beverages & Juices'], 'name' => 'Bottled Mineral Water Crates', 'qty' => 40.0, 'unit' => 'boxes', 'storage' => 'ambient', 'perish' => false],
            ['d' => $donationModels[1], 'loc' => $invLocationModels[7], 'cat' => $catModels['Bakery & Pastry'], 'name' => 'French Baguettes Pack', 'qty' => 25.0, 'unit' => 'items', 'storage' => 'ambient', 'perish' => true],
            ['d' => $donationModels[3], 'loc' => $invLocationModels[8], 'cat' => $catModels['Dairy & Eggs'], 'name' => 'Farm Fresh Eggs (30-egg Trays)', 'qty' => 15.0, 'unit' => 'boxes', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[2], 'loc' => $invLocationModels[9], 'cat' => $catModels['Pantry & Canned Goods'], 'name' => 'Black Beans Canned Packs', 'qty' => 100.0, 'unit' => 'items', 'storage' => 'ambient', 'perish' => false],
        ];

        $foodItemModels = [];
        foreach ($foodItemsList as $fi) {
            $foodItemModels[] = FoodItem::create([
                'donation_id' => $fi['d']->id,
                'inventory_location_id' => $fi['loc']->id,
                'category_id' => $fi['cat']->id,
                'name' => $fi['name'],
                'description' => 'High quality food item inspected for safety compliance.',
                'quantity' => $fi['qty'],
                'unit' => $fi['unit'],
                'expiry_date' => $fi['d']->expiry_date,
                'storage_requirements' => $fi['storage'],
                'is_perishable' => $fi['perish'],
                'image_paths' => $fi['d']->image_paths
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 7. Allergen-FoodItem Pivot Relationships
        // ──────────────────────────────────────────────────────────
        $foodItemModels[0]->allergenTags()->sync([$tagModels['Gluten']->id]);
        $foodItemModels[1]->allergenTags()->sync([$tagModels['Dairy']->id]);
        $foodItemModels[2]->allergenTags()->sync([$tagModels['Gluten']->id]);
        $foodItemModels[3]->allergenTags()->sync([$tagModels['Soy']->id]);
        $foodItemModels[4]->allergenTags()->sync([$tagModels['Dairy']->id, $tagModels['Egg']->id]);
        $foodItemModels[5]->allergenTags()->sync([$tagModels['Soy']->id, $tagModels['Gluten']->id]);
        $foodItemModels[6]->allergenTags()->sync([$tagModels['Seafood']->id]);
        $foodItemModels[7]->allergenTags()->sync([$tagModels['Sulfites']->id]);
        $foodItemModels[8]->allergenTags()->sync([$tagModels['Gluten']->id, $tagModels['Contains Nuts']->id]);
        $foodItemModels[9]->allergenTags()->sync([$tagModels['Soy']->id]);
        $foodItemModels[10]->allergenTags()->sync([$tagModels['Gluten']->id]);
        $foodItemModels[11]->allergenTags()->sync([$tagModels['Gluten']->id, $tagModels['Dairy']->id, $tagModels['Contains Nuts']->id]);

        // ──────────────────────────────────────────────────────────
        // 8. Claims (10 Records in Various Lifecycle States)
        // ──────────────────────────────────────────────────────────
        $claimsData = [
            ['don' => $donationModels[1], 'ngo' => $ngo1, 'status' => 'approved', 'just' => 'Distributing artisan sourdough breads for morning breakfast service.'],
            ['don' => $donationModels[2], 'ngo' => $ngo1, 'status' => 'collected', 'just' => 'Stocking central community pantry with canned soups.'],
            ['don' => $donationModels[4], 'ngo' => $ngo2, 'status' => 'collected', 'just' => 'Providing gourmet meal boxes to homeless shelter residents.'],
            ['don' => $donationModels[6], 'ngo' => $ngo2, 'status' => 'approved', 'just' => 'Cold pressed juice distribution for children health program.'],
            ['don' => $donationModels[7], 'ngo' => $ngo3, 'status' => 'collected', 'just' => 'Cereal packs for B40 school student breakfast assistance.'],
            ['don' => $donationModels[8], 'ngo' => $ngo3, 'status' => 'collected', 'just' => 'Infant cereal purée pouches for single mother support group.'],
            ['don' => $donationModels[10], 'ngo' => $ngo1, 'status' => 'collected', 'just' => 'Whole roasted chicken meal boxes for community dinner.'],
            ['don' => $donationModels[11], 'ngo' => $ngo2, 'status' => 'collected', 'just' => 'Mineral water crate distribution for flood relief center.'],
            ['don' => $donationModels[0], 'ngo' => $ngo3, 'status' => 'pending', 'just' => 'Fresh organic produce needed for weekend community soup kitchen.'],
            ['don' => $donationModels[3], 'ngo' => $ngo2, 'status' => 'pending', 'just' => 'Farm fresh eggs and milk needed for children shelter nutrition.'],
        ];

        $claimModels = [];
        foreach ($claimsData as $cd) {
            $claimModels[] = Claim::create([
                'donation_id' => $cd['don']->id,
                'user_id' => $cd['ngo']->id,
                'status' => $cd['status'],
                'pickup_scheduled_at' => Carbon::now()->addHours(rand(2, 24)),
                'justification' => $cd['just']
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 9. Vehicles (10 Records)
        // ──────────────────────────────────────────────────────────
        $vehiclesData = [
            ['claim' => $claimModels[0], 'plate' => 'VHT1484', 'type' => 'van', 'driver' => 'Bala Subra', 'phone' => '+60129998888', 'cap' => 800.0],
            ['claim' => $claimModels[1], 'plate' => 'WKT8899', 'type' => 'truck', 'driver' => 'Ahmad Razak', 'phone' => '+60172223333', 'cap' => 2500.0],
            ['claim' => $claimModels[2], 'plate' => 'BND5050', 'type' => 'van', 'driver' => 'Lee Wei Hong', 'phone' => '+60193332222', 'cap' => 1000.0],
            ['claim' => $claimModels[3], 'plate' => 'KDD1234', 'type' => 'car', 'driver' => 'David Tan', 'phone' => '+60114445555', 'cap' => 400.0],
            ['claim' => $claimModels[4], 'plate' => 'PKK7788', 'type' => 'truck', 'driver' => 'Muthu Kumar', 'phone' => '+60165554444', 'cap' => 3000.0],
            ['claim' => $claimModels[5], 'plate' => 'VAA9911', 'type' => 'van', 'driver' => 'Siti Nurhaliza', 'phone' => '+60189990000', 'cap' => 750.0],
            ['claim' => $claimModels[6], 'plate' => 'WYY3344', 'type' => 'truck', 'driver' => 'Chong Meng', 'phone' => '+60132221111', 'cap' => 2000.0],
            ['claim' => $claimModels[7], 'plate' => 'BPL8822', 'type' => 'van', 'driver' => 'Hassan Basri', 'phone' => '+60176665555', 'cap' => 900.0],
            ['claim' => $claimModels[8], 'plate' => 'KMC4455', 'type' => 'motorcycle', 'driver' => 'Ravi Arumugam', 'phone' => '+60148883333', 'cap' => 100.0],
            ['claim' => $claimModels[9], 'plate' => 'WXC6677', 'type' => 'van', 'driver' => 'Jason Wong', 'phone' => '+60127774444', 'cap' => 850.0],
        ];

        foreach ($vehiclesData as $v) {
            Vehicle::create([
                'claim_id' => $v['claim']->id,
                'plate_number' => $v['plate'],
                'vehicle_type' => $v['type'],
                'driver_name' => $v['driver'],
                'driver_phone' => $v['phone'],
                'capacity_kg' => $v['cap']
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 10. Collection Receipts (10 Records)
        // ──────────────────────────────────────────────────────────
        $collectedClaims = [$claimModels[1], $claimModels[2], $claimModels[4], $claimModels[5], $claimModels[6], $claimModels[7], $claimModels[0], $claimModels[3], $claimModels[8], $claimModels[9]];
        foreach ($collectedClaims as $idx => $clm) {
            CollectionReceipt::create([
                'claim_id' => $clm->id,
                'receipt_number' => 'REC-NUTRI-' . date('Ymd') . '-' . sprintf('%03d', $idx + 1),
                'quantity_collected' => $clm->donation->quantity,
                'unit' => $clm->donation->unit,
                'collected_by' => $clm->user->organization_name ?? $clm->user->name,
                'condition_notes' => 'Inspected at pickup site — good quality and safety compliance verified.',
                'collected_at' => Carbon::now()->subHours(rand(1, 48))
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 11. Distribution Logs (10 Records for SDG 2 Impact)
        // ──────────────────────────────────────────────────────────
        foreach ($collectedClaims as $idx => $clm) {
            DistributionLog::create([
                'claim_id' => $clm->id,
                'beneficiaries_count' => rand(40, 200),
                'distribution_location' => $clm->user->address ?? 'Kuala Lumpur Community Distribution Hub',
                'quantity_distributed' => $clm->donation->quantity,
                'unit' => $clm->donation->unit,
                'notes' => 'Direct distribution to B40 families and shelter residents under SDG 2 Zero Hunger program.',
                'distributed_at' => Carbon::now()->subHours(rand(1, 24))
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 12. NGO Verification Documents (10 Records)
        // ──────────────────────────────────────────────────────────
        $docsData = [
            ['user' => $ngo1, 'type' => 'registration_cert', 'file' => 'verification_documents/ngo1_cert.pdf', 'status' => 'approved', 'remarks' => 'Verified against Registrar of Societies Malaysia.'],
            ['user' => $ngo1, 'type' => 'tax_exempt', 'file' => 'verification_documents/ngo1_tax.pdf', 'status' => 'approved', 'remarks' => 'Inland Revenue Board tax exemption approved.'],
            ['user' => $ngo2, 'type' => 'registration_cert', 'file' => 'verification_documents/ngo2_cert.pdf', 'status' => 'approved', 'remarks' => 'ROS Certificate confirmed active.'],
            ['user' => $ngo2, 'type' => 'license', 'file' => 'verification_documents/ngo2_license.pdf', 'status' => 'approved', 'remarks' => 'Food Handler Hygiene License valid.'],
            ['user' => $ngo3, 'type' => 'registration_cert', 'file' => 'verification_documents/ngo3_cert.pdf', 'status' => 'approved', 'remarks' => 'MyKasih Trust Charter verified.'],
            ['user' => $ngo3, 'type' => 'tax_exempt', 'file' => 'verification_documents/ngo3_tax.pdf', 'status' => 'approved', 'remarks' => 'Tax exemption document verified.'],
            ['user' => $ngo4, 'type' => 'registration_cert', 'file' => 'verification_documents/ngo4_cert.pdf', 'status' => 'pending', 'remarks' => 'Awaiting admin verification check.'],
            ['user' => $ngo4, 'type' => 'license', 'file' => 'verification_documents/ngo4_license.pdf', 'status' => 'pending', 'remarks' => 'Under review by platform moderator.'],
            ['user' => $ngo1, 'type' => 'license', 'file' => 'verification_documents/ngo1_license.pdf', 'status' => 'approved', 'remarks' => 'State Health Dept Food Premises License approved.'],
            ['user' => $ngo2, 'type' => 'tax_exempt', 'file' => 'verification_documents/ngo2_tax.pdf', 'status' => 'approved', 'remarks' => 'LHDN Tax Exemption status active.'],
        ];

        foreach ($docsData as $doc) {
            VerificationDocument::create([
                'user_id' => $doc['user']->id,
                'document_type' => $doc['type'],
                'file_path' => $doc['file'],
                'original_filename' => basename($doc['file']),
                'status' => $doc['status'],
                'admin_remarks' => $doc['remarks'],
                'reviewed_by' => 1,
                'reviewed_at' => $doc['status'] !== 'pending' ? Carbon::now()->subDays(rand(1, 10)) : null
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 13. User Reviews & Ratings (10 Records)
        // ──────────────────────────────────────────────────────────
        $reviewsData = [
            ['rev' => $donor1, 'target' => $ngo1, 'rating' => 5, 'comment' => 'Punctual driver, smooth communication, and great food handling!'],
            ['rev' => $donor2, 'target' => $ngo1, 'rating' => 5, 'comment' => 'Very professional team. They collected 250 canned items cleanly.'],
            ['rev' => $donor3, 'target' => $ngo2, 'rating' => 5, 'comment' => 'Kechara team arrived right on schedule with insulated boxes.'],
            ['rev' => $donor4, 'target' => $ngo2, 'rating' => 4, 'comment' => 'Great initiative! Cold juices were picked up safely.'],
            ['rev' => $donor4, 'target' => $ngo3, 'rating' => 5, 'comment' => 'MyKasih team handled cereal pack distribution seamlessly.'],
            ['rev' => $donor5, 'target' => $ngo3, 'rating' => 5, 'comment' => 'Excellent communication and swift pickup at Publika.'],
            ['rev' => $ngo1, 'target' => $donor1, 'rating' => 5, 'comment' => 'Sunway Bakery provides pristine organic produce and breads!'],
            ['rev' => $ngo2, 'target' => $donor2, 'rating' => 5, 'comment' => 'Jaya Grocer is a consistent donor helping hundreds of families.'],
            ['rev' => $ngo3, 'target' => $donor3, 'rating' => 5, 'comment' => 'Shangri-La Executive kitchen staff packaged meals perfectly.'],
            ['rev' => $ngo1, 'target' => $donor5, 'rating' => 5, 'comment' => 'High quality baby cereal pouches received in perfect condition.'],
        ];

        foreach ($reviewsData as $r) {
            Review::create([
                'reviewer_id' => $r['rev']->id,
                'reviewee_id' => $r['target']->id,
                'rating' => $r['rating'],
                'comment' => $r['comment']
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 14. Notification Templates (10 Records)
        // ──────────────────────────────────────────────────────────
        $templatesData = [
            ['name' => 'donation_created', 'subject' => 'New Donation Published', 'body' => 'A new food donation "{donation_title}" has been published by {donor_name}.'],
            ['name' => 'donation_claimed', 'subject' => 'Donation Claimed Alert', 'body' => 'Your donation "{donation_title}" was claimed by {ngo_name}.'],
            ['name' => 'claim_approved', 'subject' => 'Claim Approved', 'body' => 'Your claim for "{donation_title}" has been approved! Please schedule pickup.'],
            ['name' => 'claim_rejected', 'subject' => 'Claim Status Update', 'body' => 'Your claim for "{donation_title}" could not be fulfilled.'],
            ['name' => 'claim_collected', 'subject' => 'Donation Collection Completed', 'body' => 'Donation "{donation_title}" has been marked as collected.'],
            ['name' => 'verification_approved', 'subject' => 'NGO Verification Approved', 'body' => 'Congratulations! Your NGO organization verification document was approved.'],
            ['name' => 'verification_rejected', 'subject' => 'Verification Document Action Required', 'body' => 'Your verification document requires update: {remarks}.'],
            ['name' => 'review_submitted', 'subject' => 'New Peer Review Received', 'body' => '{reviewer_name} submitted a {rating}-star review for your organization.'],
            ['name' => 'report_generated', 'subject' => 'SDG Impact Report Generated', 'body' => 'A new platform analytics report "{report_title}" is ready.'],
            ['name' => 'login_alert', 'subject' => 'Account Security Login Alert', 'body' => 'Successful sign-in detected for {user_name} on {timestamp}.'],
        ];

        $templateModels = [];
        foreach ($templatesData as $t) {
            $templateModels[] = NotificationTemplate::create($t);
        }

        // ──────────────────────────────────────────────────────────
        // 15. In-App Notifications (12 Records)
        // ──────────────────────────────────────────────────────────
        for ($i = 0; $i < 12; $i++) {
            Notification::create([
                'user_id' => ($i % 2 === 0) ? $donor1->id : $ngo1->id,
                'notification_template_id' => $templateModels[$i % 10]->id,
                'donation_id' => $donationModels[$i % 12]->id,
                'title' => $templateModels[$i % 10]->subject,
                'message' => 'Notification alert update regarding NutriShare platform activities.',
                'channel' => 'email',
                'is_read' => ($i % 3 === 0),
                'sent_at' => Carbon::now()->subHours($i * 3)
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 16. System Audit Logs (12 Records)
        // ──────────────────────────────────────────────────────────
        $logsData = [
            ['user' => $userModels['admin@nutrishare.com'], 'act' => 'user.login', 'desc' => 'System Admin signed in to admin dashboard.', 'lvl' => 'info'],
            ['user' => $userModels['moderator@nutrishare.com'], 'act' => 'ngo.verified', 'desc' => 'Moderator approved Food Rescue Foundation registration document.', 'lvl' => 'info'],
            ['user' => $donor1, 'act' => 'donation.created', 'desc' => 'Donor published Fresh Organic Fruits & Veggies Pack (120.5 kg).', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'claim.created', 'desc' => 'NGO submitted claim for Artisan Sourdough Breads & Pastries.', 'lvl' => 'info'],
            ['user' => $donor1, 'act' => 'claim.approved', 'desc' => 'Donor approved claim from Food Rescue Foundation.', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'vehicle.assigned', 'desc' => 'Assigned pickup van VHT1484 (Driver Bala Subra).', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'receipt.generated', 'desc' => 'Generated collection receipt REC-NUTRI-20260802-001.', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'distribution.logged', 'desc' => 'Recorded distribution log: 125 beneficiaries fed in KL.', 'lvl' => 'info'],
            ['user' => $userModels['admin@nutrishare.com'], 'act' => 'report.generated', 'desc' => 'Admin generated SDG 2 Zero Hunger Impact Report Q3 2026.', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'inventory.created', 'desc' => 'Registered Central Storage Facility (Capacity: 2,000 kg).', 'lvl' => 'info'],
            ['user' => $donor2, 'act' => 'donation.updated', 'desc' => 'Updated pickup availability details for Canned Soups.', 'lvl' => 'info'],
            ['user' => $userModels['mod2@nutrishare.com'], 'act' => 'system.audit', 'desc' => 'Platform Moderator completed security compliance check.', 'lvl' => 'info'],
        ];

        foreach ($logsData as $l) {
            SystemLog::create([
                'user_id' => $l['user']->id,
                'action' => $l['act'],
                'description' => $l['desc'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'level' => $l['lvl']
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 17. Reports (10 Records)
        // ──────────────────────────────────────────────────────────
        $reportsList = [
            ['title' => 'SDG 2 Zero Hunger Impact Report (Q3 2026)', 'type' => 'sdg_impact'],
            ['title' => 'Monthly Surplus Food Redistribution Summary', 'type' => 'donation_summary'],
            ['title' => 'NGO & Donor User Platform Activity Log', 'type' => 'user_activity'],
            ['title' => 'Kuala Lumpur Metro Food Rescue Metrics', 'type' => 'sdg_impact'],
            ['title' => 'Petaling Jaya Bakery & Produce Rescue Overview', 'type' => 'donation_summary'],
            ['title' => 'B40 Beneficiary Distribution Analytics', 'type' => 'sdg_impact'],
            ['title' => 'Supermarket & Grocer Surplus Audit', 'type' => 'donation_summary'],
            ['title' => 'Hotel & Catering Meals Waste Reduction Report', 'type' => 'sdg_impact'],
            ['title' => 'Platform Trust & Peer Review Rating Matrix', 'type' => 'user_activity'],
            ['title' => 'Annual Zero Hunger SDG Impact Assessment 2026', 'type' => 'sdg_impact'],
        ];

        foreach ($reportsList as $rep) {
            Report::create([
                'user_id' => 1,
                'title' => $rep['title'],
                'type' => $rep['type'],
                'content' => 'NutriShare platform analytics report facilitating surplus food rescue and SDG 2 Zero Hunger impact.',
                'report_date' => Carbon::now()->subDays(rand(1, 30))
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 18. Password Reset OTPs (10 Records)
        // ──────────────────────────────────────────────────────────
        $otpsList = [
            ['email' => 'ngo@nutrishare.com', 'otp' => '123456', 'verified' => true],
            ['email' => 'donor@nutrishare.com', 'otp' => '654321', 'verified' => false],
            ['email' => 'kechara@nutrishare.com', 'otp' => '888999', 'verified' => true],
            ['email' => 'jayagrocer@nutrishare.com', 'otp' => '112233', 'verified' => false],
            ['email' => 'mykasih@nutrishare.com', 'otp' => '445566', 'verified' => true],
            ['email' => 'lotus@nutrishare.com', 'otp' => '778899', 'verified' => false],
            ['email' => 'shangrila@nutrishare.com', 'otp' => '990011', 'verified' => true],
            ['email' => 'pichaeats@nutrishare.com', 'otp' => '223344', 'verified' => false],
            ['email' => 'bens@nutrishare.com', 'otp' => '556677', 'verified' => true],
            ['email' => 'moderator@nutrishare.com', 'otp' => '334455', 'verified' => false],
        ];

        foreach ($otpsList as $o) {
            PasswordResetOtp::create([
                'email' => $o['email'],
                'otp' => $o['otp'],
                'verified_at' => $o['verified'] ? Carbon::now() : null,
                'expires_at' => Carbon::now()->addMinutes(10)
            ]);
        }
    }
}
