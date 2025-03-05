<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Interfaces\ParamHandler\FormHandlerInterface;
use NanoLibs\Http\Param\FormParam;
use NanoLibs\Http\Traits\ParamSanitizationTrait;

class FormParamHandler extends BaseHandler implements FormHandlerInterface
{
    use ParamSanitizationTrait;
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }
}