<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Distribution Logs - SDG impact tracking (Module 3).
     * Records how claimed food was distributed to beneficiaries.
     */
    public function up(): void
    {
        Schema::create('distribution_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->onDelete('cascade');
            $table->integer('beneficiaries_count');
            $table->text('distribution_location');
            $table->text('notes')->nullable();
            $table->decimal('quantity_distributed', 10, 2);
            $table->string('unit')->default('kg');
            $table->dateTime('distributed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_logs');
    }
};
