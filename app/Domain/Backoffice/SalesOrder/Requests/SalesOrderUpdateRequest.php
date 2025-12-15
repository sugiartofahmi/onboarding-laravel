<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:pending,paid,void'],
        ];
    }
}
