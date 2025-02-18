<?php

namespace Nano\Http\Param;

use Nano\Http\Interfaces\ParamHandlerInterface;

abstract class BaseParameter {

    /**
     * Array of Global variable content ($_GET, $_POST, $_SERVER ,...)
     * @var array $parameters
     */
    protected array $parameters = [];
    protected ParamHandlerInterface $handler;

    public function __construct(
        protected array $globalParam
    ) {
        foreach ($globalParam as $key => $value) {
            $this->parameters[$key] = $value;
        }
    }

    abstract public function getHandler(): ParamHandlerInterface;
}