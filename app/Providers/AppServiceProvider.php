<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
            $this->mapWebRoutes();
    }

    public function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace('App\\Http\\Controllers\\')
            ->group(function () {
                $host = request()->getHost();

                if($host !== env('MAIN_DOMAIN')) {
                    Route::middleware(['venue'])
                        ->group(base_path('routes/venue/web.php'));
                } else {
                    require base_path('routes/web.php');
                }
            });
    }
}
