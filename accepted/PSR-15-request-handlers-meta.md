HTTP Server Request Handlers Meta Document
==========================================

## 1. Summary

The purpose of this PSR is to define formal interfaces for HTTP server request
handlers ("request handlers") and HTTP server request middleware ("middleware")
that are compatible with HTTP messages as defined in [PSR-7][psr7] or subsequent
replacement PSRs.

_Note: All references to "request handlers" and "middleware" are specific to
**server request** processing._

[psr7]: http://www.php-fig.org/psr/psr-7/

## 2. Why Bother?

The HTTP messages specification does not contain any reference to request
handlers or middleware.

Request handlers are a fundamental part of any web application. The handler is
the component that receives a request and produces a response. Nearly all code
that works with HTTP messages will have some kind of request handler.

[Middleware][middleware] has existed for many years in the PHP ecosystem. The
general concept of reusable middleware was popularized by [StackPHP][stackphp].
Since the release of HTTP messages as a PSR, many frameworks have adopted
middleware that use HTTP message interfaces.

Agreeing on formal request handler and middleware interfaces eliminates several
problems and has a number of benefits:

* Provides a formal standard for developers to commit to.
* Enables any middleware component to run in any compatible framework.
* Eliminates duplication of similar interfaces defined by various frameworks.
* Avoids minor discrepancies in method signatures.

[middleware]: https://en.wikipedia.org/wiki/Middleware
[stackphp]: http://stackphp.com/

## 3. Scope

### 3.1 Goals

* Create a request handler interface that uses HTTP messages.
* Create a middleware interface that uses HTTP messages.
* Implement request handler and middleware signatures that are based on
  best practices.
* Ensure that request handlers and middleware will be compatible with any
  implementation of HTTP messages.

### 3.2 Non-Goals

* Attempting to define the mechanism by which HTTP responses are created.
* Attempting to define interfaces for client/asynchronous middleware.
* Attempting to define how middleware is dispatched.

## 4. Request Handler Approaches

There are many approaches to request handlers that use HTTP messages. However,
the general process is the same in all of them:

Given an HTTP request, produce an HTTP response for that request.

The internal requirements of that process will vary from framework to framework
and application to application. This proposal makes no effort to determine what
that process should be.

## 5. Middleware Approaches

There are currently two common approaches to middleware that use HTTP messages.

### 5.1 Double Pass

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
  3. A `callable` that receives the request and response to delegate to the next middleware.

[php-callable]: http://php.net/manual/language.types.callable.php

A significant number of projects provide and/or use exactly the same interface.
This approach is often referred to as "double pass" in reference to both the
request and response being passed to the middleware.

#### 5.1.1 Projects Using Double Pass

* [mindplay/middleman v1](https://github.com/mindplay-dk/middleman/blob/1.0.0/src/MiddlewareInterface.php#L24)
* [relay/relay v1](https://github.com/relayphp/Relay.Relay/blob/1.0.0/src/MiddlewareInterface.php#L24)
* [slim/slim v3](https://github.com/slimphp/Slim/blob/3.4.0/Slim/MiddlewareAwareTrait.php#L66-L75)
* [zendframework/zend-stratigility v1](https://github.com/zendframework/zend-stratigility/blob/1.0.0/src/MiddlewarePipe.php#L69-L79)

#### 5.1.2 Middleware Implementing Double Pass

* [bitexpert/adroit](https://github.com/bitExpert/adroit)
* [akrabat/rka-ip-address-middleware](https://github.com/akrabat/rka-ip-address-middleware)
* [akrabat/rka-scheme-and-host-detection-middleware](https://github.com/akrabat/rka-scheme-and-host-detection-middleware)
* [bear/middleware](https://github.com/bearsunday/BEAR.Middleware)
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
a callable, there is currently no way to strictly type a closure.

### 5.2 Single Pass (Lambda)

The other approach to middleware is much closer to [StackPHP][stackphp] style
and is defined as:

```
fn(request, next): response
```

Middleware taking this approach generally has the following commonalities:

* The middleware is defined with a specific interface with a method that takes
  the request for processing.
* The middleware is passed 2 arguments during invocation:
  1. An HTTP request message.
  2. A request handler to which the middleware can delegate the responsibility
     of producing an HTTP response message.

In this form, middleware has no access to a response until one is generated by
the request handler. Middleware can then modify the response before returning it.

This approach is often referred to as "single pass" or "lambda" in reference to
only the request being passed to the middleware.

#### 5.2.1 Projects Using Single Pass

There are fewer examples of this approach within projects using HTTP messages,
with one notable exception.

[Guzzle middleware][guzzle-middleware] is focused on outgoing (client) requests
and uses this signature:

```php
function (RequestInterface $request, array $options): ResponseInterface
```

#### 5.2.2 Additional Projects Using Single Pass

There are also significant projects that predate HTTP messages using this approach.

[StackPHP][stackphp] is based on [Symfony HttpKernel][httpkernel] and supports
middleware with this signature:

```php
function handle(Request $request, $type, $catch): Response
```

_Note: While Stack has multiple arguments, a response object is not included._

[Laravel middleware][laravel-middleware] uses Symfony components and supports
middleware with this signature:

```php
function handle(Request $request, callable $next): Response
```

[guzzle-middleware]: http://docs.guzzlephp.org/en/latest/handlers-and-middleware.html
[httpkernel]: https://symfony.com/doc/2.0/components/http_kernel/introduction.html
[laravel-middleware]: https://laravel.com/docs/master/middleware

### 5.3 Comparison of Approaches

The single pass approach to middleware has been well established in the PHP
community for many years. This is most evident with the large number of packages
that are based around StackPHP.

The double pass approach is much newer but has been almost universally used by
early adopters of HTTP messages (PSR-7).

### 5.4 Chosen Approach

Despite the nearly universal adoption of the double-pass approach, there are
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

**Due to these significant issues, the lambda approach has been chosen for this proposal.**

## 6. Design Decisions

### 6.1 Request Handler Design

The `RequestHandlerInterface` defines a single method that accepts a request and
MUST return a response. The request handler MAY delegate to another handler.

#### Why is a server request required?

To make it clear that the request handler can only be used in a server side context.
In an client side context, a [promise][promises] would likely be returned instead
of a response.

[promises]: https://promisesaplus.com/

#### Why the term "handler"?

The term "handler" means something designated to manage or control. In terms of
request processing, a request handler is the point where the request must be
acted upon to create a response.

As opposed to the term "delegate", which was used in a previous version of this
specification, the internal behavior of this interface is not specified.
As long as the request handler ultimately produces a response, it is valid.

#### Why doesn't request handler use `__invoke`?

Using `__invoke` is less transparent than using a named method. It also makes
it easier to call the request handler when it is assigned to a class variable,
without using `call_user_func` or other less common syntax.

_See "discussion of FrameInterface" in [relevant links](#9-relevant-links) for
 additional information._

### 6.2 Middleware Design

The `MiddlewareInterface` defines a single method that accepts an HTTP request
and a request handler and must return a response. The middleware may:

- Evolve the request before passing it to the request handler.
- Evolve the response received from the request handler before returning it.
- Create and return a response without passing the request to the request handler,
  thereby handling the request itself.

When delegating from one middleware to another in a sequence, one approach for
dispatching systems is to use an intermediary request handler composing the
middleware sequence as a way to link middleware together. The final or innermost
middleware will act as a gateway to application code and generate a response
from its results; alternately, the middleware MAY delegate this responsibility
to a dedicated request handler.

#### Why doesn't middleware use `__invoke`?

Doing so would conflict with existing middleware that implements the double-pass
approach and may want to implement the middleware interface for purposes of
forward compatibility with this specification.

#### Why the name `process()`?

We reviewed a number of existing monolithic and middleware frameworks to
determine what method(s) each defined for processing incoming requests. We found
the following were commonly used:

- `__invoke` (within middleware systems, such as Slim, Expressive, Relay, etc.)
- `handle` (in particular, software derived from Symfony's [HttpKernel][HttpKernel])
- `dispatch` (Zend Framework's [DispatchableInterface][DispatchableInterface])

[HttpKernel]: https://symfony.com/doc/current/components/http_kernel.html
[DispatchableInterface]: https://github.com/zendframework/zend-stdlib/blob/980ce463c29c1a66c33e0eb67961bba895d0e19e/src/DispatchableInterface.php

We chose to allow a forward-compatible approach for such classes to repurpose
themselves as middleware (or middleware compatible with this specification),
and thus needed to choose a name not in common usage. As such, we chose
`process`, to indicate _processing_ a request.

#### Why is a server request required?

To make it clear that the middleware can only be used in a synchronous, server
side context.

While not all middleware will need to use the additional methods defined by the
server request interface, outbound requests are typically processed asynchronously
and would typically return a [promise][promises] of a response. (This is primarily
due to the fact that multiple requests can be made in parallel and processed as
they are returned.) It is outside the scope of this proposal to address the needs
of asynchronous request/response life cycles.

Attempting to define client middleware would be premature at this point. Any future
proposal that is focused on client side request processing should have the opportunity
to define a standard that is specific to the nature of asynchronous middleware.

_See "client vs server side middleware" in [relevant links](#9-relevant-links) for
additional information._

#### What is the role of the request handler?

Middleware has the following roles:

- Producing a response on its own. If specific request conditions are met, the
  middleware can produce and return a response.

- Returning the result of the request handler. In cases where the middleware
  cannot produce its own response, it can delegate to the request handler to
  produce one; sometimes this may involve providing a transformed request (e.g.,
  to inject a request attribute, or the results of parsing the request body).

- Manipulating and returning the response produced by the request handler. In
  some cases, the middleware may be interested in manipulating the response
  the request handler returns (e.g., to gzip the response body, to add CORS
  headers, etc.). In such cases, the middleware will capture the response
  returned by the request handler, and return a transformed response on
  completion.

In these latter two cases, the middleware may have code such as the following:

```php
// Straight delegation:
return $handler->handle($request);

// Capturing the response to manipulate:
$response = $handler->handle($request);
```

How the handler acts is entirely up to the developer, so long as it produces a
response.

In one common scenario, the handler implements a _queue_ or a _stack_ of
middleware instances internally. In such cases, calling
`$handler->handle($request)` will advance the internal pointer, pull the
middleware associated with that pointer, and call it using
`$middleware->process($request, $this)`. If no more middleware exists, it will
generally either raise an exception or return a canned response.

Another possibility is for  _routing middleware_ that matches the incoming
server request to a specific handler, and then returns the response generated by
executing that handler. If unable to route to a handler, it would instead
execute the handler provided to the middleware. (This sort of mechanism can even
be used in conjunction with middleware queues and stacks.)

### 6.3 Example Interface Interactions

The two interfaces, `RequestHandlerInterface` and `MiddlewareInterface`, were
designed to work in conjunction with one another. Middleware gains flexibility
when de-coupled from any over-arching application layer, and instead only
relying on the provided request handler to produce a response.

Two approaches to middleware dispatch systems that the Working Group observed
and/or implemented are demonstrated below. Additionally, examples of re-usable
middleware are provided to demonstrate how to write middleware that is
loosely-coupled.

Please note that these are not suggested as definitive or exclusive approaches
to defining middleware dispatch systems.

#### Queue-based request handler

In this approach, a request handler maintains a queue of middleware, and a
fallback response to return if the queue is exhausted without returning a
response. When executing the first middleware, the queue passes itself as a
request handler to the middleware.

```php
class QueueRequestHandler implements RequestHandlerInterface
{
    private $middleware = [];
    private $fallbackHandler;
    
    public function __construct(RequestHandlerInterface $fallbackHandler)
    {
        $this->fallbackHandler = $fallbackHandler;
    }
    
    public function add(MiddlewareInterface $middleware)
    {
        $this->middleware[] = $middleware;
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Last middleware in the queue has called on the request handler.
        if (0 === count($this->middleware)) {
            return $this->fallbackHandler->handle($request);
        }
        
        $middleware = array_shift($this->middleware);
        return $middleware->process($request, $this);
    }
}
```

An application bootstrap might then look like this:

```php
// Fallback handler:
$fallbackHandler = new NotFoundHandler();

// Create request handler instance:
$app = new QueueRequestHandler($fallbackHandler);

// Add one or more middleware:
$app->add(new AuthorizationMiddleware());
$app->add(new RoutingMiddleware());

// execute it:
$response = $app->handle(ServerRequestFactory::fromGlobals());
```

This system has two request handlers: one that will produce a response if the
last middleware delegates to the request handler, and one for dispatching the
middleware layers. (In this example, the `RoutingMiddleware` will likely execute
composed handlers on a successful route match; see more on that below.)

This approach has the following benefits:

- Middleware does not need to know anything about any other middleware or how it
  is composed in the application.
- The `QueueRequestHandler` is agnostic of the PSR-7 implementation in use.
- Middleware is executed in the order it is added to the application, making the
  code explicit.
- Generation of the "fallback" response is delegated to the application
  developer. This allows the developer to determine whether that should be a
  "404 Not Found" condition, a default page, etc.

#### Decoration-based request handler

In this approach, a request handler implementation decorates both a middleware
instance and a fallback request handler to pass to it. The application is built
from the outside-in, passing each request handler "layer" to the next outer one.

```php
class DecoratingRequestHandler implements RequestHandlerInterface
{
    private $middleware;
    private $nextHandler;

    public function __construct(MiddlewareInterface $middleware, RequestHandlerInterface $nextHandler)
    {
        $this->middleware = $middleware;
        $this->nextHandler = $nextHandler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request, $this->nextHandler);
    }
}

// Create a response prototype to return if no middleware can produce a response
// on its own. This could be a 404, 500, or default page.
$responsePrototype = (new Response())->withStatus(404);
$innerHandler = new class ($responsePrototype) implements RequestHandlerInterface {
    private $responsePrototype;

    public function __construct(ResponseInterface $responsePrototype)
    {
        $this->responsePrototype = $responsePrototype;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responsePrototype;
    }
};

$layer1 = new DecoratingRequestHandler(new RoutingMiddleware(), $innerHandler);
$layer2 = new DecoratingRequestHandler(new AuthorizationMiddleware(), $layer1);

$response = $layer2->handle(ServerRequestFactory::fromGlobals());
```

Similar to the queue-based middleware, request handlers serve two purposes in
this system:

- Producing a fallback response if no other layer does.
- Dispatching middleware.

#### Reusable Middleware Examples

In the examples above, we have two middleware composed in each. In order for
these to work in either situation, we need to write them such that they interact
appropriately.

Implementors of middleware striving for maximum interoperability may want to
consider the following guidelines:

- Test the request for a required condition. If it does not satisfy that
  condition, use a composed prototype response or a composed response factory
  to generate and return a response.

- If pre-conditions are met, delegate creation of the response to the provided
  request handler, optionally providing a "new" request by manipulating the
  provided request (e.g., `$handler->handle($request->withAttribute('foo',
  'bar')`).

- Either pass the response returned by the request handler unaltered, or provide
  a new response by manipulating the one returned (e.g., `return
  $response->withHeader('X-Foo-Bar', 'baz')`).

The `AuthorizationMiddleware` is one that will exercise all three of these guidelines:

- If authorization is required, but the request is not authorized, it will use a
  composed prototype response to produce an "unauthorized" response.
- If authorization is not required, it will delegate the request to the handler
  without changes.
- If authorization is required and the request is authorized, it will delegate
  the request to the handler, and sign the response returned based on the request.

```php
class AuthorizationMiddleware implements MiddlewareInterface
{
    private $authorizationMap;

    public function __construct(AuthorizationMap $authorizationMap)
    {
        $this->authorizationMap = $authorizationMap;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! $this->authorizationMap->needsAuthorization($request)) {
            return $handler->handle($request);
        }

        if (! $this->authorizationMap->isAuthorized($request)) {
            return $this->authorizationMap->prepareUnauthorizedResponse();
        }

        $response = $handler->handle($request);
        return $this->authorizationMap->signResponse($response, $request);
    }
}
```

Note that the middleware is not concerned with how the request handler is
implemented; it merely uses it to produce a response when pre-conditions have
been met.

The `RoutingMiddleware` implementation described below follows a similar
process: it analyzes the request to see if it matches known routes. In this
particular implementation, routes map to request handlers, and the middleware
essentially delegates to them in order to produce a response. However, in the
case that no route is matched, it will execute the handler passed to it to
produce the response to return.

```php
class RoutingMiddleware implements MiddlewareInterface
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $result = $this->router->match($request);

        if ($result->isSuccess()) {
            return $result->getHandler()->handle($request);
        }

        return $handler->handle($request);
    }
}
```

## 7. People

This PSR was produced by a FIG Working Group with the following members:

* Matthew Weier O'Phinney (sponsor), <mweierophinney@gmail.com>
* Woody Gilk (editor), <woody.gilk@gmail.com>
* Glenn Eggleton
* Matthieu Napoli
* Oscar Otero
* Korvin Szanto
* Stefano Torresi

The working group would also like to acknowledge the contributions of:

* Jason Coward, <jason@opengeek.com>
* Paul M. Jones, <pmjones88@gmail.com>
* Rasmus Schultz, <rasmus@mindplay.dk>

## 8. Votes

* [Working Group Formation](https://groups.google.com/d/msg/php-fig/rPFRTa0NODU/tIU9BZciAgAJ)
* [Review Period Initiation](https://groups.google.com/d/msg/php-fig/mfTrFinTvEM/PiYvU2S6BAAJ)
* [Acceptance](https://groups.google.com/d/msg/php-fig/bhQmHt39hJE/ZCYrK_O2AQAJ)

## 9. Relevant Links

_**Note:** Order descending chronologically._

* [PHP-FIG mailing list thread](https://groups.google.com/d/msg/php-fig/vTtGxdIuBX8/NXKieN9vDQAJ)
* [The PHP League middleware proposal](https://groups.google.com/d/msg/thephpleague/jyztj-Nz_rw/I4lHVFigAAAJ)
* [PHP-FIG discussion of FrameInterface](https://groups.google.com/d/msg/php-fig/V12AAcT_SxE/aRXmNnIVCwAJ)
* [PHP-FIG discussion about client vs server side middleware](https://groups.google.com/d/msg/php-fig/vBk0BRgDe2s/GTaT0yKNBgAJ)

## 10. Errata

...
