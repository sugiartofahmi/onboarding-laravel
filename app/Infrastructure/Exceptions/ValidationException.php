<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct(
        string $message = 'Validation failed',
        private array $errors = []
    ) {
        parent::__construct($message);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
