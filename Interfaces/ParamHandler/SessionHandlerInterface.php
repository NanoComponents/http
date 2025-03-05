<?php

namespace NanoLibs\Http\Interfaces\ParamHandler;

interface SessionHandlerInterface extends ParamHandlerInterface
{
    public function get(string $value): mixed;
}
