<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Claims - NGO claims on available donations (Module 3).
     * State Pattern lifecycle: pending -> approved -> collected
     */
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // NGO user
            $table->enum('status', ['pending', 'approved', 'rejected', 'collected', 'cancelled'])->default('pending');
            $table->text('justification')->nullable(); // Why the NGO needs this donation
            $table->dateTime('pickup_scheduled_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
