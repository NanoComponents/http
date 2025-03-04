<?php

namespace Nano\Http\Param;

use Nano\Http\Handlers\QueryParamHandler;
use Nano\Http\Interfaces\ParamHandler\QueryHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Traits\ParamGetterTrait;

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
