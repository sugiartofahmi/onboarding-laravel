<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\File\Messages;

class FileErrorMessage
{
    public const FILE_UPLOAD_FAILED = 'Failed to upload file';
    public const INVALID_FILE_TYPE = 'Invalid file type';
    public const FILE_TOO_LARGE = 'File size exceeds maximum limit';
}
