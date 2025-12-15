<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Messages;

class PermissionErrorMessage
{
    public const NOT_FOUND = 'Permission not found';
    public const CREATE_FAILED = 'Failed to create permission';
    public const UPDATE_FAILED = 'Failed to update permission';
    public const DELETE_FAILED = 'Failed to delete permission';
}
