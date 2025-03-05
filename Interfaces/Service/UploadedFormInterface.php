<?php

namespace Nano\Http\Interfaces\Service;

interface UploadedFormInterface {
    public function getFormName(): string;
    public function addUploadedFile(UploadedFileInterface $uploadedFileInterface): self;
    public function getFiles(): array;
    public function getFieldNames(): array;
}