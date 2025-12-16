<?php

declare(strict_types=1);

namespace App\Domain\API\Profile\Repositories;

use App\Models\User;

class ProfileRepository
{
    public function __construct(private User $model) {}

    public function findById(string $id): ?User
    {
        return $this->model->with(['roles'])->find($id);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh(['roles']);
    }
}
