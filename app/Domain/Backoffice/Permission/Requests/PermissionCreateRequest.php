<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'sometimes|string|max:255|unique:permissions,guard_name',
        ];
    }
}
