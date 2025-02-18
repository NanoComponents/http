<?php

namespace Nano\Http\Interfaces;

use Nano\Http\Collections\ParamCollection;

interface ParameterFactoryInterface {
    public function createParamCollection(): ParamCollection;
}