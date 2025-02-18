<?php 

namespace Nano\Http\Tests\HandlerTest;

use Nano\Http\Param\ParameterFactory;
use Nano\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestFileHandlerTest extends TestCase
{
    public function testValidFileUpload()
    {
        $_FILES = [
            'file' => [
                'name' => 'valid-image.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 2000
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertTrue($request->getFile()->isFileExists('file'));
 //       $this->assertEquals('valid-image.jpg', $request->getFile()->file->getName());
  //      $this->assertEquals('image/jpeg', $request->getFile()->file->getType());
    }

    public function testFileTooLarge()
    {
        $_FILES = [
            'file' => [
                'name' => 'large-image.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 5000000 // Exceeds the limit
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertFalse($request->getFile()->has('file'));
        $this->assertEquals('File size exceeds limit.', $request->getFile()->getError('file'));
    }

    public function testInvalidFileType()
    {
        $_FILES = [
            'file' => [
                'name' => 'invalid-file.exe',
                'type' => 'application/octet-stream',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 2000
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertFalse($request->getFile()->has('file'));
        $this->assertEquals('Invalid file type.', $request->getFile()->getError('file'));
    }

    public function testMissingFile()
    {
        $_FILES = [];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertFalse($request->getFile()->has('file'));
        $this->assertNull($request->getFile()->get('file'));
    }

    public function testMalformedFileUpload()
    {
        $_FILES = [
            'file' => [
                'name' => 'corrupt-file',
                'type' => '',
                'tmp_name' => '/tmp/php12345',
                'error' => 1, // PHP error during upload
                'size' => 2000
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertFalse($request->getFile()->has('file'));
        $this->assertEquals('File upload error.', $request->getFile()->getError('file'));
    }

    public function testMultipleFileUpload()
    {
        $_FILES = [
            'files' => [
                'name' => ['image1.jpg', 'image2.jpg'],
                'type' => ['image/jpeg', 'image/jpeg'],
                'tmp_name' => ['/tmp/php12345', '/tmp/php12346'],
                'error' => [0, 0],
                'size' => [2000, 2000]
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertCount(2, $request->getFile()->files);
        $this->assertEquals('image1.jpg', $request->getFile()->files[0]->getName());
        $this->assertEquals('image2.jpg', $request->getFile()->files[1]->getName());
    }

    public function testFileUploadPath()
    {
        $_FILES = [
            'file' => [
                'name' => 'valid-image.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 2000
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $uploadedFilePath = $request->getFile()->file->getRealPath();
        $this->assertFileExists($uploadedFilePath);
        $this->assertStringContainsString('/tmp/', $uploadedFilePath);
    }

    public function testExecutableFileUpload()
    {
        $_FILES = [
            'file' => [
                'name' => 'malicious-file.php',
                'type' => 'application/x-httpd-php',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 2000
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertFalse($request->getFile()->has('file'));
        $this->assertEquals('Invalid file type.', $request->getFile()->getError('file'));
    }

    public function testFileUploadWithSpecialCharsInFilename()
    {
        $_FILES = [
            'file' => [
                'name' => 'file with spaces and üñîçødë.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 2000
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertTrue($request->getFile()->has('file'));
        $this->assertEquals('file with spaces and üñîçødë.jpg', $request->getFile()->file->getName());
    }

    public function testLargeFileUpload()
    {
        $_FILES = [
            'file' => [
                'name' => 'large-file.mp4',
                'type' => 'video/mp4',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 104857600 // 100MB
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertTrue($request->getFile()->has('file'));
        $this->assertEquals('large-file.mp4', $request->getFile()->file->getName());
    }

    public function testMultipleFilesWithError()
    {
        $_FILES = [
            'files' => [
                'name' => ['file1.jpg', 'file2.jpg'],
                'type' => ['image/jpeg', 'image/jpeg'],
                'tmp_name' => ['/tmp/php12345', '/tmp/php12346'],
                'error' => [0, 1], // file2 has an error (size too large)
                'size' => [2000, 5000000] // file2 is too large
            ]
        ];

        $request = Request::initializeGlobals(new ParameterFactory);

        $this->assertTrue($request->getFile()->has('files[0]'));
        $this->assertFalse($request->getFile()->has('files[1]'));
        $this->assertEquals('File upload error.', $request->getFile()->getError('files[1]'));
    }

}