<?php

namespace NanoLibs\Http\Interfaces;

interface ParamInterface
{
    public function getAll(): array;
    public function get(string $key): string|array|object|int|null;
}
