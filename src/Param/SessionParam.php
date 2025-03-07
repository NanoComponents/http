<?php

namespace NanoLibs\Http\Param;

use NanoLibs\Http\Handlers\SessionParamHandler;
use NanoLibs\Http\Interfaces\ParamHandler\SessionHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Traits\ParamGetterTrait;

/**
 * @extends BaseParameter<SessionHandlerInterface>
 */
class SessionParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;


    public function getHandler(): SessionHandlerInterface
    {
        return $this->handler ??= new SessionParamHandler($this);
    }
}
