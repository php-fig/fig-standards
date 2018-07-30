HTTP Factories Meta
===================

## 1. Summary

The purpose of this PSR is to provide factory interfaces that define methods to
create [PSR-7][psr7] objects.

[psr7]: https://www.php-fig.org/psr/psr-7/

## 2. Why Bother?

The current specification for PSR-7 allows for most objects to be modified by
creating immutable copies. However, there are two notable exceptions:

- `StreamInterface` is a mutable object based on a resource that only allows
  the resource to be written to when the resource is writable.
- `UploadedFileInterface` is a read-only object based on a resource that offers
  no modification capabilities.

The former is a significant pain point for PSR-7 middleware, as it can leave
the response in an incomplete state. If the stream attached to the response body
is not seekable or not writable, there is no way to recover from an error
condition in which the body has already been written to.

This scenario can be avoided by providing a factory to create new streams. Due to
the lack of a formal standard for HTTP object factories, a developer must rely on
a specific vendor implementation in order to create these objects.

Another pain point is when writing re-usable middleware or request handlers. In
such cases, package authors may need to create and return a response. However,
creating discrete instances then ties the package to a specific PSR-7
implementation. If these packages rely on the request factory interface instead,
they can remain agnostic of the PSR-7 implementation.

Creating a formal standard for factories will allow developers to avoid
dependencies on specific implementations while having the ability to create new
objects when necessary.

## 3. Scope

### 3.1 Goals

- Provide a set of interfaces that define methods to create PSR-7 compatible objects.

### 3.2 Non-Goals

- Provide a specific implementation of PSR-7 factories.

## 4. Approaches

### 4.1 Chosen Approach

The factory method definition has been chosen based on whether or not the object
can be modified after instantiation. For interfaces that cannot be modified, all
of the object properties must be defined at the time of instantiation.

In the case of `UriInterface` a complete URI may be passed for convenience.

The method names used will not conflict. This allows for a single class to
implement multiple interfaces when appropriate.

### 4.2 Existing Implementations

All of the current implementations of PSR-7 have defined their own requirements.
In most cases, the required parameters are the same or less strict than the proposed
factory methods.

#### 4.2.1 Diactoros

[Diactoros][zend-diactoros] was one of the first HTTP Messages implementations for
server usage, and was developed parallel to the PSR-7 specification.

- [`Request`][diactoros-request] No required parameters, method and URI default to `null`.
- [`Response`][diactoros-response] No required parameters, status code defaults to `200`.
- [`ServerRequest`][diactoros-server-request] No required parameters. Contains a separate
  [`ServerRequestFactory`][diactoros-server-request-factory] for creating requests from globals.
- [`Stream`][diactoros-stream] Requires `string|resource $stream` for the body.
- [`UploadedFile`][diactoros-uploaded-file] Requires `string|resource $streamOrFile`, `int $size`,
  `int $errorStatus`. Error status must be a PHP upload constant.
- [`Uri`][diactoros-uri] No required parameters, `string $uri` is empty by default.

[zend-diactoros]: https://docs.zendframework.com/zend-diactoros/
[diactoros-request]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/Request.php#L33
[diactoros-response]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/Response.php#L114
[diactoros-server-request]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/ServerRequest.php#L78-L89
[diactoros-server-request-factory]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/ServerRequestFactory.php#L52-L58
[diactoros-stream]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/Stream.php#L36
[diactoros-uploaded-file]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/UploadedFile.php#L62
[diactoros-uri]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/Uri.php#L94

Overall this approach is quite similar to the proposed factories. In some cases,
more options are given by Diactoros which are not required for a valid object.
The proposed uploaded file factory allows for size and error status to be optional.

#### 4.2.2 Guzzle

[Guzzle][guzzle] is an HTTP Messages implementation that focuses on client usage.

- [`Request`][guzzle-request] Requires both `string $method` and `string|UriInterface $uri`.
- [`Response`][guzzle-response] No required parameters, status code defaults to `200`.
- [`Stream`][guzzle-stream] Requires `resource $stream` for the body.
- [`Uri`][guzzle-uri] No required parameters, `string $uri` is empty by default.

_Being geared towards client usage, Guzzle does not contain a `ServerRequest` or
`UploadedFile` implementation._

[guzzle]: https://github.com/guzzle/psr7
[guzzle-request]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/Request.php#L32-L38
[guzzle-response]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/Response.php#L88-L94
[guzzle-stream]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/Stream.php#L51
[guzzle-uri]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/Uri.php#L48

Overall this approach is also quite similar to the proposed factories. One notable
difference is that Guzzle requires streams to be constructed with a resource and
does not allow a string. However, it does contain a helper function [`stream_for`][guzzle-stream-for]
that will create a stream from a string of content and a function [`try_fopen`][guzzle-try-fopen]
that will create a resource from a file path.

[guzzle-stream-for]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/functions.php#L78
[guzzle-try-fopen]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/functions.php#L295

#### 4.2.3 Slim

[Slim][slim] is a micro-framework that makes use of HTTP Messages from version
3.0 forward.

- [`Request`][slim-request] Requires `string $method`, `UriInterface $uri`,
  `HeadersInterface $headers`, `array $cookies`, `array $serverParams`, and
  `StreamInterface $body`. Contains a factory method `createFromEnvironment(Environment $environment)`
  that is framework specific but analogous to the proposed `createServerRequestFromArray`.
- [`Response`][slim-response] No required parameters, status code defaults to `200`.
- [`Stream`][slim-stream] Requires `resource $stream` for the body.
- [`UploadedFile`][slim-uploaded-file] Requires `string $file` for the source file.
  Contains a factory method `parseUploadedFiles(array $uploadedFiles)` for creating
  an array of `UploadedFile` instances from `$_FILES` or similar format. Also contains
  a factory method `createFromEnvironment(Environment $env)` that is framework specific
  and makes use of `parseUploadedFiles`.
- [`Uri`][slim-uri] Requires `string $scheme` and `string $host`. Contains a factory
  method `createFromString($uri)` that can be used to create a `Uri` from a string.

_Being geared towards server usage only, Slim does not contain an implementation
of `Request`. The implementation listed above is an implementation of `ServerRequest`._

[slim]: https://www.slimframework.com/
[slim-request]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/Request.php#L170-L178
[slim-response]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/Response.php#L123
[slim-stream]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/Stream.php#L96
[slim-uploaded-file]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/UploadedFile.php#L151
[slim-uri]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/Uri.php#L112-L121

Of the compared approaches, Slim is most different from the proposed factories.
Most notably, the `Request` implementation contains requirements specific
to the framework that are not defined in HTTP Messages specification. The factory
methods that are included are generally similar with the proposed factories.

### 4.3 Potential Issues

The most difficult task in establishing this standard will be defining the
method signatures for the interfaces. As there is no clear declaration in PSR-7
as to what values are explicitly required, the properties that are read-only
must be inferred based on whether the interfaces have methods to copy-and-modify
the object.

## 5. Design Decisions

### 5.1 Why PHP 7?

While PSR-7 does not target PHP 7, the authors of this specification note that,
at the time of writing (April 2018), PHP 5.6 stopped receiving bugfixes 15
months ago, and will no longer receive security patches in 8 months; PHP 7.0
itself will stop receiving security fixes in 7 months (see the [PHP supported
versions document][php-support] for current support details). Since
specifications are meant to be long-term, the authors feel the specification
should target versions that will be supported for the foreseeable future; PHP 5
will not. As such, from a security standpoint, targeting anything under PHP 7 is
a disservice to users, as doing so would be tacit approval of usage of
unsupported PHP versions.

Additionally, and equally importantly, PHP 7 gives us the ability to provide
return type hints to interfaces we define. This guarantees a strong,
predicatable contract for end users, as they can assume that the values returned
by implementations will be exactly what they expect.

[php-support]: http://php.net/supported-versions.php

### 5.2 Why multiple interfaces?

Each proposed interface is (primarily) responsible for producing one PSR-7 type.
This allows consumers to typehint on exactly what they need: if they need a
response, they typehint on `ResponseFactoryInterface`; if they need a URI, they
typehint on `UriFactoryInterface`. In this way, users can be granular about what
they need.

Doing so also allows application developers to provide anonymous implementations
based on the PSR-7 implementation they are using, producing only the instances
they need for the specific context. This reduces boilerplate; developers do not
need to write stubs for unused methods.

### 5.3 Why does the $reasonPhrase argument to the ResponseFactoryInterface exist?

`ResponseFactoryInterface::createResponse()` includes an optional string
argument, `$reasonPhrase`. In the PSR-7 specification, you can only provide a
reason phrase at the same time you provide a status code, as the two are related
pieces of data. The authors of this specification have chosen to mimic the PSR-7
`ResponseInterface::withStatus()` signature to ensure both sets of data may be
present in the response created.

### 5.4 Why does the $serverParams argument to the ServerRequestFactoryInterface exist?

`ServerRequestFactoryInterface::createServerRequest()` includes an optional
`$serverParams` array argument. The reason this is provided is to ensure that an
instance can be created with the server params populated. Of the data accessible
via the `ServerRequestInterface`, the only data that does not have a mutator
method is the one corresponding to the server params. As such, this data MUST be
provided at initial creation. For this reason, it exists as an argument to the
factory method.

### 5.5 Why is there no factory for creating a ServerRequestInterface from superglobals?

The primary use case of `ServerRequestFactoryInterface` is for creating a new
`ServerRequestInterface` instance from known data. Any solution around
marshaling data from superglobals assumes that:

- superglobals are present
- superglobals follow a specific structure

These two assumptions are not always true. When using asynchronous systems such
as [Swoole][swoole], [ReactPHP][reactphp], and others:

- will not populate standard superglobals such as `$_GET`, `$_POST`, `$_COOKIE`,
  and `$_FILES`
- will not populate `$_SERVER` with the same elements as a standard SAPI (such as
  mod_php, mod-cgi, and mod-fpm)

Moreover, different standard SAPIs provide different information to `$_SERVER`
and access to request headers, requiring different approaches for initial
population of the request.

As such, designing an interface for population of an instance from superglobals
is out of scope of this specification, and should largely be
implementation-specfic.

[swoole]: https://www.swoole.co.uk/
[reactphp]: https://reactphp.org/

### 5.6 Why does RequestFactoryInterface::createRequest allow a string URI?

The primary use case of `RequestFactoryInterface` is to create a request, and
the only required values for any request are the request method and a URI. While
`RequestFactoryInterface::createRequest()` can accept a `UriInterface` instance,
it also allows a string.

The rationale is two-fold. First, the majority use case is to create a request
instance; creation of the URI instance is secondary. Requiring a `UriInterface`
means users would either need to also have access to a `UriFactoryInterface`, or
the `RequestFactoryInterface` would have a hard requirement on a
`UriFactoryInterface`. The first complicates usage for consumers of the factory,
the second complicates usage for either developers of the factory, or those
creating the factory instance.

Second, `UriFactoryInterface` provides exactly one way to create a
`UriInterface` instance, and that is from a string URI. If creation of the URI
is based on a string, there's no reason for the `RequestFactoryInterface` not to
allow the same semantics. Additionally, every PSR-7 implementation surveyed at
the time this proposal was developed allowed a string URI when creating a
`RequestInterface` instance, as the value was then passed to whatever
`UriInterface` implementation they provided. As such, accepting a string is
expedient and follows existing semantics.

## 6. People

This PSR was produced by a FIG Working Group with the following members:

- Woody Gilk (editor), <woody.gilk@gmail.com>
- Matthew Weier O'Phinney (sponsor), <mweierophinney@gmail.com>
- Stefano Torresi
- Matthieu Napoli
- Korvin Szanto
- Glenn Eggleton
- Oscar Otero
- Tobias Nyholm

The working group would also like to acknowledge the contributions of:

- Paul M. Jones, <pmjones88@gmail.com>
- Rasmus Schultz, <rasmus@mindplay.dk>
- Roman Tsjupa, <draconyster@gmail.com>

## 7. Votes

- [Entrance Vote](https://groups.google.com/forum/#!topic/php-fig/6rZPZ8VglIM)
- [Working Group Formation](https://groups.google.com/d/msg/php-fig/A5mZYTn5Jm8/j0FN6eZtBAAJ)
- [Review Period Initiation](https://groups.google.com/d/msg/php-fig/OpUnkrnFhe0/y2dT7CakAQAJ)
- [Acceptance Vote](https://groups.google.com/d/msg/php-fig/M8PapGXXE1E/uBq2Dq-ZAwAJ)

## 8. Relevant Links

_**Note:** Order descending chronologically._

- [PSR-7 Middleware Proposal](https://github.com/php-fig/fig-standards/pull/755)
- [PHP-FIG mailing list discussion of middleware](https://groups.google.com/forum/#!topic/php-fig/vTtGxdIuBX8)
- [ircmaxwell All About Middleware](http://blog.ircmaxell.com/2016/05/all-about-middleware.html)
- [shadowhand All About PSR-7 Middleware](http://shadowhand.me/all-about-psr-7-middleware/)
- [AndrewCarterUK PSR-7 Objects Are Not Immutable](http://andrewcarteruk.github.io/programming/2016/05/22/psr-7-is-not-immutable.html)
- [shadowhand Dependency Inversion and PSR-7 Bodies](http://shadowhand.me/dependency-inversion-and-psr-7-bodies/)
- [PHP-FIG mailing list thread discussing factories](https://groups.google.com/d/msg/php-fig/G5pgQfQ9fpA/UWeM1gm1CwAJ)
- [PHP-FIG mailing list thread feedback on proposal](https://groups.google.com/d/msg/php-fig/piRtB2Z-AZs/8UIwY1RtDgAJ)
