HTTP Message Meta Document
==========================

1. Summary
----------

The purpose of this proposal is to provide a set of common interfaces for HTTP
messages as described in [RFC 7230] and [RFC 7231].

[RFC 7230]: http://www.ietf.org/rfc/rfc7230.txt
[RFC 7231]: http://www.ietf.org/rfc/rfc7231.txt

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
bloating their dependencies, or projects needing to create redundant
[adapters for common libraries](https://github.com/geocoder-php/Geocoder/tree/6a729c6869f55ad55ae641c74ac9ce7731635e6e/src/Geocoder/HttpAdapter).

It should be noted that the goal of this proposal is not to obsolete the
current interfaces utilized by existing PHP libraries. This proposal is aimed
at interoperability between PHP packages for the purpose of describing HTTP
messages.

3. Scope
--------

### 3.1 Goals

* Provide the interfaces needed for describing HTTP messages.
* Keep the interfaces as minimal as possible.
* Ensure that the API does not impose arbitrary limits on HTTP messages. For
  example, some HTTP message bodies can be too large to store in memory, so we
  must account for this.
* Provide useful abstractions for handling incoming requests for server-side
  applications.

### 3.2 Non-Goals

* This proposal does not expect all HTTP client libraries or server-side
  frameworks to change their interfaces to conform. It is strictly meant for
  interoperability.
* While everyone's perception of what is and is not an implementation detail
  varies, this proposal should not impose implementation details. However,
  because RFC 7230 and RFC 7231 do not force any particular implementation,
  there will be a certain amount of invention needed to describe HTTP message
  interfaces in PHP.

4. Design Decisions
-------------------

### Message design

The design of the `MessageInterface`, `RequestInterface`,
`IncomingRequestInterface`, and `ResponseInterface` interfaces are based on
existing projects in the PHP community, as well as influences from other
languages/frameworks, including Python, Ruby, Node, and Java.

#### Why are there header methods on messages rather than in a header bag?

Moving headers to a "header bag" breaks the Law of Demeter and exposes the
internal implementation of a message to its collaborators. In order for
something to access the headers of a message, they need to reach into the
message's header bag (`$message->getHeaders()->getHeader('Foo')`).

Moving headers from messages into an externally mutable "header bag" exposes the
internal implementation of how a message manages its headers, and has a
side-effect that messages are no longer aware of changes to their headers. This
can lead to messages entering into an invalid or inconsistent state.

#### Mutability of messages

Headers and messages are mutable to reflect real-world usage in clients and
server-side applications. A large number of HTTP clients allow you to modify a
request pre-flight in order to implement custom logic (for example, signing a
request, compression, encryption, etc...). Examples include:

* Guzzle: http://guzzlephp.org/guide/plugins.html
* Buzz: https://github.com/kriswallsmith/Buzz/blob/master/lib/Buzz/Listener/BasicAuthListener.php
* Requests/PHP:  https://github.com/rmccue/Requests/blob/master/docs/hooks.md

This is not just a popular pattern in the PHP community:

* Requests: http://docs.python-requests.org/en/latest/user/advanced/#event-hooks
* Typhoeus: https://github.com/typhoeus/typhoeus/blob/master/lib/typhoeus/request/before.rb
* RestClient: https://github.com/archiloque/rest-client#hook
* Java's HttpClient: http://hc.apache.org/httpcomponents-client-ga/httpclient/examples/org/apache/http/examples/client/ClientGZipContentCompression.java
* etc...

On the server-side, the application will write to the response instance in order
to populate it before sending it back to the client. Additionally, many aspects
of the request must be mutable:

* Body parameters are often "discovered" via deserialization of the incoming
  request body, and the serialization method will need to be determined by
  introspecting incoming `Content-Type` headers.
* Cookies may be encrypted, and a process may decrypt them and re-inject them
  into the request for later collaborators to access.
* Routing and other tools are often used to "discover" request attributes (e.g.,
  decomposing the URL `/user/phil` to assign the value "phil" to the attribute
  "user"). Such logic is application-specific, but still considered part of the
  request state; it can only be injected after instantiation.

Each of the above, as well as other activities, require mutable incoming request
objects.

Having mutable and immutable messages would add a significant amount of
complexity to a HTTP message PSR and would not reflect what is currently being
used by a majority of PHP projects.

### Using streams instead of X

`MessageInterface` uses a body value that must implement `StreamableInterface`. This
design decision was made so that developers can send and receive (and/or receive
and send) HTTP messages that contain more data than can practically be stored in
memory while still allowing the convenience of interacting with message bodies
as a string. While PHP provides a stream abstraction by way of stream wrappers,
stream resources can be cumbersome to work with: stream resources can only be
cast to a string using `stream_get_contents()` or manually reading the remainder
of a string. Adding custom behavior to a stream as it is consumed or populated
requires registering a stream filter; however, stream filters can only be added
to a stream after the filter is registered with PHP (i.e., there is no stream
filter autoloading mechanism).

The use of a very well defined stream interface allows for the potential of
flexible stream decorators that can be added to a request or response
pre-flight to enable things like encryption, compression, ensuring that the
number of bytes downloaded reflects the number of bytes reported in the
`Content-Length` of a response, etc. Decorating streams is a well-established
[pattern in the Java](http://docs.oracle.com/javase/7/docs/api/java/io/package-tree.html)
and [Node](http://nodejs.org/api/stream.html#stream_class_stream_transform_1)
communities that allows for very flexible streams.

The majority of the `StreamableInterface` API is based on
[Python's io module](http://docs.python.org/3.1/library/io.html), which provides
a practical and consumable API. Instead of implementing stream
capabilities using something like a `WritableStreamInterface` and
`ReadableStreamInterface`, the capabilities of a stream are provided by methods
like `isReadable()`, `isWritable()`, etc. This approach is used by Python,
[C#, C++](http://msdn.microsoft.com/en-us/library/system.io.stream.aspx),
[Ruby](http://www.ruby-doc.org/core-2.0.0/IO.html),
[Node](http://nodejs.org/api/stream.html), and likely others.

#### Rationale for IncomingRequestInterface

The base interfaces, `RequestInterface` and `ResponseInterface`, have 1:1
correlations with the messages described in [RFC 7230](http://www.ietf.org/rfc/rfc7230.txt)
They provide interfaces for implementing value objects that correspond to each
of those HTTP message types.

For server-side applications, however, there are other considerations for
incoming requests:

- Access to the query string arguments (usually encapsulated in PHP via the
  `$_GET` superglobal).
- Access to body parameters (i.e., data deserialized from the incoming request
  body, and usually encapsulated by PHP in the `$_POST` superglobal).
- Access to uploaded files (usually encapsulated in PHP via the `$_FILES`
  superglobal).
- Access to cookie values (usually encapsulated in PHP via the `$_COOKIE`
  superglobal).
- Access to parameters derived from the request (usually against the URL path).

Uniform access to these parameters increases the viability of interoperability
between frameworks and libraries, as they can now assume that if a request
implements `IncomingRequestInterface`, they can get at these values. It also
solves problems within the PHP language itself:

- Until 5.6.0, `php://input` was read-once; as such, instantiating multiple
  request instances from multiple frameworks/libraries could lead to
  inconsistent state, as the first to access `php://input` would be the only
  one to receive the data.
- Unit testing against superglobals (e.g., `$_GET`, `$_FILES`, etc.) is
  difficult and typically brittle. Encapsulating them inside the
  `IncomingRequestInterface` implementation eases testing considerations.

The interface as defined only provides mutators for body parameters, cookies,
and derived attributes. The assumption is that all other values either (a) may be
injected at instantiation from superglobals, or (b) should not change over the
course of the incoming request.

- Body parameters are excluded as `$_POST` is only valid for HTTP POST requests
  specifically of the Content-Type `application/x-www-form-urlencoded`; any
  other request method or Content-Type requires that the application perform
  content negotiation to map the Content-Type to a deserialization strategy. As
  such, body parameters will generally need to be injected manually by the
  application.
- A growing security trend involves encrypting cookie values. As such, the
  application will often have a layer that decrypts and validates the cookie
  value, and then re-injects the discovered value. This practice requires that
  cookie parameters in the request be mutable. (For one example, see the
  [Laravel cookie implementation](http://laravel.com/docs/4.2/requests#cookies).)
- Derived attributes will vary based on the application logic (in particular,
  routing), and are the result of inspecting the request; as such, they must be
  mutable.

#### What about "special" header values?

A number of header values contain unique representation requirements which can
pose problems both for consumption as well as generation; in particular, cookies
and the Accept header.

This proposal does not provide any special treatment of any header types. The
base `MessageInterface` provides methods for header retrieval and setting, and
all header values are, in the end, string values.

Developers are encouraged to write commodity libraries for interacting with
these header values, either for the purposes of parsing or generation. Users may
then consume these libraries when needing to interact with those values.
Examples of this practice already exist in libraries such as
[willdurand/Negotiation](https://github.com/willdurand/Negotiation) and
[aura/accept](https://github.com/pmjones/Aura.Accept). So long as the object
has functionality for casting the value to a string, these objects can be
used to populate the headers of an HTTP message.

5. People
---------

### 5.1 Editor(s)

* Michael Dowling
* Matthew Weier O'Phinney

### 5.2 Sponsors

* Phil Sturgeon (coordinator)
* Beau Simensen

### 5.3 Contributors

* Chris Wilkinson
