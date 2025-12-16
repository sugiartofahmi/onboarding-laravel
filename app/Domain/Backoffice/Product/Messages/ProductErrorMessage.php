<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Messages;

class ProductErrorMessage
{
    public const NOT_FOUND = 'Product not found';
    public const LOCK_ACQUISITION_FAILED = 'Unable to update product at this time, please try again';
}
