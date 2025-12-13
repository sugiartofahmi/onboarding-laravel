<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class UnprocessableEntityException extends Exception
{
    public function __construct(
        string $message = 'Unprocessable entity',
        private array $errors = []
    ) {
        parent::__construct($message);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
