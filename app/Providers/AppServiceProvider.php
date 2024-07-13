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
            $this->mapApiRoutes();
    }

    public function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace('App\\Http\\Controllers\\')
            ->group(function () {
                $host = request()->getHttpHost();

                if($host !== env('MAIN_DOMAIN')) {
                    ray('test')->green();
                    Route::middleware(['web'])
                        ->group(base_path('routes/application/web.php'));
                } else {
                    require base_path('routes/web.php');
                }
            });
    }

    public function mapApiRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\\Http\\Controllers\\')
            ->group(function () {
                $host = request()->getHost();
                if($host !== env('MAIN_DOMAIN')) {
                    require base_path('routes/application/api.php');
                } else {
                    require base_path('routes/api.php');
                }
            });
    }
}
