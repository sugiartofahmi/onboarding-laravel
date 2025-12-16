<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['sometimes', 'date', 'before_or_equal:end_date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
        ];
    }
}
