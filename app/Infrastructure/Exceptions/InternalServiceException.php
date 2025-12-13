<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class InternalServiceException extends Exception
{
    public function __construct(string $message = 'Internal service error')
    {
        parent::__construct($message);
    }
}
