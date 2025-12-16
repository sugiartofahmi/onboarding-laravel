<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Messages;

class StockMovementErrorMessage
{
    public const NOT_FOUND = 'Stock movement not found';
    public const INSUFFICIENT_STOCK = 'Insufficient stock available';
    public const LOCK_ACQUISITION_FAILED = 'Unable to process stock movement at this time, please try again';
}
