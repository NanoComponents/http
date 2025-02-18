<?php 

namespace Nano\Http\Tests\HandlerTest;

use Nano\Http\Param\ParameterFactory;
use Nano\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestGetHandlerTest extends TestCase
{
    public function testQueryHandlerProcessBasicQueryParameters()
    {
        $getDefault = [
            'name'          =>  'iman',
            'age'           =>  '22',
            'isVerified'    => true
        ];
        $_GET = $getDefault;
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals($getDefault['name'], $request->getQuery()->name);
        $this->assertEquals($getDefault['age'], $request->getQuery()->age);
        $this->assertEquals($getDefault['isVerified'], $request->getQuery()->isVerified);
    }

    public function testQueryHandlerProcessEmptyQueryParameters()
    {
        $_GET = [];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEmpty($request->getQuery()->getAll());
    }

    public function testQueryHandlerProcessSpecialCharacters()
    {
        $_GET = [
            'search' => 'query+with+spaces',
            'filter' => 'category=books&price<50'
        ];
        
        // Initialize the Request class with the $_GET data
        $request = Request::initializeGlobals(new ParameterFactory);

        // After the handler processes the query parameters:
        $this->assertEquals('query with spaces', $request->getQuery()->search); // + should be decoded to space
        $this->assertEquals('category=books&price<50', $request->getQuery()->filter); // %26 is decoded to & automatically
    }

    public function testQueryHandlerProcessArrayParameters()
    {
        $_GET = [
            'tags' => ['php', 'zend', 'phpunit']
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals(['php', 'zend', 'phpunit'], $request->getQuery()->tags);
    }

    public function testQueryHandlerProcessNumericKeys()
    {
        $_GET = [
            '0' => 'first',
            '1' => 'second'
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
    
        $this->assertEquals('first', $request->getQuery()->{0});
        $this->assertEquals('second', $request->getQuery()->{1});
    }
    public function testQueryHandlerProcessBooleanValues()
    {
        $_GET = [
            'isActive' => 'true',
            'isAdmin'  => 'false'
        ];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals('true', $request->getQuery()->isActive);
        $this->assertEquals('false', $request->getQuery()->isAdmin);
    }

    public function testQueryHandlerHandlesMissingKeys()
    {
        $_GET = [
            'name' => 'iman'
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
        //$this->assertEquals('iman', $request->getQuery()->name);
        $this->assertNull($request->getQuery()->age); 
    }

    public function testQueryHandlerHandlesLargeData()
    {
        $_GET = [
            'largeData' => str_repeat('a', 10000) // 10,000 characters
        ];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals(str_repeat('a', 10000), $request->getQuery()->largeData);
    }
    public function testQueryHandlerProcessNestedArrays()
    {
        $_GET = [
            'user' => [
                'name' => 'iman',
                'age'  => '22'
            ]
        ];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals('iman', $request->getQuery()->user['name']);
        $this->assertEquals('22', $request->getQuery()->user['age']);
    }

    public function testQueryHandlerDecodesUrlEncodedValues()
    {
        $_GET = [
            'search' => 'query%20with%20spaces'
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
    
        $this->assertEquals('query with spaces', $request->getQuery()->search);
    }

    public function testQueryHandlerHandlesInvalidDataTypes()
    {
        $_GET = [
            'invalid' => new \stdClass() // Invalid data type
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
    
        $this->assertNull($request->getQuery()->invalid); // Assuming invalid types return null
    }
    public function testQueryHandlerProcessUtf8Characters()
    {
        $_GET = [
            'message' => 'こんにちは' // Japanese characters
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
    
        $this->assertEquals('こんにちは', $request->getQuery()->message);
    }
}