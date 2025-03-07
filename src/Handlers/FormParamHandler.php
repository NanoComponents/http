<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Interfaces\ParamHandler\FormHandlerInterface;
use NanoLibs\Http\Traits\ParamSanitizationTrait;

class FormParamHandler extends BaseHandler implements FormHandlerInterface
{
    use ParamSanitizationTrait;
}
