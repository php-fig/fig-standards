HTTP Server Middleware
======================

This document describes a common standard for HTTP server middleware components
using HTTP Messages defined by [PSR-7](http://www.php-fig.org/psr/psr-7/).

HTTP middleware has been an important concept on other web development platforms
for a good number of years, and since the introduction of a formal HTTP Messages
standard has been growing increasingly popular with web frameworks.

The interfaces described in this document are abstractions for HTTP middleware
and the containers that are used to process HTTP requests.

_**Note:** Any references to "middleware" in this document are specific to
**server middleware**._

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

### References

- [RFC 2119](http://tools.ietf.org/html/rfc2119)
- [PSR-7](http://www.php-fig.org/psr/psr-7/)

## 1. Specification

An HTTP middleware component is an individual component participating, together
with other middleware components, in the processing of an incoming HTTP Request
and the creation of a resulting HTTP Response, as defined by PSR-7.

Middleware using this standard MUST implement the following interface:

- `Psr\Http\ServerMiddleware\MiddlewareInterface`

Middleware dispatching systems using this standard MUST implement the following
interface:

- `Psr\Http\ServerMiddleware\DelegateInterface`

Legacy middleware implementing a double pass approach MUST be wrapped using an
object that implements the `MiddlewareInterface`.

### 1.1 Dispatchers

An HTTP middleware dispatcher is an object that holds multiple middleware
components that can be used to process one or more requests in sequence.

The middleware dispatcher MUST pass the request and a delegate to each
middleware component that it executes. The delegate MUST be able to execute
the next available middleware or if no more middleware is available, create a
default response.

### 1.2 Generating Responses

It is RECOMMENDED that any middleware that needs to generate a response will
use HTTP Factories as defined in [PSR-17](http://www.php-fig.org/psr/psr-17/),
in order to prevent dependence on a specific HTTP message implementation.

## 2. Interfaces

### 2.1 Psr\Http\ServerMiddleware\MiddlewareInterface

The following interface MUST be implemented by compatible server middleware components.

```php
namespace Psr\Http\ServerMiddleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    );
}
```

### 2.2 Psr\Http\ServerMiddleware\DelegateInterface

The following interface MUST be implemented by middleware delegates.

```php
namespace Psr\Http\ServerMiddleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface DelegateInterface
{
    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request);
}
```

If there is no available middleware to dispatch, the delegate MUST return a
default response.
