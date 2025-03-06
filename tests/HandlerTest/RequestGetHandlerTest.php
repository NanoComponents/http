<?php 

namespace NanoLibs\Http\Tests\HandlerTest;

use NanoLibs\Http\RequestFactory;
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
        $request = RequestFactory::create();

        $this->assertEquals($getDefault['name'], $request->getQuery()->get('name'));
        $this->assertEquals($getDefault['age'], $request->getQuery()->get('age'));
        $this->assertEquals($getDefault['isVerified'], $request->getQuery()->get('isVerified'));
    }

    public function testQueryHandlerProcessEmptyQueryParameters()
    {
        $_GET = [];
        $request = RequestFactory::create();

        $this->assertEmpty($request->getQuery()->getAll());
    }

    public function testQueryHandlerProcessSpecialCharacters()
    {
        $_GET = [
            'search' => 'query+with+spaces',
            'filter' => 'category=books&price<50'
        ];
        
        // Initialize the Request class with the $_GET data
        $request = RequestFactory::create();

        // After the handler processes the query parameters:
        $this->assertEquals('query with spaces', $request->getQuery()->get('search')); // + should be decoded to space
        $this->assertEquals('category=books&price<50', $request->getQuery()->get('filter')); // %26 is decoded to & automatically
    }

    public function testQueryHandlerProcessArrayParameters()
    {
        $_GET = [
            'tags' => ['php', 'zend', 'phpunit']
        ];

        $request = RequestFactory::create();

        $this->assertEquals(['php', 'zend', 'phpunit'], $request->getQuery()->get('tags'));
    }

    public function testQueryHandlerProcessNumericKeys()
    {
        $_GET = [
            '0' => 'first',
            '1' => 'second'
        ];
        $request = RequestFactory::create();
    
        $this->assertEquals('first', $request->getQuery()->get(0));
        $this->assertEquals('second', $request->getQuery()->get(1));
    }
    public function testQueryHandlerProcessBooleanValues()
    {
        $_GET = [
            'isActive' => 'true',
            'isAdmin'  => 'false'
        ];
        $request = RequestFactory::create();

        $this->assertEquals('true', $request->getQuery()->get('isActive'));
        $this->assertEquals('false', $request->getQuery()->get('isAdmin'));
    }

    public function testQueryHandlerHandlesMissingKeys()
    {
        $_GET = [
            'name' => 'iman'
        ];
        $request = RequestFactory::create();

        $this->assertEquals('iman', $request->getQuery()->get('name'));
        $this->assertNull($request->getQuery()->get('age')); 
    }

    public function testQueryHandlerHandlesLargeData()
    {
        $_GET = [
            'largeData' => str_repeat('a', 10000) // 10,000 characters
        ];
        $request = RequestFactory::create();

        $this->assertEquals(str_repeat('a', 10000), $request->getQuery()->get('largeData'));
    }
    public function testQueryHandlerProcessNestedArrays()
    {
        $_GET = [
            'user' => [
                'name' => 'iman',
                'age'  => '22'
            ]
        ];
        $request = RequestFactory::create();

        $this->assertEquals('iman', $request->getQuery()->get('user')['name']);
        $this->assertEquals('22', $request->getQuery()->get('user')['age']);
    }

    public function testQueryHandlerDecodesUrlEncodedValues()
    {
        $_GET = [
            'search' => 'query%20with%20spaces'
        ];
        $request = RequestFactory::create();
    
        $this->assertEquals('query with spaces', $request->getQuery()->get('search'));
    }

    public function testQueryHandlerHandlesInvalidDataTypes()
    {
        $_GET = [
            'invalid' => new \stdClass() // Invalid data type
        ];
        $request = RequestFactory::create();
    
        $this->assertNull($request->getQuery()->get('invalid')); // Assuming invalid types return null
    }
    public function testQueryHandlerProcessUtf8Characters()
    {
        $_GET = [
            'message' => 'こんにちは' // Japanese characters
        ];
        $request = RequestFactory::create();
    
        $this->assertEquals('こんにちは', $request->getQuery()->get('message'));
    }
}