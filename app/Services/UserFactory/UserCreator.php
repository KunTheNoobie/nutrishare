<?php

namespace App\Services\UserFactory;

use App\Models\User;

/**
 * DESIGN PATTERN: Factory Method Pattern — Abstract Creator (Module 2)
 *
 * Defines the factory method interface for creating users with
 * role-specific profile setup. Concrete factories (DonorCreator,
 * NgoCreator, AdminCreator) implement the creation logic.
 *
 * This pattern encapsulates the complexity of role-based user creation,
 * ensuring each role gets the correct default attributes, verification
 * status, and post-registration setup.
 */
abstract class UserCreator
{
    /**
     * Factory Method: Create a user with role-specific configuration.
     *
     * @param array $baseData Common user data (name, email, password)
     * @return User The created user instance
     */
    abstract public function createUser(array $baseData): User;

    /**
     * Post-creation hook for role-specific setup.
     * Override in subclasses for additional setup logic.
     */
    protected function postCreationSetup(User $user): void
    {
        // Default: no additional setup
    }

    /**
     * Static factory method to resolve the correct creator based on role string.
     * This is the entry point for the Factory Method Pattern.
     *
     * @param string $role The user role ('donor', 'ngo', 'admin')
     * @return static The concrete creator instance
     * @throws \InvalidArgumentException If role is not recognized
     */
    public static function resolve(string $role): static
    {
        return match (strtolower($role)) {
            'donor'     => new DonorCreator(),
            'ngo'       => new NgoCreator(),
            'admin'     => new AdminCreator(),
            'moderator' => new ModeratorCreator(),
            default     => throw new \InvalidArgumentException("Unknown user role: {$role}"),
        };
    }
}
