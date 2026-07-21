<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reviews - Trust/reputation system between users (Module 2).
     * Reviewers rate reviewees after completed donations/claims.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['reviewer_id', 'reviewee_id']); // One review per pair
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
