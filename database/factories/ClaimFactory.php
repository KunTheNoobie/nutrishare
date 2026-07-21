<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    public function definition(): array
    {
        return [
            'donation_id' => Donation::factory(),
            'user_id' => User::factory()->ngo(),
            'status' => 'pending',
            'justification' => fake()->paragraph(),
            'pickup_scheduled_at' => fake()->dateTimeBetween('+1 day', '+7 days'),
            'admin_notes' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    public function collected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'collected',
        ]);
    }
}
