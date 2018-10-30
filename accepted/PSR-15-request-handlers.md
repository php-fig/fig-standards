HTTP Server Request Handlers
============================

This document describes common interfaces for HTTP server request handlers
("request handlers") and HTTP server middleware components ("middleware")
that use HTTP messages as described by [PSR-7][psr7] or subsequent
replacement PSRs.

HTTP request handlers are a fundamental part of any web application. Server-side
code receives a request message, processes it, and produces a response message.
HTTP middleware is a way to move common request and response processing away from
the application layer.

The interfaces described in this document are abstractions for request handlers
and middleware.

_Note: All references to "request handlers" and "middleware" are specific to
**server request** processing._

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][rfc2119].

[psr7]: http://www.php-fig.org/psr/psr-7/
[rfc2119]: http://tools.ietf.org/html/rfc2119

### References

- [PSR-7][psr7]
- [RFC 2119][rfc2119]

## 1. Specification

### 1.1 Request Handlers

A request handler is an individual component that processes a request and
produces a response, as defined by PSR-7.

A request handler MAY throw an exception if request conditions prevent it from
producing a response. The type of exception is not defined.

Request handlers using this standard MUST implement the following interface:

- `Psr\Http\Server\RequestHandlerInterface`

### 1.2 Middleware

A middleware component is an individual component participating, often together
with other middleware components, in the processing of an incoming request and
the creation of a resulting response, as defined by PSR-7.

A middleware component MAY create and return a response without delegating to
a request handler, if sufficient conditions are met.

Middleware using this standard MUST implement the following interface:

- `Psr\Http\Server\MiddlewareInterface`

### 1.3 Generating Responses

It is RECOMMENDED that any middleware or request handler that generates a response
will either compose a prototype of a PSR-7 `ResponseInterface` or a factory capable
of generating a `ResponseInterface` instance in order to prevent dependence on a
specific HTTP message implementation.

### 1.4 Handling Exceptions

It is RECOMMENDED that any application using middleware includes a component
that catches exceptions and converts them into responses. This middleware SHOULD
be the first component executed and wrap all further processing to ensure that
a response is always generated.

## 2. Interfaces

### 2.1 Psr\Http\Server\RequestHandlerInterface

The following interface MUST be implemented by request handlers.

```php
namespace Psr\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Handles a server request and produces a response.
 *
 * An HTTP request handler process an HTTP request in order to produce an
 * HTTP response.
 */
interface RequestHandlerInterface
{
    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
```

### 2.2 Psr\Http\Server\MiddlewareInterface

The following interface MUST be implemented by compatible middleware components.

```php
namespace Psr\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Participant in processing a server request and response.
 *
 * An HTTP middleware component participates in processing an HTTP message:
 * by acting on the request, generating the response, or forwarding the
 * request to a subsequent middleware and possibly acting on its response.
 */
interface MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
```
