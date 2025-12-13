<?php

declare(strict_types=1);

namespace App\Domain\API\Category\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
