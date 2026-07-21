<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('donations', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('claims', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('inventory_locations', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('donations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('claims', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('inventory_locations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
