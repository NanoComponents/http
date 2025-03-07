<?php

namespace NanoLibs\Http\Services\Files;

use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;
use NanoLibs\Http\Interfaces\Service\UploadedFormInterface;

class UploadedForm implements UploadedFormInterface
{
    /** @var array<int, UploadedFileInterface> $uploadedFilesInterface */
    protected array  $uploadedFilesInterface;

    public function __construct(
        protected string $formName,
    ) {
    }

    public function getFormName(): string
    {
        return $this->formName;
    }

    public function addUploadedFile(UploadedFileInterface $uploadedFile): self
    {
        $fullFieldNameWithSuffix = $this->createFullFieldName($uploadedFile);
        $this->uploadedFilesInterface[] = $uploadedFile;
        return $this;
    }

    /**
     * @return array<int, UploadedFileInterface>
     */
    public function getFiles(): array
    {
        return $this->uploadedFilesInterface;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getFieldNames(): array 
    {
        return array_keys($this->uploadedFilesInterface);
    }

    protected function createFullFieldName(UploadedFileInterface $uploadedFile): string
    {
        return $uploadedFile->getFieldNameSuffix() 
        ? $this->formName . '.' . $uploadedFile->getFieldNameSuffix()
        : $this->formName;
    }

}
