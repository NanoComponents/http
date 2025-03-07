<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Exceptions\InvalidFileArrayException;
use NanoLibs\Http\Interfaces\ParamHandler\FileHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;
use NanoLibs\Http\Services\Files\UploadedFile;
use NanoLibs\Http\Services\Files\UploadRegistry;

class FileParamHandler extends BaseHandler implements FileHandlerInterface
{
    protected UploadRegistry $uploadedRegistry;

    public function __construct(
        protected readonly ParamInterface $paramInterface,
        protected ?string $specifiedFormName = null
    ) {
        $this->registerUploadRegistry();
    }

    /**
     * @return array<int, UploadedFileInterface>
     */
    public function getAll(): array
    {
        return $this->uploadedRegistry->getUploadedFile();
    }

    public function isFileExists(string $fileName): bool
    {
        return in_array($fileName, $this->getName(), true);
    }

    public function isFieldNameExists(string $fieldName): bool
    {
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            if ($fieldName === $file->getFieldNameSuffix()) {
                print_r($file->getFieldNameSuffix());
                return true;
            }
        }
        return false;
    }

    /**
     * @return array<string>
     */
    public function getName()    : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileName();
        }
        return $result;
    }

    /**
     * Summary of getFullPath
     * @return array<string>
     */
    public function getFullPath(): array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileFullPath();
        }
        return $result;
    }

    /**
     * Summary of getType
     * @return array<string>
     */
    public function getType()    : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileType();
        }
        return $result;
    }

    /**
     * Summary of getTempName
     * @return array<string>
     */
    public function getTempName(): array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileTempName();
        }
        return $result;
    }

    /**
     * Summary of getError
     * @return array<int|string>
     */
    public function getError()   : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileError();
        }
        return $result;
    }

    /**
     * Summary of getSize
     * @return array<int, int>
     */
    public function getSize()    : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileSize();
        }
        return $result;
    }

    /**
     * Summary of getErrorMessages
     * @return array<int, string>
     */
    public function getErrorMessages()    : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {

            foreach ($file->getFileErrorMessages() as $error) {
                $result[] = $error;
            }
        }
        return $result;
    }

    public function __destruct()
    {
        unset($this->uploadedRegistry);
    }

    /**
     * Get UploadedFileInterface of specified form and specified file name
     * @param string $fileName
     * @return array<UploadedFileInterface>|null
     */
    public function get(string $fileName): ?array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            if ($fileName === $file->getFileName()) {
                $result[] = $file;
            }
        }
        return $result ?: null;
    }


    /**
     * @throws InvalidFileArrayException
     */
    protected function registerUploadRegistry(): void
    {
        $this->uploadedRegistry = new UploadRegistry($this->paramInterface->getAll());
    }

    /**
     * @return array<UploadedFileInterface>
     */
    protected function getFilesArrayOfSpecifiedFormNameOrAll(): array
    {
        return $this->uploadedRegistry->getUploadedFile(fieldName: $this->specifiedFormName);
    }
}
