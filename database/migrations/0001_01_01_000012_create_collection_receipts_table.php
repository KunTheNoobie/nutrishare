<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Collection Receipts - Proof of food collection (Module 3).
     * Generated when a claim transitions to 'collected' state.
     */
    public function up(): void
    {
        Schema::create('collection_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->decimal('quantity_collected', 10, 2);
            $table->string('unit')->default('kg');
            $table->string('collected_by'); // Name of person who collected
            $table->text('condition_notes')->nullable(); // Food condition at collection
            $table->string('signature_path')->nullable(); // Digital signature image
            $table->dateTime('collected_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_receipts');
    }
};
