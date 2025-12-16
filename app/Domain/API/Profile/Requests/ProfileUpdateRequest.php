<?php

declare(strict_types=1);

namespace App\Domain\API\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = JWTAuth::user()->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', "unique:users,email,{$userId}"],
            'password' => ['sometimes', 'string', 'min:8'],
        ];
    }
}
