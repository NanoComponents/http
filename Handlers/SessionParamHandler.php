<?php

namespace Nano\Http\Handlers;

use Nano\Http\Interfaces\ParamHandler\SessionHandlerInterface;
use Nano\Http\Traits\ParamSanitizationTrait;

class SessionParamHandler extends BaseHandler implements SessionHandlerInterface
{
    use ParamSanitizationTrait;
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }
}