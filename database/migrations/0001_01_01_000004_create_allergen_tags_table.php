<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allergen Tags - food safety allergen identifiers (Module 4).
     * Many-to-Many with FoodItems via pivot table.
     */
    public function up(): void
    {
        Schema::create('allergen_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Gluten', 'Nuts', 'Dairy', 'Shellfish'
            $table->string('severity')->default('moderate'); // low, moderate, high
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergen_tags');
    }
};
