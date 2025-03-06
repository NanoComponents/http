<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Interfaces\ParamHandler\QueryHandlerInterface;
use NanoLibs\Http\Traits\ParamSanitizationTrait;

class QueryParamHandler extends BaseHandler implements QueryHandlerInterface
{
    use ParamSanitizationTrait;
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }

}