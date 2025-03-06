<?php

namespace NanoLibs\Http\Tests;

use NanoLibs\Http\Handlers\CookieParamHandler;
use NanoLibs\Http\Handlers\FormParamHandler;
use NanoLibs\Http\Handlers\QueryParamHandler;
use NanoLibs\Http\Handlers\ServerParamHandler;
use NanoLibs\Http\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestGetParamTypeTest extends TestCase
{
    public function testGetQueryReturnCorrectHandler(): void
    {
        $request          = RequestFactory::create();

        $paramHandlerType = QueryParamHandler::class;
        $handlerType      = $request->getQuery();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testFormQueryReturnCorrectHandler(): void
    {
        $request          = RequestFactory::create();

        $paramHandlerType = FormParamHandler::class;
        $handlerType      = $request->getForm();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testServerQueryReturnCorrectHandler(): void
    {
        $request          = RequestFactory::create();

        $paramHandlerType = ServerParamHandler::class;
        $handlerType      = $request->getServer();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testCookieQueryReturnCorrectHandler(): void
    {
        $request          = RequestFactory::create();

        $paramHandlerType = CookieParamHandler::class;
        $handlerType      = $request->getCookie();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }

    public function testFileQueryReturnCorrectHandler(): void
    {
        $request          = RequestFactory::create();

        $paramHandlerType = FormParamHandler::class;
        $handlerType      = $request->getForm();
        $this->assertInstanceOf($paramHandlerType, $handlerType);
    }
}
