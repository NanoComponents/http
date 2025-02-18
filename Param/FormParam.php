<?php

namespace Nano\Http\Param;

use Nano\Http\Handlers\FormParamHandler;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Traits\ParamGetterTrait;

class FormParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    public function getHandler(): FormParamHandler
    {
        return $this->handler ??= new FormParamHandler($this);
    }
}
