<?php

namespace App\Services\UserFactory;

use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;

/**
 * DESIGN PATTERN: Factory Method — Concrete Creator for Moderator role.
 *
 * Moderators are auto-approved and can oversee platform operations
 * but CANNOT delete resources (unlike Admin).
 */
class ModeratorCreator extends UserCreator
{
    /**
     * Create a Moderator user with approved status.
     */
    public function createUser(array $baseData): User
    {
        $user = User::create([
            'name' => $baseData['name'],
            'email' => $baseData['email'],
            'password' => Hash::make($baseData['password']),
            'role' => 'moderator',
            'verification_status' => 'approved',
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
            'description' => "Moderator '{$user->name}' account created.",
            'level' => 'info',
        ]);
    }
}
