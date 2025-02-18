<?php

namespace Nano\Http\Tests;

use Nano\Http\Handlers\CookieParamHandler;
use Nano\Http\Handlers\FormParamHandler;
use Nano\Http\Handlers\QueryParamHandler;
use Nano\Http\Handlers\ServerParamHandler;
use Nano\Http\Param\ParameterFactory;
use Nano\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestGetParamTypeTest extends TestCase
{
    public function testGetQueryReturnCorrectHandler(): void
    {
        $request          = Request::initializeGlobals(new ParameterFactory);

        $paramHandlerType = QueryParamHandler::class;
        $handlerType      = $request->getQuery();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testFormQueryReturnCorrectHandler(): void
    {
        $request          = Request::initializeGlobals(new ParameterFactory);

        $paramHandlerType = FormParamHandler::class;
        $handlerType      = $request->getForm();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testServerQueryReturnCorrectHandler(): void
    {
        $request          = Request::initializeGlobals(new ParameterFactory);

        $paramHandlerType = ServerParamHandler::class;
        $handlerType      = $request->getServer();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testCookieQueryReturnCorrectHandler(): void
    {
        $request          = Request::initializeGlobals(new ParameterFactory);

        $paramHandlerType = CookieParamHandler::class;
        $handlerType      = $request->getCookie();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testFileQueryReturnCorrectHandler(): void
    {
        $request          = Request::initializeGlobals(new ParameterFactory);

        $paramHandlerType = FormParamHandler::class;
        $handlerType      = $request->getForm();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }
}
