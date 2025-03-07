<?php

namespace NanoLibs\Http\Param;

use NanoLibs\Http\Handlers\FormParamHandler;
use NanoLibs\Http\Interfaces\ParamHandler\FormHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Traits\ParamGetterTrait;

/**
 * @extends BaseParameter<FormHandlerInterface>
 */
class FormParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    public function getHandler(): FormHandlerInterface
    {
        return $this->handler ??= new FormParamHandler($this);
    }
}
