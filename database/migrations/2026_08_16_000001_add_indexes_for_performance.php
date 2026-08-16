<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->index(['status', 'expiry_date'], 'idx_donations_status_expiry');
            $table->index(['user_id', 'status'], 'idx_donations_user_status');
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->index(['status', 'user_id'], 'idx_claims_status_user');
            $table->index(['donation_id', 'status'], 'idx_claims_donation_status');
        });

        Schema::table('food_items', function (Blueprint $table) {
            $table->index(['expiry_date'], 'idx_food_items_expiry');
        });

        Schema::table('inventory_locations', function (Blueprint $table) {
            $table->index(['user_id', 'storage_type'], 'idx_inventory_user_storage');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropIndex('idx_donations_status_expiry');
            $table->dropIndex('idx_donations_user_status');
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->dropIndex('idx_claims_status_user');
            $table->dropIndex('idx_claims_donation_status');
        });

        Schema::table('food_items', function (Blueprint $table) {
            $table->dropIndex('idx_food_items_expiry');
        });

        Schema::table('inventory_locations', function (Blueprint $table) {
            $table->dropIndex('idx_inventory_user_storage');
        });
    }
};
