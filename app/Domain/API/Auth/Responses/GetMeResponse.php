<?php

declare(strict_types=1);

namespace App\Domain\API\Auth\Responses;

use App\Models\User;

class GetMeResponse
{
    public function __construct(private User $user) {}

    public function toArray(): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ];
    }
}
