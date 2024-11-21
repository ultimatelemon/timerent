<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modulePath = app_path('Modules');
        $modules = array_filter(glob($modulePath . '/*'), 'is_dir');

        foreach ($modules as $module) {
            $this->loadModuleRoutes($module);
            $this->loadModuleApiRoutes($module);
            $this->loadModuleViews($module);
            $this->loadModuleMigrations($module);
            $this->loadModuleConfig($module);
        }
    }

    private function loadModuleRoutes($modulePath): void
    {
        $routesFile = $modulePath . '/Routes/web.php';
        if (file_exists($routesFile)) {
            $this->loadRoutesFrom($routesFile);
        }
    }

    private function loadModuleApiRoutes($modulePath): void
    {
        $apiRoutesFile = $modulePath . '/Routes/api.php';
        if (file_exists($apiRoutesFile)) {
            Route::middleware('api')
                ->prefix('api')
                ->group(function() use ($apiRoutesFile) {
                    require base_path('./app/Modules/Moneybird/Routes/api.php');
                });
        }
    }

    private function loadModuleViews($modulePath) {
        $viewsPath = $modulePath . '/Views';
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, basename($modulePath));
        }
    }

    private function loadModuleMigrations($modulePath) {
        $migrationsPath = $modulePath . '/Migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    private function loadModuleConfig($modulePath) {
        $configPath = $modulePath . '/Config';
        if (is_dir($configPath)) {
            foreach(glob($configPath . '/*.php') as $configFile) {
                $this->mergeConfigFrom($configFile, basename($modulePath));
            }
        }
    }

}
