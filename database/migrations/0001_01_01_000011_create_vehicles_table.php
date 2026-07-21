<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vehicles - Assigned to claims for logistics (Module 3).
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->onDelete('cascade');
            $table->string('plate_number');
            $table->string('vehicle_type'); // e.g., 'van', 'truck', 'car'
            $table->string('driver_name');
            $table->string('driver_phone')->nullable();
            $table->decimal('capacity_kg', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
