HTTP Client Meta Document
=========================

## Summary

HTTP requests and responses are the two fundamental objects in web programming.
All clients communicating to an external API use some form of HTTP client. Many
libraries are coupled to one specific client or implement a client and/or
adapter layer themselves. This leads to bad library design, version conflicts,
or code unrelated to the library domain.

## Why bother?

Thanks to PSR-7 we know how HTTP requests and responses ideally look, but nothing
defines how a request should be sent and a response received. A common interface for HTTP
clients will allow libraries to be decoupled from specific implementations.

## Scope

### Goals

* A common interface for sending PSR-7 messages and returning PSR-7 responses.

### Non-Goals

* Support for asynchronous HTTP requests is left for another future PSR.
* This PSR does not define how to configure an HTTP client. It only specifies the
  default behaviours.
* This PSR is neutral about the use of middleware.

#### Asynchronous HTTP client

The reason asynchronous requests are not covered by this PSR is the lack of a
common standard for Promises. Promises are sufficiently complex enough that they
deserve their own specification, and should not be wrapped into this one.

A separate interface for asynchronous requests can be defined in a separate PSR
once a Promise PSR is accepted. The method signature for asynchronous requests
MUST be different from the method signature for synchronous requests because
the return type of asynchronous calls will be a Promise. Thus this PSR is forwards
compatible, and clients will be able to implement one or both interfaces based
on the features they wish to expose.

## Approaches

### Default behavior

The intention of this PSR is to provide library developers with HTTP clients that
have a well defined behaviour. A library should be able to use any compliant client
without special code to handle client implementation details (Liskov substitution
principle). The PSR does not try to restrict nor define how to configure HTTP clients.

An alternative approach would be to pass configuration to the client. That approach
would have a few drawbacks:

* Configuration must be defined by the PSR.
* All clients must support the defined configuration.
* If no configuration is passed to the client, the behavior is unpredictable.

### Exception Model

The domain exceptions `NetworkException` and `RequestException` define
a contract very similar to each other. The chosen approach is to not let them extend each other
because inheritance does not make sense in the domain model. A `RequestException` is simply not a
`NetworkException`.

Allowing exceptions to extend a `RequestAwareException` and/or `ResponseAwareException` interface
has been discussed but that is a convenience shortcut that one should not take. One should rather
catch the specific exceptions and handle them accordingly.

One could be more granular when defining exceptions. For example, `TimeOutException` and `HostNotFoundException`
could be subtypes of `NetworkException`. The chosen approach is not to define such subtypes because
the exception handling in a consuming library would in most cases not be different between those exceptions.

#### Throwing exceptions for 4xx and 5xx responses

The initial idea was to allow the client to be configured to throw exceptions for responses
with HTTP status 4xx and 5xx. That approach is not desired because consuming libraries would
have to check for 4xx and 5xx responses twice: first, by verifying the status code on the response,
and second by catching potential exceptions.

To make the specification more predictable, it was decided that HTTP clients never will throw
exceptions for 4xx and 5xx responses.

### Background

The HTTP client PSR has been inspired and created by the [php-http team](https://github.com/orgs/php-http/people).
Back in 2015, they created HTTPlug as a common interface for HTTP clients. They wanted an
abstraction that third party libraries could use so as not to rely on a specific HTTP client
implementation. A stable version was tagged in January 2016, and the project has been 
widely adopted since then. With over 3 million downloads in the two years
following the initial stable version, it was time to convert this "de-facto"
standard into a formal specification.

## People

### 5.1 Editor

* Tobias Nyholm

### 5.2 Sponsors

* Sara Golemon

### 5.3 Workgroup

* Simon Asika (Windwalker)
* David Buchmann (HTTPlug)
* David De Boer (HTTPlug)
* Sara Golemon (Sponsor)
* Jeremy Lindblom (Guzzle)
* Christian Lück (Buzz react)
* Tobias Nyholm (Editor)
* Matthew Weier O'Phinney (Zend)
* Mark Sagi-Kazar (Guzzle)
* Joel Wurtz (HTTPlug)
