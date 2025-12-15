<?php

declare(strict_types=1);

namespace App\Infrastructure\Helpers;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge([
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'sort_by' => ['sometimes', 'string', 'max:50'],
            'order' => ['sometimes', 'string', 'in:asc,desc'],
        ], $this->queryRules());
    }

    public function getPerPage(): int
    {
        return $this->input('per_page', 10);
    }

    public function getPage(): int
    {
        return $this->input('page', 1);
    }

    public function getSortBy(): string
    {
        return $this->input('sort_by', 'created_at');
    }

    public function getOrder(): string
    {
        return $this->input('order', 'desc');
    }

    abstract protected function queryRules(): array;
}
