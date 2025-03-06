<?php

namespace NanoLibs\Http\Interfaces\ParamHandler;

interface CookieHandlerInterface extends ParamHandlerInterface
{
    public function isCookieExists(string $fileName): bool;
    public function get(string $value): mixed;
}
