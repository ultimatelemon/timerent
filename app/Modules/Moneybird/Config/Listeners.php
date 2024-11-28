<?php

namespace App\Modules\Moneybird\Config;

use App\Events\InvoiceGenerated;
use App\Modules\Moneybird\Listeners\ProcessInvoice;
use PHPStan\Parallel\Process;

return [
  InvoiceGenerated::class => [
      ProcessInvoice::class,
  ],
];