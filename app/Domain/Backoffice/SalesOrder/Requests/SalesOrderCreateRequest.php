<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'uuid', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
