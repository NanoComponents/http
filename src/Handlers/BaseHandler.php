<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Interfaces\ParamHandler\ParamHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;

abstract class BaseHandler implements ParamHandlerInterface
{
    public function __construct(
        protected readonly ParamInterface $paramInterface
    ) {
    }

    /** @return array<mixed>  */
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }

    public function getValue(string $name): mixed
    {
        $value = $this->paramInterface->get($name);
        return $value ?? null;
    }

    /** @return array<mixed>  */
    protected function getGlobalParamArray(): array
    {
        return $this->paramInterface->getAll();
    }
}
