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

    #[\Override]
    /** @return array<mixed>  */
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }
}
