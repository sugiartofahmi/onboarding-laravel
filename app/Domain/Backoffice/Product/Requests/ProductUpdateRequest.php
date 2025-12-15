<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Requests;

use App\Infrastructure\Storage\Validations\FileValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'uuid', 'exists:categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:products,slug,' . $this->route('id')],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['sometimes', 'numeric', 'min:0', 'max:999999999.99'],
            'thumbnail_path' => ['sometimes', 'string', 'max:500'],
            'images_paths' => ['sometimes', 'array', 'max:10'],
            'images_paths.*' => ['string', 'max:500'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            // Note: status is NOT included - it's auto-updated by observer based on stock
        ];
    }
}
