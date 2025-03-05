<?php

namespace NanoLibs\Http\Rules;

use function in_array;
use function key_exists;

use NanoLibs\Http\Exceptions\UnknownErrorStatus;
use NanoLibs\Http\Services\Files\UploadedFile;

final class FileUploadingRules
{
    /**
     * Sizes are in kb
     */
    public const int|float MAX_FILE_SIZE = 5 * 1024 * 1024;
    public const int|float MIN_FILE_SIZE = 1 * 1024;
    protected static ?array $error_messages = null;
    protected static ?UploadedFile $uploadedFile = null;


    public static array $verifiedFileTypes = [
        'image/jpeg',
        'image/png',
        'application/pdf',
        'text/plain',
        'application/zip',
    ];

    /**
     * @throws UnknownErrorStatus
     */
    public static function checkAllFileError(?UploadedFile $uploadedFile = null): array
    {
        self::$uploadedFile ??= $uploadedFile;

        $errors[] = self::checkMaxFileError(self::$uploadedFile->getFileSize());
        $errors[] = self::checkMinFileError(self::$uploadedFile->getFileSize());
        $errors[] = self::checkFileTypeError(self::$uploadedFile->getFileType());
        $errors[] = self::checkUploadErrorInGlobalFileArray(self::$uploadedFile->getFileError());

        $result = [];
        foreach ($errors as $error) {
            if (false !== $error) {
                $result[] = $error;
            }
        }
        self::$uploadedFile = null;
        return $result;
    }

    protected static function checkMaxFileError(int $uploadedFileSize): string|false
    {
        if ($uploadedFileSize > self::MAX_FILE_SIZE) {
            return self::getErrorMessage()['max_size_error'];
        }
        return false;
    }

    protected static function checkMinFileError(int $uploadedFileSize): string|false
    {
        if ($uploadedFileSize < self::MIN_FILE_SIZE) {
            return self::getErrorMessage()['min_size_error'];
        }
        return false;
    }

    protected static function checkFileTypeError(string $fileType):     string|false
    {
        if (!in_array($fileType, self::$verifiedFileTypes, true)) {
            return self::getErrorMessage()['invalid_file_type'];
        }
        return false;
    }

    /**
     * @throws UnknownErrorStatus
     */
    protected static function checkUploadErrorInGlobalFileArray($errorNumber)
    {
        if (!array_key_exists($errorNumber, self::getErrorMessage())) {
            throw new UnknownErrorStatus('Unknown error status detected : ' . $errorNumber);
        }
        return self::getErrorMessage()[$errorNumber];
    }

    protected static function getErrorMessage(): array
    {
        return self::$error_messages ??= require 'FileUploadingRuleMessages.php';
    }

}
