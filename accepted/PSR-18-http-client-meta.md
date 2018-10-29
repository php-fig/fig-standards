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

#### Naming rationale

The main interface behaviour is defined by the method `sendRequest(RequestInterface $request): ResponseInterface`.  
While the shorter method name `send()` has been proposed, this was already used by existing and very common HTTP clients like Guzzle. As such, if they are to adopt this standard, they may need to break backwards compatibility in order to implement the specification. By defining `sendRequest()` instead, we ensure they can adopt without any immediate BC breaks.

### Exception Model

The domain exceptions `NetworkExceptionInterface` and `RequestExceptionInterface` define
a contract very similar to each other. The chosen approach is to not let them extend each other
because inheritance does not make sense in the domain model. A `RequestExceptionInterface` is simply not a
`NetworkExceptionInterface`.

Allowing exceptions to extend a `RequestAwareException` and/or `ResponseAwareException` interface
has been discussed but that is a convenience shortcut that one should not take. One should rather
catch the specific exceptions and handle them accordingly.

One could be more granular when defining exceptions. For example, `TimeOutException` and `HostNotFoundException`
could be subtypes of `NetworkExceptionInterface`. The chosen approach is not to define such subtypes because
the exception handling in a consuming library would in most cases not be different between those exceptions.

#### Throwing exceptions for 4xx and 5xx responses

The initial idea was to allow the client to be configured to throw exceptions for responses
with HTTP status 4xx and 5xx. That approach is not desired because consuming libraries would
have to check for 4xx and 5xx responses twice: first, by verifying the status code on the response,
and second by catching potential exceptions.

To make the specification more predictable, it was decided that HTTP clients never will throw
exceptions for 4xx and 5xx responses.

## Middleware and wrapping a client

The specification does not put any limitations on middleware or classes that want 
to wrap/decorate an HTTP client. If the decorating class also implements `ClientInterface`
then it must also follow the specification. 

It is temping to allow configuration or add middleware to an HTTP client so it could i.e.
follow redirects or throw exceptions. If that is a decision from an application developer, 
they have specifically said they want to break the specification. That is an issue (or feature)
the application developer should handle. Third party libraries MUST NOT assume that
a HTTP client breaks the specification.

## Background

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

## Votes

* [Entrance vote](https://groups.google.com/d/topic/php-fig/MJGYRXfUJGk/discussion)
* [Review Period Initiation](https://groups.google.com/d/topic/php-fig/dV9zIaOooZ4/discussion)
* [Acceptance](https://groups.google.com/d/topic/php-fig/rScdiW38nLM/discussion)

## Proposed implementations

Below are the two implementations provided by the working group to pass the review period:

 * HTTPlug has prepared a 2.0 to make sure it is supporting the new PSR. 
   They are just waiting for the PSR to be released: https://github.com/php-http/httplug/tree/2.x
 * Buzz has been adapting to every version of the PSR and has their 0.17.3 release with the latest 
   version of psr/http-client: https://github.com/kriswallsmith/Buzz
