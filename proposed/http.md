HTTP message interfaces
=======================

This document describes common interfaces for representing HTTP messages.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specification
----------------

### 1.1 Basics

HTTP messages consist of requests from client to server and responses from
server to client. These are represented by `Psr\Http\RequestInterface` and
`Psr\Http\ResponseInterface` respectively.

Both message types extend `Psr\Http\MessageInterface`, which MUST not be
implemented directly.

2. Package
----------

The interfaces and classes described as well as a test suite to verify your
implementation are provided as part of the
[psr/http](https://packagist.org/packages/psr/http) package.

3. Interfaces
-------------

### 3.1 `Psr\Http\MessageInterface`

```php
<?php

namespace Psr\Http;

/**
 * HTTP messages consist of requests from client to server and responses from
 * server to client.
 *
 * This interface is not to be implemented directly, instead implement
 * `RequestInterface` or `ResponseInterface` as appropriate.
 */
interface MessageInterface
{
    /**
     * Returns the message as an HTTP string.
     *
     * @return string Message as an HTTP string.
     */
    public function __toString();

    /**
     * Gets the HTTP protocol version.
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion();

    /**
     * Sets the HTTP protocol version.
     *
     * @param string $protocolVersion The HTTP protocol version.
     *
     * @return self Reference to the message.
     *
     * @throws \InvalidArgumentException When the HTTP protocol version is not valid.
     */
    public function setProtocolVersion($protocolVersion);

    /**
     * Gets a header.
     *
     * @param string $header Header name.
     *
     * @return string|null Header value, or null if not set.
     */
    public function getHeader($header);

    /**
     * Gets all headers.
     *
     * The array keys are the header name, the values the header value.
     *
     * @return array Headers.
     */
    public function getHeaders();

    /**
     * Checks if a certain header is present.
     *
     * @param string $header Header name.
     *
     * @return bool If the header is present.
     */
    public function hasHeader($header);

    /**
     * Sets a header, replacing the existing header if has already been set.
     *
     * The header name and value MUST be a string, or an object that implement
     * the `__toString()` method. The value MAY also be an array, in which case
     * it MUST be converted to a comma-separated string; the ordering MUST be
     * maintained.
     *
     * A null value will remove the existing header.
     *
     * @param string $header Header name.
     * @param string $value  Header value.
     *
     * @return self Reference to the message.
     *
     * @throws \InvalidArgumentException When the header name or value is not valid.
     */
    public function setHeader($header, $value);

    /**
     * Sets headers, removing any that have already been set.
     *
     * The array keys must the header name, the values the header value.
     *
     * The header names and values MUST strings, or objects that implement the
     * `__toString()` method. The values MAY also be arrays, in which case they
     * MUST be converted to comma-separated strings; the ordering MUST be
     * maintained.
     *
     * @param array $headers Headers to set.
     *
     * @return self Reference to the message.
     *
     * @throws \InvalidArgumentException When part of the header set is not valid.
     */
    public function setHeaders(array $headers);

    /**
     * Gets the body.
     *
     * This returns the original form, in contrast to `getBodyAsString()`.
     *
     * @return mixed|null Body, or null if not set.
     *
     * @see getBodyAsString()
     */
    public function getBody();

    /**
     * Gets the body as a string.
     *
     * @return string|null Body as a string, or null if not set.
     */
    public function getBodyAsString();

    /**
     * Sets the body.
     *
     * The body SHOULD be a string, or an object that implements the
     * `__toString()` method.
     *
     * A null value will remove the existing body.
     *
     * An implementation MAY accept other types, but MUST reject anything that
     * it does not know how to turn into a string.
     *
     * @param mixed $body Body.
     *
     * @return self Reference to the message.
     *
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function setBody($body);
}
```

### 3.2 `Psr\Http\RequestInterface`

```php
<?php

namespace Psr\Http;

/**
 * A request message from a client to a server.
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Gets the method.
     *
     * @return string Method.
     */
    public function getMethod();

    /**
     * Sets the method.
     *
     * @param string $method Method.
     *
     * @return self Reference to the request.
     */
    public function setMethod($method);

    /**
     * Gets the absolute URL.
     *
     * @return string URL.
     */
    public function getUrl();

    /**
     * Sets the absolute URL.
     *
     * @param string $url URL.
     *
     * @return self Reference to the request.
     *
     * @throws \InvalidArgumentException If the URL is invalid.
     */
    public function setUrl($url);
}
```

### 3.3 `Psr\Http\ResponseInterface`

```php
<?php

namespace Psr\Http;

/**
 * A request message from a server to a client.
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Gets the response status code.
     *
     * @return integer Status code.
     */
    public function getStatusCode();

    /**
     * Gets the response reason phrase.
     *
     * If it has not been explicitly set using `setReasonPhrase()` it SHOULD
     * return the RFC 2616 recommended reason phrase.
     *
     * @return string|null Reason phrase, or null if unknown.
     */
    public function getReasonPhrase();
}
```
