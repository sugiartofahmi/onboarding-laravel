<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Enums;

enum OrderStatusType: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case VOID = 'void';
}
