<?php

namespace Nano\Http\Interfaces;

interface ParamInterface
{
    public function getAll(): array;
    public function get(string $key): mixed;

    public function getHandler(): ParamHandlerInterface;
}
