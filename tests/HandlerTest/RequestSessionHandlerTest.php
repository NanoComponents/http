<?php

namespace Nano\Http\Tests\HandlerTest;

use Nano\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestSessionHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testValidSessionParams()
    {
        $_SESSION['user_id'] = '12345';
        $_SESSION['auth'] = true;

        $request = Request::initialize();

        $this->assertEquals('12345', $request->getSession()->get('user_id'));
        $this->assertTrue((bool)$request->getSession()->get('auth'));
    }

    public function testMissingSessionParam()
    {
        $request = Request::initialize();
        $this->assertNull($request->getSession()->get('NON_EXISTENT_SESSION'));
    }

    public function testEdgeCaseEmptyValues()
    {
        $_SESSION['user_id'] = '';
        $_SESSION['auth'] = null;

        $request = Request::initialize();

        $this->assertEquals('', $request->getSession()->get('user_id'));
        $this->assertNull($request->getSession()->get('auth'));
    }

    public function testSessionParamWithDefaultValue()
    {
        $request = Request::initialize();
        $this->assertNull($request->getSession()->get('UNKNOWN_SESSION', 'default'));
    }
}
