<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UserFactory\UserCreator;

/**
 * Artisan command to create Admin or Moderator accounts.
 *
 * Usage:
 *   php artisan nutrishare:create-admin
 *   php artisan nutrishare:create-admin --role=moderator
 *   php artisan nutrishare:create-admin --name="John" --email="john@example.com" --password="Password1!" --role=admin
 */
class CreateAdminUser extends Command
{
    protected $signature = 'nutrishare:create-admin
        {--name= : The user name}
        {--email= : The user email}
        {--password= : The user password}
        {--role=admin : The role (admin or moderator)}';

    protected $description = 'Create an Admin or Moderator account for NutriShare';

    public function handle(): int
    {
        $role = $this->option('role');

        if (!in_array($role, ['admin', 'moderator'])) {
            $this->error("Invalid role '{$role}'. Use 'admin' or 'moderator'.");
            return self::FAILURE;
        }

        $name = $this->option('name') ?: $this->ask('Enter the user name');
        $email = $this->option('email') ?: $this->ask('Enter the email address');
        $password = $this->option('password') ?: $this->secret('Enter the password (min 8 chars, 1 uppercase, 1 lowercase, 1 digit, 1 special char)');

        // Validate password complexity
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[@$!%*?&#]/', $password)) {
            $this->error('Password must be at least 8 characters with uppercase, lowercase, digit, and special character.');
            return self::FAILURE;
        }

        // Check for existing email
        if (\App\Models\User::where('email', $email)->exists()) {
            $this->error("A user with email '{$email}' already exists.");
            return self::FAILURE;
        }

        try {
            $creator = UserCreator::resolve($role);
            $user = $creator->createUser([
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ]);

            $this->info("✅ " . ucfirst($role) . " account created successfully!");
            $this->table(
                ['Field', 'Value'],
                [
                    ['Name', $user->name],
                    ['Email', $user->email],
                    ['Role', $user->role],
                    ['Status', $user->verification_status],
                ]
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create user: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
