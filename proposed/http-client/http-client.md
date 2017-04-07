HTTP Client
===========

This document describes common interfaces for sending HTTP messages.


## Specification

An HTTP client has the responsibility to send a PSR-7 request and return a PSR-7
response. When there is an error during sending the request, network or the response an
exception should be thrown. 


## Goal

The goal of this PSR is to allow developers to create libraries decoupled from HTTP Client
implementations. This would make libraries more stable since the reduced number of
dependencies and the likelihood to get in version conflicts is reduced.

The second goal is that all HTTP clients should follow the [Liskov substitutions principle][Liskov].
This means that all clients should act the same when sending a request. By default a HTTP client
should not follow redirect nor throw exceptions on HTTP responses with status 4xx or 5xx.


## Interfaces

The following interfaces MAY be implemented together within a single class or
in separate classes.


### HttpClientInterface

```php
namespace Psr\Http\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpClient
{
    /**
     * Sends a PSR-7 request.
     *
     * If a request is sent without any prior configuration, an exception MUST NOT be thrown
     * when a response is recieved, no matter the HTTP status code.
     *
     * If a request is sent without any prior configuration, a HTTP client MUST NOT follow redirects.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Psr\Http\Client\Exception If an error happens during processing the request.
     */
    public function sendRequest(RequestInterface $request);
```


### Exception

```php
namespace Psr\Http\Client;

/**
 * Every HTTP Client related Exception MUST implement this interface.
 */
interface Exception
{
}
```


### RequestException

```php
namespace Psr\Http\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Client\Exception;

/**
 * Exception for when a request failed.
 *
 * Examples:
 *      - Request is invalid (eg. method is missing)
 *      - Runtime request errors (like the body stream is not seekable)
 */
interface RequestException extends Exception
{
    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to HttpClient::sendRequest()
     *
     * @return RequestInterface
     */
    public function getRequest();
}
```


### NetworkException

```php
namespace Psr\Http\Client\Exception;

use Psr\Http\Client\Exception;

/**
 * Thrown when the request cannot be completed because of network issues.
 *
 * There is no response object as this exception is thrown when no response has been received.
 *
 * Example: the target host name can not be resolved or the connection failed.
 */
interface NetworkException extends Exception
{
    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to HttpClient::sendRequest()
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
use Psr\Http\Client\Exception;

/**
 * Thrown when a response was received but the request itself failed.
 *
 * This exception MAY be thrown on HTTP response codes 4xx and 5xx.
 * This exception MUST NOT be thrown when using the client's default configuration.
 */
interface HttpException extends Exception
{
    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to HttpClient::sendRequest()
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
