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

HTTP messages consist of requests from a client to a server and responses from
a server to a client. These messages are represented by
`Psr\Http\RequestInterface` and `Psr\Http\ResponseInterface` respectively.

Both message types extend from `Psr\Http\MessageInterface`, which SHOULD not be
implemented directly.

2. Package
----------

The interfaces and classes described are provided as part of the
[psr/http-message](https://packagist.org/packages/psr/http-message) package.

3. Interfaces
-------------

### 3.1 `Psr\Http\MessageInterface`

```php
<?php

namespace Psr\Http;

/**
 * HTTP messages consist of requests from a client to a server and responses
 * from a server to a client.
 *
 * This interface SHOULD not be implemented directly; instead, implement
 * `RequestInterface` or `ResponseInterface` as appropriate.
 */
interface MessageInterface
{
    /**
     * Returns a string representation of the HTTP message.
     *
     * @return string Message as a string.
     */
    public function __toString();

    /**
     * Gets the HTTP protocol version.
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion();

    /**
     * Retrieve an HTTP header by name.
     *
     * If a header contains multiple values for the given case-insensitive name,
     * then the header values MUST be combined using a comma separator followed
     * by a space (i.e., ", ") as specified in RFC 2616.
     *
     * @param string $header Header name.
     *
     * @return string|null Header value, or null if not set.
     */
    public function getHeader($header);

    /**
     * Gets all headers.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * the values are an array of strings representing the header values.
     *
     * The return value of this method MUST be an array or a PHP object that
     * implements \Traversable.
     *
     * @return array|Traversable An iterable representation of all of the
     *                           message's HTTP headers.
     */
    public function getHeaders();

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $header Case-insensitive header name.
     *
     * @return bool Returns true if any header names match the given header
     *              name using a case-insensitive string comparison. Returns
     *              false if no matching header name is found in the message.
     */
    public function hasHeader($header);

    /**
     * Sets a header, replacing any existing values of any headers with the
     * same case-insensitive name.
     *
     * The header name and value MUST be a string, or an object that implement
     * the `__toString()` method. The value MAY also be an array of header
     * values.
     *
     * @param string       $header Header name
     * @param string|array $value  Header value(s)
     *
     * @return self Returns the message.
     *
     * @throws \InvalidArgumentException When the header name or value is not valid.
     */
    public function setHeader($header, $value);

    /**
     * Sets headers, replacing any headers that have already been set on the
     * message.
     *
     * The array keys must be strings representing the header name. The values
     * for each key MUST be one of the following: string, and object that
     * implements the `__toString()` method, or an array of string values.
     *
     * @param array $headers Headers to set.
     *
     * @return self Returns the message.
     *
     * @throws \InvalidArgumentException When part of the header set is not valid.
     */
    public function setHeaders(array $headers);

    /**
     * Gets the body of the message.
     *
     * @return mixed|null Returns the message body, or null if not set.
     */
    public function getBody();

    /**
     * Sets the body of the message.
     *
     * The body SHOULD be a string, or an object that implements the
     * `__toString()` method.
     *
     * A null value MUST remove the existing body.
     *
     * An implementation MAY accept other types, but MUST reject anything that
     * it does not know how to turn into a string.
     *
     * @param mixed $body Body.
     *
     * @return self Returns the message.
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
 * A HTTP request message.
 * @link http://tools.ietf.org/html/rfc2616#section-5
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Gets the method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod();

    /**
     * Sets the method to be performed on the resource identified by the
     * Request-URI. The method is case-sensitive.
     *
     * @param string $method Case-insensitive method.
     *
     * @return self Returns the request.
     */
    public function setMethod($method);

    /**
     * Gets the request URL.
     *
     * @return string URL.
     */
    public function getUrl();

    /**
     * Sets the request URL.
     *
     * The URL MUST be a string, or an object that implements the
     * `__toString()` method.
     *
     * @param string $url Request URL.
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
 * A HTTP response message.
 * @link http://tools.ietf.org/html/rfc2616#section-6
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Gets the response Status-Code, a 3-digit integer result code of the
     * server's attempt to understand and satisfy the request.
     *
     * @return integer Status code.
     */
    public function getStatusCode();

    /**
     * Gets the response Reason-Phrase, a short textual description of the
     * Status-Code.
     *
     * Because a Reason-Phrase is not a required element in response
     * Status-Line, the Reason-Phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 2616 recommended reason phrase for the
     * response's Status-Code.
     *
     * @return string|null Reason phrase, or null if unknown.
     */
    public function getReasonPhrase();
}
```
