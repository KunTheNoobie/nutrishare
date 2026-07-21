<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Dairy', 'Produce', 'Bakery', 'Canned Goods', 'Frozen Foods',
                'Beverages', 'Grains', 'Meat', 'Seafood', 'Snacks',
            ]),
            'description' => fake()->sentence(),
        ];
    }
}
