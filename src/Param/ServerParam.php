<?php

namespace NanoLibs\Http\Param;

use NanoLibs\Http\Handlers\ServerParamHandler;
use NanoLibs\Http\Interfaces\ParamHandler\ServerHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Traits\ParamGetterTrait;

/**
 * @extends BaseParameter<ServerHandlerInterface>
 */
class ServerParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;


    public function getHandler(): ServerHandlerInterface
    {
        return $this->handler ??= new ServerParamHandler($this);
    }
}
