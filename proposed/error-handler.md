# Error handler

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119].

## 1. Overview

This document defines the behavior of an error handler that converts PHP errors
to exceptions. This strategy allows for simple error handling logic, and
improved interoperability through consistency of error behavior.

## 2. Specification

- The error handler MUST throw an exception of type [ErrorException] when an
  error occurs unless stated otherwise by this document.
- The error handler MUST NOT halt execution.
- The error handler MUST NOT throw an exception if the error's severity is
  `E_DEPRECATED` or `E_USER_DEPRECATED`.
- The error handler MUST NOT throw an exception if the error control operator
  (`@`) is in use.
- The error handler SHOULD NOT log errors, or perform other
  performance-intensive operations if the error control operator (`@`) is in
  use.
- The exception methods `getSeverity()`, `getMessage()`, `getFile()`, and
  `getLine()` MUST return identical values to those passed to the error handler.
- Exceptions thrown MAY be a subclass of [ErrorException].
- The installed error handler MAY perform other operations before its execution
  completes, such as logging the error details.

## 3. Example implementation

```php
set_error_handler(
    function ($severity, $message, $path, $lineNumber) {
        if (E_DEPRECATED === $severity || E_USER_DEPRECATED === $severity) {
            return false;
        }
        if (0 === error_reporting()) {
            return true;
        }

        throw new ErrorException($message, 0, $severity, $path, $lineNumber);
    }
);
```

<!-- References -->

[ErrorException]: http://php.net/manual/en/class.errorexception.php
[RFC 2119]: http://tools.ietf.org/html/rfc2119
