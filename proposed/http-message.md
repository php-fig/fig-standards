﻿HTTP message interfaces
=======================

This document describes common interfaces for representing HTTP messages
described in [RFC 7230].

It also describes an interface for representing URIs as described in [RFC 3986],
specifically for composition within an HTTP request message.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[RFC 7230]: http://www.ietf.org/rfc/rfc7230.txt
[RFC 3986]: http://www.ietf.org/rfc/rfc3986.txt

1. Specification
----------------

### 1.1 Messages

HTTP messages consist of requests from a client to a server and responses from
a server to a client. These messages are represented by
`Psr\Http\Message\RequestInterface` and `Psr\Http\Message\ResponseInterface` respectively.

- Both `Psr\Http\Message\RequestInterface` and `Psr\Http\Message\ResponseInterface` extend
  `Psr\Http\Message\MessageInterface`. While `Psr\Http\Message\MessageInterface` MAY be
  implemented directly, implementors are encouraged to implement
  `Psr\Http\Message\RequestInterface` and `Psr\Http\Message\ResponseInterface`.

#### 1.2 HTTP Headers

##### Case-insensitive headers

HTTP messages include case-insensitive headers. Headers are retrieved from
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
Use `getHeaderAsArray()` to retrieve an array of all the header values for a
particular case-insensitive header by name.

```php
$message->setHeader('foo', 'bar');
$message->addHeader('foo', 'baz');
$header = $message->getHeader('foo');

echo $header;
// Outputs: bar, baz

$header = $message->getHeaderAsArray('foo');
// ['bar', 'baz']
```

Note: Not all header values can be concatenated using a comma
(e.g., Set-Cookie). When working with such headers, consumers of the
MessageInterface SHOULD rely on the `getHeaderAsArray()` method for retrieving
such multi-valued headers

### 1.2 Streams

HTTP messages consist of a start-line, headers, and a body. The body of an HTTP
message can be very small or extremely large. Attempting to represent the body
of a message as a string can easily consume more memory than intended because
the body must be stored completely in memory. Attempting to store the body of a
request or response in memory would preclude the use of that implementation from
being able to work with large message bodies. The `StreamableInterface` is used in
order to hide the implementation details when a stream of data is read from
or written to.

`StreamableInterface` exposes several methods that enable streams to be read
from, written to, and traversed effectively.

- Streams expose their capabilities using three methods: `isReadable()`,
  `isWritable()`, and `isSeekable()`. These methods can be used by stream
  collaborators to determine if a stream is capable of their requirements.

  Each stream instance will have various capabilities: it can be read-only,
  write-only, or read-write. It can also allow arbitrary random access (seeking
  forwards or backwards to any location), or only sequential access (for
  example in the case of a socket or pipe).

### 1.3 URIs

A Uniform Resource Indicator (URI) provides a means for addressing and
identifying a resource. In the realm of HTTP, this means a URI that can be
addressed using an HTTP or HTTPS scheme, via which HTTP messages may be
exchanged.

URIs are strings which consist of one or more of the following parts:

- Scheme
- User Information
- Host
- Port
- Path
- Query
- Fragment

When describing URIs in the context of HTTP, a URI will consist of minimally the
scheme and host. In cases where a port is not specified, the default port
for the given scheme may be assumed (port 80 for HTTP, port 443 for HTTPS). In
cases where the path is omitted, a path of `/` may be assumed.

The `UriInterface` hides the implementation details of identifying the parts of
a URI, and provides a consistent interface for retrieving the data associated
with each part. Additionally, it implements PHP's "magic" `__toString()` method,
allowing it to be cast to a string representation of the URI.

`Psr\Http\Message\RequestInterface` recommends using a `Psr\Http\UriInterface`
implementation for its "url" member in order to provide a uniform contract for
accessing the parts of a URI.

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
 * from a server to a client.
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
     * Gets the body of the message.
     *
     * @return StreamableInterface|null Returns the body, or null if not set.
     */
    public function getBody();

    /**
     * Sets the body of the message.
     *
     * The body MUST be a StreamableInterface object. Setting the body to null MUST
     * remove the existing body.
     *
     * @param StreamableInterface|null $body Body.
     *
     * @return void
     *
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function setBody(StreamableInterface $body = null);

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
     * @return array Returns an associative array of the message's headers.
     */
    public function getHeaders();

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $header Case-insensitive header name.
     *
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($header);

    /**
     * Retrieve a header by the given case-insensitive name as a string.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * @param string $header Case-insensitive header name.
     *
     * @return string
     */
    public function getHeader($header);

    /**
     * Retrieves a header by the given case-insensitive name as an array of strings.
     *
     * @param string $header Case-insensitive header name.
     *
     * @return string[]
     */
    public function getHeaderAsArray($header);

    /**
     * Sets a header, replacing any existing values of any headers with the
     * same case-insensitive name.
     *
     * The header name is case-insensitive. The header values MUST be a string
     * or an array of strings.
     *
     * @param string $header Header name
     * @param string|string[] $value  Header value(s)
     *
     * @return void
     */
    public function setHeader($header, $value);

    /**
     * Sets headers, replacing any headers that have already been set on the message.
     *
     * The array keys MUST be a string. The array values must be either a
     * string or an array of strings.
     *
     * @param array $headers Headers to set.
     *
     * @return void
     */
    public function setHeaders(array $headers);

    /**
     * Appends a header value for the specified header.
     *
     * Existing values for the specified header will be maintained. The new
     * value will be appended to the existing list.
     *
     * @param string $header Header name to add
     * @param string $value  Value of the header
     *
     * @return void
     */
    public function addHeader($header, $value);

    /**
     * Merges in an associative array of headers.
     *
     * Each array key MUST be a string representing the case-insensitive name
     * of a header. Each value MUST be either a string or an array of strings.
     * For each value, the value is appended to any existing header of the same
     * name, or, if a header does not already exist by the given name, then the
     * header is added.
     *
     * @param array $headers Associative array of headers to add to the message
     *
     * @return void
     */
    public function addHeaders(array $headers);

    /**
     * Remove a specific header by case-insensitive name.
     *
     * @param string $header HTTP header to remove
     *
     * @return void
     */
    public function removeHeader($header);
}
```

### 3.2 `Psr\Http\Message\RequestInterface`

```php
<?php

namespace Psr\Http\Message;

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
     * Sets the method to be performed on the resource identified by the Request-URI.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * @param string $method Case-insensitive method.
     *
     * @return void
     */
    public function setMethod($method);

    /**
     * Gets the absolute request URL.
     *
     * @return string|object Returns the URL as a string, or an object that
     *    implements the `__toString()` method. The URL must be an absolute URI
     *    as specified in RFC 3986. SHOULD be a Psr\Http\UriInterface
     *    implementation.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     */
    public function getUrl();

    /**
     * Sets the request URL.
     *
     * The URL MUST be a string, or an object that implements the
     * `__toString()` method. The URL must be an absolute URI as specified
     * in RFC 3986. SHOULD be a Psr\Http\UriInterface implementation.
     *
     * @param string|object $url Request URL.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the URL is invalid.
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     */
    public function setUrl($url);
}
```

### 3.3 `Psr\Http\Message\ResponseInterface`

```php
<?php

namespace Psr\Http\Message;

/**
 * A HTTP response message.
 * @link http://tools.ietf.org/html/rfc2616#section-6
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
     * Sets the status code of this response.
     *
     * @param integer $code The 3-digit integer result code to set.
     */
    public function setStatusCode($code);

    /**
     * Gets the response Reason-Phrase, a short textual description of the Status-Code.
     *
     * Because a Reason-Phrase is not a required element in response
     * Status-Line, the Reason-Phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 2616 recommended reason phrase for the
     * response's Status-Code.
     *
     * @return string|null Reason phrase, or null if unknown.
     */
    public function getReasonPhrase();

    /**
     * Sets the Reason-Phrase of the response.
     *
     * If no Reason-Phrase is specified, implementations MAY choose to default
     * to the RFC 2616 recommended reason phrase for the response's Status-Code.
     *
     * @param string $phrase The Reason-Phrase to set.
     */
    public function setReasonPhrase($phrase);
}
```

### 3.4 `Psr\Http\Message\StreamableInterface`

```php
<?php

namespace Psr\Http\Message;

/**
 * Describes streamable content.
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
     * Replaces the underlying stream resource with the provided stream.
     *
     * Use this method to replace the underlying stream with another; as an
     * example, in server-side code, if you decide to return a file, you
     * would replace the original content-oriented stream with the file
     * stream.
     *
     * Any internal state such as caching of cursor position should be reset
     * when attach() is called, as the stream has changed.
     *
     * @return void
     */
    public function attach($stream);

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
     * @param string $key Specific metadata to retrieve.
     *
     * @return array|mixed|null Returns an associative array if no key is
     *                          provided. Returns a specific key value if a key
     *                          is provided and the value is found, or null if
     *                          the key is not found.
     * @see http://php.net/manual/en/function.stream-get-meta-data.php
     */
    public function getMetadata($key = null);
}
```

### 3.5 `Psr\Http\UriInterface`

```php
namespace Psr\Http;

/**
 * Describes a URI.
 */
interface UriInterface
{
    /**
     * Cast the URI instance to a string value (the URI representation).
     *
     * Casts the URI instance to a string value, with the following caveats:
     *
     * - The scheme MAY be omitted, but SHOULD either be fully present, or
     *   represented using relative scheme notation ("//").
     * - If the current port is the default port for the given scheme, it SHOULD
     *   be omitted from the representation.
     * - If the current path is "/" it CAN be omitted from the representation.
     * 
     * @return string The URI representation.
     */
    public function __toString();

    /**
     * Retrieve the URI scheme.
     *
     * @return string The URI scheme (typically HTTP or HTTPS).
     */
    public function getScheme();

    /**
     * Retrieve the URI user information.
     *
     * Returns the URI user information. For a case where only the username is
     * is present in the URI, this can return a string. Otherwise, an array
     * with the keys "username" and "password" SHOULD be returned.
     *
     * For consistency, implementations SHOULD return an associative array
     * with the keys "username" and "password" regardless of what is present.
     *
     * @return string|array The user information, if any; if returned as an
     *                      array, the array MUST contain the elements
     *                      "username" and optionally "password".
     */
    public function getUserInformation();

    /**
     * Retrieve the URI host.
     * 
     * @return string The host represented in the URI.
     */
    public function getHost();

    /**
     * Retrieve the URI port.
     *
     * This should always return a port even if the underlying URI does not
     * specify one (implying the default port for the scheme should be used).
     * 
     * @return string The port represented in or implied by the URI.
     */
    public function getPort();

    /**
     * Retrieve the URI path.
     *
     * If no path is present in the URI, this should still return minimally
     * a "/", indicating the root path of the server.
     * 
     * @return string The path represented in or implied by the URI.
     */
    public function getPath();

    /**
     * Retrieve the URI query string.
     *
     * Returns the query string arguments for the URI, without the "?" prefix.
     * If none are present, an empty string may be returned.
     *
     * SHOULD NOT return the parsed/deserialized representation of them.
     * 
     * @return string The query string represented by the URI.
     */
    public function getQuery();

    /**
     * Retrieve the URI fragment.
     *
     * The URI fragment is the value following a "#" symbol in the URI. This
     * value will never be seen by server-side applications, but can be useful
     * when representing links to specific sections of a resource.
     * 
     * @return string The URI fragment.
     */
    public function getFragment();
}
```
