<?php

namespace NanoLibs\Http\Tests\HandlerTest;

use NanoLibs\Http\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestSessionHandlerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testValidSessionParams()
    {
        $_SESSION['user_id'] = '12345';
        $_SESSION['auth'] = true;

        $request = RequestFactory::create();

        $this->assertEquals('12345', $request->getSession()->get('user_id'));
        $this->assertTrue((bool)$request->getSession()->get('auth'));
    }

    public function testMissingSessionParam()
    {
        $request = RequestFactory::create();
        $this->assertNull($request->getSession()->get('NON_EXISTENT_SESSION'));
    }

    public function testEdgeCaseEmptyValues()
    {
        $_SESSION['user_id'] = '';
        $_SESSION['auth'] = null;

        $request = RequestFactory::create();

        $this->assertEquals('', $request->getSession()->get('user_id'));
        $this->assertNull($request->getSession()->get('auth'));
    }

    public function testSessionParamWithDefaultValue()
    {
        $request = RequestFactory::create();
        $this->assertNull($request->getSession()->get('UNKNOWN_SESSION', 'default'));
    }
}
