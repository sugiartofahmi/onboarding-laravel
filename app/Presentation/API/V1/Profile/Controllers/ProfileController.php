<?php

declare(strict_types=1);

namespace App\Presentation\API\V1\Profile\Controllers;

use App\Domain\API\Profile\Messages\ProfileMessage;
use App\Domain\API\Profile\Requests\ProfileShowRequest;
use App\Domain\API\Profile\Requests\ProfileUpdateRequest;
use App\Domain\API\Profile\Services\ProfileService;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Responses\ApiDataResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService
    ) {}

    public function show(ProfileShowRequest $request): JsonResponse
    {
        $response = $this->profileService->show();

        return (new ApiDataResponse(
            data: $response->toArray(),
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $response = $this->profileService->update($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: ProfileMessage::UPDATE_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }
}
