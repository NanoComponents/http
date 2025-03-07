<?php

namespace NanoLibs\Http\Param;

use NanoLibs\Http\Interfaces\ParamHandler\ParamHandlerInterface;

/**
 * @template T of ParamHandlerInterface
 */
abstract class BaseParameter {

    /**
     * Array of Global variable content ($_GET, $_POST, $_SERVER ,...)
     * @var array<mixed> $parameters
     */
    protected array $parameters = [];

    /** @var T */
    protected ParamHandlerInterface $handler;

    /**
     * Summary of __construct
     * @param array<mixed> $globalParam
     */
    public function __construct(
        protected array $globalParam
    ) {
        $this->parameters = $globalParam;
    }

    public function get(string $key): mixed
    {
        if (!isset($this->parameters[$key])) {
            return null;
        }
        return $this->parameters[$key];
    }

    abstract public function getHandler(): ParamHandlerInterface;
}