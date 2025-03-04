<?php

namespace Nano\Http\Interfaces;

interface UploadedFileInterface
{
    public function getFileName()                       : string;
    public function getFileFullPath()                   : string;
    public function getFileType()                       : string;
    public function getFileTempName()                   : string;
    public function getFileError()                      : string;
    public function getFileSize()                       : string;
    public function getFileArray()                      : array;
    public function getFileErrorMessages()              : array;
    public function setFieldNameSuffix(string $suffix)  : void;
    public function getFieldNameSuffix()                : string;
}
