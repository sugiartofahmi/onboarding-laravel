<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\File\Responses;

class FileUploadResponse
{
    public function __construct(
        private string $fileDirectory,
        private string $fileName,
        private string $filePath
    ) {}

    public function toArray(): array
    {
        return [
            'file_directory' => $this->fileDirectory,
            'file_name' => $this->fileName,
            'file_path' => $this->filePath,
        ];
    }
}
