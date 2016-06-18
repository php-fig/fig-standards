HTTP Factories
==============

This document describes a common standard for factories that create [PSR-7][psr7]
compliant HTTP objects.

PSR-7 did not include a recommendation on how to create HTTP objects, which leads
to difficulty when needing to create new HTTP objects within components that are
not tied to a specific implementation of PSR-7.

The interfaces described in this document describe methods by which PSR-7 objects
can be instantiated.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][rfc2119].

[psr7]: http://www.php-fig.org/psr/psr-7/
[rfc2119]: http://tools.ietf.org/html/rfc2119

## 1. Specification

An HTTP factory is a method by which a new HTTP object, as defined by PSR-7,
is created. HTTP factories MUST implement these interfaces for each object type
that is provided by the package.

## 2. Interfaces

The following interfaces MAY be implemented together within a single class or
in separate classes.

### 2.1 RequestFactoryInterface

Has the ability to create client requests.

```php
namespace Psr\Http\Message;

interface RequestFactoryInterface
{
    /**
     * Create a new request.
     *
     * @param string $method
     * @param UriInterface|string $uri
     *
     * @return RequestInterface
     */
    public function createRequest($method, $uri);
}
```

### 2.2 ResponseFactoryInterface

Has the ability to create responses.

```php
namespace Psr\Http\Message;

interface ResponseFactoryInterface
{
    /**
     * Create a new response.
     *
     * @param integer $code HTTP status code
     *
     * @return ResponseInterface
     */
    public function createResponse($code = 200);
}
```

### 2.3 ServerRequestFactoryInterface

Has the ability to create server requests.

```php
namespace Psr\Http\Message;

interface ServerRequestFactoryInterface
{
    /**
     * Create a new server request.
     *
     * @param string $method
     * @param UriInterface|string $uri
     *
     * @return ServerRequestInterface
     */
    public function createServerRequest($method, $uri);

    /**
     * Create a new server request from PHP globals.
     *
     * @return ServerRequestInterface
     */
    public function createServerRequestFromGlobals();
}
```

### 2.4 StreamFactoryInterface

Has the ability to create streams for requests and responses.

```php
namespace Psr\Http\Message;

interface StreamFactoryInterface
{
    /**
     * Create a new stream with no content.
     *
     * The stream will be writable and seekable.
     *
     * @return StreamInterface
     */
    public function createStream();

    /**
     * Create a new stream from a callback.
     *
     * The stream will be read-only and not seekable.
     *
     * @param callable $callback
     *
     * @return StreamInterface
     */
    public function createStreamFromCallback(callable $callback);

    /**
     * Create a new stream from a resource.
     *
     * @param resource $body
     *
     * @return StreamInterface
     */
    public function createStreamFromResource($body);

    /**
     * Create a new stream from a string.
     *
     * A temporary resource will be created with the content of the string.
     * The resource will be writable and seekable.
     *
     * @param string $body
     *
     * @return StreamInterface
     */
    public function createStreamFromString($body);
}
```

Implementations of this interface SHOULD use a temporary file when creating
resources from strings. The RECOMMENDED method for doing so is:

```php
$resource = fopen('php://temp', 'r+');
fwrite($resource, $body);
```

### 2.5 UploadedFileFactoryInterface

Has the ability to create streams for uploaded files.

```php
namespace Psr\Http\Message;

interface UploadedFileFactoryInterface
{
    /**
     * Create a new uploaded file.
     *
     * If a string is passed it is assumed to be a file path.
     *
     * If a size is not provided it will be determined by checking the size of
     * the file.
     *
     * @see http://php.net/manual/features.file-upload.post-method.php
     * @see http://php.net/manual/features.file-upload.errors.php
     *
     * @param string|resource $file
     * @param integer $size in bytes
     * @param integer $error PHP file upload error
     * @param string $clientFilename
     * @param string $clientMediaType
     *
     * @return UploadedFileInterface
     */
    public function createUploadedFile(
        $file,
        $size = null,
        $error = \UPLOAD_ERR_OK,
        $clientFilename = null,
        $clientMediaType = null
    );
}
```

Implementations of this interface SHOULD use a temporary file when creating
resources from strings. The RECOMMENDED method for doing so is:

```php
$resource = fopen('php://temp', 'r+');
fwrite($resource, $body);
```

### 2.6 UriFactoryInterface

Has the ability to creates URIs for client and server requests.

```php
namespace Psr\Http\Message;

interface UriFactoryInterface
{
    /**
     * Create a new URI.
     *
     * @param string $uri
     *
     * @return UriInterface
     *
     * @throws \InvalidArgumentException
     *  If the given URI cannot be parsed.
     */
    public function createUri($uri = '');
}
```
