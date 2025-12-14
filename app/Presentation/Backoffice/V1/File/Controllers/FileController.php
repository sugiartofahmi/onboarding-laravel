<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\File\Controllers;

use App\Domain\Backoffice\File\Messages\FileMessage;
use App\Domain\Backoffice\File\Requests\FileUploadRequest;
use App\Domain\Backoffice\File\Services\FileService;
use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Infrastructure\Attributes\PermissionAttribute;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Helpers\ApiDataResponse;
use App\Infrastructure\Helpers\BaseController;
use Illuminate\Http\JsonResponse;

class FileController extends BaseController
{
    public function __construct(
        private FileService $fileService
    ) {}

    public function upload(FileUploadRequest $request): JsonResponse
    {
        $response = $this->fileService->upload($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: FileMessage::UPLOAD_SUCCESS,
            statusCode: HttpStatusCode::CREATED
        ))->toResponse();
    }
}
