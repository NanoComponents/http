<?php

namespace NanoLibs\Http\Traits;

trait ParamGetterTrait {

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    /**
     * @return array<mixed>
     */
    public function getAll(): array
    {
        return $this->parameters;
    }
}