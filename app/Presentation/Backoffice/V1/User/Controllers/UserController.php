<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\User\Controllers;

use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Infrastructure\Attributes\PermissionAttribute;
use App\Domain\Backoffice\User\Messages\UserMessage;
use App\Domain\Backoffice\User\Requests\UserCreateRequest;
use App\Domain\Backoffice\User\Requests\UserDeleteRequest;
use App\Domain\Backoffice\User\Requests\UserIndexRequest;
use App\Domain\Backoffice\User\Requests\UserShowRequest;
use App\Domain\Backoffice\User\Requests\UserUpdateRequest;
use App\Domain\Backoffice\User\Services\UserService;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Helpers\ApiDataResponse;
use App\Infrastructure\Helpers\ApiPaginationResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_USER)]
    public function index(UserIndexRequest $request): JsonResponse
    {
        $response = $this->userService->index($request);

        return (new ApiPaginationResponse(
            paginator: $response->getPaginator(),
            message: UserMessage::INDEX_SUCCESS
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_USER)]
    public function show(UserShowRequest $request, string $id): JsonResponse
    {
        $response = $this->userService->show($id);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: UserMessage::SHOW_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::CREATE_USER)]
    public function store(UserCreateRequest $request): JsonResponse
    {
        $response = $this->userService->create($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: UserMessage::CREATE_SUCCESS,
            statusCode: HttpStatusCode::CREATED
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::UPDATE_USER)]
    public function update(UserUpdateRequest $request, string $id): JsonResponse
    {
        $response = $this->userService->update($id, $request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: UserMessage::UPDATE_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::DELETE_USER)]
    public function destroy(UserDeleteRequest $request, string $id): JsonResponse
    {
        $response = $this->userService->delete($id);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: UserMessage::DELETE_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }
}
