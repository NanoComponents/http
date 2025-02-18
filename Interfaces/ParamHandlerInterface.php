<?php

namespace Nano\Http\Interfaces;

interface ParamHandlerInterface{
    public function getAll();

    public function __get($name);
}