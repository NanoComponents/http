# Architecture

## Overview
`Nano\Http` provides a structured, object-oriented approach to HTTP request handling, abstracting superglobals into manageable components.

## Design Principles
- **Dependency Injection**: `Request` uses constructor injection for parameter objects, enhancing testability.
- **Single Responsibility**: Each handler (e.g., `FileParamHandler`) manages one superglobal type.
- **Normalization**: `GlobalFileArraySerializer` transforms nested `$_FILES` into dot-notation keys.
- **Error Handling**: Comprehensive file upload validation via `FileUploadingRules`.
- **Extensibility**: Interfaces (e.g., `FileHandlerInterface`) enable custom handlers.

## Structure
- **Handlers**: Process specific superglobals (e.g., `FileParamHandler`).
- **Param**: Wrap raw data (e.g., `FileParam`).
- **Services**: Manage uploads (`UploadRegistry`) and streams (`StreamInputService`).
- **Serializers**: Normalize data (`GlobalFileArraySerializer`).
- **Tests**: Validate functionality (e.g., `RequestFileHandlerTest`).
