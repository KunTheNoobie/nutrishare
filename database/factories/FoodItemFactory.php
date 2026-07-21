<?php

namespace Database\Factories;

use App\Models\FoodItem;
use App\Models\Donation;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodItemFactory extends Factory
{
    protected $model = FoodItem::class;

    public function definition(): array
    {
        return [
            'donation_id' => Donation::factory(),
            'inventory_location_id' => null,
            'category_id' => Category::factory(),
            'name' => fake()->randomElement(['Rice', 'Bread', 'Milk', 'Vegetables', 'Canned Beans', 'Pasta', 'Eggs', 'Fruits']),
            'description' => fake()->sentence(),
            'quantity' => fake()->randomFloat(2, 0.5, 100),
            'unit' => fake()->randomElement(['kg', 'litres', 'items']),
            'expiry_date' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'storage_requirements' => fake()->randomElement(['cold', 'dry', 'frozen', 'ambient']),
            'is_perishable' => fake()->boolean(70),
        ];
    }
}
