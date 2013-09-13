HTTP message interfaces
=======================

This document describes common interfaces for representing HTTP messages.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specification
----------------

### 1.1 Messages

HTTP messages consist of requests from a client to a server and responses from
a server to a client. These messages are represented by
`Psr\Http\RequestInterface` and `Psr\Http\ResponseInterface` respectively.

- Both `Psr\Http\RequestInterface` and `Psr\Http\ResponseInterface` implement
  `Psr\Http\MessageInterface`. While `Psr\Http\MessageInterface` MAY be
  implemented directly, implementors are encourages to implement
  `Psr\Http\RequestInterface` and `Psr\Http\ResponseInterface`.

- Both `Psr\Http\MessageInterface` extends the `Psr\Http\HasHeadersInterface`.
  The `Psr\Http\HasHeadersInterface` MAY be implemented directly in cases where
  an object needs an array of HTTP headers.

### 1.2 Streams

HTTP messages consist of a start-line, headers, and a body. The body of an HTTP
message can be very small or extremely large. Attempting to represent the body
of a message as a string can easily consume more memory than intended because
the body must be stored completely in memory. Attempting to store the body of a
request or response in memory would preclude the use of that implementation from
being able to work with large message bodies. The `StreamInterface` is used in
order to hide the implementation details of where a stream of data is read from
or written to.

`StreamInterface` exposes several methods that enable streams to be read
  from, written to, and traversed effectively.

- Streams expose their capabilities using three methods: `isReadable()`,
  `isWritable()`, and `isSeekable()`. These methods can be used by stream
  collaborators to determine if a stream is capable of their requirements.

  Each stream instance will have various capabilities: it can be read-only,
  write-only, or read-write. It can also allow arbitrary random access (seeking
  forwards or backwards to any location), or only sequential access (for
  example in the case of a socket or pipe).

- The `StreamFactoryInterface` exposes a single factory method,
  `create($data)`, that is used to create `StreamInterface` objects from
  various input types including but not limited to strings, PHP stream
  resources, and objects that implement the `__toString()` method.

2. Package
----------

The interfaces and classes described are provided as part of the
[psr/http-message](https://packagist.org/packages/psr/http-message) package.

3. Interfaces
-------------

### 3.1 `Psr\Http\HasHeadersInterface`

```php
<?php

namespace Psr\Http;

/**
 * Represents an object that contains an array of HTTP headers.
 *
 * This interface is extended by RequestInterface and ResponseInterface. This
 * interface MAY be implemented directly as needed.
 */
interface HasHeadersInterface
{
    /**
     * Gets all headers.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * the values are an array of strings representing the header values.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ': ' .  implode(', ', $values) . "\r\n";
     *     }
     *
     * @return array Returns an associative array of the message's HTTP headers
     *     where the key is the name of the header as it will be sent over the
     *     wire, and the value is an array of strings for a particular header.
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
     * Appends a header value to any existing values associated with the
     * given header name.
     *
     * @param string $header Header name to add
     * @param string $value  Value of the header
     *
     * @return self
     */
    public function addHeader($header, $value);

    /**
     * Remove a specific header by case-insensitive name.
     *
     * @param string $header HTTP header to remove
     *
     * @return self
     */
    public function removeHeader($header);
}
```

### 3.2 `Psr\Http\MessageInterface`

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
interface MessageInterface exists HasHeadersInterface
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
     * Gets the body of the message.
     *
     * @return StreamInterface|null Returns the body, or null if not set.
     */
    public function getBody();

    /**
     * Sets the body of the message.
     *
     * The body MUST be a StreamInterface object, a string, or a PHP stream
     * resource (e.g., the return value of fopen()). Implementations MAY choose
     * to accept additional types.
     *
     * A null value MUST remove the existing body.
     *
     *
     * @param StreamInterface|string|resource|null $body Body.
     *
     * @return self Returns the message.
     *
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function setBody($body);
}
```

### 3.3 `Psr\Http\RequestInterface`

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
     * Gets the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod();

    /**
     * Sets the method to be performed on the resource identified by the
     * Request-URI. While method names are case case-sensitive, implementations
     * SHOULD convert the method to all uppercase characters.
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

### 3.4 `Psr\Http\ResponseInterface`

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

3.6 `Psr\Stream\StreamInterface`
-------------------------------

```php
<?php

namespace Psr\Http;

/**
 * Describes a stream instance.
 */
interface StreamInterface
{
    /**
     * Reads the remainder of the stream from the current position until the
     * end of the stream is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * @return string
     */
    public function __toString();

    /**
     * Closes the stream and any underlying resources.
     */
    public function close();

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     */
    public function detach();

    /**
     * Get the size of the stream if known
     *
     * @return int|null Returns the size in bytes if known, or null if unknown
     */
    public function getSize();

    /**
     * Get the filename/URL associated with the stream (if known)
     *
     * @return null|string
     */
    public function getUri();

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int|bool Position of the file pointer or false on error
     */
    public function tell();

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof();

    /**
     * Returns whether or not the stream is seekable
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Seek to a position in the stream
     *
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *                    based on the seek offset. Valid values are identical
     *                    to the built-in PHP $whence values for `fseek()`.
     *                    SEEK_SET: Set position equal to offset bytes
     *                    SEEK_CUR: Set position to current location plus offset
     *                    SEEK_END: Set position to end-of-stream plus offset
     *
     * @return bool Returns TRUE on success or FALSE on failure
     * @link   http://www.php.net/manual/en/function.fseek.php
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Returns whether or not the stream is writable
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     *
     * @return int|bool Returns the number of bytes written to the stream on
     *                  success or FALSE on failure.
     */
    public function write($string);

    /**
     * Returns whether or not the stream is readable
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Read data from the stream
     *
     * @param int $length Read up to $length bytes from the object and return
     *                    them. Fewer than $length bytes may be returned if
     *                    underlying stream call returns fewer bytes.
     *
     * @return string     Returns the data read from the stream.
     */
    public function read($length);
}
```

2. `Psr\Stream\StreamFactoryInterface`
--------------------------------------

```php
<?php

namespace Psr\Http\StreamFactoryInterface;

/**
 * Describes a stream factory instance that is used to create
 * StreamFactoryInterface objects.
 */
interface StreamFactoryInterface
{
    /**
     * Creates an {@see StreamInterface} object from various input formats. The
     * following input types SHOULD be supported, and implementations MAY add
     * additional creational behavior if necessary.
     *
     * 1. Pass a string or object that implements __toString() to create a
     *    stream object that contains a string of data. The created stream MUST
     *    be readable and writable.
     * 2. Pass a PHP resource returned from fopen() to create a stream that
     *    wraps a PHP stream resource.
     * 3. Pass NULL or omit the argument to create an empty StreamInterface
     *    object that is readable and writable.
     * 4. Implementations MAY choose to expose additional creational behavior
     *    as necessary.
     *
     * @param string|resource|object $data Stream data to use when creating the
     *                                     StreamInterface object.
     * @return StreamInterface
     */
    public function create($data = null);
}
```