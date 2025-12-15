<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $permissionId = $this->route('id');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($permissionId),
            ],
            'guard_name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('permissions', 'guard_name')->ignore($permissionId),
            ],
        ];
    }
}
