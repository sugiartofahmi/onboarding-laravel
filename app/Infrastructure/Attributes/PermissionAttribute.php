<?php

declare(strict_types=1);

namespace App\Infrastructure\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class PermissionAttribute
{
    public function __construct(
        public string $permission
    ) {}
}
