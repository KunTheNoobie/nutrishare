<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Notification Templates - Predefined templates for system alerts (Module 1).
     */
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'donation_created', 'claim_approved'
            $table->string('subject');
            $table->text('body'); // Supports placeholders like {donor_name}, {donation_title}
            $table->string('channel')->default('email'); // email, sms, both
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
