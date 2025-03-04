<?php

namespace Nano\Http\Tests\HandlerTest;

use Nano\Http\Handlers\ServerParamHandler;
use Nano\Http\Interfaces\ParamHandler\ServerHandlerInterface;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestServerHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER = [];
    }

    private function createHandler(array $serverParams): ServerHandlerInterface
    {
        $paramInterface = $this->createMock(ParamInterface::class);
        $paramInterface->method('get')->willReturnCallback(fn($key) => $serverParams[$key] ?? null);
        
        return new ServerParamHandler($paramInterface);
    }

    public function testMissingServerParam()
    {
        $request = Request::initialize();
        $this->assertNull($request->getServer()->get('NON_EXISTENT_PARAM'));
    }

    public function testServerParamWithDefaultValue()
    {
        $request = Request::initialize();
        $this->assertNull($request->getServer()->get('UNKNOWN_PARAM', 'default'));
    }

    public function testValidServerParams()
    {
        $server = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0',
            'REQUEST_METHOD' => 'POST',
            'HTTP_HOST' => 'example.com',
            'HTTPS' => 'on',
            'REMOTE_ADDR' => '192.168.1.1',
            'REQUEST_URI' => '/test',
            'QUERY_STRING' => 'foo=bar',
            'SCRIPT_NAME' => '/index.php',
            'SERVER_PROTOCOL' => 'HTTP/2.0',
            'HTTP_REFERER' => 'https://google.com',
            'HTTP_ACCEPT_LANGUAGE' => 'en-US',
            'HTTP_AUTHORIZATION' => 'Bearer token',
            'SERVER_NAME' => 'api.example.com',
            'SERVER_PORT' => '443',
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'text/html',
            'HTTP_X_FORWARDED_FOR' => '10.0.0.1',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
        ];

        $handler = $this->createHandler($server);

        $this->assertEquals('Mozilla/5.0', $handler->getUserAgent());
        $this->assertEquals('POST', $handler->getMethod());
        $this->assertEquals('example.com', $handler->getHost());
        $this->assertEquals('192.168.1.1', $handler->getClientIp());
        $this->assertEquals('/test', $handler->getRequestUri());
        $this->assertEquals('foo=bar', $handler->getQueryString());
        $this->assertEquals('/index.php', $handler->getScriptName());
        $this->assertEquals('HTTP/2.0', $handler->getProtocol());
        $this->assertTrue($handler->isSecure());
        $this->assertEquals('https://google.com', $handler->getReferer());
        $this->assertEquals('en-US', $handler->getAcceptLanguage());
        $this->assertEquals('Bearer token', $handler->getAuthorizationHeader());
        $this->assertEquals('api.example.com', $handler->getServerName());
        $this->assertSame(443, $handler->getPort());
        $this->assertEquals('application/json', $handler->getContentType());
        $this->assertEquals('text/html', $handler->getAccept());
        $this->assertEquals('10.0.0.1', $handler->getForwardedFor());
        $this->assertTrue($handler->isXmlHttpRequest());
    }

    public function testMissingParams()
    {
        $handler = $this->createHandler([]);

        $this->assertNull($handler->getUserAgent());
        $this->assertNull($handler->getMethod());
        $this->assertNull($handler->getHost());
        $this->assertNull($handler->getClientIp());
        $this->assertNull($handler->getRequestUri());
        $this->assertNull($handler->getQueryString());
        $this->assertNull($handler->getScriptName());
        $this->assertNull($handler->getProtocol());
        $this->assertFalse($handler->isSecure());
        $this->assertNull($handler->getReferer());
        $this->assertNull($handler->getAcceptLanguage());
        $this->assertNull($handler->getAuthorizationHeader());
        $this->assertNull($handler->getServerName());
        $this->assertNull($handler->getPort());
        $this->assertNull($handler->getContentType());
        $this->assertNull($handler->getAccept());
        $this->assertNull($handler->getForwardedFor());
        $this->assertFalse($handler->isXmlHttpRequest());
    }

    public function testEdgeCaseEmptyValues()
    {
        $server = [
            'HTTP_HOST' => '',
            'REQUEST_URI' => null,
            'SERVER_PORT' => 'invalid'
        ];

        $handler = $this->createHandler($server);

        $this->assertEquals('', $handler->getHost());
        $this->assertNull($handler->getRequestUri());
        $this->assertNull($handler->getPort());
    }

    public function testPortConversion()
    {
        $handler = $this->createHandler(['SERVER_PORT' => '8080']);
        $this->assertSame(8080, $handler->getPort());
    }

    public function testSecureDetectionVariations()
    {
        $testCases = [
            // Explicit HTTPS cases
            [
                'HTTPS' => 'on',
                'SERVER_PORT' => '80',
                'expected' => true
            ],
            [
                'HTTPS' => 'off',
                'SERVER_PORT' => '80',
                'expected' => false
            ],
            [
                'HTTPS' => '1',
                'SERVER_PORT' => '80',
                'expected' => true
            ],
            [
                'HTTPS' => '0',
                'SERVER_PORT' => '80',
                'expected' => false
            ],
            
            // Port-based cases (with explicit HTTPS status)
            [
                'HTTPS' => null,
                'SERVER_PORT' => '443',
                'expected' => true
            ],
            [
                'HTTPS' => null,
                'SERVER_PORT' => '80',
                'expected' => false
            ],
            
            // Edge case: both HTTPS and port set
            [
                'HTTPS' => 'on',
                'SERVER_PORT' => '443',
                'expected' => true
            ],
            [
                'HTTPS' => 'off',
                'SERVER_PORT' => '443',
                'expected' => true // Port takes precedence
            ]
        ];

        foreach ($testCases as $case) {
            $handler = $this->createHandler($case);
            $this->assertSame(
                $case['expected'],
                $handler->isSecure(),
                "Failed for: " . json_encode($case)
            );
        }
    }

    public function testXmlHttpRequestDetection()
    {
        $testCases = [
            ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest', 'expected' => true],
            ['HTTP_X_REQUESTED_WITH' => 'OtherValue', 'expected' => false],
            ['HTTP_X_REQUESTED_WITH' => '', 'expected' => false],
            ['HTTP_X_REQUESTED_WITH' => null, 'expected' => false],
        ];

        foreach ($testCases as $case) {
            $handler = $this->createHandler($case);
            $this->assertSame($case['expected'], $handler->isXmlHttpRequest(), json_encode($case));
        }
    }

    public function testMethodNormalization()
    {
        $testCases = [
            ['REQUEST_METHOD' => 'get', 'expected' => 'GET'],
            ['REQUEST_METHOD' => 'Post', 'expected' => 'POST'],
            ['REQUEST_METHOD' => 'PUT', 'expected' => 'PUT'],
            ['REQUEST_METHOD' => null, 'expected' => null],
        ];

        foreach ($testCases as $case) {
            $handler = $this->createHandler($case);
            $this->assertSame($case['expected'], $handler->getMethod());
        }
    }

}
