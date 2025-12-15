<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Messages;

class StockMovementMessage
{
    public const INDEX_SUCCESS = 'Success get stock movements';
    public const SHOW_SUCCESS = 'Success get stock movement detail';
    public const CREATE_SUCCESS = 'Stock movement created successfully';
}
