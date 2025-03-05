<?php

namespace Nano\Http\Tests\HandlerTest;

use Nano\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestFileHandlerTest extends TestCase
{
    public function testValidFileUpload(): void
    {
        $_FILES = [
            'file' => [
                'name' => 'valid-image.jpg',
                'tmp_name' => '/tmp/php12345',
                'type' => 'image/jpeg',
                'full_path' => 'Screenshot from 2025-02-19 15-18-30.png',
                'error' => 0,
                'size' => 2000
            ]
        ];

        $request = Request::initialize();

        $this->assertTrue($request->getFile()->isFieldNameExists('file'));
        $this->assertEquals('valid-image.jpg', $request->getFile()->getName()[0]);
        $this->assertEquals('image/jpeg', $request->getFile()->getType()[0]);
    }

    public function testFileTooLarge(): void
    {
        $_FILES = [
            'file' => [
                'name' => 'large-image.jpg',
                'full_path' => 'Screenshot from 2025-02-19 15-18-30.png',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 5300000 // Exceeds the limit
            ]
        ];

        $request = Request::initialize();
        $this->assertFalse($request->getFile()->isFileExists('file'));
        $this->assertEquals('The uploaded file was above max allowed size', $request->getFile()->getErrorMessages('file')[0]);
    }

    public function testInvalidFileType(): void
    {
        $_FILES = [
            'file' => [
                'full_path' => 'Screenshot from 2025-02-19 15-18-30.png',
                'name' => 'invalid-file.exe',
                'type' => 'application/octet-stream',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 2000
            ]
        ];

        $request = Request::initialize();

        $this->assertFalse($request->getFile()->isFieldNameExists('notfile'));
        $this->assertEquals('The uploaded file type is invalid', $request->getFile()->getErrorMessages()[0]);
    }

    public function testMissingFile(): void
    {
        $_FILES = [];

        $request = Request::initialize();

        $this->assertFalse($request->getFile()->isFileExists('file'));
        $this->assertNull($request->getFile()->get('file'));
    }

    public function testMalformedFileUpload(): void
    {
        $_FILES = [
            'file' => [
                'name' => 'corrupt-file',
                'full_path' => 'Screenshot from 2025-02-19 15-18-30.png',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/php12345',
                'error' => 1, // PHP error during upload
                'size' => 2000
            ]
        ];

        $request = Request::initialize();

        $this->assertFalse($request->getFile()->isFileExists('files'));
        $this->assertEquals('The uploaded file exceeds the upload_max_filesize directive in php.ini.', $request->getFile()->getErrorMessages('file')[0]);
    }

    public function testMultipleFileUpload()
    {
        $_FILES = [
            'files' => [
                'name' => ['image1.jpg', 'image2.jpg'],
                'type' => ['image/jpeg', 'image/jpeg'],
                'tmp_name' => ['/tmp/php12345', '/tmp/php12346'],
                'error' => [0, 0],
                'size' => [2000, 2000],
                'full_path' => ['image1.jpg', 'image2.jpg']
            ]
        ];

        $request = Request::initialize();

        $this->assertCount(2, $request->getFile()->getAll());
        $this->assertEquals('image1.jpg', $request->getFile()->getAll()[0]->getFileName());
        $this->assertEquals('image2.jpg', $request->getFile()->getAll()[1]->getFileName());
    }

    public function testExecutableFileUpload()
    {
        $_FILES = [
            'file' => [
                'name' => 'malicious-file.php',
                'type' => 'application/x-httpd-php',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 2000,
                'full_path' => 'image1.jpg'
            ]
        ];

        $request = Request::initialize();

        $this->assertFalse($request->getFile()->isFileExists('file'));
        $this->assertEquals('The uploaded file type is invalid', $request->getFile()->getErrorMessages('file')[0]);
    }

    public function testFileUploadWithSpecialCharsInFilename()
    {
        $_FILES = [
            'file' => [
                'name' => 'file with spaces and üñîçødë.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 2000,
                'full_path' => 'image1.jpg'
            ]
        ];

        $request = Request::initialize();

        $this->assertTrue($request->getFile()->isFieldNameExists('file'));
        $this->assertEquals('file with spaces and üñîçødë.jpg', $request->getFile()->getName()[0]);
    }

    public function testLargeFileUpload()
    {
        $_FILES = [
            'file' => [
                'name' => 'large-file.mp4',
                'type' => 'video/mp4',
                'tmp_name' => '/tmp/php12345',
                'error' => 0,
                'size' => 104857600, // 100MB
                'full_path' => 'image1.jpg'
            ]
        ];

        $request = Request::initialize();

        $this->assertTrue($request->getFile()->isFieldNameExists('file'));
        $this->assertEquals('large-file.mp4', $request->getFile()->getName()[0]);
    }

    public function testMultipleFilesWithError()
    {
        $_FILES = [
            'files' => [
                'name' => ['file1.jpg', 'file2.jpg'],
                'type' => ['image/jpeg', 'image/jpeg'],
                'tmp_name' => ['/tmp/php12345', '/tmp/php12346'],
                'error' => [0, 1], // file2 has an error (size too large)
                'size' => [2000, 5000000], // file2 is too large
                'full_path' => ['file1.jpg', 'file2.jpg'],
            ]
        ];

        $request = Request::initialize();

        $this->assertTrue($request->getFile()->isFileExists('file2.jpg'));
        $this->assertFalse($request->getFile()->isFileExists('file3.jpg'));
        $this->assertEquals('The uploaded file exceeds the upload_max_filesize directive in php.ini.', $request->getFile()->getErrorMessages()[1]);
    }

    public function testNestedFieldNames()
    {
        $_FILES = [
            'user' => [
                'name' => [
                    'documents' => ['file1.txt', 'file2.txt']
                ],
                'full_path' => [
                    'documents' => ['file1.txt', 'file2.txt']
                ],
                'type' => [
                    'documents' => ['text/plain', 'text/plain']
                ],
                'tmp_name' => [
                    'documents' => ['/tmp/php123', '/tmp/php456']
                ],
                'error' => [
                    'documents' => [0, 0]
                ],
                'size' => [
                    'documents' => [100, 200]
                ]
            ]
        ];

        $request = Request::initialize();
        $fileHandler = $request->getFile();

        $this->assertTrue($fileHandler->isFieldNameExists('user.documents'));
        $this->assertCount(2, $fileHandler->getAll());
        $this->assertEquals('user.documents', $fileHandler->getAll()[0]->getFieldNameSuffix());
        $this->assertEquals('user.documents', $fileHandler->getAll()[1]->getFieldNameSuffix());
    }

    public function testMultipleFormFields()
    {
        $_FILES = [
            'avatar' => [
                'name' => 'avatar.jpg',
                'full_path' => 'avatar.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/php123',
                'error' => 0,
                'size' => 2000
            ],
            'documents' => [
                'name' => ['doc1.pdf', 'doc2.pdf'],
                'full_path' => ['doc1.pdf', 'doc2.pdf'],
                'type' => ['application/pdf', 'application/pdf'],
                'tmp_name' => ['/tmp/php456', '/tmp/php789'],
                'error' => [0, 0],
                'size' => [3000, 4000]
            ]
        ];

        $request = Request::initialize();
        $fileHandler = $request->getFile();

        $this->assertTrue($fileHandler->isFieldNameExists('avatar'));
        $this->assertTrue($fileHandler->isFieldNameExists('documents'));
        $this->assertCount(3, $fileHandler->getAll());
    }

    public function testDeeplyNestedFieldNames()
    {
        $_FILES = [
            'form' => [
                'name' => [
                    'user' => [
                        'profile' => ['avatar.jpg']
                    ]
                ],
                'full_path' => [
                    'user' => [
                        'profile' => ['avatar.jpg']
                    ]
                ],
                'type' => [
                    'user' => [
                        'profile' => ['image/jpeg']
                    ]
                ],
                'tmp_name' => [
                    'user' => [
                        'profile' => ['/tmp/php123']
                    ]
                ],
                'error' => [
                    'user' => [
                        'profile' => [0]
                    ]
                ],
                'size' => [
                    'user' => [
                        'profile' => [2000]
                    ]
                ]
            ]
        ];

        $request = Request::initialize();
        $fileHandler = $request->getFile();

        $uploadedFiles = $fileHandler->getAll();
        $this->assertEquals('form.user.profile', $uploadedFiles[0]->getFieldNameSuffix());
    }
}
