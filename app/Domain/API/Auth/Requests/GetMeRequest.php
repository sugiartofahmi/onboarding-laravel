<?php

declare(strict_types=1);

namespace App\Domain\API\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetMeRequest extends FormRequest
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
