PSR-17 Meta Document
====================

1. Summary
----------

The purpose of this PSR is to provide factory interfaces that define methods to
create PSR-7 objects.

2. Why Bother?
--------------

The current specification for PSR-7 allows for most objects to be modified by
creating immutable copies. However, there are two notable exceptions:

* `StreamInterface` is a mutable object based on a resource that only allows
  the resource to be written to when the resource is writable.
* `UploadedFileInterface` is a read-only object based on a resource that offers
  no modification capabilities.

The former is a significant pain point for PSR-7 middleware, as it can leave
the response in an incomplete state. If the stream attached to the response body
is not seekable or not writable, there is no way to recover from an error
condition in which the body has already been written too.

This scenario can be avoided by providing a factory to create new streams. Due to
the lack of formal standard for HTTP object factories, a developer must rely on
a specific vendor implementation in order to create these objects. Creating a
formal standard for factories will allow for developers to avoid dependency on
specific implementations while having the ability to create new objects when
necessary.

3. Scope
--------

## 3.1 Goals

* Provide a set of interfaces that define methods to create PSR-7 compatible objects.

## 3.2 Non-Goals

* Provide a specific implementation of PSR-7 factories.

4. Approaches
-------------

### 4.1 Chosen Approach

The factory method definition has been chosen based on whether or not the object
can be modified after instantiation. For interfaces that cannot be modified, all
of the object properties must be defined at the time of instantiation.

In the case of `UriInterface` a complete URI may be passed for convenience.

The method names used will not conflict. This allows for a single class to
implement multiple interfaces when appropriate.

#### 4.2 Existing Implementations

All of the current implementations of PSR-7 have defined their own requirements.
In most cases, the required parameters the same or less strict than the proposed
factory methods.

##### 4.2.1 Diactoros

Zend Diactoros is currently the most popular HTTP Messages implementation for
server usage.

- [`Request`][diactoros-request] No required parameters, method and URI default to `null`.
- [`Response`][diactoros-response] No required parameters, status code defaults to `200`.
- [`ServerRequest`][diactoros-server-request] No required parameters. Contains a separate
  [`ServerRequestFactory`][diactoros-server-request-factory] for creating requests from globals.
- [`Stream`][diactoros-stream] Requires `string|resource $stream` for the body.
- [`UploadedFile`][diactoros-uploaded-file] Requires `string|resource $streamOrFile`, `int $size`,
  `int $errorStatus`. Error status must be a PHP upload constant.
- [`Uri`][diactoros-uri] No required parameters, `string $uri` is empty by default.

[diactoros-request]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/Request.php#L33)
[diactoros-response]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/Response.php#L114
[diactoros-server-request]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/ServerRequest.php#L78-L89
[diactoros-server-request-factory]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/ServerRequestFactory.php#L52-L58
[diactoros-stream]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/Stream.php#L36
[diactoros-uploaded-file]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/UploadedFile.php#L62
[diactoros-uri]: https://github.com/zendframework/zend-diactoros/blob/b4e7758556c97b5bb9a5260d898e9788ee800538/src/Uri.php#L94

Overall this approach is quite similar to the proposed factories. In some cases,
more options are given by Diactoros which are not required for a valid object.
The proposed uploaded file factory allows for size and error status to be optional.

##### 4.2.2 Guzzle

Guzzle is currently the most popular HTTP Messages implementation for client usage.

- [`Request`][guzzle-request] Requires both `string $method` and `string|UriInterface $uri`.
- [`Response`][guzzle-response] No required parameters, status code defaults to `200`.
- [`Stream`][guzzle-stream] Requires `resource $stream` for the body.
- [`Uri`][guzzle-uri] No required parameters, `string $uri` is empty by default.

_Being geared towards client usage, Guzzle does not contain a `ServerRequest` or
`UploadedFile` implementation._

[guzzle-request]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/Request.php#L32-L38
[guzzle-response]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/Response.php#L88-L94
[guzzle-stream]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/Stream.php#L51
[guzzle-uri]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/Uri.php#L48

Overall this approach is also quite similar to the proposed factories. One notable
difference is that Guzzle requires streams to be constructed with a resource and
does not allow a string. However, it does contain a helper function [`stream_for`][guzzle-stream-for]
that will create a stream from a string of content and a function [`try_fopen`][guzzle-try-fopen]
that create a resource from a file path.

[guzzle-stream-for]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/functions.php#L78
[guzzle-try-fopen]: https://github.com/guzzle/psr7/blob/58828615f7bb87013ce6365e9b1baa08580c7fc8/src/functions.php#L295

##### 4.2.3 Slim

Slim is a popular micro-framework that makes use of HTTP Messages from version
3.0 forward.

- [`Request`][slim-request] Requires `string $method`, `UriInterface $uri`,
  `HeadersInterface $headers`, `array $cookies`, `array $serverParams`, and
  `StreamInterface $body`. Contains a factory method `createFromEnvironment(Environment $environment)`
  that is framework specific but analogous to the proposed `createServerRequestFromGlobals`.
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

[slim-request]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/Request.php#L170-L178
[slim-response]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/Response.php#L123
[slim-stream]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/Stream.php#L96
[slim-uploaded-file]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/UploadedFile.php#L151
[slim-uri]: https://github.com/slimphp/Slim/blob/30cfe3c07dac28ec1129c0577e64b90ba11a54c4/Slim/Http/Uri.php#L112-L121

Of the compared approaches, Slim is most different from the proposed factories.
Most notably, the `Request` implementation contains requirements that specific
to the framework and are not defined in HTTP Messages specification. The factory
methods that are included are generally similar with the proposed factories.

#### 4.3 Potential Issues

The most difficult part of defining the method signatures for the interfaces.
As there is no clear declaration in PSR-7 as to what values are explicitly
required, the properties that are read only must be inferred based on whether
the interfaces have methods to copy-and-modify the object.

5. People
---------

### 5.1 Editor(s)

* Woody Gilk, <woody.gilk@gmail.com>

### 5.2 Sponsors

* Roman Tsjupa, <draconyster@gmail.com> (Coordinator)
* Paul M Jones, <pmjones88@gmail.com>

### 5.3 Contributors

* Rasmus Schultz, <rasmus@mindplay.dk>

6. Votes
--------

* [Entrance Vote](https://groups.google.com/forum/#!topic/php-fig/6rZPZ8VglIM)
* **Acceptance Vote:** _(not yet taken)_

7. Relevant Links
-----------------

_**Note:** Order descending chronologically._

* [PSR-7 Middleware Proposal](https://github.com/php-fig/fig-standards/pull/755)
* [PHP-FIG mailing list discussion of middleware](https://groups.google.com/forum/#!topic/php-fig/vTtGxdIuBX8)
* [ircmaxwell All About Middleware](http://blog.ircmaxell.com/2016/05/all-about-middleware.html)
* [shadowhand All About PSR-7 Middleware](http://shadowhand.me/all-about-psr-7-middleware/)
* [AndrewCarterUK PSR-7 Objects Are Not Immutable](http://andrewcarteruk.github.io/programming/2016/05/22/psr-7-is-not-immutable.html)
* [shadowhand Dependency Inversion and PSR-7 Bodies](http://shadowhand.me/dependency-inversion-and-psr-7-bodies/)
* [PHP-FIG mailing list thread discussing factories](https://groups.google.com/d/msg/php-fig/G5pgQfQ9fpA/UWeM1gm1CwAJ)
* [PHP-FIG mailing list thread feedback on proposal](https://groups.google.com/d/msg/php-fig/piRtB2Z-AZs/8UIwY1RtDgAJ)
