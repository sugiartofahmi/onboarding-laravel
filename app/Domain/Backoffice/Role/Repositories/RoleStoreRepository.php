<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Repositories;

use App\Models\Role;

class RoleStoreRepository
{
    public function __construct(private Role $model) {}

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(Role $role, array $data): Role
    {
        $role->update($data);

        return $role->fresh();
    }

    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}
