<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Interfaces\ParamHandler\SessionHandlerInterface;
use NanoLibs\Http\Traits\ParamSanitizationTrait;

class SessionParamHandler extends BaseHandler implements SessionHandlerInterface
{
    use ParamSanitizationTrait;
}
