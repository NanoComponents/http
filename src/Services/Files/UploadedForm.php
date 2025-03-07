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

    #[\Override]
    public function getFormName(): string
    {
        return $this->formName;
    }

    #[\Override]
    public function addUploadedFile(UploadedFileInterface $uploadedFileInterface): self
    {
        $this->uploadedFilesInterface[] = $uploadedFileInterface;
        return $this;
    }

    /**
     * @return array<int, UploadedFileInterface>
     */
    #[\Override]
    public function getFiles(): array
    {
        return $this->uploadedFilesInterface;
    }

    /**
     * @return array<int|string, mixed>
     */
    #[\Override]
    public function getFieldNames(): array 
    {
        return array_keys($this->uploadedFilesInterface);
    }
}
