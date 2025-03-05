# Usage

## Initialization
Create a `Request` instance with superglobals:
```php
use Nano\Http\Request;
$request = Request::initialize();
```

## Query Parameters
Access `$_GET` data, including nested arrays and special characters:
```php
$_GET = ['user' => ['name' => 'Iman'], 'search' => 'query%20with%20spaces'];
$request = Request::initialize();
echo $request->getQuery()->get('user')['name']; // "Iman"
echo $request->getQuery()->get('search');       // "query with spaces"
```

## Form Data
Handle `$_POST` with arrays and UTF-8:
```php
$_POST = ['tags' => ['php', 'zend'], 'message' => 'こんにちは'];
$request = Request::initialize();
$tags = $request->getForm()->get('tags'); // ['php', 'zend']
echo $request->getForm()->get('message'); // "こんにちは"
```

## File Uploads
### Single File
```php
$_FILES = ['avatar' => ['name' => 'pic.jpg', 'type' => 'image/jpeg', 'tmp_name' => '/tmp/php123', 'error' => 0, 'size' => 2000, 'full_path' => 'pic.jpg']];
$request = Request::initialize();
$file = $request->getFile('avatar')->getAll()[0];
echo $file->getFileName(); // "pic.jpg"
```

### Multiple Files
```php
$_FILES = ['docs' => ['name' => ['doc1.pdf', 'doc2.pdf'], 'type' => ['application/pdf', 'application/pdf'], 'tmp_name' => ['/tmp/php1', '/tmp/php2'], 'error' => [0, 0], 'size' => [3000, 4000], 'full_path' => ['doc1.pdf', 'doc2.pdf']]];
$request = Request::initialize();
$files = $request->getFile('docs')->getAll();
echo count($files); // 2
```

### Nested Files
Access nested files using dot-notation:
```php
$_FILES = ['user' => ['name' => ['documents' => ['resume.pdf']], 'type' => ['documents' => ['application/pdf']], 'tmp_name' => ['documents' => ['/tmp/php123']], 'error' => ['documents' => [0]], 'size' => ['documents' => [5000]], 'full_path' => ['documents' => ['resume.pdf']]]];
$request = Request::initialize();
$file = $request->getFile('user.documents')->getAll()[0];
echo $file->getFieldNameSuffix(); // "user.documents"
echo $file->getFileName();        // "resume.pdf"
```

### Error Handling
```php
$_FILES = ['file' => ['name' => 'large.exe', 'type' => 'application/octet-stream', 'tmp_name' => '/tmp/php123', 'error' => 1, 'size' => 5000000, 'full_path' => 'large.exe']];
$request = Request::initialize();
$errors = $request->getFile('file')->getErrorMessages();
echo $errors[0]; // "The uploaded file exceeds the upload_max_filesize directive in php.ini."
```

## Cookies
```php
$_COOKIE = ['user_id' => '123', 'is_admin' => 'true'];
$request = Request::initialize();
echo $request->getCookie()->get('user_id');   // "123"
$allCookies = $request->getCookie()->getAll(); // ['user_id' => '123', 'is_admin' => 'true']
```

## Session
```php
$_SESSION = ['user_id' => '12345', 'auth' => true];
$request = Request::initialize();
echo $request->getSession()->get('user_id'); // "12345"
```

## Server Data
```php
$_SERVER = ['REQUEST_METHOD' => 'POST', 'HTTPS' => 'on'];
$request = Request::initialize();
echo $request->getServer()->get('REQUEST_METHOD'); // "POST"
```

## Stream Input
Process raw request bodies (e.g., JSON):
```php
$service = $request->getStreamInput();
$service->toArray(); // e.g., ['name' => 'John'] from '{"name": "John"}'
```
