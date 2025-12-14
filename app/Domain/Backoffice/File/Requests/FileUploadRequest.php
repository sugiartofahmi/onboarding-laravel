<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\File\Requests;

use App\Infrastructure\Storage\Validations\FileValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => FileValidationRules::imageRules(required: true),
            'directory' => ['required', 'string', 'max:255'],
        ];
    }
}
