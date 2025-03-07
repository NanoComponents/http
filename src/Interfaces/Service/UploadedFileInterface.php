<?php

namespace NanoLibs\Http\Interfaces\Service;

interface UploadedFileInterface
{
    public function getFileName()                       : string;
    public function getFileFullPath()                   : string;
    public function getFileType()                       : string;
    public function getFileTempName()                   : string;
    public function getFileError()                      : string|int;
    public function getFileSize()                       : int;
    /**
     * Summary of getFileErrorMessages
     * @return array<string>
     */
    public function getFileErrorMessages()              : array;
    public function setFieldNameSuffix(string $suffix)  : void;
    public function getFieldNameSuffix()                : string;
}
