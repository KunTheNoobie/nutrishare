<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Strategies\Notification\NotificationStrategyInterface;
use App\Strategies\Notification\EmailStrategy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the default notification strategy (Strategy Pattern - Module 4)
        $this->app->bind(NotificationStrategyInterface::class, EmailStrategy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        if ($this->app->runningInConsole()) {
            $argv = $_SERVER['argv'] ?? [];
            if (isset($argv[1]) && $argv[1] === 'serve') {
                $this->ensureMailpitRunning();
            }
        }
    }

    /**
     * Auto-start Mailpit background process if not already listening on port 8025.
     */
    private function ensureMailpitRunning(): void
    {
        $connection = @fsockopen('127.0.0.1', 8025, $errno, $errstr, 0.5);
        if (is_resource($connection)) {
            fclose($connection);
            return;
        }

        $mailpitPath = base_path('mailpit.exe');
        if (file_exists($mailpitPath)) {
            if (str_contains(PHP_OS, 'WIN')) {
                pclose(popen("start /B \"\" \"{$mailpitPath}\"", "r"));
            } else {
                exec("{$mailpitPath} > /dev/null 2>&1 &");
            }
        }
    }
}
