<?php

namespace Nano\Http\Param;

use Nano\Http\Handlers\ServerParamHandler;
use Nano\Http\Interfaces\ParamHandler\ServerHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Traits\ParamGetterTrait;

class ServerParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;


    public function getHandler(): ServerHandlerInterface
    {
        return $this->handler ??= new ServerParamHandler($this);
    }
}
