<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Validations;

class FileValidationRules
{
    public static function imageRules(bool $required = false): array
    {
        $rules = [
            'image',
            'mimes:jpg,jpeg,png',
            'max:5120', // 5MB in KB
        ];

        if ($required) {
            array_unshift($rules, 'required');
        } else {
            array_unshift($rules, 'nullable');
        }

        return $rules;
    }

    public static function maxFileSize(): int
    {
        return 5120; // 5MB
    }

    public static function allowedImageMimes(): array
    {
        return ['jpg', 'jpeg', 'png'];
    }
}
