HTTP Message Meta Document
==========================

1. Summary
----------

The purpose of this proposal is to provide a set of common interfaces for HTTP
messages as described in [RFC 2616](http://www.ietf.org/rfc/rfc2616.txt).

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

5. People
---------

### 5.1 Editor(s)

* Michael Dowling

### 5.2 Sponsors

* Phil Sturgeon (coordinator)
* Beau Simensen

### 5.3 Contributors

* Chris Wilkinson
