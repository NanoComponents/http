<?php

namespace Nano\Http\Handlers;

use Nano\Http\Interfaces\ParamHandler\CookieHandlerInterface;
use Nano\Http\Traits\ParamSanitizationTrait;


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