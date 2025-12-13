<?php

declare(strict_types=1);

namespace App\Domain\API\Auth\Messages;

class AuthMessage
{
    public const REGISTER_SUCCESS = 'User registered successfully';
    public const LOGIN_SUCCESS = 'Login successful';
    public const SELECT_ROLE_SUCCESS = 'Role selected successfully';
    public const LOGOUT_SUCCESS = 'Logout successful';
}
