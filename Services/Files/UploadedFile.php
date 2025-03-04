<?php

namespace Nano\Http\Services\Files;

use Nano\Http\Interfaces\UploadedFileInterface;
use Nano\Http\Rules\FileUploadingRules;

class UploadedFile implements UploadedFileInterface
{
    protected array $detectedError = [];
    public function __construct(
        protected string $fileName,
        protected string $fullPath,
        protected string $fileType,
        protected string $tempName,
        protected string $error,
        protected string $size,
        protected string $fieldNameSuffix = ''
    ) {
    }

    public function getFileArray(): array
    {
        return [
            'name'      =>      $this->fileName,
            'full_path' =>      $this->fullPath,
            'type'      =>      $this->fileType,
            'tmp_name'  =>      $this->tempName,
            'error'     =>      $this->error,
            'size'      =>      $this->size
        ];
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFileFullPath(): string
    {
        return $this->fullPath;
    }

    public function getFileType(): string
    {
        return $this->fileType;
    }

    public function getFileTempName(): string
    {
        return $this->tempName;
    }

    public function getFileError(): string
    {
        return $this->error;
    }

    public function getFileSize(): string
    {
        return $this->size;
    }

    public function getFileErrorMessages(): array
    {
        return FileUploadingRules::checkAllFileError($this);
    }

    public function setFieldNameSuffix(string $suffix): void
    {
        $this->fieldNameSuffix = $suffix;
    }

    public function getFieldNameSuffix(): string
    {
        return $this->fieldNameSuffix;
    }
}
