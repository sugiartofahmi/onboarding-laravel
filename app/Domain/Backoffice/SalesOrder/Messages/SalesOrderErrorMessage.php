<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Messages;

class SalesOrderErrorMessage
{
    public const NOT_FOUND = 'Sales order not found';
    public const INSUFFICIENT_STOCK = 'Insufficient stock for one or more products';
    public const INVALID_STATUS_TRANSITION = 'Invalid status transition';
    public const LOCK_ACQUISITION_FAILED = 'Unable to process order at this time, please try again';
}
