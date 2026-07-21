<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Users table - Core entity supporting sub-roles: Donor, NGO, Admin.
     * Central to all modules with relationships to donations, claims, reviews, etc.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Bcrypt hashed (Module 2 Security)
            $table->enum('role', ['donor', 'ngo', 'admin'])->default('donor');
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('organization_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('notification_preference', ['email', 'sms', 'both'])->default('email');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
