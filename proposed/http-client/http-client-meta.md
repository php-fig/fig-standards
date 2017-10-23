HTTP Client Meta Document
=========================


## Summary

HTTP request and responses are the two fundamental objects in web programming.
All clients communicating to an external API use some form of HTTP client. Many
libraries are coupled to one specific client or implement a client and/or adapter
layer themselves. This leads to bad library design, version conflicts or too much
code not related to the library domain.


## Why bother?

Thanks to PSR-7 we know how HTTP requests and responses ideally look like, but nothing
defines how a request should be sent and a response received. A common interface for HTTP
client will allow libraries to be decoupled from an implementation such as Guzzle.


## Scope


### Goals

* A common interface for sending PSR-7 messages.


### Non-Goals

* The purpose of this PSR is not to support asynchronous HTTP clients.
* This PSR will not include how to configure a HTTP client. It does only
specify the default behaviours.
* The purpose is not to be opinionated about the use of middlewares (PSR-15).


## Approaches

### Default behavior

The intention of this PSR is ensure library developers that all HTTP clients have the same 
**default behavior**. That means that all HTTP clients MUST follow Liskov substitution principle
when no configuration is provided. The PSR does not try to restrict nor define configuration for 
HTTP clients. An implementing library is free to be configured by the application author to follow
redirects, to throw exceptions or any other possible setting.  

An alternative approach would be to pass configuration to the client. That approach would have
a few drawbacks: 

* Configuration must be defined by the PSR
* All client must support the defined configuration
* If no configuration are passed to the client, the behavior will not be predictable

### Exceptions

The domain exceptions `NetworkException`, `RequestException` and `HttpException` define
a contract very similar to each other. The chosen approach is to not let them extend each other
because inheritance does not make sense in the domain model. A `RequestException` is not a
`NetworkException`.

Allowing exception to extend a `RequestAwareException` and/or `ResponseAwareException` interface
has been discussed but that is a convenience shortcut that one should not take. One should rather
catch the specific exceptions and handle them accordingly.

One could be more granular when defining exception. For example, `TimeOutException` and `HostNotFoundException`
could be subtypes of `NetworkException`. The chosen approach is not to define such subtypes because
the exception handling in a consuming library would not be different between those exceptions. 

### Background

The HTTP client PSR has been inspired and created by the [php-http team](https://github.com/orgs/php-http/people). 
Back in 2015 they created HTTPlug which was an interface for HTTP clients. 
They wanted an abstraction that third party libraries can use to not rely
on a specific HTTP client implementation like Guzzle 5, Guzzle 6 or Buzz.
A stable version was tagged in January 2016 and the project became widely 
popular short there after. With over 3 million downloads the next
two years it was time to convert this "de-facto" standard into a real PSR. 

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
* Jermey Lindstrom (Guzzle)
* Christian Lück (Buzz react)
* Tobias Nyholm (Editor)
* Matthew O'Phinney (Zend)
* Mark Sagi-Kazar (Guzzle)
* Joel Wurtz (HTTPlug)



