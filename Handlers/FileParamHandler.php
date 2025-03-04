<?php

namespace Nano\Http\Handlers;

use function array_map;

use Nano\Http\Interfaces\ParamHandler\FileHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Interfaces\UploadedFileInterface;
use Nano\Http\Services\Files\UploadRegistry;

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
     * @return array<\Nano\Http\Services\Files\UploadedFile>
     */
    public function getAll(): array
    {
        return $this->uploadedRegistry->getUploadedFile();
    }

    public function isFileExists(string $fileName): bool
    {
        return in_array($fileName, $this->getName());
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
     * @return array<\Nano\Http\Services\Files\UploadedFile>|null
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


    protected function registerUploadRegistry()
    {
        $this->uploadedRegistry = new UploadRegistry($this->paramInterface->getAll());
    }

    /**
     * @return array<\Nano\Http\Services\Files\UploadedFile>
     */
    protected function getFilesArrayOfSpecifiedFormNameOrAll(): array
    {
        return $this->uploadedRegistry->getUploadedFile($this->specifiedFormName);
    }
}
