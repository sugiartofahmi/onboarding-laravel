<?php

declare(strict_types=1);

namespace App\Infrastructure\Helpers;

use Illuminate\Http\JsonResponse;

abstract class ApiResponse
{
    protected string $version = '1.0';

    abstract public function toResponse(): JsonResponse;
}
