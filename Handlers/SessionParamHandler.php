<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Interfaces\ParamHandler\SessionHandlerInterface;
use NanoLibs\Http\Traits\ParamSanitizationTrait;

class SessionParamHandler extends BaseHandler implements SessionHandlerInterface
{
    use ParamSanitizationTrait;
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }
}