<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class DataNotFoundException extends Exception
{
    public function __construct(string $message = 'Data not found')
    {
        parent::__construct($message);
    }
}
