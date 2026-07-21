<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Account
        User::updateOrCreate(['email' => 'admin@nutrishare.com'], [
            'name' => 'System Admin',
            'password' => Hash::make('Password1!'),
            'role' => 'admin',
        ]);

        // Create Dummy NGO Account
        User::updateOrCreate(['email' => 'ngo@nutrishare.com'], [
            'name' => 'Food Rescue NGO',
            'password' => Hash::make('Password1!'),
            'role' => 'ngo',
            'organization_name' => 'Food Rescue Foundation',
        ]);

        // Create Dummy Donor Account
        User::updateOrCreate(['email' => 'donor@nutrishare.com'], [
            'name' => 'Local Supermarket',
            'password' => Hash::make('Password1!'),
            'role' => 'donor',
        ]);
    }
}
