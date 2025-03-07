<?php

namespace NanoLibs\Http\Param;

use NanoLibs\Http\Handlers\QueryParamHandler;
use NanoLibs\Http\Interfaces\ParamHandler\QueryHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Traits\ParamGetterTrait;

/**
 * @extends BaseParameter<QueryhandlerInterface>
 */
class QueryParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    /**
     * @return array<array<mixed>|mixed>
     */
    #[\Override]
    public function getAll(): array
    {
        return $this->parameters;
    }

    #[\Override]
    public function getHandler(): QueryHandlerInterface
    {
    return $this->handler ??= new QueryParamHandler($this);
    }
}
