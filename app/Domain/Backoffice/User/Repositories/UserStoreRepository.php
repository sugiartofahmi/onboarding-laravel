<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Repositories;

use App\Models\User;

class UserStoreRepository
{
    public function __construct(private User $model) {}

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
