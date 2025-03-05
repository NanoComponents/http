<?php

namespace NanoLibs\Http\Interfaces\ParamHandler;

interface QueryHandlerInterface extends ParamHandlerInterface
{
    public function get(string $value): mixed;
}