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
that is implemented.

## 2. Interfaces

The following interfaces MAY be implemented together within a single class or
in separate classes.

### 2.1 RequestFactoryInterface

```php
namespace Psr\Http\Message;

interface RequestFactoryInterface
{
    /**
     * Create a new request.
     *
     * @return RequestInterface
     */
    public function createRequest();
}
```

### 2.2 ResponseFactoryInterface

```php
namespace Psr\Http\Message;

interface ResponseFactoryInterface
{
    /**
     * Create a new response.
     *
     * @return ResponseInterface
     */
    public function createResponse();
}
```

### 2.3 ServerRequestFactoryInterface

```php
namespace Psr\Http\Message;

interface ServerRequestFactoryInterface
{
    /**
     * Create a new server request.
     *
     * @return ServerRequestInterface
     */
    public function createServerRequest();
}
```

### 2.4 StreamFactoryInterface

```php
namespace Psr\Http\Message;

interface StreamFactoryInterface
{
    /**
     * Create a new stream.
     *
     * If a string is used to create the stream, a temporary resource will be
     * created with the content of the string. The resource will be writable
     * and seekable.
     *
     * If a resource is passed it must be readable.
     *
     * @param string|resource $body
     *
     * @return StreamInterface
     *
     * @throws \InvalidArgumentException
     *  If a passed resource is not readable.
     */
    public function createStream($body = '');
}
```

Implementations of this interface SHOULD use a temporary file when creating
resources from strings. The RECOMMENDED method for doing so is:

```php
$resource = fopen('php://temp', 'r+');
fwrite($resource, $body);
```

### 2.5 UploadedFileFactoryInterface

```php
namespace Psr\Http\Message;

interface UploadedFileFactoryInterface
{
    /**
     * Create a new uploaded file.
     *
     * If a string is used to create the file, a temporary resource will be
     * created with the content of the string.
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
     *
     * @throws \InvalidArgumentException
     *  If the file resource is not readable.
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

