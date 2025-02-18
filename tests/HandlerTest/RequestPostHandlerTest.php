<?php

namespace Nano\Http\Tests\HandlerTest;

use Nano\Http\Param\ParameterFactory;
use Nano\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestPostHandlerTest extends TestCase
{
    public function testPostHandlerProcessBasicPostParameters()
    {
        $postDefault = [
            'name'          => 'iman',
            'age'           => '22',
            'isVerified'    => true
        ];
        $_POST = $postDefault;
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals($postDefault['name'], $request->getForm()->name);
        $this->assertEquals($postDefault['age'], $request->getForm()->age);
        $this->assertEquals($postDefault['isVerified'], $request->getForm()->isVerified);
    }

    // Test when POST data is empty
    public function testPostHandlerProcessEmptyPostParameters()
    {
        $_POST = [];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEmpty($request->getForm()->getAll());
    }

    // Test POST with special characters like spaces and symbols
    public function testPostHandlerProcessSpecialCharacters()
    {
        $_POST = [
            'search' => 'query+with+spaces',
            'filter' => 'category=books&price<50'
        ];
        
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals('query with spaces', $request->getForm()->search); // + should be decoded to space
        $this->assertEquals('category=books&price<50', $request->getForm()->filter); // %26 should decode to &
    }

    // Test POST with array parameters
    public function testPostHandlerProcessArrayParameters()
    {
        $_POST = [
            'tags' => ['php', 'zend', 'phpunit']
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals(['php', 'zend', 'phpunit'], $request->getForm()->tags);
    }

    // Test POST with numeric keys (usually bad practice but should still be handled)
    public function testPostHandlerProcessNumericKeys()
    {
        $_POST = [
            '0' => 'first',
            '1' => 'second'
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
    
        $this->assertEquals('first', $request->getForm()->{0});
        $this->assertEquals('second', $request->getForm()->{1});
    }

    // Test POST with boolean-like values (e.g., "true" or "false" as string)
    public function testPostHandlerProcessBooleanValues()
    {
        $_POST = [
            'isActive' => 'true',
            'isAdmin'  => 'false'
        ];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals('true', $request->getForm()->isActive);
        $this->assertEquals('false', $request->getForm()->isAdmin);
    }

    // Test POST with missing keys
    public function testPostHandlerHandlesMissingKeys()
    {
        $_POST = [
            'name' => 'iman'
        ];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertNull($request->getForm()->age); // Missing key
    }

    // Test POST with large data
    public function testPostHandlerHandlesLargeData()
    {
        $_POST = [
            'largeData' => str_repeat('a', 10000) // 10,000 characters
        ];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals(str_repeat('a', 10000), $request->getForm()->largeData);
    }

    // Test POST with nested arrays (multi-dimensional arrays)
    public function testPostHandlerProcessNestedArrays()
    {
        $_POST = [
            'user' => [
                'name' => 'iman',
                'age'  => '22'
            ]
        ];
        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertEquals('iman', $request->getForm()->user['name']);
        $this->assertEquals('22', $request->getForm()->user['age']);
    }

    // Test POST with URL-encoded values (e.g., spaces encoded as %20)
    public function testPostHandlerDecodesUrlEncodedValues()
    {
        $_POST = [
            'search' => 'query%20with%20spaces'
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
    
        $this->assertEquals('query with spaces', $request->getForm()->search);
    }

    // Test POST with invalid data types (e.g., objects, nulls)
    public function testPostHandlerHandlesInvalidDataTypes()
    {
        $_POST = [
            'invalid' => new \stdClass() // Invalid data type
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
    
        $this->assertNull($request->getForm()->invalid); // Assuming invalid types return null
    }

    // Test POST with UTF-8 characters
    public function testPostHandlerProcessUtf8Characters()
    {
        $_POST = [
            'message' => 'こんにちは' // Japanese characters
        ];
        $request = Request::initializeGlobals(new ParameterFactory);
    
        $this->assertEquals('こんにちは', $request->getForm()->message);
    }

}