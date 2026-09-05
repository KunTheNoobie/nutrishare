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
            ['email' => 'admin@nutrishare.com', 'name' => 'System Admin', 'role' => 'admin', 'org' => 'NutriShare HQ', 'phone' => '+60100000001', 'status' => 'approved', 'addr' => 'Level 18, Menara NutriShare, KL'],
            ['email' => 'moderator@nutrishare.com', 'name' => 'Platform Moderator', 'role' => 'moderator', 'org' => 'NutriShare Compliance', 'phone' => '+60100000002', 'status' => 'approved', 'addr' => 'NutriShare Compliance Wing, KL'],
            ['email' => 'mod2@nutrishare.com', 'name' => 'Sarah Lin', 'role' => 'moderator', 'org' => 'NutriShare Safety Unit', 'phone' => '+60100000003', 'status' => 'approved', 'addr' => 'NutriShare Safety Center, PJ'],
            
            // NGOs
            ['email' => 'ngo@nutrishare.com', 'name' => 'Food Rescue Foundation', 'role' => 'ngo', 'org' => 'Food Rescue Foundation', 'phone' => '+60123456789', 'status' => 'approved', 'addr' => '100 Community Way, KL'],
            ['email' => 'kechara@nutrishare.com', 'name' => 'Kechara Soup Kitchen', 'role' => 'ngo', 'org' => 'Kechara Soup Kitchen Society', 'phone' => '+60169876543', 'status' => 'approved', 'addr' => '17 Jalan Barat, KL'],
            ['email' => 'mykasih@nutrishare.com', 'name' => 'MyKasih Foundation', 'role' => 'ngo', 'org' => 'MyKasih Charity Trust', 'phone' => '+60134445555', 'status' => 'approved', 'addr' => 'Level 8, Menara LGB, TTDI, KL'],
            ['email' => 'pichaeats@nutrishare.com', 'name' => 'PichaEats Social Enterprise', 'role' => 'ngo', 'org' => 'PichaEats Relief', 'phone' => '+60178889999', 'status' => 'pending', 'addr' => '25 Jalan Bangsar, KL'],
            
            // Donors
            ['email' => 'donor@nutrishare.com', 'name' => 'Sunway Bakery & Grocer', 'role' => 'donor', 'org' => 'Sunway Bakery & Grocer', 'phone' => '+60198887777', 'status' => 'approved', 'addr' => '789 Sunway Ave, PJ'],
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
        // 4. Surplus Food Donations (16 Records)
        // ──────────────────────────────────────────────────────────
        $donationsList = [
            // 0: For Claim 0 (Pending)
            [
                'user_id' => $donor1->id,
                'title' => 'Fresh Organic Fruits & Veggies Pack',
                'description' => 'Surplus organic honeycrisp apples, fresh kale, carrots, and avocados from morning stock. Excellent quality.',
                'quantity' => 120.50, 'unit' => 'kg', 'pickup_address' => '789 Sunway Ave, Loading Bay C, PJ',
                'latitude' => 3.0738, 'longitude' => 101.6074, 'expiry_date' => Carbon::now()->addDays(4), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1610832958506-aa56368176cf?q=80&w=2070&auto=format&fit=crop']
            ],
            // 1: For Claim 1 (Pending)
            [
                'user_id' => $donor2->id,
                'title' => 'Grade A Farm Fresh Eggs & Dairy Milk',
                'description' => 'Cartons of pasteurized whole milk and fresh farm chicken eggs. Stored in cold chillers.',
                'quantity' => 80.00, 'unit' => 'boxes', 'pickup_address' => '12 Plaza Damansara, Cold Room, KL',
                'latitude' => 3.1517, 'longitude' => 101.6558, 'expiry_date' => Carbon::now()->addDays(5), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1516467508483-a7212febe31a?q=80&w=2072&auto=format&fit=crop']
            ],
            // 2: For Claim 2 (Approved, NO vehicle yet)
            [
                'user_id' => $donor1->id,
                'title' => 'Artisan Sourdough Breads & Pastries',
                'description' => 'Collection of fresh sourdough loaves, French baguettes, and croissants baked fresh this morning.',
                'quantity' => 45.00, 'unit' => 'items', 'pickup_address' => '789 Sunway Ave, Bakery Counter, PJ',
                'latitude' => 3.0738, 'longitude' => 101.6074, 'expiry_date' => Carbon::now()->addDays(2), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop']
            ],
            // 3: For Claim 3 (Approved, WITH vehicle assigned)
            [
                'user_id' => $donor4->id,
                'title' => 'Cold Pressed Orange & Apple Juices',
                'description' => 'Bottles of 100% natural cold pressed orange and green apple juices. Refrigerated at 4°C.',
                'quantity' => 150.00, 'unit' => 'litres', 'pickup_address' => '11 Jalan Sultan Ismail, Kitchen, KL',
                'latitude' => 3.1530, 'longitude' => 101.7080, 'expiry_date' => Carbon::now()->addDays(3), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?q=80&w=2070&auto=format&fit=crop']
            ],
            // 4: For Claim 4 (Collected)
            [
                'user_id' => $donor2->id,
                'title' => 'Assorted Canned Soups & Pantry Boxes',
                'description' => 'Pallet of canned tomato soups, beans, and whole wheat pasta boxes. Long shelf life, ideal for pantry storage.',
                'quantity' => 250.00, 'unit' => 'items', 'pickup_address' => '12 Plaza Damansara, Storage B, KL',
                'latitude' => 3.1517, 'longitude' => 101.6558, 'expiry_date' => Carbon::now()->addMonths(6), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1584473457406-6240486418e9?q=80&w=2072&auto=format&fit=crop']
            ],
            // 5: For Claim 5 (Collected)
            [
                'user_id' => $donor3->id,
                'title' => 'Cooked Gourmet Buffet Trays (Unserved)',
                'description' => 'Unserved hotel gala dinner trays: roasted chicken breast, mixed vegetables, and butter rice. Blast frozen.',
                'quantity' => 20.00, 'unit' => 'boxes', 'pickup_address' => '3 Jalan Kepong, Loading Bay 1, KL',
                'latitude' => 3.2100, 'longitude' => 101.6300, 'expiry_date' => Carbon::now()->addDays(2), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1555244162-833832eb1cce?q=80&w=2070&auto=format&fit=crop']
            ],
            // 6: For Claim 6 (Collected)
            [
                'user_id' => $donor4->id,
                'title' => 'Premix Cereal & Breakfast Grain Packs',
                'description' => 'Nutritious oats, cornflakes, and cereal grain boxes for breakfast shelter distribution.',
                'quantity' => 90.00, 'unit' => 'items', 'pickup_address' => '11 Jalan Sultan Ismail, Pantry, KL',
                'latitude' => 3.1530, 'longitude' => 101.7080, 'expiry_date' => Carbon::now()->addMonths(4), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1521483451569-e33803c0330c?q=80&w=2070&auto=format&fit=crop']
            ],
            // 7: For Claim 7 (Collected)
            [
                'user_id' => $donor5->id,
                'title' => 'Infant Organic Cereal & Fruit Pouches',
                'description' => 'Organic baby rice cereal and puréed banana fruit pouches. Safe and sealed.',
                'quantity' => 110.00, 'unit' => 'items', 'pickup_address' => 'Publika Shopping Gallery, B.I.G., KL',
                'latitude' => 3.1708, 'longitude' => 101.6660, 'expiry_date' => Carbon::now()->addMonths(5), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1596797882870-8c33deeac224?q=80&w=2070&auto=format&fit=crop']
            ],
            // 8: For Claim 8 (Collected)
            [
                'user_id' => $donor1->id,
                'title' => 'Whole Roasted Chicken Meal Trays',
                'description' => 'Roasted chicken meals served with gravy and mashed potatoes. Ready for immediate dinner service.',
                'quantity' => 30.00, 'unit' => 'boxes', 'pickup_address' => '789 Sunway Ave, Kitchen C, PJ',
                'latitude' => 3.0738, 'longitude' => 101.6074, 'expiry_date' => Carbon::now()->addHours(36), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?q=80&w=2070&auto=format&fit=crop']
            ],
            // 9: For Claim 9 (Collected)
            [
                'user_id' => $donor2->id,
                'title' => 'Surplus Mineral Water Crate Packs',
                'description' => '500ml mineral water bottles packaged in shrink-wrapped 24-bottle crates.',
                'quantity' => 40.00, 'unit' => 'boxes', 'pickup_address' => '12 Plaza Damansara, Bay 3, KL',
                'latitude' => 3.1517, 'longitude' => 101.6558, 'expiry_date' => Carbon::now()->addYear(), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1548839140-29a749e1bc4e?q=80&w=2070&auto=format&fit=crop']
            ],
            // 10: For Claim 10 (Collected)
            [
                'user_id' => $donor3->id,
                'title' => 'Fresh Salmon Fillets & Seafood Pack',
                'description' => 'Chilled Atlantic salmon fillets and tiger prawns. Packed in ice crates ready for pick up.',
                'quantity' => 35.00, 'unit' => 'kg', 'pickup_address' => '3 Jalan Kepong, Fish Counter, KL',
                'latitude' => 3.2100, 'longitude' => 101.6300, 'expiry_date' => Carbon::now()->addDays(2), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=2070&auto=format&fit=crop']
            ],
            // 11: For Claim 11 (Collected)
            [
                'user_id' => $donor5->id,
                'title' => 'Frozen Mixed Peas & Sweet Corn Packs',
                'description' => 'Blast frozen green peas, sweet corn, and diced carrots in 1kg commercial packs.',
                'quantity' => 60.00, 'unit' => 'kg', 'pickup_address' => 'Publika Shopping Gallery, Freezer 4, KL',
                'latitude' => 3.1708, 'longitude' => 101.6660, 'expiry_date' => Carbon::now()->addMonths(3), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1574316071802-0d684efa7bf5?q=80&w=2070&auto=format&fit=crop']
            ],
            // 12: For Claim 12 (Collected)
            [
                'user_id' => $donor1->id,
                'title' => 'Fresh Brioche Buns & Morning Croissants',
                'description' => 'Fluffy French brioche hamburger buns and golden butter croissants.',
                'quantity' => 50.00, 'unit' => 'items', 'pickup_address' => '789 Sunway Ave, Bakery Dispatch, PJ',
                'latitude' => 3.0738, 'longitude' => 101.6074, 'expiry_date' => Carbon::now()->addDays(2), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=2070&auto=format&fit=crop']
            ],
            // 13: For Claim 13 (Collected)
            [
                'user_id' => $donor2->id,
                'title' => 'High-Protein Tofu & Soy Milk Bundles',
                'description' => 'Vacuum sealed organic tofu blocks and chilled unsweetened fresh soy milk.',
                'quantity' => 45.00, 'unit' => 'boxes', 'pickup_address' => '12 Plaza Damansara, Chiller 2, KL',
                'latitude' => 3.1517, 'longitude' => 101.6558, 'expiry_date' => Carbon::now()->addDays(4), 'status' => 'claimed',
                'image_paths' => ['https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=2070&auto=format&fit=crop']
            ],
            // 14: Available for Live Demo Claiming
            [
                'user_id' => $donor3->id,
                'title' => 'Garden Fresh Tomatoes & Bell Peppers',
                'description' => 'Crisp local Cameron Highlands vine tomatoes and tri-color bell peppers. Perfect for kitchen cooking.',
                'quantity' => 75.00, 'unit' => 'kg', 'pickup_address' => '3 Jalan Kepong, Fresh Bay, KL',
                'latitude' => 3.2100, 'longitude' => 101.6300, 'expiry_date' => Carbon::now()->addDays(5), 'status' => 'available',
                'image_paths' => ['https://images.unsplash.com/photo-1592924357228-91a4daadcfea?q=80&w=2070&auto=format&fit=crop']
            ],
            // 15: Available for Live Demo Claiming
            [
                'user_id' => $donor4->id,
                'title' => 'Assorted Gourmet Sandwiches & Wraps',
                'description' => 'Pre-packed chicken avocado wraps and smoked turkey breast sandwiches from executive banquet.',
                'quantity' => 35.00, 'unit' => 'items', 'pickup_address' => '11 Jalan Sultan Ismail, Concierge, KL',
                'latitude' => 3.1530, 'longitude' => 101.7080, 'expiry_date' => Carbon::now()->addHours(24), 'status' => 'available',
                'image_paths' => ['https://images.unsplash.com/photo-1528735602780-2552fd46c7af?q=80&w=2073&auto=format&fit=crop']
            ],
        ];

        $donationModels = [];
        foreach ($donationsList as $d) {
            $donationModels[] = Donation::create($d);
        }

        // ──────────────────────────────────────────────────────────
        // 5. Inventory Locations (10 Records)
        // ──────────────────────────────────────────────────────────
        $locationsData = [
            ['user' => $ngo1, 'name' => 'Central Storage Facility', 'addr' => '100 Community Way, KL', 'type' => 'dry', 'cap' => 2000.0],
            ['user' => $ngo1, 'name' => 'Cold Storage Blast Freezer', 'addr' => '100 Community Way, Block B, KL', 'type' => 'frozen', 'cap' => 800.0],
            ['user' => $ngo1, 'name' => 'Dry Pantry Storage Depot', 'addr' => '100 Community Way, Room A, KL', 'type' => 'ambient', 'cap' => 1200.0],
            ['user' => $ngo2, 'name' => 'Kechara Main Shelter Depot', 'addr' => '17 Jalan Barat, KL', 'type' => 'dry', 'cap' => 1500.0],
            ['user' => $ngo2, 'name' => 'Kechara Walk-in Chiller', 'addr' => '17 Jalan Barat Kitchen, KL', 'type' => 'cold', 'cap' => 600.0],
            ['user' => $ngo3, 'name' => 'MyKasih Central Distribution Hub', 'addr' => 'Menara LGB, TTDI, KL', 'type' => 'dry', 'cap' => 3000.0],
            ['user' => $ngo3, 'name' => 'MyKasih Chilled Coldroom', 'addr' => 'Section 13, Petaling Jaya', 'type' => 'cold', 'cap' => 1000.0],
            ['user' => $ngo4, 'name' => 'PichaEats Community Kitchen Depot', 'addr' => '25 Jalan Bangsar, KL', 'type' => 'ambient', 'cap' => 500.0],
            ['user' => $ngo4, 'name' => 'PichaEats Cold Storage Unit', 'addr' => '25 Jalan Bangsar, Unit 2, KL', 'type' => 'cold', 'cap' => 400.0],
            ['user' => $ngo1, 'name' => 'Cheras Emergency Relief Food Bank', 'addr' => 'Cheras Community Center, KL', 'type' => 'ambient', 'cap' => 2500.0],
        ];

        $invLocationModels = [];
        foreach ($locationsData as $loc) {
            $invLocationModels[] = InventoryLocation::create([
                'user_id' => $loc['user']->id,
                'name' => $loc['name'],
                'address' => $loc['addr'],
                'storage_type' => $loc['type'],
                'capacity' => $loc['cap'],
                'current_occupancy' => rand(80, (int)($loc['cap'] * 0.45))
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 6. Food Items (18 Records)
        // ──────────────────────────────────────────────────────────
        $foodItemsList = [
            ['d' => $donationModels[0], 'loc' => $invLocationModels[0], 'cat' => $catModels['Fresh Produce'], 'name' => 'Organic Honeycrisp Apples', 'qty' => 50.0, 'unit' => 'kg', 'storage' => 'ambient', 'perish' => true],
            ['d' => $donationModels[0], 'loc' => $invLocationModels[1], 'cat' => $catModels['Fresh Produce'], 'name' => 'Fresh Kale & Spinach Bunches', 'qty' => 70.5, 'unit' => 'kg', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[1], 'loc' => $invLocationModels[4], 'cat' => $catModels['Dairy & Eggs'], 'name' => 'Farm Fresh Grade A Eggs (30-tray)', 'qty' => 40.0, 'unit' => 'boxes', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[1], 'loc' => $invLocationModels[4], 'cat' => $catModels['Dairy & Eggs'], 'name' => 'Pasteurized Whole Milk (1L)', 'qty' => 40.0, 'unit' => 'boxes', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[2], 'loc' => $invLocationModels[3], 'cat' => $catModels['Bakery & Pastry'], 'name' => 'Artisan Sourdough Loaf', 'qty' => 45.0, 'unit' => 'items', 'storage' => 'dry', 'perish' => true],
            ['d' => $donationModels[3], 'loc' => $invLocationModels[6], 'cat' => $catModels['Beverages & Juices'], 'name' => 'Cold Pressed Orange Juice (1L)', 'qty' => 150.0, 'unit' => 'litres', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[4], 'loc' => $invLocationModels[3], 'cat' => $catModels['Pantry & Canned Goods'], 'name' => 'Tomato Soup Cans (400g)', 'qty' => 150.0, 'unit' => 'items', 'storage' => 'dry', 'perish' => false],
            ['d' => $donationModels[4], 'loc' => $invLocationModels[9], 'cat' => $catModels['Pantry & Canned Goods'], 'name' => 'Whole Wheat Pasta (500g)', 'qty' => 100.0, 'unit' => 'items', 'storage' => 'ambient', 'perish' => false],
            ['d' => $donationModels[5], 'loc' => $invLocationModels[4], 'cat' => $catModels['Prepared Meals'], 'name' => 'Roasted Chicken Breast Trays', 'qty' => 20.0, 'unit' => 'boxes', 'storage' => 'frozen', 'perish' => true],
            ['d' => $donationModels[6], 'loc' => $invLocationModels[5], 'cat' => $catModels['Pantry & Canned Goods'], 'name' => 'Oats & Cornflakes Cereal Packs', 'qty' => 90.0, 'unit' => 'items', 'storage' => 'dry', 'perish' => false],
            ['d' => $donationModels[7], 'loc' => $invLocationModels[5], 'cat' => $catModels['Baby Food & Formula'], 'name' => 'Infant Purée Banana Pouches', 'qty' => 110.0, 'unit' => 'items', 'storage' => 'ambient', 'perish' => false],
            ['d' => $donationModels[8], 'loc' => $invLocationModels[3], 'cat' => $catModels['Meat & Poultry'], 'name' => 'Roasted Chicken Meal Boxes', 'qty' => 30.0, 'unit' => 'boxes', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[9], 'loc' => $invLocationModels[2], 'cat' => $catModels['Beverages & Juices'], 'name' => 'Bottled Mineral Water Crates', 'qty' => 40.0, 'unit' => 'boxes', 'storage' => 'ambient', 'perish' => false],
            ['d' => $donationModels[10], 'loc' => $invLocationModels[6], 'cat' => $catModels['Seafood & Fish'], 'name' => 'Fresh Atlantic Salmon Fillets', 'qty' => 35.0, 'unit' => 'kg', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[11], 'loc' => $invLocationModels[1], 'cat' => $catModels['Frozen Foods'], 'name' => 'Frozen Sweet Corn & Green Peas', 'qty' => 60.0, 'unit' => 'kg', 'storage' => 'frozen', 'perish' => true],
            ['d' => $donationModels[12], 'loc' => $invLocationModels[7], 'cat' => $catModels['Bakery & Pastry'], 'name' => 'Fresh Brioche Burger Buns', 'qty' => 50.0, 'unit' => 'items', 'storage' => 'ambient', 'perish' => true],
            ['d' => $donationModels[13], 'loc' => $invLocationModels[8], 'cat' => $catModels['Dairy & Eggs'], 'name' => 'Organic Firm Tofu Blocks', 'qty' => 45.0, 'unit' => 'boxes', 'storage' => 'cold', 'perish' => true],
            ['d' => $donationModels[14], 'loc' => $invLocationModels[0], 'cat' => $catModels['Fresh Produce'], 'name' => 'Cameron Highlands Vine Tomatoes', 'qty' => 75.0, 'unit' => 'kg', 'storage' => 'ambient', 'perish' => true],
        ];

        $foodItemModels = [];
        foreach ($foodItemsList as $fi) {
            $foodItemModels[] = FoodItem::create([
                'donation_id' => $fi['d']->id,
                'inventory_location_id' => $fi['loc']->id,
                'category_id' => $fi['cat']->id,
                'name' => $fi['name'],
                'description' => 'High quality surplus food inspected for safety and quality compliance.',
                'quantity' => $fi['qty'],
                'unit' => $fi['unit'],
                'expiry_date' => $fi['d']->expiry_date,
                'storage_requirements' => $fi['storage'],
                'is_perishable' => $fi['perish'],
                'image_paths' => $fi['d']->image_paths
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 7. Allergen-FoodItem Pivot Relationships (18+ Records)
        // ──────────────────────────────────────────────────────────
        $foodItemModels[0]->allergenTags()->sync([$tagModels['Gluten']->id]);
        $foodItemModels[1]->allergenTags()->sync([$tagModels['Dairy']->id]);
        $foodItemModels[2]->allergenTags()->sync([$tagModels['Egg']->id]);
        $foodItemModels[3]->allergenTags()->sync([$tagModels['Dairy']->id]);
        $foodItemModels[4]->allergenTags()->sync([$tagModels['Gluten']->id]);
        $foodItemModels[5]->allergenTags()->sync([$tagModels['Sulfites']->id]);
        $foodItemModels[6]->allergenTags()->sync([$tagModels['Soy']->id]);
        $foodItemModels[7]->allergenTags()->sync([$tagModels['Gluten']->id]);
        $foodItemModels[8]->allergenTags()->sync([$tagModels['Soy']->id, $tagModels['Gluten']->id]);
        $foodItemModels[9]->allergenTags()->sync([$tagModels['Gluten']->id, $tagModels['Contains Nuts']->id]);
        $foodItemModels[10]->allergenTags()->sync([$tagModels['Soy']->id]);
        $foodItemModels[11]->allergenTags()->sync([$tagModels['Gluten']->id]);
        $foodItemModels[12]->allergenTags()->sync([$tagModels['Sulfites']->id]);
        $foodItemModels[13]->allergenTags()->sync([$tagModels['Seafood']->id]);
        $foodItemModels[14]->allergenTags()->sync([$tagModels['Sulfites']->id]);
        $foodItemModels[15]->allergenTags()->sync([$tagModels['Gluten']->id, $tagModels['Egg']->id]);
        $foodItemModels[16]->allergenTags()->sync([$tagModels['Soy']->id]);
        $foodItemModels[17]->allergenTags()->sync([$tagModels['Sulfites']->id]);

        // ──────────────────────────────────────────────────────────
        // 8. Claims (14 Records — Structured for Presentation Demo)
        // ──────────────────────────────────────────────────────────
        $claimsData = [
            // Claim 0: Pending (For Donor/Admin to demo APPROVE)
            ['don' => $donationModels[0], 'ngo' => $ngo1, 'status' => 'pending', 'just' => 'Organic produce needed for weekend community kitchen serving 120 B40 families.'],
            // Claim 1: Pending (For Donor/Admin to demo REJECT or REVIEW)
            ['don' => $donationModels[1], 'ngo' => $ngo2, 'status' => 'pending', 'just' => 'Farm fresh eggs and milk needed for children shelter nutrition program.'],
            // Claim 2: Approved, NO vehicle assigned (For NGO to demo ASSIGN VEHICLE)
            ['don' => $donationModels[2], 'ngo' => $ngo1, 'status' => 'approved', 'just' => 'Artisan sourdough breads for community breakfast redistribution.'],
            // Claim 3: Approved, WITH vehicle assigned (For NGO to demo COLLECT button)
            ['don' => $donationModels[3], 'ngo' => $ngo2, 'status' => 'approved', 'just' => 'Cold pressed juices for youth shelter vitamin supplementation.'],
            // Claims 4 to 13: Collected (With Receipts, Vehicles & Distribution Logs)
            ['don' => $donationModels[4], 'ngo' => $ngo1, 'status' => 'collected', 'just' => 'Stocking central community food pantry with nutritious canned goods.'],
            ['don' => $donationModels[5], 'ngo' => $ngo2, 'status' => 'collected', 'just' => 'Providing gourmet meal boxes to homeless shelter evening dinner.'],
            ['don' => $donationModels[6], 'ngo' => $ngo3, 'status' => 'collected', 'just' => 'Breakfast cereal grain packs for B40 school students assistance program.'],
            ['don' => $donationModels[7], 'ngo' => $ngo3, 'status' => 'collected', 'just' => 'Infant cereal purée pouches for single mother welfare support group.'],
            ['don' => $donationModels[8], 'ngo' => $ngo1, 'status' => 'collected', 'just' => 'Whole roasted chicken meal boxes for community kitchen soup run.'],
            ['don' => $donationModels[9], 'ngo' => $ngo2, 'status' => 'collected', 'just' => 'Mineral water crate distribution for flood relief emergency center.'],
            ['don' => $donationModels[10], 'ngo' => $ngo1, 'status' => 'collected', 'just' => 'Chilled Atlantic salmon fillets for elderly welfare shelter meals.'],
            ['don' => $donationModels[11], 'ngo' => $ngo3, 'status' => 'collected', 'just' => 'Frozen mixed vegetables for community food bank weekly distribution.'],
            ['don' => $donationModels[12], 'ngo' => $ngo1, 'status' => 'collected', 'just' => 'French brioche buns and morning pastries for shelter breakfast.'],
            ['don' => $donationModels[13], 'ngo' => $ngo2, 'status' => 'collected', 'just' => 'Fresh firm tofu and soy milk packs for diabetic nutrition pantry.'],
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
        // 9. Vehicles (11 Records: 1 for Approved Claim 3 + 10 for Collected Claims 4-13)
        // ──────────────────────────────────────────────────────────
        $vehiclesData = [
            // Vehicle for Claim 3 (Approved, ready to collect)
            ['claim' => $claimModels[3], 'plate' => 'VHT 1484', 'type' => 'van', 'driver' => 'Bala Subra', 'phone' => '+6012-9998888', 'cap' => 800.0],
            
            // Vehicles for Collected Claims 4 to 13
            ['claim' => $claimModels[4], 'plate' => 'WKT 8899', 'type' => 'truck', 'driver' => 'Ahmad Razak', 'phone' => '+6017-2223333', 'cap' => 2500.0],
            ['claim' => $claimModels[5], 'plate' => 'BND 5050', 'type' => 'van', 'driver' => 'Lee Wei Hong', 'phone' => '+6019-3332222', 'cap' => 1000.0],
            ['claim' => $claimModels[6], 'plate' => 'PKK 7788', 'type' => 'truck', 'driver' => 'Muthu Kumar', 'phone' => '+6016-5554444', 'cap' => 3000.0],
            ['claim' => $claimModels[7], 'plate' => 'VAA 9911', 'type' => 'van', 'driver' => 'Siti Nurhaliza', 'phone' => '+6018-9990000', 'cap' => 750.0],
            ['claim' => $claimModels[8], 'plate' => 'WYY 3344', 'type' => 'truck', 'driver' => 'Chong Meng', 'phone' => '+6013-2221111', 'cap' => 2000.0],
            ['claim' => $claimModels[9], 'plate' => 'BPL 8822', 'type' => 'van', 'driver' => 'Hassan Basri', 'phone' => '+6017-6665555', 'cap' => 900.0],
            ['claim' => $claimModels[10], 'plate' => 'KMC 4455', 'type' => 'van', 'driver' => 'Ravi Arumugam', 'phone' => '+6014-8883333', 'cap' => 850.0],
            ['claim' => $claimModels[11], 'plate' => 'WXC 6677', 'type' => 'van', 'driver' => 'Jason Wong', 'phone' => '+6012-7774444', 'cap' => 850.0],
            ['claim' => $claimModels[12], 'plate' => 'VEE 2233', 'type' => 'van', 'driver' => 'Farid Kamil', 'phone' => '+6019-4445555', 'cap' => 900.0],
            ['claim' => $claimModels[13], 'plate' => 'KDD 1234', 'type' => 'car', 'driver' => 'David Tan', 'phone' => '+6011-4445555', 'cap' => 400.0],
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
        // 10. Collection Receipts (10 Records — for Collected Claims 4-13)
        // ──────────────────────────────────────────────────────────
        $collectedClaims = [
            $claimModels[4], $claimModels[5], $claimModels[6], $claimModels[7], $claimModels[8],
            $claimModels[9], $claimModels[10], $claimModels[11], $claimModels[12], $claimModels[13]
        ];

        foreach ($collectedClaims as $idx => $clm) {
            CollectionReceipt::create([
                'claim_id' => $clm->id,
                'receipt_number' => 'REC-NUTRI-' . date('Ymd') . '-' . sprintf('%03d', $idx + 1),
                'quantity_collected' => $clm->donation->quantity,
                'unit' => $clm->donation->unit,
                'collected_by' => $clm->user->organization_name ?? $clm->user->name,
                'condition_notes' => 'Inspected at pickup site — good temperature and food safety compliance verified.',
                'collected_at' => Carbon::now()->subHours(rand(1, 48))
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 11. Distribution Logs (12 Records for SDG 2 Impact)
        // ──────────────────────────────────────────────────────────
        foreach ($collectedClaims as $idx => $clm) {
            $beneficiaries = rand(45, 180);
            $cleanLocation = $clm->user->address ?? 'Kuala Lumpur Community Distribution Center';

            DistributionLog::create([
                'claim_id' => $clm->id,
                'beneficiaries_count' => $beneficiaries,
                'distribution_location' => $cleanLocation,
                'quantity_distributed' => $clm->donation->quantity,
                'unit' => $clm->donation->unit,
                'notes' => 'Direct food aid distribution to B40 families and shelter residents under UN SDG 2 Zero Hunger program.',
                'distributed_at' => Carbon::now()->subHours(rand(1, 24))
            ]);
        }

        // Extra distribution logs for multi-entry demo on Claims 4 and 5
        DistributionLog::create([
            'claim_id' => $claimModels[4]->id,
            'beneficiaries_count' => 60,
            'distribution_location' => 'Central Storage Facility — 100 Community Way, KL',
            'quantity_distributed' => 50.00,
            'unit' => 'items',
            'notes' => 'Secondary distribution batch dispatched to Sentul community shelter.',
            'distributed_at' => Carbon::now()->subHours(12)
        ]);

        DistributionLog::create([
            'claim_id' => $claimModels[5]->id,
            'beneficiaries_count' => 85,
            'distribution_location' => 'Kechara Main Shelter Depot — 17 Jalan Barat, KL',
            'quantity_distributed' => 10.00,
            'unit' => 'boxes',
            'notes' => 'Emergency evening meal distribution to urban homeless beneficiaries.',
            'distributed_at' => Carbon::now()->subHours(6)
        ]);

        // ──────────────────────────────────────────────────────────
        // 12. NGO Verification Documents (10 Records)
        // ──────────────────────────────────────────────────────────
        $docsData = [
            ['user' => $ngo1, 'type' => 'registration_cert', 'file' => 'verification_documents/ngo1_cert.pdf', 'status' => 'approved', 'remarks' => 'Verified against Registrar of Societies (ROS) Malaysia database.'],
            ['user' => $ngo1, 'type' => 'tax_exempt', 'file' => 'verification_documents/ngo1_tax.pdf', 'status' => 'approved', 'remarks' => 'Inland Revenue Board (LHDN) tax exemption status verified active.'],
            ['user' => $ngo2, 'type' => 'registration_cert', 'file' => 'verification_documents/ngo2_cert.pdf', 'status' => 'approved', 'remarks' => 'ROS Certificate confirmed active and compliant.'],
            ['user' => $ngo2, 'type' => 'license', 'file' => 'verification_documents/ngo2_license.pdf', 'status' => 'approved', 'remarks' => 'Food Premises & Hygiene Certificate validated.'],
            ['user' => $ngo3, 'type' => 'registration_cert', 'file' => 'verification_documents/ngo3_cert.pdf', 'status' => 'approved', 'remarks' => 'MyKasih Trust Charter verified.'],
            ['user' => $ngo3, 'type' => 'tax_exempt', 'file' => 'verification_documents/ngo3_tax.pdf', 'status' => 'approved', 'remarks' => 'Tax exemption document approved by compliance team.'],
            ['user' => $ngo4, 'type' => 'registration_cert', 'file' => 'verification_documents/ngo4_cert.pdf', 'status' => 'pending', 'remarks' => 'Pending review by platform moderator in verification queue.'],
            ['user' => $ngo4, 'type' => 'license', 'file' => 'verification_documents/ngo4_license.pdf', 'status' => 'pending', 'remarks' => 'Under active review by platform moderator.'],
            ['user' => $ngo1, 'type' => 'license', 'file' => 'verification_documents/ngo1_license.pdf', 'status' => 'approved', 'remarks' => 'State Health Department Premises License approved.'],
            ['user' => $ngo2, 'type' => 'tax_exempt', 'file' => 'verification_documents/ngo2_tax.pdf', 'status' => 'approved', 'remarks' => 'LHDN Section 44(6) Tax Exemption status verified.'],
        ];

        foreach ($docsData as $doc) {
            $createdDoc = VerificationDocument::create([
                'user_id' => $doc['user']->id,
                'document_type' => $doc['type'],
                'file_path' => $doc['file'],
                'original_filename' => basename($doc['file']),
                'status' => $doc['status'],
                'admin_remarks' => $doc['remarks'],
                'reviewed_by' => 1,
                'reviewed_at' => $doc['status'] !== 'pending' ? Carbon::now()->subDays(rand(1, 10)) : null
            ]);
            \App\Services\VerificationDocumentService::ensureFileExists($createdDoc);
        }

        // ──────────────────────────────────────────────────────────
        // 13. User Reviews & Ratings (10 Records)
        // ──────────────────────────────────────────────────────────
        $reviewsData = [
            ['rev' => $donor1, 'target' => $ngo1, 'rating' => 5, 'comment' => 'Punctual driver, smooth logistics coordination, and professional food handling!'],
            ['rev' => $donor2, 'target' => $ngo1, 'rating' => 5, 'comment' => 'Very reliable NGO partner. Picked up 250 canned items swiftly and cleanly.'],
            ['rev' => $donor3, 'target' => $ngo2, 'rating' => 5, 'comment' => 'Kechara team arrived exactly on schedule with insulated temperature containers.'],
            ['rev' => $donor4, 'target' => $ngo2, 'rating' => 4, 'comment' => 'Great communication! Cold juices were transported safely to children shelter.'],
            ['rev' => $donor4, 'target' => $ngo3, 'rating' => 5, 'comment' => 'MyKasih team handled cereal pack distribution smoothly and sent photo proofs.'],
            ['rev' => $donor5, 'target' => $ngo3, 'rating' => 5, 'comment' => 'Pristine pickup protocol at Publika. Highly recommended charity partner.'],
            ['rev' => $ngo1, 'target' => $donor1, 'rating' => 5, 'comment' => 'Sunway Bakery consistently provides fresh organic produce and breads in top shape!'],
            ['rev' => $ngo2, 'target' => $donor2, 'rating' => 5, 'comment' => 'Jaya Grocer is a stellar donor helping feed hundreds of vulnerable individuals.'],
            ['rev' => $ngo3, 'target' => $donor3, 'rating' => 5, 'comment' => 'Shangri-La Executive kitchen staff packaged banquet meals with superb care.'],
            ['rev' => $ngo1, 'target' => $donor5, 'rating' => 5, 'comment' => 'High quality baby cereal pouches received sealed in original protective cartons.'],
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
        // 15. In-App Notifications (14 Records)
        // ──────────────────────────────────────────────────────────
        for ($i = 0; $i < 14; $i++) {
            Notification::create([
                'user_id' => ($i % 2 === 0) ? $donor1->id : $ngo1->id,
                'notification_template_id' => $templateModels[$i % 10]->id,
                'donation_id' => $donationModels[$i % 16]->id,
                'title' => $templateModels[$i % 10]->subject,
                'message' => 'Notification alert regarding NutriShare surplus food redistribution activity.',
                'channel' => 'email',
                'is_read' => ($i % 3 === 0),
                'sent_at' => Carbon::now()->subHours($i * 3)
            ]);
        }

        // ──────────────────────────────────────────────────────────
        // 16. System Audit Logs (14 Records)
        // ──────────────────────────────────────────────────────────
        $logsData = [
            ['user' => $userModels['admin@nutrishare.com'], 'act' => 'user.login', 'desc' => 'System Admin signed in to admin dashboard.', 'lvl' => 'info'],
            ['user' => $userModels['moderator@nutrishare.com'], 'act' => 'ngo.verified', 'desc' => 'Moderator approved Food Rescue Foundation ROS registration document.', 'lvl' => 'info'],
            ['user' => $donor1, 'act' => 'donation.created', 'desc' => 'Donor published Fresh Organic Fruits & Veggies Pack (120.50 kg).', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'claim.created', 'desc' => 'NGO submitted claim for Artisan Sourdough Breads & Pastries.', 'lvl' => 'info'],
            ['user' => $donor1, 'act' => 'claim.approved', 'desc' => 'Donor approved claim from Food Rescue Foundation.', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'vehicle.assigned', 'desc' => 'Assigned pickup van VHT 1484 (Driver: Bala Subra).', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'receipt.generated', 'desc' => 'Generated collection receipt REC-NUTRI-20260905-001.', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'distribution.logged', 'desc' => 'Recorded distribution log: 125 beneficiaries fed in KL.', 'lvl' => 'info'],
            ['user' => $userModels['admin@nutrishare.com'], 'act' => 'report.generated', 'desc' => 'Admin generated SDG 2 Zero Hunger Impact Report Q3 2026.', 'lvl' => 'info'],
            ['user' => $ngo1, 'act' => 'inventory.created', 'desc' => 'Registered Central Storage Facility (Capacity: 2,000 kg).', 'lvl' => 'info'],
            ['user' => $donor2, 'act' => 'donation.updated', 'desc' => 'Updated pickup availability details for Canned Soups.', 'lvl' => 'info'],
            ['user' => $userModels['mod2@nutrishare.com'], 'act' => 'system.audit', 'desc' => 'Platform Moderator completed security compliance check.', 'lvl' => 'info'],
            ['user' => $ngo2, 'act' => 'distribution.logged', 'desc' => 'Kechara recorded distribution of 20 boxes to 85 shelter residents.', 'lvl' => 'info'],
            ['user' => $userModels['admin@nutrishare.com'], 'act' => 'system.backup', 'desc' => 'Automated nightly database backup completed successfully.', 'lvl' => 'info'],
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
