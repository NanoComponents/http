# Nano HTTP Request Component

A robust, modular PHP library for handling HTTP requests, providing seamless access to superglobals (`$_GET`, `$_POST`, `$_FILES`, etc.) with advanced features like nested file uploads and stream input processing.

## Key Features
- **Unified Access**: Retrieve query, form, file, cookie, session, and server data through a consistent interface.
- **Nested File Handling**: Supports nested `$_FILES` arrays with dot-notation access (e.g., `user.documents`).
- **Error Management**: Detailed error detection and messaging for file uploads.
- **Stream Input**: Process raw request bodies (e.g., JSON) efficiently.
- **Tested**: Comprehensive test suite ensures reliability.

## Quickstart

```php
use NanoLibs\Http\Request;

$request = Request::initialize();
$file = $request->getFile('user.documents')->getAll()[0]; // Access nested file
echo $file->getFileName();
```

## Documentation
- [Installation](installation.md)
- [Usage](usage.md)
- [API Reference](api-reference.md)
- [Architecture](architecture.md)
- [Contributing](contributing.md)
- [Testing](testing.md)
- [Changelog](changelog.md)

## License
MIT (assumed; update as needed)
