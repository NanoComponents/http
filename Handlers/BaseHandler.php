<?php

namespace Nano\Http\Handlers;

use Nano\Http\Interfaces\ParamHandler\ParamHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;

abstract class BaseHandler implements ParamHandlerInterface
{
    public function __construct(
        protected readonly ParamInterface $paramInterface
    ) {
    }

    public function getValue(string $name): mixed
    {
        $value = $this->paramInterface->get($name);
        return $value ?? null;
    }

    protected function getGlobalParamArray(): array
    {
        return $this->paramInterface->getAll();
    }
}
