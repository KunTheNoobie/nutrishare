<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot table for Many-to-Many relationship: FoodItem <-> AllergenTag (Module 4).
     */
    public function up(): void
    {
        Schema::create('allergen_tag_food_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('allergen_tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['food_item_id', 'allergen_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergen_tag_food_item');
    }
};
