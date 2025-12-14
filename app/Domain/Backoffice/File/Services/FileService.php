<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\File\Services;

use App\Domain\Backoffice\File\Messages\FileErrorMessage;
use App\Domain\Backoffice\File\Requests\FileUploadRequest;
use App\Domain\Backoffice\File\Responses\FileUploadResponse;
use App\Infrastructure\Storage\Services\StorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileService
{
    public function __construct(
        private StorageService $storageService
    ) {}

    public function upload(FileUploadRequest $request): FileUploadResponse
    {
        try {
            /** @var UploadedFile $file */
            $file = $request->file('file');
            $directory = $request->input('directory');

            // Generate filename with datetime format
            $fileName = $this->generateFilename($file);

            // Upload to storage
            $filePath = $this->storageService->upload($file, $directory, $fileName);

            return new FileUploadResponse(
                fileDirectory: $directory,
                fileName: $fileName,
                filePath: $filePath
            );
        } catch (\Exception $e) {
            throw new \RuntimeException(
                FileErrorMessage::FILE_UPLOAD_FAILED . ': ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    private function generateFilename(UploadedFile $file): string
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);

        // Sanitize filename - remove special characters
        $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nameWithoutExtension);

        // Generate datetime prefix (YmdHis format)
        $datetime = now()->format('YmdHis');

        return "{$datetime}_{$sanitizedName}.{$extension}";
    }
}
