<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Messages;

class RoleMessage
{
    public const INDEX_SUCCESS = 'Roles retrieved successfully';
    public const SHOW_SUCCESS = 'Role retrieved successfully';
    public const CREATE_SUCCESS = 'Role created successfully';
    public const UPDATE_SUCCESS = 'Role updated successfully';
    public const DELETE_SUCCESS = 'Role deleted successfully';
}
