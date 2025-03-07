<?php

namespace NanoLibs\Http\Interfaces\ParamHandler;

use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;

interface FileHandlerInterface extends ParamHandlerInterface
{
    public function isFileExists(string $fileName): bool;
    public function isFieldNameExists(string $fieldName): bool;

    /**
     * Summary of getName
     * @return array<string>
     */
    public function getName()    : array;
    /**
     * Summary of getFullPath
     * @return array<string>
     */
    public function getFullPath(): array;
    /**
     * Summary of getType
     * @return array<string>
     */
    public function getType()    : array;
    /**
     * Summary of getTempName
     * @return array<string>
     */
    public function getTempName(): array;
    /**
     * Summary of getError
     * @return array<int, string>
     */
    public function getError()   : array;
    /**
     * Summary of getSize
     * @return array<int, int>
     */
    public function getSize()    : array;

    /**
     * Get UploadedFileInterface of specified form and specified file name
     * @param string $fileName
     * @return array<\NanoLibs\Http\Services\Files\UploadedFile>|null
     */
    public function get(string $fileName): ?array;

    /**
     * @return array<\NanoLibs\Http\Services\Files\UploadedFile>
     */
    public function getAll()          : array;
    /**
     * Summary of getErrorMessages
     * @return array<int, string>
     */
    public function getErrorMessages(): array;
}
