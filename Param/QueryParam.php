<?php

namespace NanoLibs\Http\Param;

use NanoLibs\Http\Handlers\QueryParamHandler;
use NanoLibs\Http\Interfaces\ParamHandler\QueryHandlerInterface;
use NanoLibs\Http\Interfaces\ParamInterface;
use NanoLibs\Http\Traits\ParamGetterTrait;

class QueryParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;

    public function get(string $key): string|array|object|int|null
    {
        if (!isset($this->parameters[$key])) {
            return null;
        }
        return $this->parameters[$key];
    }

    public function getAll(): array
    {
        return $this->parameters;
    }

    public function getHandler(): QueryHandlerInterface
    {
    return $this->handler ??= new QueryParamHandler($this);
    }
}
