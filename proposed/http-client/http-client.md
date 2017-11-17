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

When the client get a request that is invalid and cannot be sent, the HTTP client 
MUST throw a `Psr\Http\Client\Exception\RequestException`. If there is an error
with the network or the remote server cannot be reached, the HTTP client MUST throw
a `Psr\Http\Client\Exception\NetworkException`. 

Smaller issues like wrong HTTP version is not blocking the HTTP client to send the
request and MUST not cause any exception. 

No exception should be thrown if the remote server answers with an response that can
be parsed onto a PSR-7 Response. For example a 400 or 500 response should not cause 
an exception.

## Goal

The goal of this PSR is to allow developers to create libraries decoupled from HTTP client
implementations. This would make libraries more stable since the reduced number of
dependencies and the likelihood to get in version conflicts is reduced.

The second goal is that all HTTP clients should follow the [Liskov substitutions principle][Liskov].
This means that all clients should act the same when sending a request. By default a HTTP client
should not follow redirect nor throw exceptions on HTTP responses with status 4xx or 5xx.


## Interfaces

The following interfaces MAY be implemented together within a single class or
in separate classes.

### ClientInterface

```php
namespace Psr\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * Sends a PSR-7 request.
     *
     * If a request is sent without any prior configuration, an exception MUST NOT be thrown
     * when a response is recieved, no matter the HTTP status code.
     *
     * If a request is sent without any prior configuration, a HTTP client MUST NOT follow redirects.
     *
     * The client MAY do modifications to the Request before sending it. Because PSR-7 objects are
     * immutable, one cannot assume that the object passed to Client::sendRequest will be the same
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
    public function sendRequest(RequestInterface $request);
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
     * The request object MAY be a different object from the one passed to Client::sendRequest()
     *
     * @return RequestInterface
     */
    public function getRequest();
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
     * The request object MAY be a different object from the one passed to Client::sendRequest()
     *
     * @return RequestInterface
     */
    public function getRequest();
}
```


### HttpException

```php
namespace Psr\Http\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Client\ClientException;

/**
 * Thrown when a response was received but the request itself failed.
 *
 * This exception MAY be thrown on HTTP response codes 4xx and 5xx.
 * This exception MUST NOT be thrown when using the client's default configuration.
 */
interface HttpException extends ClientException
{
    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to Client::sendRequest()
     *
     * @return RequestInterface
     */
    public function getRequest();

    /**
     * Returns the response.
     *
     * @return ResponseInterface
     */
    public function getResponse();
}
```

[Liskov]: https://en.wikipedia.org/wiki/Liskov_substitution_principle
