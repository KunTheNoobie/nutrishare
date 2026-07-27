<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->json('image_paths')->nullable();
        });

        // Migrate existing data
        $donations = DB::table('donations')->whereNotNull('image_path')->get();
        foreach ($donations as $donation) {
            DB::table('donations')
                ->where('id', $donation->id)
                ->update(['image_paths' => json_encode([$donation->image_path])]);
        }

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('image_path')->nullable();
        });

        $donations = DB::table('donations')->whereNotNull('image_paths')->get();
        foreach ($donations as $donation) {
            $paths = json_decode($donation->image_paths, true);
            $firstPath = is_array($paths) && count($paths) > 0 ? $paths[0] : null;
            if ($firstPath) {
                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update(['image_path' => $firstPath]);
            }
        }

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('image_paths');
        });
    }
};
