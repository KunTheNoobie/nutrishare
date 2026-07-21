<?php

namespace Database\Factories;

use App\Models\AllergenTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllergenTagFactory extends Factory
{
    protected $model = AllergenTag::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Gluten', 'Nuts', 'Dairy', 'Shellfish', 'Soy',
                'Eggs', 'Fish', 'Wheat', 'Sesame', 'Sulphites',
            ]),
            'severity' => fake()->randomElement(['low', 'moderate', 'high']),
            'description' => fake()->sentence(),
        ];
    }
}
