<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Messages;

class UserErrorMessage
{
    public const NOT_FOUND = 'User not found';
    public const EMAIL_ALREADY_EXISTS = 'Email already exists';
}
