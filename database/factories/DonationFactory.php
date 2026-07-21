<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->donor(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'quantity' => fake()->randomFloat(2, 1, 500),
            'unit' => fake()->randomElement(['kg', 'litres', 'items', 'boxes']),
            'pickup_address' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'expiry_date' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'status' => 'available',
            'image_path' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => fake()->dateTimeBetween('-30 days', '-1 day'),
            'status' => 'expired',
        ]);
    }

    public function claimed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'claimed',
        ]);
    }
}
