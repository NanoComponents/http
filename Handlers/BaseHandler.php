<?php

namespace Nano\Http\Handlers;

use Nano\Http\Interfaces\ParamHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;

abstract class BaseHandler implements ParamHandlerInterface{
    public function __construct(
        private readonly ParamInterface $paramInterface
    ) {}

    public function __get($name): mixed
    {
        $value = $this->paramInterface->get($name);
        if ((!isset($value) && empty($value)) || is_object($value)) {
            return null;
        }
        if(is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            return urldecode($value);
        }
        return null;
    }

    public function getAll() 
    {
        return $this->paramInterface->getAll();
    }
}