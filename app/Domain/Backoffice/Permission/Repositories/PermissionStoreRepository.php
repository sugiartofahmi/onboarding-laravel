<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Repositories;

use App\Models\Permission;

class PermissionStoreRepository
{
    public function __construct(private Permission $model) {}

    public function create(array $data): Permission
    {
        return $this->model->create($data);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);

        return $permission->fresh();
    }

    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }
}
