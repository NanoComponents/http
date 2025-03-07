<?php

namespace NanoLibs\Http\Services\Files;

use NanoLibs\Http\Exceptions\InvalidUploadMessagesPathException;
use NanoLibs\Http\Exceptions\UnknownErrorStatus;
use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;
use NanoLibs\Http\Rules\FileUploadingRules;

class UploadedFile implements UploadedFileInterface
{
    public function __construct(
        protected string $fileName,
        protected string $fullPath,
        protected string $fileType,
        protected string $tempName,
        protected string|int $error,
        protected string|int $size,
        protected string $fieldNameSuffix = ''
    ) {
    }

    #[\Override]
    public function getFileName(): string
    {
        return $this->fileName;
    }

    #[\Override]
    public function getFileFullPath(): string
    {
        return $this->fullPath;
    }

    #[\Override]
    public function getFileType(): string
    {
        return $this->fileType;
    }

    #[\Override]
    public function getFileTempName(): string
    {
        return $this->tempName;
    }

    #[\Override]
    public function getFileError(): string|int
    {
        return $this->error;
    }

    #[\Override]
    public function getFileSize(): int
    {
        return (int)$this->size;
    }

    /**
     * @throws UnknownErrorStatus
     * @throws InvalidUploadMessagesPathException
     */
    #[\Override]
    public function getFileErrorMessages(): array
    {
        return FileUploadingRules::checkAllFileError($this);
    }

    #[\Override]
    public function setFieldNameSuffix(string $suffix): void
    {
        $this->fieldNameSuffix = $suffix;
    }

    #[\Override]
    public function getFieldNameSuffix(): string
    {
        return $this->fieldNameSuffix;
    }
}
