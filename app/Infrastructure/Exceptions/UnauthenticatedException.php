<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class UnauthenticatedException extends Exception
{
    public function __construct(string $message = 'Token not valid')
    {
        parent::__construct($message);
    }
}
