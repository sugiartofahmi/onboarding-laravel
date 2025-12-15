<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Messages;

class UserMessage
{
    public const INDEX_SUCCESS = 'Success get users';
    public const SHOW_SUCCESS = 'Success get user detail';
    public const CREATE_SUCCESS = 'User created successfully';
    public const UPDATE_SUCCESS = 'User updated successfully';
    public const DELETE_SUCCESS = 'User deleted successfully';
}
