<?php

namespace Nano\Http\Interfaces\ParamHandler;

interface FormHandlerInterface extends ParamHandlerInterface
{
    public function get(string $value): mixed;
}