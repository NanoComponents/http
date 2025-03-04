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
        if (!isset($value)) {
            return null;
        }
        return $value;
    }

    protected function getGlobalParamArray()
    {
        return $this->paramInterface->getAll();
    }
}
