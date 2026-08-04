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

        // 1. Seed Categories
        $categoriesData = [
            'Fresh Produce' => 'Fresh organic fruits and vegetables from local farms and markets.',
            'Bakery & Pastry' => 'Freshly baked artisanal breads, rolls, and morning pastries.',
            'Dairy & Eggs' => 'Fresh milk, artisan cheeses, farm eggs, and plant-based alternatives.',
            'Meat & Seafood' => 'Quality meat cuts, poultry, and fresh seafood.',
            'Pantry & Canned Goods' => 'Non-perishable canned goods, pasta, rice, and pantry staples.',
            'Prepared Meals' => 'Ready-to-eat gourmet cooked meals and catering surplus.',
        ];
        foreach ($categoriesData as $name => $desc) {
            Category::updateOrCreate(['name' => $name], ['description' => $desc]);
        }

        // 2. Seed Allergen Tags
        $allergens = ['Gluten', 'Dairy', 'Contains Nuts', 'Soy', 'Egg', 'Seafood'];
        foreach ($allergens as $name) {
            AllergenTag::updateOrCreate(['name' => $name]);
        }

        $catProduce = Category::where('name', 'Fresh Produce')->first();
        $catBakery = Category::where('name', 'Bakery & Pastry')->first();
        $catPantry = Category::where('name', 'Pantry & Canned Goods')->first();
        $catMeals = Category::where('name', 'Prepared Meals')->first();

        $tagGluten = AllergenTag::where('name', 'Gluten')->first();
        $tagDairy = AllergenTag::where('name', 'Dairy')->first();
        $tagNuts = AllergenTag::where('name', 'Contains Nuts')->first();

        // 3. Create Professional NGO Accounts
        $ngo1 = User::updateOrCreate(['email' => 'ngo@nutrishare.com'], [
            'name' => 'Food Rescue Foundation',
            'password' => $password,
            'role' => 'ngo',
            'organization_name' => 'Food Rescue Foundation (BBB)',
            'phone' => '+60123456789',
            'verification_status' => 'approved',
            'email_verified_at' => now(),
            'address' => '100 Community Way, Kuala Lumpur',
            'notification_preference' => 'email',
        ]);

        $ngo2 = User::updateOrCreate(['email' => 'kechara@nutrishare.com'], [
            'name' => 'Kechara Soup Kitchen',
            'password' => $password,
            'role' => 'ngo',
            'organization_name' => 'Kechara Soup Kitchen Society',
            'phone' => '+60169876543',
            'verification_status' => 'approved',
            'email_verified_at' => now(),
            'address' => '17 Jalan Barat, Off Jalan Imbi, Kuala Lumpur',
            'notification_preference' => 'email',
        ]);

        // 4. Create Professional Donor Accounts
        $donor1 = User::updateOrCreate(['email' => 'donor@nutrishare.com'], [
            'name' => 'Sunway Bakery & Grocer',
            'password' => $password,
            'role' => 'donor',
            'organization_name' => 'Sunway Bakery & Grocer AAA',
            'phone' => '+60198887777',
            'verification_status' => 'approved',
            'email_verified_at' => now(),
            'address' => '789 Sunway Avenue, Petaling Jaya',
            'notification_preference' => 'email',
        ]);

        $donor2 = User::updateOrCreate(['email' => 'jayagrocer@nutrishare.com'], [
            'name' => 'Jaya Grocer Supermarket',
            'password' => $password,
            'role' => 'donor',
            'organization_name' => 'Jaya Grocer Outlets',
            'phone' => '+60123334444',
            'verification_status' => 'approved',
            'email_verified_at' => now(),
            'address' => '12 Plaza Damansara, Kuala Lumpur',
            'notification_preference' => 'email',
        ]);

        // 5. Create High-Quality Donations
        $donation1 = Donation::create([
            'user_id' => $donor1->id,
            'title' => 'Fresh Organic Fruits & Veggies Pack',
            'description' => 'Surplus organic honeycrisp apples, fresh kale, carrots, and avocados from morning shipment stock. High quality and perfectly fresh.',
            'quantity' => 120.50,
            'unit' => 'kg',
            'pickup_address' => '789 Sunway Avenue, Loading Bay C, Petaling Jaya',
            'latitude' => 3.0738,
            'longitude' => 101.6074,
            'expiry_date' => Carbon::now()->addDays(4),
            'status' => 'available',
            'image_paths' => [
                'https://images.unsplash.com/photo-1610832958506-aa56368176cf?q=80&w=2070&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=2070&auto=format&fit=crop'
            ]
        ]);

        $donation2 = Donation::create([
            'user_id' => $donor1->id,
            'title' => 'Artisan Sourdough Breads & Pastries',
            'description' => 'A large collection of fresh artisan sourdough loaves, French baguettes, and butter croissants baked fresh this morning.',
            'quantity' => 45.00,
            'unit' => 'items',
            'pickup_address' => '789 Sunway Avenue, Bakery Counter, Petaling Jaya',
            'latitude' => 3.0738,
            'longitude' => 101.6074,
            'expiry_date' => Carbon::now()->addDays(2),
            'status' => 'claimed',
            'image_paths' => [
                'https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop'
            ]
        ]);

        $donation3 = Donation::create([
            'user_id' => $donor2->id,
            'title' => 'Assorted Canned Soups & Pantry Boxes',
            'description' => 'Pallet of non-perishable canned tomato soups, black beans, and whole wheat pasta boxes. Long shelf life, ideal for pantry storage.',
            'quantity' => 250.00,
            'unit' => 'items',
            'pickup_address' => '12 Plaza Damansara, Storage Room B, Kuala Lumpur',
            'latitude' => 3.1517,
            'longitude' => 101.6558,
            'expiry_date' => Carbon::now()->addMonths(6),
            'status' => 'claimed',
            'image_paths' => [
                'https://images.unsplash.com/photo-1584473457406-6240486418e9?q=80&w=2072&auto=format&fit=crop'
            ]
        ]);

        // 6. Food Items for Donations
        $item1 = FoodItem::create([
            'donation_id' => $donation1->id,
            'category_id' => $catProduce->id,
            'name' => 'Organic Honeycrisp Apples',
            'description' => 'Crisp, sweet organic apples packed in wooden crates.',
            'quantity' => 50.00,
            'unit' => 'kg',
            'expiry_date' => $donation1->expiry_date,
            'storage_requirements' => 'ambient',
            'is_perishable' => true,
            'image_paths' => [$donation1->image_paths[0]]
        ]);

        $item2 = FoodItem::create([
            'donation_id' => $donation2->id,
            'category_id' => $catBakery->id,
            'name' => 'Artisan Sourdough Loaf',
            'description' => 'Freshly baked sourdough bread.',
            'quantity' => 45.00,
            'unit' => 'items',
            'expiry_date' => $donation2->expiry_date,
            'storage_requirements' => 'dry',
            'is_perishable' => true,
            'image_paths' => [$donation2->image_paths[0]]
        ]);
        $item2->allergenTags()->sync([$tagGluten->id]);

        // 7. Inventory Locations
        $invLocation1 = InventoryLocation::create([
            'user_id' => $ngo1->id,
            'name' => 'Central Storage Facility (NGO Central Facility)',
            'address' => '100 Community Way, Kuala Lumpur',
            'storage_type' => 'dry',
            'capacity' => 2000.00,
            'current_occupancy' => 450.00
        ]);

        $invLocation2 = InventoryLocation::create([
            'user_id' => $ngo1->id,
            'name' => 'Cold Storage Blast Freezer',
            'address' => '100 Community Way (Block B), Kuala Lumpur',
            'storage_type' => 'cold',
            'capacity' => 800.00,
            'current_occupancy' => 150.00
        ]);

        // 8. Claims (Claimed & Collected)
        $claim1 = Claim::create([
            'donation_id' => $donation2->id,
            'user_id' => $ngo1->id,
            'status' => 'approved',
            'pickup_scheduled_at' => Carbon::now()->addHours(3),
            'justification' => 'We will distribute these artisan breads to 150 beneficiaries at our community breakfast center.'
        ]);

        $claim2 = Claim::create([
            'donation_id' => $donation3->id,
            'user_id' => $ngo1->id,
            'status' => 'collected',
            'pickup_scheduled_at' => Carbon::now()->subDays(1),
            'justification' => 'Stocking up our central community pantry for weekend food pack distribution.'
        ]);

        // 9. Vehicles
        Vehicle::create([
            'claim_id' => $claim1->id,
            'plate_number' => 'VHT1484',
            'vehicle_type' => 'van',
            'driver_name' => 'Bala Subra',
            'driver_phone' => '+60129998888',
            'capacity_kg' => 800.00
        ]);

        Vehicle::create([
            'claim_id' => $claim2->id,
            'plate_number' => 'WKT8899',
            'vehicle_type' => 'truck',
            'driver_name' => 'Ahmad Razak',
            'driver_phone' => '+60172223333',
            'capacity_kg' => 2500.00
        ]);

        // 10. Collection Receipts
        CollectionReceipt::create([
            'claim_id' => $claim2->id,
            'receipt_number' => 'REC-WATTKO-20260802',
            'quantity_collected' => 250.00,
            'unit' => 'items',
            'collected_by' => 'Food Rescue Foundation (BBB)',
            'condition_notes' => 'Verified & collected in good condition at pickup site.',
            'collected_at' => Carbon::now()->subDays(1)
        ]);

        // 11. Distribution Logs (SDG 2 Impact)
        DistributionLog::create([
            'claim_id' => $claim2->id,
            'beneficiaries_count' => 125,
            'distribution_location' => 'Central Storage Facility (100 Community Way, Kuala Lumpur)',
            'quantity_distributed' => 250.00,
            'unit' => 'items',
            'notes' => 'Food packs distributed to B40 families under SDG 2 Zero Hunger initiative.',
            'distributed_at' => Carbon::now()->subHours(6)
        ]);

        // 12. Verification Documents
        VerificationDocument::create([
            'user_id' => $ngo1->id,
            'document_type' => 'registration_cert',
            'file_path' => 'verification_documents/ngo_cert.pdf',
            'original_filename' => 'NGO_Registration_Cert_2026.pdf',
            'status' => 'approved',
            'admin_remarks' => 'Verified against Registrar of Societies Malaysia.',
            'reviewed_by' => 1,
            'reviewed_at' => Carbon::now()->subDays(10)
        ]);

        // 13. Reviews & Ratings
        Review::create([
            'reviewer_id' => $donor1->id,
            'reviewee_id' => $ngo1->id,
            'rating' => 5,
            'comment' => 'Punctual driver, smooth communication, and great food redistribution handling!'
        ]);

        // 14. Notification Templates
        $tpl1 = NotificationTemplate::create([
            'name' => 'donation_claimed',
            'subject' => 'Donation Claimed Notification',
            'body' => 'Your donation "{donation_title}" was claimed by {ngo_name}.',
            'channel' => 'email'
        ]);

        // 15. Notifications
        Notification::create([
            'user_id' => $donor1->id,
            'notification_template_id' => $tpl1->id,
            'donation_id' => $donation2->id,
            'title' => 'Donation Claimed',
            'message' => 'Food Rescue Foundation claimed your donation "Artisan Sourdough Breads & Pastries".',
            'channel' => 'email',
            'is_read' => false,
            'sent_at' => Carbon::now()->subHours(2)
        ]);

        // 16. System Audit Logs
        SystemLog::create([
            'user_id' => $ngo1->id,
            'action' => 'claim.created',
            'description' => 'NGO submitted a new claim for Artisan Sourdough Breads & Pastries.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'level' => 'info'
        ]);

        // 17. Platform Reports
        Report::create([
            'user_id' => 1,
            'title' => 'SDG 2 Zero Hunger Impact Report (Q3 2026)',
            'content' => 'NutriShare has successfully redistributed 415.5 kg of surplus food to 175 beneficiaries, achieving Zero Hunger impact.',
            'type' => 'sdg_impact',
            'report_date' => Carbon::now()
        ]);

        // 18. Password Reset OTP
        PasswordResetOtp::create([
            'email' => 'ngo@nutrishare.com',
            'otp' => '123456',
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);
    }
}
