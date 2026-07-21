<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Donations - Core donation entity (Module 1).
     * Published by Donors, subject to Observer Pattern notifications.
     * Lifecycle: available -> claimed -> collected -> completed / expired
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Donor
            $table->string('title');
            $table->text('description');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('kg'); // kg, litres, items, boxes
            $table->string('pickup_address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('expiry_date');
            $table->enum('status', ['available', 'claimed', 'collected', 'completed', 'expired'])->default('available');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
