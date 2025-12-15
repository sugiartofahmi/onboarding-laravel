<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Messages;

class PermissionMessage
{
    public const INDEX_SUCCESS = 'Permissions retrieved successfully';
    public const SHOW_SUCCESS = 'Permission retrieved successfully';
    public const CREATE_SUCCESS = 'Permission created successfully';
    public const UPDATE_SUCCESS = 'Permission updated successfully';
    public const DELETE_SUCCESS = 'Permission deleted successfully';
}
