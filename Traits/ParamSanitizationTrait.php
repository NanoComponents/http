<?php

namespace Nano\Http\Traits;

trait ParamSanitizationTrait {

    public function get(string $value): mixed
    {
        $result = $this->paramInterface->get($value);
        if ($result !== null) {
            return $this->handleParamValueTypes($result);
        }
        return null;
    }

    protected function handleParamValueTypes(mixed $value)
    {
        if (is_object($value)) {
            return null;
        }
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            return urldecode($value);
        }
        if (is_int(intval($value))) {
            return $value;
        }
        if (is_bool($value)) {
            return $value;
        }
        return null;
    }
}