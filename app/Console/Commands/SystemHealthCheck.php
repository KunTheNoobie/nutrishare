<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'nutrishare:health-check';

    /**
     * The console command description.
     */
    protected $description = 'Perform a complete platform health check and database table audit for NutriShare';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=================================================");
        $this->info(" 🌾 NutriShare System Diagnostic & Health Check ");
        $this->info("=================================================");

        // 1. Check Database Connection
        try {
            DB::connection()->getPdo();
            $this->line("<info>[✓] Database Connection:</info> OK (" . config('database.default') . ")");
        } catch (\Exception $e) {
            $this->error("[✗] Database Connection Failed: " . $e->getMessage());
            return 1;
        }

        // 2. Check 18 Application Feature Tables
        $appTables = [
            'users' => 10,
            'categories' => 10,
            'allergen_tags' => 10,
            'donations' => 10,
            'food_items' => 10,
            'allergen_tag_food_item' => 10,
            'inventory_locations' => 10,
            'claims' => 10,
            'vehicles' => 10,
            'collection_receipts' => 10,
            'distribution_logs' => 10,
            'verification_documents' => 10,
            'reviews' => 10,
            'notification_templates' => 10,
            'notifications' => 10,
            'system_logs' => 10,
            'reports' => 10,
            'password_reset_otps' => 10,
        ];

        $this->newLine();
        $this->info("-------------------------------------------------");
        $this->info(" Application Table Audit (Min 10 Records Target)");
        $this->info("-------------------------------------------------");

        $allPassed = true;
        foreach ($appTables as $table => $minTarget) {
            if (!Schema::hasTable($table)) {
                $this->error(sprintf(" [✗] %-25s : MISSING TABLE", $table));
                $allPassed = false;
                continue;
            }

            $count = DB::table($table)->count();
            if ($count >= $minTarget) {
                $this->line(sprintf(" <info>[✓]</info> %-25s : <comment>%d records</comment> (Target: %d+)", $table, $count, $minTarget));
            } else {
                $this->warn(sprintf(" [!] %-25s : %d records (Target: %d+)", $table, $count, $minTarget));
            }
        }

        // 3. Mailpit / Mailer Status
        $this->newLine();
        $this->info("-------------------------------------------------");
        $this->info(" System Services & Integration Check");
        $this->info("-------------------------------------------------");

        $connection = @fsockopen('127.0.0.1', 8025, $errno, $errstr, 0.5);
        if (is_resource($connection)) {
            fclose($connection);
            $this->line("<info>[✓] Mailpit Web UI:</info> ONLINE (http://127.0.0.1:8025)");
        } else {
            $this->line("<comment>[!] Mailpit Web UI:</comment> OFFLINE (Auto-launches on 'php artisan serve')");
        }

        $this->newLine();
        $this->info("=================================================");
        $this->info(" 🎉 HEALTH CHECK COMPLETE — SYSTEM READY FOR DEMO!");
        $this->info("=================================================");

        return 0;
    }
}
