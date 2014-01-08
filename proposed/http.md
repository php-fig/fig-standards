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

#### 1.2 HTTP Headers

##### Case-insensitive headers

HTTP messages include case-insensitive headers. Headers are retrieved from
classes implementing the HasHeadersInterface interface in a case-insensitive
manner. Retrieving the "foo" header will return the same result as retrieving
the "FoO" header. Similarly, setting the "Foo" header will overwrite any
previously set "foo" header.

```php
$message->setHeader('foo', 'bar');
echo $message->getHeader('foo');
// Outputs: bar

echo $message->getHeader('FOO');
// Outputs: bar

$message->setHeader('fOO', 'baz');
echo $message->getHeader('foo');
// Outputs: baz
```

##### Headers with multiple values

In order to accommodate headers with multiple values yet still provide the
convenience of working with headers as strings, all header values are
implemented using `HeaderValuesInterface` objects. Any object implementing
`HeaderValuesInterface` can be used as an array or cast to a string. When a
header containing multiple values is cast to a string, the values will be
concatenated using a comma separator.

```php
$message->setHeader('foo', 'bar');
$message->addHeader('foo', 'baz');
$header = $message->getHeader('foo');

echo $header;
// Outputs: bar, baz

echo $header[0];
// Outputs: bar

echo $header[1];
// Outputs: baz

foreach ($header as $value) {
    echo $value . ' ';
}
// Outputs: baz bar
```

Implementations MAY choose to internally maintain the state of classes
implementing HeaderValuesInterface using an array of strings or a string value.
However, it is recommended that implementations maintain the internal state
using an array so that headers that cannot be concatenated using a comma
(e.g., Set-Cookie) can be serialized using the array values rather than an
invalid string.

Because some headers cannot be concatenated using a comma, the most accurate
method used for serializing message headers is to iterate over header values
and serialize based on any rules for the specific header.

For example, when converting an object implementing RequestInterface to a
string, an underlying implementation MAY mimic the following behavior.

```php
$str = $message->getUrl() . ' ' . $path . ' HTTP/'
    . $message->getProtocolVersion()) . "\r\n\r\n";

foreach ($message->getHeaders() as $key => $value) {
    // Custom handling MAY be applied for specific keys
    $str .= $key . ': ' . $value . "\r\n";
}

$str .= "\r\n";
$str .= $message->getBody();
```

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
     * The keys of the returned array represents the header name as it will be
     * sent over the wire, and each value is a HeaderValuesInterface object that
     * can be used like an array or cast to a string.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo "{$name}: {$values}\r\n";
     *     }
     *
     * @return array Returns an associative array of the message's headers
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
     * Retrieve a header by name.
     *
     * @param string $header Header name.
     *
     * @return HeaderValuesInterface|null Returns the header values or or null
     *                                    if not set.
     */
    public function getHeader($header);

    /**
     * Sets a header, replacing any existing values of any headers with the
     * same case-insensitive name.
     *
     * The header values MUST be a string, an array of strings, or a
     * HeaderValuesInterface object.
     *
     * @param string                             $header Header name
     * @param string|array|HeaderValuesInterface $value  Header value(s)
     *
     * @return self Returns the message.
     */
    public function setHeader($header, $value);

    /**
     * Sets headers, replacing any headers that have already been set on the
     * message.
     *
     * The array keys MUST be a string. The array values must be either a
     * string, array of strings, or a HeaderValuesInterface object.
     *
     * @param array $headers Headers to set.
     *
     * @return self Returns the message.
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

### 3.2 `Psr\Http\HeaderValuesInterface`

```php
<?php

namespace Psr\Http;

/**
 * Represents a collection of header values.
 */
interface HeaderValuesInterface extends \Countable, \Traversable, \ArrayAccess
{
    /**
     * Convert the header values to a string, concatenating multiple values
     * using a comma.
     *
     * @return string
     */
    public function __toString();
}
```

### 3.3 `Psr\Http\MessageInterface`

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
     * The body MUST be a StreamInterface object. Setting the body to null MUST
     * remove the existing body.
     *
     * @param StreamInterface|null $body Body.
     *
     * @return self Returns the message.
     *
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function setBody(StreamInterface $body = null);
}
```

### 3.4 `Psr\Http\RequestInterface`

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
     * @return string Returns the URL as a string.
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
     * @throws \InvalidArgumentException If the URL is invalid.
     */
    public function setUrl($url);
}
```

### 3.5 `Psr\Http\ResponseInterface`

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

### 3.6 `Psr\Http\MessageFactoryInterface`

```php
<?php

namespace Psr\Http;

/**
 * Message factory.
 */
interface MessageFactoryInterface
{
    /**
     * Create a new request based on the HTTP method.
     *
     * The URL MUST be a string or an object that implements the __toString()
     * method.
     *
     * Headers must be an array which maps header names to either a string, an
     * array of strings, or a HeaderValuesInterface object.
     *
     * Body MUST be either a StreamInterface, string, an object that implements
     * the __toString() method, or a resource as returned from PHP's fopen()
     * function.
     *
     * The $options array is a custom associative array of options that are
     * completely implementation specific options. These are options allow
     * implementations to be interoperable, while still providing the ability
     * to expose custom features.
     *
     * @param string $method  HTTP method (GET, POST, PUT, etc...)
     * @param string $url     URL to send the request to
     * @param array  $headers Request headers
     * @param mixed  $body    Body to send in the request
     * @param array  $options Array of options to apply to the request
     *
     * @return RequestInterface
     */
    public function createRequest(
        $method,
        $url,
        array $headers = array(),
        $body = null,
        array $options = array()
    );

    /**
     * Creates a response.
     *
     * Headers must be an array which maps header names to either a string, an
     * array of strings, or a HeaderValuesInterface object.
     *
     * Body MUST be either a StreamInterface, string, an object that implements
     * the __toString() method, or a resource as returned from PHP's fopen()
     * function.
     *
     * The $options array is a custom associative array of options that are
     * completely implementation specific options. These are options allow
     * implementations to be interoperable, while still providing the ability
     * to expose custom features.
     *
     * @param int   $statusCode HTTP status code
     * @param array $headers    Response headers
     * @param mixed $body       Response body
     * @param array $options    Array of options to apply to the response.
     *
     * @return ResponseInterface
     */
    public function createResponse(
        $statusCode,
        array $headers = array(),
        $body = null,
        array $options = array()
    );
}
```

### 3.7 `Psr\Http\StreamInterface`

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

### 3.8 `Psr\Http\StreamFactoryInterface`

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