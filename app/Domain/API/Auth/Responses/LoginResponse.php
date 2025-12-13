<?php

declare(strict_types=1);

namespace App\Domain\API\Auth\Responses;

use App\Models\User;
use Illuminate\Support\Collection;

class LoginResponse
{
    public function __construct(
        private User $user,
        private string $token,
        private Collection $roles
    ) {}

    public function toArray(): array
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'token' => $this->token,
            'token_type' => 'Bearer',
            'roles' => $this->roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
            ])->toArray(),
        ];
    }
}
