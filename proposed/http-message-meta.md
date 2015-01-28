HTTP Message Meta Document
==========================

1. Summary
----------

The purpose of this proposal is to provide a set of common interfaces for HTTP
messages as described in [RFC 7230](http://tools.ietf.org/html/rfc7230) and
[RFC 7231](http://tools.ietf.org/html/rfc7231), and URIs as described in
[RFC 3986](http://tools.ietf.org/html/rfc3986) (in the context of HTTP messages).

- RFC 7230: http://www.ietf.org/rfc/rfc7230.txt
- RFC 7231: http://www.ietf.org/rfc/rfc7231.txt
- RFC 3986: http://www.ietf.org/rfc/rfc3986.txt

All HTTP messages consist of the HTTP protocol version being used, headers, and
a message body. A _Request_ builds on the message to include the HTTP method
used to make the request, and the URI to which the request is made. A
_Response_ includes the HTTP status code and reason phrase.

In PHP, HTTP messages are used in two contexts:

- To send an HTTP request, via the `ext/curl` extension, PHP's native stream
  layer, etc., and process the received HTTP response. In other words, HTTP
  messages are used when using PHP as an _HTTP client_.
- To process an incoming HTTP request to the server, and return an HTTP response
  to the client making the request. PHP can use HTTP messages when used as a
  _server-side application_ to fulfill HTTP requests.

This proposal presents an API for fully describing all parts of the various
HTTP messages within PHP.

2. HTTP Messages in PHP
-----------------------

PHP does not have built-in support for HTTP messages.

### Client-side HTTP support

PHP supports sending HTTP requests via several mechanisms:

- [PHP streams](http://php.net/streams)
- The [cURL extension](http://php.net/curl)
- [ext/http](http://php.net/http) (v2 also attempts to address server-side support)

PHP streams are the most convenient and ubiquitous way to send HTTP requests,
but pose a number of limitations with regards to properly configuring SSL
support, and provide a cumbersome interface around setting things such as
headers. cURL provides a complete and expanded feature-set, but, as it is not a
default extension, is often not present. The http extension suffers from the
same problem as cURL, as well as the fact that it has traditionally had far
fewer examples of usage.

Most modern HTTP client libraries tend to abstract the implementation, to
ensure they can work on whatever environment they are executed on, and across
any of the above layers.

### Server-side HTTP Support

PHP uses Server APIs (SAPI) to interpret incoming HTTP requests, marshal input,
and pass off handling to scripts. The original SAPI design mirrored [Common
Gateway Interface](http://www.w3.org/CGI/), which would marshal request data
and push it into environment variables before passing delegation to a script;
the script would then pull from the environment variables in order to process
the request and return a response.

PHP's SAPI design abstracts common input sources such as cookies, query string
arguments, and url-encoded POST content via superglobals (`$_COOKIE`, `$_GET`,
and `$_POST`, respectively), providing a layer of convenience for web developers.

On the response side of the equation, PHP was originally developed as a
templating language, and allows intermixing HTML and PHP; any HTML portions of
a file are immediately flushed to the output buffer. Modern applications and
frameworks, however, eschew this practice, as it can lead to issues with
regards to emitting a status line and/or response headers; they tend to
aggregate all headers and content, and emit them at once when all other
application processing is complete. Special care needs to be paid to ensure
that error reporting and other actions that send content to the output buffer
do not flush the output buffer.

3. Why Bother?
--------------

HTTP messages are used in a wide number of PHP projects -- both clients and
servers. In each case, we observe one or more of the following patterns or
situations:

1. Projects use PHP's superglobals directly.
2. Projects will create implementations from scratch.
3. Projects may require a specific HTTP client/server library that provides
   HTTP message implementations.
4. Projects may create adapters for common HTTP message implementations.

As examples:

1. Just about any application that began development before the rise of
   frameworks, which includes a number of very popular CMS, forum, and shopping
   cart systems, have historically used superglobals.
2. Frameworks such as Symfony and Zend Framework each define HTTP components
   that form the basis of their MVC layers; even small, single-purpose
   libraries such as oauth2-server-php provide and require their own HTTP
   request/response implementations. Guzzle, Buzz, and other HTTP client
   implementations each create their own HTTP message implementations as well.
3. Projects such as Silex, Stack, and Drupal 8 have hard dependencies on
   Symfony's HTTP kernel. Any SDK built on Guzzle has a hard requirement on
   Guzzle's HTTP message implementations.
4. Projects such as Geocoder create redundant [adapters for common
   libraries](https://github.com/geocoder-php/Geocoder/tree/6a729c6869f55ad55ae641c74ac9ce7731635e6e/src/Geocoder/HttpAdapter).

Direct usage of superglobals has a number of concerns. First, these are
mutable, which makes it possible for libraries and code to alter the values,
and thus alter state for the application. Additionally, superglobals make unit
and integration testing difficult and brittle, leading to code quality
degradation.

In the current ecosystem of frameworks that implement HTTP message abstractions,
the net result is that projects are not capable of interoperability or
cross-pollination. In order to consume code targeting one framework from
another, the first order of business is building a bridge layer between the
HTTP message implementations. On the client-side, if a particular library does
not have an adapter you can utilize, you need to bridge the request/response
pairs if you wish to use an adapter from another library.

Finally, when it comes to server-side responses, PHP gets in its own way: any
content emitted before a call to `header()` will result in that call becoming a
no-op; depending on error reporting settings, this can often mean headers
and/or response status are not correctly sent. One way to work around this is
to use PHP's output buffering features, but nesting of output buffers can
become problematic and difficult to debug. Frameworks and applications thus
tend to create response abstractions for aggregating headers and content that
can be emitted at once - and these abstractions are often incompatible.

Thus, the goal of this proposal is to abstract both client- and server-side
request and response interfaces in order to promote interoperability between
projects. If projects implement these interfaces, a reasonable level of
compatibility may be assumed when adopting code from different libraries.

It should be noted that the goal of this proposal is not to obsolete the
current interfaces utilized by existing PHP libraries. This proposal is aimed
at interoperability between PHP packages for the purpose of describing HTTP
messages.

4. Scope
--------

### 4.1 Goals

* Provide the interfaces needed for describing HTTP messages.
* Focus on practical applications and usability.
* Define the interfaces to model all elements of the HTTP message and URI
  specifications.
* Ensure that the API does not impose arbitrary limits on HTTP messages. For
  example, some HTTP message bodies can be too large to store in memory, so we
  must account for this.
* Provide useful abstractions both for handling incoming requests for
  server-side applications and for sending outgoing requests in HTTP clients.

### 4.2 Non-Goals

* This proposal does not expect all HTTP client libraries or server-side
  frameworks to change their interfaces to conform. It is strictly meant for
  interoperability.
* While everyone's perception of what is and is not an implementation detail
  varies, this proposal should not impose implementation details. However,
  because RFCs 7230, 7231, and 3986 do not force any particular implementation,
  there will be a certain amount of invention needed to describe HTTP message
  interfaces in PHP.

5. Design Decisions
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

### Why are there header methods on messages rather than in a header bag?

The message itself is a container for the headers (as well as the other message
properties). How these are represented internally is an implementation detail,
but uniform access to headers is a responsibility of the message.

### Why are URIs represented as objects?

URIs are values, with identity defined by the value, and thus should be modeled
as value objects.

Additionally, URIs contain a variety of segments which may be accessed many
times in a give request -- and which would require parsing the URI in order to
determine (e.g., via `parse_url()`). Modeling URIs as value objects allows
parsing once only, and simplifies access to individual segments. It also
provides convenience in client applications by allowing users to create new
instances of a base URI instance with just the segments that change (e.g.,
updating the path only).

### Why does the request interface have methods for dealing with the request line as whole, AND compose a URI?

RFC 7230 details the request line as containing a "request-target". Of the four
forms of request-target, only one is a URI compliant with RFC 3986; the most
common form used, however, is origin-form, which represents the URI without the
scheme or authority information. Moreover, since all forms are valid for
purposes of requests, the proposal must accommodate each.

`RequestInterface` thus has methods surrounding the request line. By default,
it will attempt to construct the request line by computing it from its own
values: the HTTP method, the composed URI, and the protocol version. Since the
most common form or specifying the request line is using origin-form (path and
query string), this is the default used. Another method, `withRequestLine()`,
allows specifying an instance with a specific request line, allowing users to
create requests that use one of the other valid request-target forms.

The URI is kept as a discrete member of the request for a variety of reasons.
For both clients and servers, knowledge of the absolute URI is typically
required. In the case of clients, the URI, and specifically the scheme and
authority details, is needed in order to make the actual TCP connection. For
server-side applications, the full URI is often required in order to validate
the request or to route to an appropriate handler.

### Immutability of messages

The proposal models immutable messages and URIs.

Messages are values where the identity is the aggregate of all parts of the
message; a change to any aspect of the message is essentially a new message.

However, the proposal also recognizes that most clients and server-side
applications will need to be able to easily update message aspects, and, as
such, provides interface methods that will create new message instances with
the updates. These are generally prefixed with the verbiage `with` or
`without`.

Immutability provides several benefits:

- Changes in URI state cannot alter the request composing the URI instance.
- Changes in headers cannot alter the message composing them.

In essence, immutability ensures the integrity of the message state, and
prevents the need for bi-directional dependencies, which can often go
out-of-sync or lead to debugging or performance issues.

For HTTP clients, they allow consumers to build a base request with data such
as the base URI and required headers, without needing to build a brand new
request or reset request state for each message the client sends:

```php
$uri = new Uri('http://api.example.com');
$baseRequest = new Request($uri, null, [
    'Authorization' => 'Bearer ' . $token,
    'Accept'        => 'application/json',
]);;

$request = $baseRequest->withUri($uri->withPath('/user'))->withMethod('GET');
$response = $client->send($request);

// get user id from $response

$body = new StringStream(json_encode(['tasks' => [
    'Code',
    'Coffee',
]]));;
$request = $baseRequest
    ->withUri($uri->withPath('/tasks/user/' . $userId))
    ->withMethod('POST')
    ->withHeader('Content-Type' => 'application/json')
    ->withBody($body);
$response = $client->send($request)

// No need to overwrite headers or body!
$request = $baseRequest->withUri($uri->withPath('/tasks'))->withMethod('GET');
$response = $client->send($request);
```

On the server-side, developers will need to:

- Deserialize the request message body.
- Decrypt HTTP cookies.
- Write to the response.

These operations can be accomplished with immutable objects as well, with a
number of benefits:

- The original request state can be stored for retrieval by any consumer.
- A default response state can be created with default headers and/or message body. 

Most popular PHP frameworks have mutable HTTP messages today. The main changes
necessary in consuming immutable messages are:

- Instead of calling setter methods or setting public properties, mutator
  messages will be called, and the result assigned.
- Developers must notify the application on a change in state.

As an example, in Zend Framework 2, instead of the following:

```php
function (MvcEvent $e)
{
    $response = $e->getResponse();
    $response->setHeaderLine('x-foo', 'bar');
}
```

one would now write:

```php
function (MvcEvent $e)
{
    $response = $e->getResponse();
    $e->setResponse(
        $response->withHeader('x-foo', 'bar');
    )
}
```

The above combines assignment and notification in a single call.

This practice has a side benefit of making explicit any changes to application
state being made.

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

### Why are streams mutable?

The `StreamableInterface` API includes methods such as `write()` which can
change the message content -- which directly contradicts having immutable
messages.

The problem that arises is due to the fact that the interface is intended to
wrap a PHP stream or similar. A write operation therefore will proxy to writing
to the stream. Even if we made `StreamableInterface` immutable, once the stream
has been updated, any instance that wraps that stream will also be updated --
making immutability impossible to enforce.

Our recommendation is that implementations use read-only streams for
server-side requests and client-side responses.

### Rationale for ServerRequestInterface

The `RequestInterface` and `ResponseInterface` have essentially 1:1
correlations with the request and response messages described in
[RFC 7230](http://www.ietf.org/rfc/rfc7230.txt) They provide interfaces for
implementing value objects that correspond to the specific HTTP message types
they model.

For server-side applications, however, there are other considerations for
incoming requests:

- Access to server parameters (potentially derived from the request, but also
  potentially the result of server configuration, and generally represented
  via the `$_SERVER` superglobal; these are part of the PHP Server API (SAPI)).
- Access to the query string arguments (usually encapsulated in PHP via the
  `$_GET` superglobal).
- Access to body parameters (i.e., data deserialized from the incoming request
  body; in PHP, this is specific to POST requests in
  `application/x-www-urlencoded` content types, and encapsulated in the
  `$_POST` superglobal).
- Access to uploaded files (encapsulated in PHP via the `$_FILES` superglobal).
- Access to cookie values (encapsulated in PHP via the `$_COOKIE` superglobal).
- Access to attributes derived from the request (usually, but not limited to,
  those matched against the URL path).

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

### What about "special" header values?

A number of header values contain unique representation requirements which can
pose problems both for consumption as well as generation; in particular, cookies
and the `Accept` header.

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

6. People
---------

### 6.1 Editor(s)

* Matthew Weier O'Phinney

### 6.2 Sponsors

* Paul M. Jones
* Beau Simensen (coordinator)

### 6.3 Contributors

* Michael Dowling
* Phil Sturgeon
* Chris Wilkinson
