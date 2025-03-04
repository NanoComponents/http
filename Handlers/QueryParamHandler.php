<?php

namespace Nano\Http\Handlers;

use Nano\Http\Interfaces\ParamHandler\QueryHandlerInterface;
use Nano\Http\Traits\ParamSanitizationTrait;

class QueryParamHandler extends BaseHandler implements QueryHandlerInterface
{
    use ParamSanitizationTrait;
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }

}