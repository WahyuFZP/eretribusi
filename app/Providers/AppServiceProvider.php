<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config as MidtransConfig;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Initialize Midtrans configuration from config/midtrans.php
        // (reads values from environment via config)
        try {
            MidtransConfig::$serverKey = config('midtrans.server_key');
            MidtransConfig::$isProduction = (bool) config('midtrans.is_production');
            MidtransConfig::$isSanitized = (bool) config('midtrans.is_sanitized');
            MidtransConfig::$is3ds = (bool) config('midtrans.is_3ds');
        } catch (\Throwable $e) {
            // silence errors in environments where Midtrans lib isn't available
            // logging is optional
            // \Log::warning('Midtrans config not initialized: '.$e->getMessage());
        }
    }
}
