HTTP Client
===========

This document describes a common interface for sending HTTP requests and receiving HTTP responses.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

## Specification

### Client

An HTTP client has the responsibility to send a PSR-7 request and return a PSR-7
response. Under the hood, the HTTP client MAY modify the request received from
the user and/or the response received from the server. In this case, the request
and the response MUST be consistent between the body and headers. For example, a
server may return a gzip-encoded body and the client may decide to decode the
body. If the client decodes the body, the client MUST also remove the
`Content-Encoding` header and adjust the `Content-Length` header.

### Exceptions

All exceptions thrown by the client MUST implement `Psr\Http\Client\ClientException`.

When the HTTP client is called with a request that is invalid and cannot be sent, the client
MUST throw a `Psr\Http\Client\RequestException`. If there is an error
with the network or the remote server cannot be reached, the HTTP client MUST throw
a `Psr\Http\Client\NetworkException`.

Smaller issues that do not block the client from sending the request (such as
invalid HTTP versions) MUST NOT result in exceptions.

If the remote server answers with a response that can be parsed into a PSR-7 response,
the client MUST NOT throw an exception. For example, response status codes in the
400 and 500 range MUST NOT cause an exception.

## Goal

The goal of this PSR is to allow developers to create libraries decoupled from HTTP client
implementations. This will make libraries more reusable as it reduces the number of
dependencies and lowers the likelihood of version conflicts.

A second goal is that HTTP clients can be replaced as per the
[Liskov substitutions principle][Liskov]. This means that all clients MUST behave in the
same way when sending a request.

## Interfaces

### ClientInterface

```php
namespace Psr\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * Every technically correct HTTP response MUST be returned as-is, even if it represents an HTTP
     * error response or a redirect instruction. The only special case is 1xx responses, which MUST
     * be assembled in the HTTP client.
     *
     * The client MAY do modifications to the Request before sending it. Because PSR-7 objects are
     * immutable, one cannot assume that the object passed to ClientInterface::sendRequest() will be the same
     * object that is actually sent. For example, the Request object that is returned by an exception MAY
     * be a different object than the one passed to sendRequest, so comparison by reference (===) is not possible.
     *
     * {@link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message-meta.md#why-value-objects}
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Psr\Http\Client\ClientException If an error happens while processing the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface;
}
```

### Exception

```php
namespace Psr\Http\Client;

/**
 * Every HTTP client related Exception MUST implement this interface.
 */
interface ClientException extends \Throwable
{
}
```

### RequestException

```php
namespace Psr\Http\Client;

use Psr\Http\Message\RequestInterface;

/**
 * Exception for when a request failed.
 *
 * Examples:
 *      - Request is invalid (e.g. method is missing)
 *      - Runtime request errors (e.g. the body stream is not seekable)
 */
interface RequestException extends ClientException
{
    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to ClientInterface::sendRequest()
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;
}
```

### NetworkException

```php
namespace Psr\Http\Client;

use Psr\Http\Message\RequestInterface;

/**
 * Thrown when the request cannot be completed because of network issues.
 *
 * There is no response object as this exception is thrown when no response has been received.
 *
 * Example: the target host name can not be resolved or the connection failed.
 */
interface NetworkException extends ClientException
{
    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to ClientInterface::sendRequest()
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;
}
```

[Liskov]: https://en.wikipedia.org/wiki/Liskov_substitution_principle
