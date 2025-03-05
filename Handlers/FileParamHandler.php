<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Interfaces\ParamHandler\FileHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;
use NanoLibs\Http\Services\Files\UploadedFile;
use NanoLibs\Http\Services\Files\UploadRegistry;

class FileParamHandler extends BaseHandler implements FileHandlerInterface
{
    protected ?UploadRegistry $uploadedRegistry;

    public function __construct(
        protected readonly ParamInterface $paramInterface,
        protected ?string $specifiedFormName = null
    ) {
        $this->registerUploadRegistry();
    }

    /**
     * @return array<UploadedFile>
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
                return true;
            }
        }
        return false;
    }

    public function getName()    : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileName();
        }
        return $result;
    }

    public function getFullPath(): array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileFullPath();
        }
        return $result;
    }

    public function getType()    : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileType();
        }
        return $result;
    }

    public function getTempName(): array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileTempName();
        }
        return $result;
    }

    public function getError()   : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileError();
        }
        return $result;
    }

    public function getSize()    : array
    {
        $result = [];
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            $result[] = $file->getFileSize();
        }
        return $result;
    }

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
     * @return array<UploadedFile>|null
     */
    public function get(string $fileName): ?UploadedFileInterface
    {
        foreach ($this->getFilesArrayOfSpecifiedFormNameOrAll() as $file) {
            if ($fileName === $file->getFileName()) {
                $result[] = $file;
            }
        }
        return isset($result) ?: null;
    }


    protected function registerUploadRegistry(): void
    {
        $this->uploadedRegistry = new UploadRegistry($this->paramInterface->getAll());
    }

    /**
     * @return array<UploadedFile>
     */
    protected function getFilesArrayOfSpecifiedFormNameOrAll(): array
    {
        return $this->uploadedRegistry->getUploadedFile($this->specifiedFormName);
    }
}
