<?php

declare(strict_types=1);

namespace App\Domain\API\Profile\Responses;

use App\Models\User;

class ProfileUpdateResponse
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
            'updated_at' => $this->user->updated_at->toISOString(),
        ];
    }
}
