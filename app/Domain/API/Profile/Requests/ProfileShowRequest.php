<?php

declare(strict_types=1);

namespace App\Domain\API\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileShowRequest extends FormRequest
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
