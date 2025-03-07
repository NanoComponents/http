<?php

namespace NanoLibs\Http\tests\ServiceTest;

use NanoLibs\Http\Services\StreamInput\StreamInputService;
use PHPUnit\Framework\TestCase;

class StreamInputServiceTest extends TestCase
{
    private const TEST_FILE = __DIR__ . '/test_input.txt';

    #[\Override]
    protected function setUp(): void
    {
        // Ensure a test file exists
        file_put_contents(self::TEST_FILE, '');
    }

    #[\Override]
    protected function tearDown(): void
    {
        // Cleanup test file
        @unlink(self::TEST_FILE);
    }

    public function testGetRawBody(): void
    {
        file_put_contents(self::TEST_FILE, 'Hello, World!');

        $service = new StreamInputService(self::TEST_FILE);
        $this->assertSame('Hello, World!', $service->getRawBody());
    }

    public function testGetRawBodyCachesValue(): void
    {
        file_put_contents(self::TEST_FILE, 'Initial Value');

        $service = new StreamInputService(self::TEST_FILE);
        $service->getRawBody();

        file_put_contents(self::TEST_FILE, 'New Value'); // Modify file after first read

        // It should return the cached value, not the new file content
        $this->assertSame('Initial Value', $service->getRawBody());
    }

    public function testToArrayReturnsEmptyArrayForEmptyInput(): void
    {
        $service = new StreamInputService(self::TEST_FILE);
        $this->assertSame([], $service->toArray());
    }

    public function testToArrayParsesValidJson(): void
    {
        file_put_contents(self::TEST_FILE, '{"name": "John", "age": 30}');

        $service = new StreamInputService(self::TEST_FILE);
        $expected = ['name' => 'John', 'age' => 30];

        $this->assertSame($expected, $service->toArray());
    }

    public function testToArrayThrowsExceptionForInvalidJson(): void
    {
        file_put_contents(self::TEST_FILE, 'invalid json');

        $this->expectException(\JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $service = new StreamInputService(self::TEST_FILE);
        $service->toArray();
    }

    public function testToJsonEncodesArray(): void
    {
        file_put_contents(self::TEST_FILE, '{"key":"value"}');

        $service = new StreamInputService(self::TEST_FILE);
        $this->assertSame('{"key":"value"}', $service->toJson());
    }

}