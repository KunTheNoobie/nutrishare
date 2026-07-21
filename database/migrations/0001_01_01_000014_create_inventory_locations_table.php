<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Inventory Locations - Storage facilities registered by users (Module 4).
     */
    public function up(): void
    {
        Schema::create('inventory_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('address');
            $table->enum('storage_type', ['cold', 'dry', 'frozen', 'ambient'])->default('dry');
            $table->decimal('capacity', 10, 2)->nullable(); // Max capacity in kg
            $table->decimal('current_occupancy', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_locations');
    }
};
