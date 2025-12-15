<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'uuid', 'exists:products,id'],
            'type' => ['required', 'string', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
