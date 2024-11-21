<?php

namespace App\Modules\Moneybird;

use Illuminate\Support\ServiceProvider;

class MoneybirdServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(app_path('Modules/Moneybird/Config/moneybird.php'), 'moneybird');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
