<?php

namespace Nano\Http\Interfaces\ParamHandler;

use Nano\Http\Interfaces\Service\UploadedFileInterface;

interface FileHandlerInterface extends ParamHandlerInterface
{
    public function isFileExists(string $fileName): bool;
    public function isFieldNameExists(string $fieldName): bool;

    public function getName()    : array;
    public function getFullPath(): array;
    public function getType()    : array;
    public function getTempName(): array;
    public function getError()   : array;
    public function getSize()    : array;

    /**
     * Get UploadedFileInterface of specified form and specified file name
     * @param string $fileName
     * @return array<\Nano\Http\Services\Files\UploadedFile>|null
     */
    public function get(string $fileName): ?UploadedFileInterface;

    /**
     * @return array<\Nano\Http\Services\Files\UploadedFile>
     */
    public function getAll()          : array;
    public function getErrorMessages(): array;
}
