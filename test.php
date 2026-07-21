<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$d = App\Models\Donation::create([
    'user_id' => 1, 
    'title' => 'Test Donation', 
    'description' => 'Test', 
    'quantity' => 10, 
    'unit' => 'kg', 
    'pickup_address' => 'Test', 
    'expiry_date' => now()->addDays(2), 
    'status' => 'available'
]);
echo "Created ID: " . $d->id . "\n";
$repo = new App\Repositories\DonationRepository();
$results = $repo->findActiveDonations()->toArray();
$ids = array_column($results['data'], 'id');
echo "Found IDs: " . implode(', ', $ids) . "\n";
