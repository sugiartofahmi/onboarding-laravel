<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class BadRequestException extends Exception
{
    public function __construct(string $message = 'Bad request')
    {
        parent::__construct($message);
    }
}
