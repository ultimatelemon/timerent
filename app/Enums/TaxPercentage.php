<?php

namespace App\Enums;

enum TaxPercentage: int
{
    case NL_ZERO = 0;
    case NL_LOW = 9;
    case NL_HIGH = 21;
}