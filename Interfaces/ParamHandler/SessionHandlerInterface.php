<?php

namespace Nano\Http\Interfaces\ParamHandler;

interface SessionHandlerInterface extends ParamHandlerInterface
{
    public function get(string $value): mixed;
}
