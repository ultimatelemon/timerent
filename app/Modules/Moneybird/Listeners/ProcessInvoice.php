<?php

namespace App\Modules\Moneybird\Listeners;

use App\Events\InvoiceGenerated;

class ProcessInvoice
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvoiceGenerated $event): void
    {
        ray($event);
    }
}
