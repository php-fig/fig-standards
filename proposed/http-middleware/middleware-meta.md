HTTP Server Middleware Meta Document
====================================

1. Summary
----------

The purpose of this PSR is to provide an interface that defines the formal
method signature for HTTP Server Middleware ("middleware") that is compatible
with HTTP Messages, as defined in [PSR-7][psr7].

_**Note:** Any references to "middleware" in this document are specific to
**server middleware**._

[psr7]: http://www.php-fig.org/psr/psr-7/

2. Why Bother?
--------------

The HTTP Messages specification does not contain any reference to HTTP Middleware.

The design pattern used by middleware has existed for many years as the
[pipeline pattern][pipeline], or more specifically, "linear pipeline processing".
The general concept of reusable middleware was popularized within PHP by
[StackPHP][stackphp]. Since the release of the HTTP Messages standard, a number
of frameworks have adopted middleware that use HTTP Message interfaces.

Agreeing on a formal  middleware interface eliminates several problems and
provides a number of benefits:

* Provides a formal standard for middleware developers to commit to.
* Eliminates duplication of similar interfaces defined by various frameworks.
* Avoids minor discrepancies in method signatures.
* Enables any middleware component to run in any compatible framework.

[pipeline]: https://en.wikipedia.org/wiki/Pipeline_(computing)
[stackphp]: http://stackphp.com/

3. Scope
--------

### 3.1 Goals

* Create a middleware interface that uses HTTP Messages.
* Ensure that middleware will not be coupled to a specific implementation of HTTP Messages.
* Implement a middleware signature that is based on best practices.

### 3.2 Non-Goals

* Attempting to define how middleware is dispatched.
* Attempting to define interfaces for client/asynchronous middleware.
* Attempting to define the mechanism by which HTTP responses are created.

4. Approaches
-------------

There are currently two common approaches to middleware that use HTTP Messages.

### 4.1 Double Pass

The signature used by most middleware implementations has been mostly the same
and is based on [Express middleware][express], which is defined as:

```
fn(request, response, next): response
```

[express]: http://expressjs.com/en/guide/writing-middleware.html

Based on the middleware implementations already used by frameworks that have
adopted this signature, the following commonalities are observed:

* The middleware is defined as a [callable][php-callable].
* The middleware is passed 3 arguments during invocation:
  1. A `ServerRequestInterface` implementation.
  2. A `ResponseInterface` implementation.
  3. A `callable` that receives the request and response to delegate the next middleware.

[php-callable]: http://php.net/manual/language.types.callable.php

A significant number of projects provide and/or use exactly the same interface.
This approach is often referred to as "double pass" in reference to both the
request and response being passed to the middleware.

#### 4.1.1 Projects Using Double Pass

* [mindplay/middleman v1](https://github.com/mindplay-dk/middleman/blob/1.0.0/src/MiddlewareInterface.php#L24)
* [relay/relay v1](https://github.com/relayphp/Relay.Relay/blob/1.0.0/src/MiddlewareInterface.php#L24)
* [slim/slim v3](https://github.com/slimphp/Slim/blob/3.4.0/Slim/MiddlewareAwareTrait.php#L66-L75)
* [zendframework/zend-stratigility v1](https://github.com/zendframework/zend-stratigility/blob/1.0.0/src/MiddlewarePipe.php#L69-L79)

#### 4.1.2 Middleware Implementing Double Pass

* [bitexpert/adroit](https://github.com/bitExpert/adroit)
* [akrabat/rka-ip-address-middleware](https://github.com/akrabat/rka-ip-address-middleware)
* [akrabat/rka-scheme-and-host-detection-middleware](https://github.com/akrabat/rka-scheme-and-host-detection-middleware)
* [bear/middleware](https://github.com/bearsunday/BEAR.Middleware)
* [hannesvdvreken/psr7-middlewares](https://github.com/hannesvdvreken/psr7-middlewares)
* [los/api-problem](https://github.com/Lansoweb/api-problem)
* [los/los-rate-limit](https://github.com/Lansoweb/LosRateLimit)
* [monii/monii-action-handler-psr7-middleware](https://github.com/monii/monii-action-handler-psr7-middleware)
* [monii/monii-nikic-fast-route-psr7-middleware](https://github.com/monii/monii-nikic-fast-route-psr7-middleware)
* [monii/monii-response-assertion-psr7-middleware](https://github.com/monii/monii-response-assertion-psr7-middleware)
* [mtymek/blast-base-url](https://github.com/mtymek/blast-base-url)
* [ocramius/psr7-session](https://github.com/Ocramius/PSR7Session)
* [oscarotero/psr7-middlewares](https://github.com/oscarotero/psr7-middlewares)
* [php-middleware/block-robots](https://github.com/php-middleware/block-robots)
* [php-middleware/http-authentication](https://github.com/php-middleware/http-authentication)
* [php-middleware/log-http-messages](https://github.com/php-middleware/log-http-messages)
* [php-middleware/maintenance](https://github.com/php-middleware/maintenance)
* [php-middleware/phpdebugbar](https://github.com/php-middleware/phpdebugbar)
* [php-middleware/request-id](https://github.com/php-middleware/request-id)
* [relay/middleware](https://github.com/relayphp/Relay.Middleware)

The primary downside of this interface is that the while the interface itself is
a callable, there is currently no way to type hint a closure in a similar way.

### 4.2 Single Pass (Lambda)

The other approach to middleware is much closer to [StackPHP][stackphp] style
and is defined as:

```
fn(request, next): response
```

Middleware taking this approach generally has the following commonalities:

* The middleware is defined with a specific interface with a method that takes
  the request for processing.
* The middleware is passed 2 arguments during invocation:
  1. An object that represents an HTTP request.
  2. A delegate that receives the request to dispatch next middleware in the pipeline.

In this form, middleware has no access to a response until one is generated by
innermost middleware. Middleware can then modify the response before returning
back up the stack.

This approach is often referred to as "single pass" or "lambda" in reference to
only the request being passed to the middleware.

#### 4.2.1 Projects Using Single Pass

There are fewer examples of this approach within projects using HTTP Messages,
with one notable exception.

[Guzzle middleware][guzzle-middleware] is focused on outgoing (client) requests
and uses this signature:

```
function (RequestInterface $request, array $options): ResponseInterface
```

#### 4.2.2 Additional Projects Using Single Pass

There are also significant projects that predate HTTP Messages using this approach.

[StackPHP][stackphp] is based on [Symfony HttpKernel][httpkernel] and supports
middleware with this signature:

```php
function handle(Request $request, $type, $catch): Response
```

*__Note__: While Stack has multiple arguments, a response object is not included.*

[Laravel middleware][laravel-middleware] uses Symfony components and supports
middleware with this signature:

```php
function handle(Request $request, callable $next): Response
```

[guzzle-middleware]: http://docs.guzzlephp.org/en/latest/handlers-and-middleware.html
[httpkernel]: https://symfony.com/doc/2.0/components/http_kernel/introduction.html
[laravel-middleware]: https://laravel.com/docs/master/middleware

### 4.3 Comparison of Approaches

The single pass approach to middleware has been well established in the PHP
community for many years. This is most evident with the large number of packages
that are based around StackPHP.

The double pass approach is much newer but has been almost universally used by
early adopters of PSR-7 (HTTP Messages).

### 4.4 Chosen Approach

Despite the nearly universal adoption of the double-pass approach there are
significant issues regarding implementation.

The most severe is that passing an empty response has no guarantees that the
response is in a usable state. This is further exacerbated by the fact that a
middleware may modify the response before passing it for further processing.

Further compounding the problem is that there is no way to ensure that the
response body has not been written to, which can lead to incomplete output or
error responses being sent with cache headers attached. It is also possible
to end up with [corrupted body content][rob-allen-filtering] when writing over
existing body content if the new content is shorter than the original. The most
effective way to resolve these issues is to always provide a fresh stream when
modifying the body of a message.

[rob-allen-filtering]: https://akrabat.com/filtering-the-psr-7-body-in-middleware/

Some have argued that passing the response helps ensure dependency inversion.
While it is true that it helps avoid depending on a specific implementation of
HTTP messages, the problem can also be resolved by injecting factories into the
middleware to create HTTP message objects, or by injecting empty message instances.
With the creation of HTTP Factories in [PSR-17][psr17], a standard approach to
handling dependency inversion is possible.

[psr17]: https://github.com/php-fig/fig-standards/blob/master/proposed/http-factory/http-factory-meta.md

A more subjective, but also important, concern is that existing double-pass
middleware typically uses the `callable` type hint to refer to middleware.
This makes strict typing impossible, as there is no assurance that the `callable`
being passed implements a middleware signature, which reduces runtime safety.

**Due to these significant issues the lambda approach has been choosen for this proposal.**

5. Design Decisions
-------------------

### 5.1 Middleware Design

The `MiddlewareInterface` defines a single method that accepts a server request
and a delegate and must return a response. The middleware may:

- Evolve the request before passing it to the delegate to execute the next
  available middleware.
- Evolve the response received from the delegate before returning it.
- Create and return a response without passing it to the delegate, thereby
  preventing any further middleware from processing.

#### Why doesn't middleware use `__invoke`?

Doing so would conflict with existing middleware that implements the double-pass
approach and may want to implement the middleware interface for purposes of
forwards compatibility with this specification.

In addition, classes that define `__invoke` can be more loosely type hinted as
`callable`, which results in less strict typing. This is generally undesirable,
especially when the `__invoke` method uses strict typing.

#### Why the name `process()`?

We reviewed a number of existing MVC and middleware frameworks to determine
what method(s) each defined for processing incoming requests. We found the
following were commonly used:

- `__invoke` (within middleware systems, such as Slim, Expressive, Relay, etc.)
- `handle` (in particular, software derived from Symfony's [HttpKernel][HttpKernel])
- `dispatch` (Zend Framework's [DispatchableInterface][DispatchableInterface])

[HttpKernel]: https://symfony.com/doc/current/components/http_kernel.html
[DispatchableInterface]: https://github.com/zendframework/zend-stdlib/blob/980ce463c29c1a66c33e0eb67961bba895d0e19e/src/DispatchableInterface.php

We chose to allow a forwards-compatible approach for such classes to repurpose
themselves as middleware (or middleware compatible with this specification),
and, as such, needed to choose a name not in common usage. As such, we chose
`process`, to indicate _processing_ a request.

#### Why is a server request required?

To make it clear that the middleware can only be used in a synchronous, server
side context.

While not all middleware will need to use the additional methods defined by the
server request interface, external requests are typically processed asynchronously
and would typically return a [promise][promises] of a response. (This is primarily
due to the fact that multiple requests can be made in parallel and processed as
they are returned.) It is outside the scope of this proposal to address the needs
of asynchronous request/response life cycles.

Attempting to define client middleware would be premature at this point. Any future
proposal that is focused on client side request processing should have the opportunity
to define a standard that is specific to the nature of asynchronous middleware.

_See "client vs server side middleware" in [relevant links](#8-relevant-links) for
additional information._

[promises]: https://promisesaplus.com/

### 5.2 Delegate Design

The `DelegateInterface` defines a single method that accepts a request and
returns a response. The delegate interface must be implemented by any middleware
dispatcher that uses middleware implementing `MiddlewareInterface`.

#### Why the term "delegate"?

The term "delegate" means something designated to act for or represent another.
In terms of middleware design, a delegate is called upon by middleware when the
middleware is unable to process the request itself; the delegate then processes
the request _for the original middleware_ in order to return a response.

#### Why isn't the delegate a `callable`?

Using an interface type hint improves runtime safety and IDE support.

_See "discussion of FrameInterface" in [relevant links](#8-relevant-links) for
additional information._

#### Why not the term `$next`?

Several existing middleware libraries use the term `$next` instead of
`$delegate`. `$next` implies an action: "Next, please!" As such, these libraries
define `$next` as a `callable`, which we note was undesirable for purposes of
this specification in the previous section.

Additionally, since we are defining an object, we chose to use a noun instead of
a verb to name the interface.

Further, we did not choose the term `next` for the action delegates invoke, as
that verb implies a queue or stack. The delegate is not required to implement
either pattern internally in order to do its work; its only job is to _process_
the request to return a response.

#### Why does the delegate conflict with middleware?

Both the middleware and delegate interface define a `process` method to
discourage misuse of middleware as delegates.

The implementation of the delegate should be defined within middleware
dispatching systems.

6. People
---------

### 6.1 Editor(s)

* Woody Gilk, <woody.gilk@gmail.com>

### 6.2 Sponsors

* Jason Coward, <jason@opengeek.com> (Sponsor)

### 6.3 Contributors

* Paul M Jones, <pmjones88@gmail.com>
* Rasmus Schultz, <rasmus@mindplay.dk>
* Matthew Weier O'Phinney, <mweierophinney@gmail.com>

7. Votes
--------

* [Entrance Vote](https://groups.google.com/d/msg/php-fig/v9AijALWJhI/04XCwqgIEAAJ)
* **Acceptance Vote:** _(not yet taken)_

8. Relevant Links
-----------------

_**Note:** Order descending chronologically._

* [PHP-FIG mailing list thread](https://groups.google.com/d/msg/php-fig/vTtGxdIuBX8/NXKieN9vDQAJ)
* [The PHP League middleware proposal](https://groups.google.com/d/msg/thephpleague/jyztj-Nz_rw/I4lHVFigAAAJ)
* [PHP-FIG discussion of FrameInterface](https://groups.google.com/d/msg/php-fig/V12AAcT_SxE/aRXmNnIVCwAJ)
* [PHP-FIG discussion about client vs server side middleware](https://groups.google.com/d/msg/php-fig/vBk0BRgDe2s/GTaT0yKNBgAJ)

9. Errata
---------

...
