# Testing

## Running Tests
```bash
vendor/bin/phpunit --configuration phpunit.xml
```

## Test Coverage
- **Handlers**: Tests for cookies, files, query, form, server, and session (e.g., `RequestFileHandlerTest`).
- **Edge Cases**: Nested files, special characters, large data, and errors.
- **Stream Input**: JSON parsing and caching (`StreamInputServiceTest`).
