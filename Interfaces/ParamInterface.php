<?php

namespace Nano\Http\Interfaces;

use Nano\Http\Interfaces\ParamHandler\ParamHandlerInterface;

interface ParamInterface
{
    public function getAll(): array;
    public function get(string $key): string|array|object|int|null;
    public function getHandler(): ParamHandlerInterface;
}
