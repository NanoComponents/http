<?php

namespace Nano\Http\Param;

use Nano\Http\Interfaces\ParamHandler\ParamHandlerInterface;

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
        $this->parameters = $globalParam;
    }

    public function get(string $key): string|array|object|int|null
    {
        if (!isset($this->parameters[$key])) {
            return null;
        }
        return $this->parameters[$key];
    }

    abstract public function getHandler(): ParamHandlerInterface;
}