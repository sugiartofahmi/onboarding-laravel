<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Enums;

enum ProductStatusType: string
{
    case AVAILABLE = 'available';
    case LOW_STOCK = 'low_stock';
    case OUT_OF_STOCK = 'out_of_stock';
}
