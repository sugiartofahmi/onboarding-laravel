<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class ServiceUnavailableException extends Exception
{
    public function __construct(string $message = 'Service unavailable')
    {
        parent::__construct($message);
    }
}
