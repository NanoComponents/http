<?php

namespace NanoLibs\Http\Tests\HandlerTest;

use NanoLibs\Http\RequestFactory;
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
        $request = RequestFactory::create();

        $this->assertEquals($postDefault['name'], $request->getForm()->get('name'));
        $this->assertEquals($postDefault['age'], $request->getForm()->get('age'));
        $this->assertEquals($postDefault['isVerified'], $request->getForm()->get('isVerified'));
    }

    // Test when POST data is empty
    public function testPostHandlerProcessEmptyPostParameters()
    {
        $_POST = [];
        $request = RequestFactory::create();

        $this->assertEmpty($request->getForm()->getAll());
    }

    // Test POST with special characters like spaces and symbols
    public function testPostHandlerProcessSpecialCharacters()
    {
        $_POST = [
            'search' => 'query+with+spaces',
            'filter' => 'category=books&price<50'
        ];
        
        $request = RequestFactory::create();

        $this->assertEquals('query with spaces', $request->getForm()->get('search')); // + should be decoded to space
        $this->assertEquals('category=books&price<50', $request->getForm()->get('filter')); // %26 should decode to &
    }

    // Test POST with array parameters
    public function testPostHandlerProcessArrayParameters()
    {
        $_POST = [
            'tags' => ['php', 'zend', 'phpunit']
        ];

        $request = RequestFactory::create();

        $this->assertEquals(['php', 'zend', 'phpunit'], $request->getForm()->get('tags'));
    }

    // Test POST with numeric keys (usually bad practice but should still be handled)
    public function testPostHandlerProcessNumericKeys()
    {
        $_POST = [
            '0' => 'first',
            '1' => 'second'
        ];
        $request = RequestFactory::create();
    
        $this->assertEquals('first', $request->getForm()->get(0));
        $this->assertEquals('second', $request->getForm()->get(1));
    }

    // Test POST with boolean-like values (e.g., "true" or "false" as string)
    public function testPostHandlerProcessBooleanValues()
    {
        $_POST = [
            'isActive' => 'true',
            'isAdmin'  => 'false'
        ];
        $request = RequestFactory::create();

        $this->assertEquals('true', $request->getForm()->get('isActive'));
        $this->assertEquals('false', $request->getForm()->get('isAdmin'));
    }

    // Test POST with missing keys
    public function testPostHandlerHandlesMissingKeys()
    {
        $_POST = [
            'name' => 'iman'
        ];
        $request = RequestFactory::create();

        $this->assertNull($request->getForm()->get('age')); // Missing key
    }

    // Test POST with large data
    public function testPostHandlerHandlesLargeData()
    {
        $_POST = [
            'largeData' => str_repeat('a', 10000) // 10,000 characters
        ];
        $request = RequestFactory::create();

        $this->assertEquals(str_repeat('a', 10000), $request->getForm()->get('largeData'));
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
        $request = RequestFactory::create();

        $this->assertEquals('iman', $request->getForm()->get('user')['name']);
        $this->assertEquals('22', $request->getForm()->get('user')['age']);
    }

    // Test POST with URL-encoded values (e.g., spaces encoded as %20)
    public function testPostHandlerDecodesUrlEncodedValues()
    {
        $_POST = [
            'search' => 'query%20with%20spaces'
        ];
        $request = RequestFactory::create();
    
        $this->assertEquals('query with spaces', $request->getForm()->get('search'));
    }

    // Test POST with invalid data types (e.g., objects, nulls)
    public function testPostHandlerHandlesInvalidDataTypes()
    {
        $_POST = [
            'invalid' => new \stdClass() // Invalid data type
        ];
        $request = RequestFactory::create();
    
        $this->assertNull($request->getForm()->get('invalid')); // Assuming invalid types return null
    }

    // Test POST with UTF-8 characters
    public function testPostHandlerProcessUtf8Characters()
    {
        $_POST = [
            'message' => 'こんにちは' // Japanese characters
        ];
        $request = RequestFactory::create();
    
        $this->assertEquals('こんにちは', $request->getForm()->get('message'));
    }

}