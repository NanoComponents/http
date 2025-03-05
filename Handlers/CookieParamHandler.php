<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Interfaces\ParamHandler\CookieHandlerInterface;
use NanoLibs\Http\Traits\ParamSanitizationTrait;


class CookieParamHandler extends BaseHandler implements CookieHandlerInterface
{
    use ParamSanitizationTrait;
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }

    public function isCookieExists(string $fileName): bool
    {
        return $this->paramInterface->get($fileName) !== null;
    }
}