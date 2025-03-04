<?php

namespace Nano\Http\Interfaces\ParamHandler;

interface QueryHandlerInterface extends ParamHandlerInterface
{
    public function get(string $value): mixed;
}