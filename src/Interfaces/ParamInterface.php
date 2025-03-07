<?php

namespace NanoLibs\Http\Interfaces;

interface ParamInterface
{
    /**
     * Summary of getAll
     * @return array<string, array<string, mixed>>
     */
    public function getAll(): array;
    public function get(string $key): mixed;
}
