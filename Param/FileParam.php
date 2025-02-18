<?php

namespace Nano\Http\Param;

use Nano\Http\Handlers\FileParamHandler;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Traits\ParamGetterTrait;

class FileParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    public function getHandler(): FileParamHandler
    {
        return $this->handler ??= new FileParamHandler($this);
    }
}
