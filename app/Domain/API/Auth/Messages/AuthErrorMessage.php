<?php

declare(strict_types=1);

namespace App\Domain\API\Auth\Messages;

class AuthErrorMessage
{
    public const INVALID_CREDENTIALS = 'Invalid credentials';
    public const UNAUTHORIZED = 'Unauthorized';
    public const REGISTER_FAILED = 'Failed to create account, please contact admin';
}
