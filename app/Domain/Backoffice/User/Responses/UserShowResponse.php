<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Responses;

use App\Models\User;

class UserShowResponse
{
    public function __construct(
        private User $user
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'roles' => $this->user->roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ])->toArray(),
            'created_at' => $this->user->created_at->toISOString(),
            'updated_at' => $this->user->updated_at->toISOString(),
        ];
    }
}
