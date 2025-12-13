<?php

declare(strict_types=1);

namespace App\Domain\API\Auth\Responses;

use Illuminate\Database\Eloquent\Collection;

class GetRolesResponse
{
    public function __construct(
        private Collection $roles
    ) {}

    public function toArray(): array
    {
        return $this->roles->map(fn ($role) => [
            'id' => $role->id,
            'name' => $role->name,
            'guard_name' => $role->guard_name,
        ])->toArray();
    }
}
