<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;

class NotAllowedException extends Exception
{
    public function __construct(string $message = 'Access denied')
    {
        parent::__construct($message);
    }
}
