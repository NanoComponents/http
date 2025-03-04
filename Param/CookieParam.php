<?php

namespace Nano\Http\Param;

use Nano\Http\Handlers\CookieParamHandler;
use Nano\Http\Interfaces\ParamHandler\CookieHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Traits\ParamGetterTrait;

class CookieParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    public function getHandler(): CookieHandlerInterface
    {
        return $this->handler ??= new CookieParamHandler($this);
    }
}
