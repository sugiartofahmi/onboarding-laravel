<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Messages;

class RoleErrorMessage
{
    public const NOT_FOUND = 'Role not found';
    public const CREATE_FAILED = 'Failed to create role';
    public const UPDATE_FAILED = 'Failed to update role';
    public const DELETE_FAILED = 'Failed to delete role';
}
