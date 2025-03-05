# API Reference

## `Request`
### `__construct`
```php
public function __construct(QueryParam $queryParam, FormParam $formParam, ServerParam $cookieParam, FileParam $fileParam, SessionParam $sessionParam, StreamInputService $streamInputService)
```
- Injected with parameter objects and stream service.

### `initialize`
```php
public static function initialize(): self
```
- Creates a `Request` with superglobals.
- Returns: `Request`

### `getFile(?string $fileName = null)`
```php
public function getFile(?string $fileName = null): FileHandlerInterface
```
- `$fileName`: Optional dot-notation field name (e.g., `user.documents`).
- Returns: `FileHandlerInterface`

## `FileParamHandler`
### `getAll`
```php
public function getAll(): array
```
- Returns: Array of `UploadedFile` objects.

### `get(string $fileName)`
```php
public function get(string $fileName): ?UploadedFileInterface
```
- `$fileName`: Specific file name to retrieve.
- Returns: `UploadedFileInterface` or `null`.

### `getErrorMessages`
```php
public function getErrorMessages(): array
```
- Returns: Array of error messages for uploaded files.

## `UploadRegistry`
### `__construct`
```php
public function __construct(array $globalArray)
```
- Processes `$_FILES` into a registry of `UploadedForm` objects.

### `getUploadedFile(?string $fieldName = null)`
```php
public function getUploadedFile(?string $fieldName = null): array
```
- `$fieldName`: Optional field name filter.
- Returns: Array of `UploadedFile` objects.

## `GlobalFileArraySerializer`
### `serialize`
```php
public static function serialize(array $globalArray): array
```
- Normalizes `$_FILES` into a dot-notation structure.
- Returns: Structured array.

## `UploadedFile`
### `getFileName`
```php
public function getFileName(): string
```
- Returns: Uploaded file’s name.

### `getFieldNameSuffix`
```php
public function getFieldNameSuffix(): string
```
- Returns: Dot-notation field name (e.g., `user.documents`).

## `StreamInputService`
### `toArray`
```php
public function toArray(): array
```
- Parses raw body as JSON.
- Returns: Array or throws `JsonException`.
