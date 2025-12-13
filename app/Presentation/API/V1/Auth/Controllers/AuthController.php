<?php

declare(strict_types=1);

namespace App\Presentation\API\V1\Auth\Controllers;

use App\Domain\API\Auth\Messages\AuthMessage;
use App\Domain\API\Auth\Requests\GetMeRequest;
use App\Domain\API\Auth\Requests\LoginRequest;
use App\Domain\API\Auth\Requests\RegisterRequest;
use App\Domain\API\Auth\Services\AuthService;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Helpers\ApiDataResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $response = $this->authService->register($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: AuthMessage::REGISTER_SUCCESS,
            statusCode: HttpStatusCode::CREATED
        ))->toResponse();
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $response = $this->authService->login($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: AuthMessage::LOGIN_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    public function roles(): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();
        $response = $this->authService->getRoles($user);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: '',
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return (new ApiDataResponse(
            data: null,
            message: AuthMessage::LOGOUT_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    public function me(GetMeRequest $request): JsonResponse
    {
        $response = $this->authService->me($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: '',
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }
}
