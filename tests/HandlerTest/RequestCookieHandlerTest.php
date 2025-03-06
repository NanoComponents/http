<?php

namespace NanoLibs\Http\Tests\HandlerTest;

use NanoLibs\Http\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestCookieHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_COOKIE = [];
    }

    public function testValidCookieParams()
    {
        $_COOKIE['user_id'] = '12345';
        $_COOKIE['session_token'] = 'abcdef';

        $request = RequestFactory::create();

        $this->assertEquals('12345', $request->getCookie()->get('user_id'));
        $this->assertEquals('abcdef', $request->getCookie()->get('session_token'));
    }

    public function testMissingCookieParam()
    {
        $request = RequestFactory::create();
        $this->assertNull($request->getCookie()->get('NON_EXISTENT_COOKIE'));
    }

    public function testEdgeCaseEmptyValues()
    {
        $_COOKIE['user_id'] = '';
        $_COOKIE['session_token'] = null;

        $request = RequestFactory::create();

        $this->assertEquals('', $request->getCookie()->get('user_id'));
        $this->assertNull($request->getCookie()->get('session_token'));
    }

    public function testCookieParamWithDefaultValue()
    {
        $request = RequestFactory::create();
        $this->assertNull($request->getCookie()->get('UNKNOWN_COOKIE'));
    }

    public function testCaseSensitiveCookieNames()
    {
        $_COOKIE['UserID'] = '123';
        $request = RequestFactory::create();

        $this->assertNull($request->getCookie()->get('userid')); // Case-sensitive check
        $this->assertEquals('123', $request->getCookie()->get('UserID'));
    }

    public function testSpecialCharactersInCookieNameAndValue()
    {
        $_COOKIE['user@name'] = 'special#value!';
        $request = RequestFactory::create();

        $this->assertEquals('special#value!', $request->getCookie()->get('user@name'));
    }

    public function testNumericCookieValueHandling()
    {
        $_COOKIE['count'] = '42';
        $request = RequestFactory::create();

        $this->assertSame('42', $request->getCookie()->get('count')); // Returned as string
    }

    public function testBooleanLikeValues()
    {
        $_COOKIE['is_admin'] = 'true';
        $_COOKIE['is_active'] = '1';
        $_COOKIE['is_banned'] = '0';

        $request = RequestFactory::create();

        $this->assertEquals('true', $request->getCookie()->get('is_admin'));
        $this->assertEquals('1', $request->getCookie()->get('is_active'));
        $this->assertSame('0', $request->getCookie()->get('is_banned'));
    }

    public function testHasMethod()
    {
        $_COOKIE['exists'] = 'yes';
        $request = RequestFactory::create();

        $this->assertTrue($request->getCookie()->isCookieExists('exists'));
        $this->assertFalse($request->getCookie()->isCookieExists('does_not_exist'));
    }

    public function testGetAllCookies()
    {
        $_COOKIE = [
            'cookie1' => 'val1',
            'cookie2' => 'val2',
            'cookie3' => 'val3'
        ];

        $request = RequestFactory::create();
        $all = $request->getCookie()->getAll();

        $this->assertCount(3, $all);
        $this->assertEquals('val1', $all['cookie1']);
        $this->assertEquals('val2', $all['cookie2']);
        $this->assertEquals('val3', $all['cookie3']);
    }

    public function testEmptyStringVsNotPresent()
    {
        $_COOKIE['empty'] = '';
        $request = RequestFactory::create();

        $this->assertTrue($request->getCookie()->isCookieExists('empty'));
        $this->assertEquals('', $request->getCookie()->get('empty'));
        $this->assertNull($request->getCookie()->get('not_present'));
    }

    public function testLargeCookieValue()
    {
        $largeValue = str_repeat('a', 4096); // 4KB
        $_COOKIE['large'] = $largeValue;

        $request = RequestFactory::create();
        $this->assertEquals($largeValue, $request->getCookie()->isCookieExists('large'));
    }

    public function testMultipleCookies()
    {
        $_COOKIE = [
            'first' => '1',
            'second' => '2',
            'third' => '3'
        ];

        $request = RequestFactory::create();
        $this->assertCount(3, $request->getCookie()->getAll());
    }

    public function testOverwriteCookieValue()
    {
        $_COOKIE['test'] = 'old';
        $_COOKIE['test'] = 'new'; // Overwrite

        $request = RequestFactory::create();
        $this->assertEquals('new', $request->getCookie()->get('test'));
    }

    public function testUnsetCookieDuringRequest()
    {
        $_COOKIE['temp'] = 'data';
        unset($_COOKIE['temp']); // Simulate unset during request

        $request = RequestFactory::create();
        $this->assertFalse($request->getCookie()->isCookieExists('temp'));
        $this->assertNull($request->getCookie()->get('temp'));
    }

    public function testMalformedCookieName()
    {
        $_COOKIE['invalid;name'] = 'value';
        $request = RequestFactory::create();

        $this->assertEquals('value', $request->getCookie()->get('invalid;name'));
    }

}
