<?php

namespace NanoLibs\Http\Param;

use NanoLibs\Http\Handlers\CookieParamHandler;
use NanoLibs\Http\Interfaces\ParamHandler\CookieHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Traits\ParamGetterTrait;

/**
 * @extends BaseParameter<CookieHandlerInterface>
 */
class CookieParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    #[\Override]
    public function getHandler(): CookieHandlerInterface
    {
        return $this->handler ??=  new CookieParamHandler($this);
    }
}
