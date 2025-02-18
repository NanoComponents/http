<?php

namespace Nano\Http\Traits;

trait ParamGetterTrait {

    public function get(string $key): mixed
    {
        if (\array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }
        return null;
    }

    public function getAll(): array
    {
        return $this->parameters;
    }
}