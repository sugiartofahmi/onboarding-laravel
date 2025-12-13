<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    public function __construct(string $message = 'Access forbidden')
    {
        parent::__construct($message);
    }
}
