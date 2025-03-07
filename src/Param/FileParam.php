<?php

namespace NanoLibs\Http\Param;

use NanoLibs\Http\Handlers\FileParamHandler;
use NanoLibs\Http\Interfaces\ParamHandler\FileHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Traits\ParamGetterTrait;

/**
 * @extends BaseParameter<FileHandlerInterface>
 */
class FileParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    public function getHandler(?string $fileName = null): FileHandlerInterface
    {
        return $this->handler ??= new FileParamHandler($this);
    }
}
