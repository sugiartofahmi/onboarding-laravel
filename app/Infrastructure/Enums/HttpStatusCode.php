<?php

declare(strict_types=1);

namespace App\Infrastructure\Enums;

enum HttpStatusCode: int
{
    // Success
    case OK = 200;
    case CREATED = 201;
    case NO_CONTENT = 204;

    // Client Error
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case CONFLICT = 409;
    case UNPROCESSABLE_ENTITY = 422;

    // Server Error
    case INTERNAL_SERVER_ERROR = 500;
    case BAD_GATEWAY = 502;
    case SERVICE_UNAVAILABLE = 503;
}
