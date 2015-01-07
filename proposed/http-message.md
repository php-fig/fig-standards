HTTP message interfaces
=======================

This document describes common interfaces for representing HTTP messages as
described in [RFC 7230](http://tools.ietf.org/html/rfc7230) and
[RFC 7231](http://tools.ietf.org/html/rfc7231).

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

- RFC 2119: http://www.ietf.org/rfc/rfc2119.txt
- RFC 7230: http://www.ietf.org/rfc/rfc7230.txt
- RFC 7231: http://www.ietf.org/rfc/rfc7231.txt

1. Specification
----------------

### 1.1 Messages

An HTTP message is either a request from a client to a server or a response from
a server to a client. This specification defines interfaces for the HTTP messages
`Psr\Http\Message\RequestInterface` and `Psr\Http\Message\ResponseInterface` respectively.

Both `Psr\Http\Message\RequestInterface` and `Psr\Http\Message\ResponseInterface` extend
`Psr\Http\Message\MessageInterface`. While `Psr\Http\Message\MessageInterface` MAY be
implemented directly, implementors are encouraged to implement
`Psr\Http\Message\RequestInterface` and `Psr\Http\Message\ResponseInterface`.


An additional interface, `Psr\Http\Message\ServerRequestInterface`, extends
`Psr\Http\Message\RequestInterface` to model the various PHP superglobals and
provide access to the various request input sources. These include:

- cookies (`$_COOKIE`)
- query string arguments (`$_GET`)
- body parameters (typically `$_POST`, but these could be deserialized JSON or
  other payloads)
- file uploads (`$_FILES`)
- the server environment (`$_SERVER`)

This practice helps reduce coupling to the superglobals by consumers, and
encourages and promotes the ability to test request consumers.

Server variables and file upload information are considered immutable, as they
are calculated by PHP and cannot be re-calculated or derived within the same
request. Cookies, query string arguments, and body parameters, however, are
mutable to reflect the mutable state of their corresponding superglobals, and
to allow for common practices such as body deserialization, cookie decryption,
etc. If the original values are desired, they can be retrieved from the server
parameters and/or request body.

The server request provides one additional mutable property, "attributes", to
allow consumers the ability to introspect, decompose, and match the request
against application-specific rules (such as path matching, scheme matching,
host matching, etc.). As such, the request can also provide messaging between
multiple request consumers.

From here forward, the namespace `Psr\Http\Message` will be omitted when
referring to these interfaces.

#### 1.2 HTTP Headers

##### Case-insensitive header names

HTTP messages include case-insensitive header names. Headers are retrieved by name from
classes implementing the `MessageInterface` interface in a case-insensitive
manner. For example, retrieving the "foo" header will return the same result as
retrieving the "FoO" header. Similarly, setting the "Foo" header will overwrite
any previously set "foo" header.

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
convenience of working with headers as strings, headers can be retrieved from
an instance of a ``MessageInterface`` as an array or string. Use the
`getHeader()` method to retrieve a header value as a string containing all
header values of a case-insensitive header by name concatenated with a comma.
Use `getHeaderLines()` to retrieve an array of all the header values for a
particular case-insensitive header by name.

```php
$message->setHeader('foo', 'bar');
$message->addHeader('foo', 'baz');

$header = $message->getHeader('foo');
// $header contains: 'bar, baz'

$header = $message->getHeaderLines('foo');
// ['bar', 'baz']
```

Note: Not all header values can be concatenated using a comma (e.g.,
`Set-Cookie`). When working with such headers, consumers of
`MessageInterface`-based classes SHOULD rely on the `getHeaderLines()` method
for retrieving such multi-valued headers.

### 1.2 Streams

HTTP messages consist of a start-line, headers, and a body. The body of an HTTP
message can be very small or extremely large. Attempting to represent the body
of a message as a string can easily consume more memory than intended because
the body must be stored completely in memory. Attempting to store the body of a
request or response in memory would preclude the use of that implementation from
being able to work with large message bodies. `StreamableInterface` is used in
order to hide the implementation details when a stream of data is read from
or written to. For situations where a string would be an appropriate message
implementation, built-in streams such as `php://memory` and `php://temp` may be
used.

`StreamableInterface` exposes several methods that enable streams to be read
from, written to, and traversed effectively.

Streams expose their capabilities using three methods: `isReadable()`,
`isWritable()`, and `isSeekable()`. These methods can be used by stream
collaborators to determine if a stream is capable of their requirements.

Each stream instance will have various capabilities: it can be read-only,
write-only, or read-write. It can also allow arbitrary random access (seeking
forwards or backwards to any location), or only sequential access (for
example in the case of a socket or pipe).

Finally, `StreamableInterface` defines a `__toString()` method to simplify
retrieving or emitting the entire body contents at once.

2. Package
----------

The interfaces and classes described are provided as part of the
[psr/http-message](https://packagist.org/packages/psr/http-message) package.

3. Interfaces
-------------

### 3.1 `Psr\Http\Message\MessageInterface`

```php
<?php

namespace Psr\Http\Message;

/**
 * HTTP messages consist of requests from a client to a server and responses
 * from a server to a client. This interface defines the methods common to
 * each.
 *
 * @link http://www.ietf.org/rfc/rfc7230.txt
 * @link http://www.ietf.org/rfc/rfc7231.txt
 */
interface MessageInterface
{
    /**
     * Gets the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion();

    /**
     * Set the HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * @param string $version HTTP protocol version
     * @return void
     */
    public function setProtocolVersion($version);

    /**
     * Gets all message headers.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * @return array Returns an associative array of the message's headers. Each
     *     key MUST be a header name, and each value MUST be an array of strings.
     */
    public function getHeaders();

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $header Case-insensitive header name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($header);

    /**
     * Retrieve a header by the given case-insensitive name, as a string.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation.
     *
     * @param string $header Case-insensitive header name.
     * @return string
     */
    public function getHeader($header);

    /**
     * Retrieves a header by the given case-insensitive name as an array of strings.
     *
     * @param string $header Case-insensitive header name.
     * @return string[]
     */
    public function getHeaderLines($header);

    /**
     * Sets a header, replacing any existing values of any headers with the
     * same case-insensitive name.
     *
     * The header name is case-insensitive. The header values MUST be a string
     * or an array of strings.
     *
     * @param string $header Header name
     * @param string|string[] $value Header value(s).
     * @return void
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function setHeader($header, $value);

    /**
     * Appends a header value for the specified header.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list.
     *
     * @param string $header Header name to add
     * @param string|string[] $value Header value(s).
     * @return void
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function addHeader($header, $value);

    /**
     * Remove a specific header by case-insensitive name.
     *
     * @param string $header HTTP header to remove
     * @return void
     */
    public function removeHeader($header);

    /**
     * Gets the body of the message.
     *
     * @return StreamableInterface Returns the body as a stream.
     */
    public function getBody();

    /**
     * Sets the body of the message.
     *
     * The body MUST be a StreamableInterface object.
     *
     * @param StreamableInterface $body Body.
     * @return void
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function setBody(StreamableInterface $body);
}
```

### 3.2 `Psr\Http\Message\RequestInterface`

```php
<?php

namespace Psr\Http\Message;

/**
 * Representation of an outgoing, client-side request.
 *
 * Per the HTTP specification, this interface includes both accessors for
 * and mutators for the following:
 *
 * - Protocol version
 * - HTTP method
 * - URL
 * - Headers
 * - Message body
 *
 * As the request CAN be built iteratively, the interface allows
 * mutability of all properties.
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod();

    /**
     * Sets the HTTP method to be performed on the resource identified by the
     * Request-URI.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * @param string $method Case-insensitive method.
     * @return void
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function setMethod($method);

    /**
     * Retrieves the base request URL.
     *
     * The base URL consists of:
     *
     * - scheme
     * - authentication (if any)
     * - server name/host
     * - port (if non-standard)
     *
     * This method is provided for convenience, particularly when considering
     * server-side requests, where data such as the scheme and server name may
     * need to be computed from more than one environmental variable.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return string Returns the base URL as a string. The URL MUST include
     *     the scheme and host; if the port is non-standard for the scheme,
     *     the port MUST be included; authentication data MAY be provided.
     */
    public function getBaseUrl();

    /**
     * Sets the base request URL.
     *
     * The base URL MUST be a string, and MUST include the scheme and host.
     *
     * If the port is non-standard for the scheme, the port MUST be provided.
     *
     * Authentication data MAY be provided.
     *
     * If path, query string, or URL fragment are provided they SHOULD be
     * stripped; optionally, an error MAY be raised in such situations.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @param string $url Base request URL.
     * @return void
     * @throws \InvalidArgumentException If the URL is invalid.
     */
    public function setBaseUrl($url);

    /**
     * Retrieves the request URL.
     *
     * The request URL is the same value as REQUEST_URI: the path and query
     * string ONLY.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3
     * @return string Returns the URL as a string. The URL MUST be an
     *     origin-form (path + query string), per RFC 7230 section 5.3
     */
    public function getUrl();

    /**
     * Sets the request URL.
     *
     * The URL MUST be a string. The URL SHOULD be an origin-form (path + query
     * string) per RFC 7230 section 5.3; if other URL parts are present, the
     * method MUST raise an exception OR remove those parts.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3
     * @param string $url Request URL, with path and optionally query string.
     * @return void
     * @throws \InvalidArgumentException If the URL is invalid.
     */
    public function setUrl($url);
}
```

#### 3.2.1 `Psr\Http\Message\ServerRequestInterface`

```php
<?php

namespace Psr\Http\Message;

/**
 * Representation of an incoming, server-side HTTP request.
 *
 * Per the HTTP specification, this interface includes accessors for
 * the following:
 *
 * - Protocol version
 * - HTTP method
 * - URL
 * - Headers
 * - Message body
 *
 * Additionally, it encapsulates all data as it has arrived to the
 * application from the PHP environment, including:
 *
 * - The values represented in $_SERVER.
 * - Any cookies provided (generally via $_COOKIE)
 * - Query string arguments (generally via $_GET, or as parsed via parse_str())
 * - Upload files, if any (as represented by $_FILES)
 * - Deserialized body parameters (generally from $_POST)
 *
 * $_SERVER and $_FILES values MUST be treated as immutable, as they represent
 * application state at the time of request. The other values SHOULD be
 * mutable, as they can be restored from $_SERVER, $_FILES, or the request
 * body, and may need treatment during the application (e.g., body parameters
 * may be deserialized based on content type).
 *
 * Additionally, this interface recognizes the utility of introspecting a
 * request to derive and match additional parameters (e.g., via URI path
 * matching, decrypting cookie values, deserializing non-form-encoded body
 * content, matching authorization headers to users, etc). These parameters
 * are stored in an "attributes" property, which MUST be mutable.
 */
interface ServerRequestInterface extends RequestInterface
{
    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    public function getServerParams();

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The data MUST be compatible with the structure of the $_COOKIE
     * superglobal.
     *
     * @return array
     */
    public function getCookieParams();

    /**
     * Set cookies.
     *
     * Set cookies sent by the client to the server.
     *
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     * @return void
     */
    public function setCookieParams(array $cookies);

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * @return array
     */
    public function getQueryParams();

    /**
     * Set query string arguments.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's `parse_str()` would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * @param array $query Array of query string arguments, typically from
     *     $_GET.
     * @return void
     */
    public function setQueryParams(array $query);

    /**
     * Retrieve the upload file metadata.
     *
     * This method MUST return file upload metadata in the same structure
     * as PHP's $_FILES superglobal.
     *
     * These values MUST remain immutable over the course of the incoming
     * request. They SHOULD be injected during instantiation, such as from PHP's
     * $_FILES superglobal, but MAY be derived from other sources.
     *
     * @return array Upload file(s) metadata, if any.
     */
    public function getFileParams();

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request body can be deserialized to an array, this method MAY be
     * used to retrieve them.
     *
     * @return array The deserialized body parameters, if any.
     */
    public function getBodyParams();

    /**
     * Set parameters provided in the request body.
     *
     * These MAY be injected during instantiation from PHP's $_POST
     * superglobal. The data IS NOT REQUIRED to come from $_POST, but MUST be
     * an array. This method can be used during the request lifetime to inject
     * parameters discovered and/or deserialized from the request body; as an
     * example, if content negotiation determines that the request data is
     * a JSON payload, this method could be used to inject the deserialized
     * parameters.
     *
     * @param array $params The deserialized body parameters.
     * @return void
     */
    public function setBodyParams(array $params);

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes();

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * @see getAttributes()
     * @param string $attribute Attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute($attribute, $default = null);

    /**
     * Set attributes derived from the request.
     *
     * This method allows setting request attributes, as described in
     * getAttributes().
     *
     * @see getAttributes()
     * @param array $attributes Attributes derived from the request.
     * @return void
     */
    public function setAttributes(array $attributes);

    /**
     * Set a single derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * @see getAttributes()
     * @param string $attribute The attribute name.
     * @param mixed $value The value of the attribute.
     * @return void
     */
    public function setAttribute($attribute, $value);
}
```

### 3.3 `Psr\Http\Message\ResponseInterface`

```php
<?php

namespace Psr\Http\Message;

/**
 * Representation of an outgoing, server-side response.
 *
 * Per the HTTP specification, this interface includes both accessors for
 * and mutators for the following:
 *
 * - Protocol version
 * - Status code and reason phrase
 * - Headers
 * - Message body
 *
 * As the response CAN be built iteratively, the interface allows
 * mutability of all properties.
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Gets the response Status-Code.
     *
     * The Status-Code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return integer Status code.
     */
    public function getStatusCode();

    /**
     * Sets the status code, and optionally reason phrase,  of this response.
     *
     * If no Reason-Phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * Status-Code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param integer $code The 3-digit integer result code to set.
     * @param null|string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function setStatus($code, $reasonPhrase = null);

    /**
     * Gets the response Reason-Phrase, a short textual description of the Status-Code.
     *
     * Because a Reason-Phrase is not a required element in a response
     * Status-Line, the Reason-Phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * Status-Code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string|null Reason phrase, or null if unknown.
     */
    public function getReasonPhrase();
}
```

### 3.4 `Psr\Http\Message\StreamableInterface`

```php
<?php

namespace Psr\Http\Message;

/**
 * Describes streamable message body content.
 *
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 */
interface StreamableInterface
{
    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * @return string
     */
    public function __toString();

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close();

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach();

    /**
     * Get the size of the stream if known
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize();

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int|bool Position of the file pointer or false on error.
     */
    public function tell();

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof();

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Seek to a position in the stream.
     *
     * @link  http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *                    based on the seek offset. Valid values are identical
     *                    to the built-in PHP $whence values for `fseek()`.
     *                    SEEK_SET: Set position equal to offset bytes
     *                    SEEK_CUR: Set position to current location plus offset
     *                    SEEK_END: Set position to end-of-stream plus offset
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Returns whether or not the stream is writable.
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
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *                    them. Fewer than $length bytes may be returned if
     *                    underlying stream call returns fewer bytes.
     * @return string|false Returns the data read from the stream, false if
     *                      unable to read or if an error occurs.
     */
    public function read($length);

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     */
    public function getContents();

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *                          provided. Returns a specific key value if a key
     *                          is provided and the value is found, or null if
     *                          the key is not found.
     */
    public function getMetadata($key = null);
}
```
