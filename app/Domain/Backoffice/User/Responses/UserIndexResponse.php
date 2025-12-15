<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserIndexResponse
{
    public function __construct(
        private LengthAwarePaginator $users
    ) {}

    public function toArray(): array
    {
        return $this->users->map(fn ($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ])->toArray(),
            'created_at' => $user->created_at->toISOString(),
        ])->toArray();
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->users;
    }
}
