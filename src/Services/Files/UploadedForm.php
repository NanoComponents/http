<?php

namespace NanoLibs\Http\Services\Files;

use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;
use NanoLibs\Http\Interfaces\Service\UploadedFormInterface;

class UploadedForm implements UploadedFormInterface
{
    /** @var array<string, UploadedFileInterface> $uploadedFilesInterface */
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
        //$this->uploadedFilesInterface[$fullFieldNameWithSuffix][] = $uploadedFile;
        $this->uploadedFilesInterface[] = $uploadedFile;
        return $this;
    }

    /**
     *     <suffixFieldName, UploadedFileInterface>
     * @return array<string, UploadedFileInterface>
     */
    public function getFiles(): array
    {
        return $this->uploadedFilesInterface;
    }

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
