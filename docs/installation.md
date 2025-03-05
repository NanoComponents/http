# Installation

## Requirements
- PHP 8.0+ (uses readonly properties, modern syntax)
- Composer (recommended)

## Via Composer
1. Install the package:
   ```bash
   composer require nano/http
   ```
2. Include the autoloader:
   ```php
   require 'vendor/autoload.php';
   ```

## Manual Installation
1. Clone or download the repository.
2. Set up your own autoloader or include files manually.

## Verification
```php
use NanoLibs\Http\Request;
$request = Request::initialize();
$serverData = $request->getServer()->getAll();
var_dump($serverData);
```
