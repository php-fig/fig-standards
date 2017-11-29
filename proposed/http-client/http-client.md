HTTP Client
===========

This document describes common interfaces for sending HTTP messages.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

## Specification

### Client

An HTTP client has the responsibility to send a PSR-7 request and return a PSR-7
response. Under the hood the HTTP client MAY modify the request/response received 
from the user/server. In this case the request and the response MUST be consistent
between the body and headers. For example a 
server may return a gzip encoded body and the client may know how to decode this, 
when it decodes the body the client MUST also remove the header that specifies the 
encoding and adjust the Content-Length header. 

### Exceptions

An implementing library MUST implement `Psr\Http\Client\ClientException` for each exception it throws. 

When the HTTP client is passed a request that is invalid and cannot be sent, the client 
MUST throw a `Psr\Http\Client\Exception\RequestException`. If there is an error
with the network or the remote server cannot be reached, the HTTP client MUST throw
a `Psr\Http\Client\Exception\NetworkException`. 

Smaller issues, like wrong HTTP version, is not blocking the HTTP client to send the
request and MUST not cause any exception. 

If the remote server answers with a response that can be parsed into a PSR-7 response,
the client MUST NOT throw an exception. For example, response status codes in the 
400 and 500 range MUST NOT cause an exception.

## Goal

The goal of this PSR is to allow developers to create libraries decoupled from HTTP client
implementations. This would make libraries more stable since the reduced number of
dependencies and the likelihood to get in version conflicts is reduced.

The second goal is that all HTTP clients should follow the [Liskov substitutions principle][Liskov].
This means that all clients should act the same when sending a request. By default a HTTP client
should not follow redirect nor throw exceptions on HTTP responses with status 4xx or 5xx.


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
     * Every technically correct HTTP response MUST be returned as is, even if it represents a HTTP 
     * error response or a redirect instruction. The only exception is 1xx responses which MUST be 
     * assembled in the HTTP client. 
     *
     * The client MAY do modifications to the Request before sending it. Because PSR-7 objects are
     * immutable, one cannot assume that the object passed to ClientInterface::sendRequest() will be the same
     * object that is actually sent. For example the Request object that is returned by an exception MAY
     * be a different object than the one passed to sendRequest, so comparison by reference (===) is not possible.
     * 
     * {@link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message-meta.md#why-value-objects}
     * 
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Psr\Http\Client\Exception If an error happens during processing the request.
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
namespace Psr\Http\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Client\ClientException;

/**
 * Exception for when a request failed.
 *
 * Examples:
 *      - Request is invalid (eg. method is missing)
 *      - Runtime request errors (like the body stream is not seekable)
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
namespace Psr\Http\Client\Exception;

use Psr\Http\Client\ClientException;

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
