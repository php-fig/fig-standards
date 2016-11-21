HTTP Middleware
===============

This document describes a common standard for HTTP middleware components using
HTTP Messages defined by [PSR-7](http://www.php-fig.org/psr/psr-7/).

HTTP middleware has been an important concept on other web development platforms
for a good number of years, and since the introduction of a formal HTTP Messages
standard has been growing increasingly popular with web frameworks.

The interfaces described in this document are abstractions for HTTP middleware
and the containers that are used to process HTTP requests.

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

Middleware consumers such as frameworks and middleware stack containers MUST
use a type declaration against one of the middleware interfaces:

- `Psr\Http\Middleware\MiddlewareInterface` if both client and server middleware are supported
- `Psr\Http\Middleware\ClientMiddlewareInterface` if only client middleware is supported
- `Psr\Http\Middleware\ServerMiddlewareInterface` if only server middleware is supported

Generally consumers SHOULD declare the type as `MiddlewareInterface` unless the
consumer only processes client requests.

### 1.1 Containers

An HTTP middleware container is an object that holds multiple middleware
components that can be used to process one or more requests in sequence.

The middleware container MUST pass the request and a request handler to each
middleware component that it executes. The request handler MUST be able to execute
the next available middleware or if no more middleware is available, create a
default response.

### 1.2 Generating Responses

It is RECOMMENDED that any middleware that needs to generate a response will
use HTTP Factories as defined in [PSR-17](http://www.php-fig.org/psr/psr-17/),
in order to prevent dependence on a specific HTTP message implementation.

## 2. Interfaces

### 2.1 Psr\Http\Middleware\MiddlewareInterface

The following interface is only used for type declarations that accept middleware
components and MUST NOT be implemented directly.

```php
namespace Psr\Http\Middleware;

interface MiddlewareInterface {}
```

### 2.3 Psr\Http\Middleware\ClientMiddlewareInterface

The following interface MUST be implemented by compatible client middleware components.

```php
namespace Psr\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientMiddlewareInterface extends MiddlewareInterface
{
    /**
     * Process an incoming request and return a response, optionally delegating
     * to the next request handler.
     *
     * @param RequestInterface        $request
     * @param RequestHandlerInterface $next
     *
     * @return ResponseInterface
     */
    public function process(
        RequestInterface $request,
        RequestHandlerInterface $next
    );
}
```

### 2.4 Psr\Http\Middleware\ServerMiddlewareInterface

The following interface MUST be implemented by compatible server middleware components.

```php
namespace Psr\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ServerMiddlewareInterface extends MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next request handler.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $next
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $next
    );
}
```

Note that the only difference between server and client middleware is that server
middleware must be passed a server request for processing.

### 2.5 Psr\Http\Middleware\RequestHandlerInterface

The following interface MUST be implemented by request handlers and MAY be
implemented by middleware containers.

```php
namespace Psr\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RequestHandlerInterface
{
    /**
     * Process a request and return the response.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request);
}
```
