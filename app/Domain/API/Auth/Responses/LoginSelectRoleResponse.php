<?php

declare(strict_types=1);

namespace App\Domain\API\Auth\Responses;

use App\Models\Role;
use App\Models\User;

class LoginSelectRoleResponse
{
    public function __construct(
        private User $user,
        private Role $role,
        private string $token
    ) {}

    public function toArray(): array
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'role' => [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'guard_name' => $this->role->guard_name,
            ],
            'token' => $this->token,
            'token_type' => 'Bearer',
        ];
    }
}
