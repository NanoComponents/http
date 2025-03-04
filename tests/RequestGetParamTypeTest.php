<?php

namespace Nano\Http\Tests;

use Nano\Http\Handlers\CookieParamHandler;
use Nano\Http\Handlers\FormParamHandler;
use Nano\Http\Handlers\QueryParamHandler;
use Nano\Http\Handlers\ServerParamHandler;
use Nano\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestGetParamTypeTest extends TestCase
{
    public function testGetQueryReturnCorrectHandler(): void
    {
        $request          = Request::initialize();

        $paramHandlerType = QueryParamHandler::class;
        $handlerType      = $request->getQuery();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testFormQueryReturnCorrectHandler(): void
    {
        $request          = Request::initialize();

        $paramHandlerType = FormParamHandler::class;
        $handlerType      = $request->getForm();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testServerQueryReturnCorrectHandler(): void
    {
        $request          = Request::initialize();

        $paramHandlerType = ServerParamHandler::class;
        $handlerType      = $request->getServer();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testCookieQueryReturnCorrectHandler(): void
    {
        $request          = Request::initialize();

        $paramHandlerType = CookieParamHandler::class;
        $handlerType      = $request->getCookie();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testFileQueryReturnCorrectHandler(): void
    {
        $request          = Request::initialize();

        $paramHandlerType = FormParamHandler::class;
        $handlerType      = $request->getForm();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }
}
