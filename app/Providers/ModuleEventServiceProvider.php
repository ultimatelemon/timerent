<?php

namespace App\Providers;

use App\Events\InvoiceGenerated;
use App\Modules\Moneybird\Listeners\ProcessInvoice;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ModuleEventServiceProvider extends EventServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modulePath = app_path('Modules');
        $modules = array_filter(glob($modulePath . '/*'), 'is_dir');

        foreach ($modules as $module) {
            $listenerConfigPath = $module . '/Config/Listeners.php';

            if (file_exists($listenerConfigPath)) {
                $listeners = require $listenerConfigPath;

                foreach ($listeners as $event => $listenerClasses) {
                    foreach ($listenerClasses as $listener) {
                        Event::listen($event, $listener);
                    }
                }
            }
        }
    }

}
