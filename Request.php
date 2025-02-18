<?php

namespace Nano\Http;

use Nano\Http\Param\QueryParam;
use Nano\Http\Param\FormParam;
use Nano\Http\Param\ServerParam;
use Nano\Http\Param\CookieParam;
use Nano\Http\Param\FileParam;
use Nano\Http\Collections\ParamCollection;
use Nano\Http\Interfaces\ParameterFactoryInterface;
use Nano\Http\Interfaces\ParamHandlerInterface;
use Nano\Http\Param\ParameterFactory;

class Request
{
    public function __construct(
        private ParamCollection $paramCollection
    ) {
    }

    /**
     * Retrieves a parameter handler for the specified parameter class.
     * 
     * @param string $paramClass
     * @return ParamHandlerInterface $handler
     * 
     * @throws \InvalidArgumentException If requested parameter handler is not found
     */
    private function getParameterFromClass(string $paramClass): ParamHandlerInterface
    {
        $handler = $this->paramCollection->getHandlerFor($paramClass);
        return $handler;
    }

    public function getQuery(): ParamHandlerInterface
    {
        return $this->getParameterFromClass(QueryParam::class);
    }
    public function getForm(): ParamHandlerInterface
    {
        return $this->getParameterFromClass(FormParam::class);
    }
    public function getServer(): ParamHandlerInterface
    {
        return $this->getParameterFromClass(ServerParam::class);
    }
    public function getCookie(): ParamHandlerInterface
    {
        return $this->getParameterFromClass(CookieParam::class);
    }

    public function getFile(): ParamHandlerInterface
    {
        return $this->getParameterFromClass(FileParam::class);
    }

    /**
     * Creates a Request instance from global variables.
     * 
     * @param ParameterFactoryInterface|null $factory Optional factory implementation
     * @return self Immutable request instance
     * 
     * @note Uses ParameterFactory::getDefault() if no factory is provided.
     *       Consider using dependency injection in production code.
     */
    public static function initializeGlobals(
        ParameterFactoryInterface|null $parameterFactoryInterface
        ): self
    {
        $parameterFactoryInterface ??= ParameterFactory::getDefaults();
        return new self(
            paramCollection: $parameterFactoryInterface->createParamCollection()
        );
    }

}
