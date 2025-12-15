<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // No validation rules - ID comes from route parameter
        ];
    }
}
