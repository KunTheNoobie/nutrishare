<?php

namespace App\Services\UserFactory;

use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;

/**
 * DESIGN PATTERN: Factory Method — Concrete Creator for Donor role.
 *
 * Donors are automatically approved upon registration since they
 * don't require license verification.
 */
class DonorCreator extends UserCreator
{
    /**
     * Create a Donor user with auto-approved status.
     */
    public function createUser(array $baseData): User
    {
        $user = User::create([
            'name' => $baseData['name'],
            'email' => $baseData['email'],
            'password' => Hash::make($baseData['password']), // Bcrypt hashing (Module 2 Security)
            'role' => 'donor',
            'verification_status' => 'approved', // Donors don't need verification
            'organization_name' => $baseData['organization_name'] ?? null,
            'phone' => $baseData['phone'] ?? null,
            'address' => $baseData['address'] ?? null,
            'notification_preference' => $baseData['notification_preference'] ?? 'email',
        ]);

        $this->postCreationSetup($user);

        return $user;
    }

    protected function postCreationSetup(User $user): void
    {
        SystemLog::create([
            'user_id' => $user->id,
            'action' => 'user.registered',
            'description' => "Donor '{$user->name}' registered successfully.",
            'level' => 'info',
        ]);
    }
}
