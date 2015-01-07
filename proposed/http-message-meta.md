HTTP Message Meta Document
==========================

1. Summary
----------

The purpose of this proposal is to provide a set of common interfaces for HTTP
messages as described in [RFC 7230](http://tools.ietf.org/html/rfc7230) and
[RFC 7231](http://tools.ietf.org/html/rfc7231).

- RFC 7230: http://www.ietf.org/rfc/rfc7230.txt
- RFC 7231: http://www.ietf.org/rfc/rfc7231.txt

All HTTP messages consist of the HTTP protocol version being used, headers, and
a message body. A _Request_ builds on the message to include the HTTP method
used to make the request, and the URI to which the request is made. A
_Response_ includes the HTTP status code and reason phrase.

In PHP, HTTP messages are used in two contexts:

- To send an HTTP request from a client, such as cURL, a web browser, etc., to
  be fulfilled by a server, which will return an HTTP response. In other words,
  PHP can use HTTP messages when acting as an _HTTP client_.
- To process an incoming HTTP request to a server, and return an HTTP response
  to the client making the request. PHP can use HTTP messages when used as a
  _server-side application_ to fulfill HTTP requests.

This proposal presents an API for describing HTTP messages in PHP in a way
that is as simple as possible while simultaneously producing no limits on
functionality.

2. Why Bother?
--------------

HTTP messages are used in a wide number of PHP projects -- both clients and
servers. In each case, we observe one or more of the following patterns or
situations:

1. Projects will create implementations from scratch.
2. Projects may require a specific HTTP client/server library that provides
   HTTP message implementations.
3. Projects may create adapters for common HTTP message implementations.

As examples:

1. Frameworks such as Symfony and Zend Framework each define HTTP components
   that form the basis of their MVC layers; even small, single-purpose
   libraries such as oauth2-server-php provide and require their own HTTP
   request/response implementations. Guzzle, Buzz, and other HTTP client
   implementations each create their own HTTP message implementations as well.
2. Projects such as Silex and Stack have hard dependencies on Symfony's HTTP
   kernel. Any SDK built on Guzzle has a hard requirement on Guzzle's HTTP message
   implementations.
3. Projects such as Geocoder create redundant [adapters for common
   libraries](https://github.com/geocoder-php/Geocoder/tree/6a729c6869f55ad55ae641c74ac9ce7731635e6e/src/Geocoder/HttpAdapter).

The net result is that projects are not capable of interoperability or
cross-pollination. In order to consume code targeting one framework from
another, the first order of business is building a bridge layer between the
HTTP message implementations. On the client-side, if a particular library does
not have an adapter you can utilize, you need to bridge the request/response
pairs if you wish to use an adapter from another library.

Thus, the goal of this proposal is to abstract both client- and server-side
request and response interfaces in order to promote interoperability between
projects. If projects implement these interfaces, a reasonable level of
compatibility may be assumed when adopting code from different libraries.

It should be noted that the goal of this proposal is not to obsolete the
current interfaces utilized by existing PHP libraries. This proposal is aimed
at interoperability between PHP packages for the purpose of describing HTTP
messages.

3. Scope
--------

### 3.1 Goals

* Provide the interfaces needed for describing HTTP messages.
* Keep the interfaces as minimal as possible.
* Focus on practical applications and usability.
* Ensure that the API does not impose arbitrary limits on HTTP messages. For
  example, some HTTP message bodies can be too large to store in memory, so we
  must account for this.
* Provide useful abstractions both for handling incoming requests for
  server-side applications and sending outgoing requests in HTTP clients.

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

The `MessageInterface` provides accessors for the elements common to all HTTP
messages, whether they are for requests or responses. These elements include:

- HTTP protocol version (e.g., "1.0", "1.1")
- HTTP headers
- HTTP message body

More specific interfaces are used to describe requests and responses, and more
specifically the context of each (client- vs. server-side). These divisions are
partly inspired by existing PHP usage, but also by other languages such as
Ruby's [Rack](https://rack.github.io),
Python's [WSGI](https://www.python.org/dev/peps/pep-0333/),
Go's [http package](http://golang.org/pkg/net/http/),
Node's [http module](http://nodejs.org/api/http.html), etc.

#### Why are there header methods on messages rather than in a header bag?

Moving headers to a "header bag" breaks the
[Law of Demeter](http://en.wikipedia.org/wiki/Law_of_Demeter) and exposes the
internal implementation of a message to its collaborators. In order for
something to access the headers of a message, they need to reach into the
message's header bag (`$message->getHeaders()->getHeader('Foo')`).

Moving headers from messages into an externally mutable "header bag" exposes the
internal implementation of how a message manages its headers, and has a
side-effect that messages are no longer aware of changes to their headers. This
can lead to messages entering into an invalid or inconsistent state.

#### Mutability of messages

The proposal models mutable messages.

Real-world usage in clients requires mutable requests. As an example, most HTTP
clients allow you to modify a request pre-flight in order to implement custom
logic (for example, signing a request, compression, encryption, etc...).
Examples include:

* Guzzle: http://guzzlephp.org/guide/plugins.html
* Buzz: https://github.com/kriswallsmith/Buzz/blob/master/lib/Buzz/Listener/BasicAuthListener.php
* Requests/PHP:  https://github.com/rmccue/Requests/blob/master/docs/hooks.md

This is not just a popular pattern in the PHP community:

* Requests: http://docs.python-requests.org/en/latest/user/advanced/#event-hooks
* Typhoeus: https://github.com/typhoeus/typhoeus/blob/master/lib/typhoeus/request/before.rb
* RestClient: https://github.com/archiloque/rest-client#hook
* Java's HttpClient: http://hc.apache.org/httpcomponents-client-ga/httpclient/examples/org/apache/http/examples/client/ClientGZipContentCompression.java
* etc...

Moreover, most HTTP clients prefer mutable responses for a variety of reasons.
As an example, the client may return the raw response, but allow passing it
through pluggable filters in order to perform actions such as decompression,
file extraction, deserialization, etc. Modeling the messages as immutable would
dictate architecture and limit adoption in these scenarios.

On the server-side, the application will write to the response instance in
order to populate it before sending it back to the client. This is particularly
useful when considering the fact that headers can no longer be emitted once
_any_ output has been sent; aggregating headers and content in a response
object is a useful and typically necessary abstraction.

An argument can be made that server-side requests should be immutable, in order
to reflect the request state at application initialization. However, this argument
fails to address many real-world scenarios:

- PHP itself models the `$_GET`, `$_POST`, and `$_COOKIE` superglobals as mutable.
- `$_POST` only models form-encoded data sent via POST. While this is arguably
  still the most common scenario for web-submitted data, it fails to address the
  very common needs of APIs, which may use methods such as PUT, PATCH, and DELETE
  to submit data, and which are likely submitting JSON or XML. Since content type
  can only be determined by inspecting the request itself, deserialization of
  such body content can only happen within the application layer - suggesting
  that body content parameters must be mutable.
- Cookie encryption, while an arguable practice, is very common in modern
  frameworks. For the practice to work, the cookie data must be mutable.
- Streams, per definition, cannot be immutable.

Cookies, query string arguments, and body parameters can always be
re-calculated from sources such as the `$_SERVER` superglobal, request content
body, or even the URL. As such, we also include server parameters in the
server request interface, but as an immutable property.

One input source cannot be calculated at runtime, however: upload files.
Technically, they _can_, but the logic for doing so is non-trivial, resource
intensive, and prone to error. As such, we also model this input source as
immutable.

We note one element outside these input sources: "attributes". Most server-side
applications utilize processes that match the request to specific criteria --
such as path segments, subdomains, etc. -- and then push the derived matches
back into the request itself. Since these processes need to introspect the
populated request, and are a product of the application itself, the proposal
allows this property to be mutable.

Finally, we note that we chose mutability as a usability concern. Without
mutability, the only way to accomplish some of the above scenarios -- body
parameter deserialization and re-injection, cookie decryption, etc. -- would
be through usage of proxies and decorators. While these concepts are not
terribly difficult to accomplish, they are non-obvious and non-trivial for
a plurality of PHP developers, for whom even basic OOP is often a new or
arcane concept. Having mutable members simplifies usage, and should speed
adoption of the interfaces.

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

The use of a well- defined stream interface allows for the potential of
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

#### Rationale for ServerRequestInterface

The `RequestInterface` and `ResponseInterface` have essentially 1:1
correlations with the request and response messages described in
[RFC 7230](http://www.ietf.org/rfc/rfc7230.txt) They provide interfaces for
implementing value objects that correspond to the specific HTTP message types
they model.

For server-side applications, however, there are other considerations for
incoming requests:

- Access to server parameters (potentially derived from the request, but also
  potentially the result of server configuration, and generally represented
  via the `$_SERVER` superglobal).
- Access to the query string arguments (usually encapsulated in PHP via the
  `$_GET` superglobal).
- Access to body parameters (i.e., data deserialized from the incoming request
  body, and usually encapsulated by PHP in the `$_POST` superglobal).
- Access to uploaded files (usually encapsulated in PHP via the `$_FILES`
  superglobal).
- Access to cookie values (usually encapsulated in PHP via the `$_COOKIE`
  superglobal).
- Access to attributes derived from the request (usually against the URL path).

Uniform access to these parameters increases the viability of interoperability
between frameworks and libraries, as they can now assume that if a request
implements `ServerRequestInterface`, they can get at these values. It also
solves problems within the PHP language itself:

- Until 5.6.0, `php://input` was read-once; as such, instantiating multiple
  request instances from multiple frameworks/libraries could lead to
  inconsistent state, as the first to access `php://input` would be the only
  one to receive the data.
- Unit testing against superglobals (e.g., `$_GET`, `$_FILES`, etc.) is
  difficult and typically brittle. Encapsulating them inside the
  `ServerRequestInterface` implementation eases testing considerations.

The interface as defined marks server and file parameters as _immutable_ as
these are the values provided by PHP at the start of the request. The other
parameters are all _mutable_, either to reflect mutability of the
corresponding superglobal, or because the values can be calculated from the
other input sources composed in the request.

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

#### Why the distinction between URL and base URL?

[RFC 7230, section 5.3](http://tools.ietf.org/html/rfc7230#section-5.3)
indicates that the target of a request can be one of four forms. The first,
"origin-form," is the path and the query string, and often termed the "relative
URL." The next three all include the scheme and host, and, if present, the
authentication information and port; these can be referred to as "absolute
URIs."

For consistency and predictability, we can only return one form from the
`getUrl()` method of a request.

In many cases, proxies, local applications, etc. will not have anything but
a relative URL available, so forcing `getUrl()` to return an absolute URI
would pose a problem; implementors would either need to hard-code the scheme
and host -- and thus effectively return false information -- or raise an
error for what is an expected situation.

For server-side applications, however, not returning the scheme, port, and
host can also be problematic. These values often need to be calculated from
the server environment (e.g., when the server is behind a proxy; when using
URL rewriting on some server environments; etc.), and the messages should
abstract these operations as much as possible.

The solution presented is to have a separate "base URL" property for holding
the other URL artifacts. This allows using the messages in application
environments where the absolute URI is unknown or un-needed, while providing
the information when it is desired.

5. People
---------

### 5.1 Editor(s)

* Matthew Weier O'Phinney

### 5.2 Sponsors

* Paul M. Jones
* Beau Simensen (coordinator)

### 5.3 Contributors

* Michael Dowling
* Phil Sturgeon
* Chris Wilkinson
