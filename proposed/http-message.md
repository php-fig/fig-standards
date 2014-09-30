HTTP message interfaces
=======================

This document describes common interfaces for representing HTTP messages
described in [RFC 7230].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[RFC 7230]: http://www.ietf.org/rfc/rfc7230.txt

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

An additional interface, `Psr\Http\Message\IncomingRequestInterface`, extends
`Psr\Http\Message\RequestInterface`, to provide accessors to common server-side
environment parameters, including the deserialized query string arguments,
deserialized body parameters, and the incoming upload files information
(typically represented in PHP via `$_FILES`).

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
     *    as specified in RFC 3986.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     */
    public function getUrl();

    /**
     * Sets the request URL.
     *
     * The URL MUST be a string, or an object that implements the
     * `__toString()` method. The URL must be an absolute URI as specified
     * in RFC 3986.
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

### 3.2.1 `Psr\Http\Message\IncomingRequestInterface`

```php
<?php

namespace Psr\Http\Message;

/**
 * An incoming (server-side) HTTP request.
 *
 * This interface further describes a server-side request and provides
 * accessors and mutators around common request data, such as query
 * string arguments, body parameters, and upload file metadata.
 */
interface IncomingRequestInterface extends RequestInterface
{
    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     * 
     * @return array
     */
    public function getQueryParams();

    /**
     * Set the query string arguments for the request.
     *
     * This will typically be used by factories to inject the values of $_GET
     * into the request instance.
     * 
     * @param array $values The deserialized query string arguments.
     *
     * @return void
     */
    public function setQueryParams(array $values);

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request body can be deserialized, and if the deserialized values
     * can be represented as an array and/or associative array, this method
     * can be used to retrieve them.
     *
     * In other cases, the parent getBody() method should be used to retrieve
     * the body content.
     * 
     * @return array The deserialized body parameters, if any.
     */
    public function getBodyParams();

    /**
     * Set the request body parameters.
     *
     * If the body content can be deserialized, the values obtained may then
     * be injected into the response using this method. This method will
     * typically be invoked by a factory marshaling request parameters.
     * 
     * @param array $values The deserialized body parameters, if any.
     *
     * @return void
     */
    public function setBodyParams(array $values);

    /**
     * Retrieve the upload file metadata.
     *
     * This method should return file upload metadata in the same structure
     * as PHP's $_FILES superglobal.
     * 
     * @return array Upload file(s) metadata, if any.
     */
    public function getIncomingFiles();

    /**
     * Set upload file metadata.
     * 
     * If files are being uploaded in the incoming request, the metadata for
     * the file(s) may be injected into the response using this method. This 
     * method will typically be invoked by a factory marshaling request parameters.
     *
     * @param array $values Upload file metadata, if any.
     *
     * @return void
     */
    public function setIncomingFiles(array $values)
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
