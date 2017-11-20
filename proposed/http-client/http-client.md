HTTP Client
===========

This document describes common interfaces for sending HTTP messages.


## Specification

An HTTP client has the responsibility to send a PSR-7 request and return a PSR-7
response. When there is an error during sending the request or an error with network an
exception should be thrown. 

An implementing library MUST implement `Psr\Http\Client\ClientException` for each exception it throws. 
It SHOULD implement exceptions for `Psr\Http\Client\Exception\NetworkException` and
`Psr\Http\Client\Exception\RequestException`.

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
     * Sends a PSR-7 request and returns a PSR-7 response. 
     * 
     * Every technically correct HTTP response MUST be returned as is, even if it represents a HTTP 
     * error response or a redirect instruction.
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


[Liskov]: https://en.wikipedia.org/wiki/Liskov_substitution_principle
