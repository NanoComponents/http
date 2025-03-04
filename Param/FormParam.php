<?php

namespace Nano\Http\Param;

use Nano\Http\Handlers\FormParamHandler;
use Nano\Http\Interfaces\ParamHandler\FormHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Traits\ParamGetterTrait;

class FormParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    public function getHandler(): FormHandlerInterface
    {
        return $this->handler ??= new FormParamHandler($this);
    }
}
