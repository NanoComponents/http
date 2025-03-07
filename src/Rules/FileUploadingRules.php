<?php

namespace NanoLibs\Http\Rules;

use NanoLibs\Http\Exceptions\InvalidUploadMessagesPathException;
use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;
use NanoLibs\Http\Exceptions\UnknownErrorStatus;

use function in_array;


final class FileUploadingRules
{
    /**
     * Sizes are in kb
     */
    public const int|float MAX_FILE_SIZE = 5 * 1024 * 1024;
    public const int|float MIN_FILE_SIZE = 1 * 1024;
    /**
     * @var array<string>
     */
    protected static ?array $error_messages = null;
    protected static UploadedFileInterface $uploadedFile;


    /**
     * @var array|string[]
     */
    public static array $verifiedFileTypes = [
        'image/jpeg',
        'image/png',
        'application/pdf',
        'text/plain',
        'application/zip',
    ];

    /**
     * @return array<string>
     * @throws InvalidUploadMessagesPathException
     * @throws UnknownErrorStatus
     */
    public static function checkAllFileError(UploadedFileInterface $uploadedFile): array
    {
        $errors = [];
        self::$uploadedFile = $uploadedFile;
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
        self::$error_messages = null;
        return $result;
    }

    /**
     * @throws InvalidUploadMessagesPathException
     */
    protected static function checkMaxFileError(int $uploadedFileSize): string|false
    {
        if ($uploadedFileSize > self::MAX_FILE_SIZE) {
            return self::getErrorMessage()['max_size_error'];
        }
        return false;
    }

    /**
     * @throws InvalidUploadMessagesPathException
     */
    protected static function checkMinFileError(int $uploadedFileSize): string|false
    {
        if ($uploadedFileSize < self::MIN_FILE_SIZE) {
            return self::getErrorMessage()['min_size_error'];
        }
        return false;
    }

    /**
     * @throws InvalidUploadMessagesPathException
     */
    protected static function checkFileTypeError(string $fileType):     string|false
    {
        if (!in_array($fileType, self::$verifiedFileTypes, true)) {
            return self::getErrorMessage()['invalid_file_type'];
        }
        return false;
    }

    /**
     * @throws UnknownErrorStatus
     * @throws InvalidUploadMessagesPathException
     */
    protected static function checkUploadErrorInGlobalFileArray(int|string $errorKey): string
    {
        if (!array_key_exists($errorKey, self::getErrorMessage())) {
            throw new UnknownErrorStatus('Unknown error status detected : ' . $errorKey);
        }
        return self::getErrorMessage()[$errorKey];
    }

    /**
     * @return array<string|int, string>
     * @throws InvalidUploadMessagesPathException
     */
    protected static function getErrorMessage(): array
    {
        if (self::$error_messages === null) {
            $path = __DIR__ . DIRECTORY_SEPARATOR . 'FileUploadingRuleMessages.php';
            if (is_file($path)) {
                /** @var array<string|array, string> $error_messages */
                $error_messages = require $path;
            } else {
                throw new InvalidUploadMessagesPathException('Not found the required path : ' . $path);
            }
            self::$error_messages = $error_messages;
        }
        return self::$error_messages;
    }

}
