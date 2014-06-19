HTTP Message Meta Document
==========================

1. Summary
----------

The purpose of this proposal is to provide a set of common interfaces for HTTP
messages as described in [RFC 7230].

[RFC 7230]: http://www.ietf.org/rfc/rfc7230.txt

2. Why Bother?
--------------

This proposal presents an API for describing HTTP messages in PHP in a way
that is as simple as possible and does not limit functionality.

HTTP messages are used in a wide number of PHP projects-- both clients and
servers. PHP applications often can rely on specific packages and do not
require a means for utilizing arbitrary HTTP messages. Projects that need to
utilize HTTP messages but do not necessarily have a hard requirement on any
particular library often take one of the following approaches:

1. Create a very minimal implementation from scratch.
2. Force developers to use a specific HTTP client/server library that provides
   HTTP message interfaces.
3. Create adapters for common HTTP message implementations.

While these are all valid approaches, this can lead to projects unnecessarily
bloating a their dependencies or projects needing to create redundant
[adapters for common libraries.](https://github.com/geocoder-php/Geocoder/tree/6a729c6869f55ad55ae641c74ac9ce7731635e6e/src/Geocoder/HttpAdapter).

It should be noted that the goal of this proposal is not to obsolete the
current interfaces utilized by existing PHP libraries. This proposal is aimed
at interoperability between PHP packages for the purpose of describing HTTP
messages.

3. Scope
--------

## 3.1 Goals

* Provide the interfaces needed for describing HTTP messages.
* Keep the interfaces as minimal as possible.
* Ensure that the API does not impose arbitrary limits on HTTP messages. For
  example, some HTTP message bodies can be too large to store in memory, so we
  must account for this.

## 3.2 Non-Goals

* This proposal does not expect all HTTP client libraries or server side
  frameworks to change their interfaces to conform. It is strictly meant for
  interoperability.
* While everyone's perception of what is and is not an implementation detail
  varies, this proposal should not impose implementation details. However,
  because RFC 2616 does not force any particular implementation, there will be
  a certain amount of invention needed to describe HTTP message interfaces in
  PHP.

4. Design Decisions
-------------------

## Message design

The design of the `MessageInterface`, `RequestInterface`, and `ResponseInterface` interfaces are based on existing projects in the PHP community.

### Why are there header methods on messages rather than in a header bag?

Moving headers to a "header bag" breaks the Law of Demeter and exposes the
internal implementation of a message to its collaborators. In order for
something to access the headers of a message, they need to reach into the the
message's header bag (`$message->getHeaders()->getHeader('Foo')`).

Moving headers from messages into an externally mutable "header bag" exposes the
internal implementation of how a message manages its headers an has a
side-effect that messages are no longer aware of changes to their headers. This
can lead to messages entering into an invalid or inconistent state.

### Mutability of messages

Headers and messages are mutable to reflect real-world usage in clients. A
large number of HTTP clients allow you to modify a request pre-flight in
order to implement custom logic (for example, signing a request, compression,
encryption, etc...).

* Guzzle: http://guzzlephp.org/guide/plugins.html
* Buzz: https://github.com/kriswallsmith/Buzz/blob/master/lib/Buzz/Listener/BasicAuthListener.php
* Requests/PHP:  https://github.com/rmccue/Requests/blob/master/docs/hooks.md

This is not just a popular pattern in the PHP community:

* Requests: http://docs.python-requests.org/en/latest/user/advanced/#event-hooks
* Typhoeus: https://github.com/typhoeus/typhoeus/blob/master/lib/typhoeus/request/before.rb
* RestClient: https://github.com/archiloque/rest-client#hook
* Java's HttpClient: http://hc.apache.org/httpcomponents-client-ga/httpclient/examples/org/apache/http/examples/client/ClientGZipContentCompression.java
* etc...

Having mutable and immutable messages would add a significant amount of
complexity to a HTTP message PSR and would not reflect what is currently being
used by a majority of PHP projects.

## Using streams instead of X

`MessageInterface` uses a body value that must implement `StreamInterface`. This
design decision was made so that developers can send and receive HTTP messages
that contain more data than can practically be stored in memory while still
allowing the convenience of interacting with message bodies as a string. While
PHP provides a stream abstraction by way of stream wrappers, stream resoures
can be cumbersome to work with: stream resources can only be cast to a string
using `stream_get_contents()` or manually reading the remainder of a string.
Adding custom behavior to a stream as it is consumed or populated requires
registering a stream filter; however, stream filters can only be added to a
stream after the filter is registered with PHP (i.e., there is no stream filter
autoloading mechanism).

The use of a very well defined stream interface allows for the potential of
flexible stream decorators that can be added to a request or response
pre-flight to enable things like encryption, compression, ensuring that the
number of bytes downloaded reflects the number of bytes reported in the
`Content-Length` of a response, etc... Decorating streams is a well-established
[pattern in the Java community](http://docs.oracle.com/javase/7/docs/api/java/io/package-tree.html)
that allows for very flexible streams.

The majority of the `StreamInterface` API is based on
[Python's io module](http://docs.python.org/3.1/library/io.html) which provides
a practical and easy to work with API. Instead of implementing stream
capabilities using something like a `WritableStreamInterface` and
`ReadableStreamInterface`, the capabilities of a stream are provided by methods
like `isReadable()`, `isWritable()`, etc... This approach is used by Python,
[C#, C++](http://msdn.microsoft.com/en-us/library/system.io.stream.aspx),
[Ruby](http://www.ruby-doc.org/core-2.0.0/IO.html), and likely others.

5. People
---------

### 5.1 Editor(s)

* Michael Dowling

### 5.2 Sponsors

* Phil Sturgeon (coordinator)
* Beau Simensen

### 5.3 Contributors

* Chris Wilkinson
