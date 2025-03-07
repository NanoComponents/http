<?php

namespace NanoLibs\Http\Interfaces\Service;

use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;

interface UploadedFormInterface {
    public function getFormName(): string;
    public function addUploadedFile(UploadedFileInterface $uploadedFileInterface): self;
    /**
     * Summary of getFiles
     * @return array<int, UploadedFileInterface>
     */
    public function getFiles(): array;
    /**
     * Summary of getFieldNames
     * @return array<string>
     */
    public function getFieldNames(): array;
}