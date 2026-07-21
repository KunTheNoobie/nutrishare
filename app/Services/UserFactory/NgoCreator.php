<?php

namespace App\Services\UserFactory;

use App\Models\User;
use App\Models\SystemLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

/**
 * DESIGN PATTERN: Factory Method — Concrete Creator for NGO role.
 *
 * NGOs start with 'pending' verification status and must upload
 * license documents for admin approval before they can claim donations.
 */
class NgoCreator extends UserCreator
{
    /**
     * Create an NGO user with pending verification status.
     */
    public function createUser(array $baseData): User
    {
        $user = User::create([
            'name' => $baseData['name'],
            'email' => $baseData['email'],
            'password' => Hash::make($baseData['password']), // Bcrypt hashing (Module 2 Security)
            'role' => 'ngo',
            'verification_status' => 'pending', // Requires admin verification
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
        // Log registration
        SystemLog::create([
            'user_id' => $user->id,
            'action' => 'user.registered',
            'description' => "NGO '{$user->organization_name}' registered. Awaiting admin verification.",
            'level' => 'info',
        ]);

        // Notify all admins about the new NGO registration
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'New NGO Registration',
                'message' => "NGO '{$user->organization_name}' has registered and is awaiting verification.",
                'channel' => 'email',
                'sent_at' => now(),
            ]);
        }
    }
}
