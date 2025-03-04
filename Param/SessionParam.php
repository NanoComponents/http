<?php

namespace Nano\Http\Param;

use Nano\Http\Handlers\SessionParamHandler;
use Nano\Http\Interfaces\ParamHandler\SessionHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Traits\ParamGetterTrait;

class SessionParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;


    public function getHandler(): SessionHandlerInterface
    {
        return $this->handler ??= new SessionParamHandler($this);
    }
}
