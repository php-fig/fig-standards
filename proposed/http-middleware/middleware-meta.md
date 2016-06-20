PSR-15 Meta Document
====================

1. Summary
----------

The purpose of this PSR is to provide an interface that defines the formal
method signature for HTTP Middleware that is compatible with HTTP Messages,
as defined in [PSR-7][psr7].

[psr7]: http://www.php-fig.org/psr/psr-7/

2. Why Bother?
--------------

The design pattern used by middle has existed for many years as [pipeline][pipeline],
or more specifically, "linear pipeline processing". The general concept of reusable
middleware was popularized within PHP by [StackPHP][stackphp]. Since the release
of the HTTP Messages standard, a number of frameworks have adopted middleware that
use HTTP Message interfaces.

Agreeing on a formal middleware interface eliminates several problems and
provides a number of benefits:

* Provides a formal standard for middleware developers to commit to.
* Eliminates duplication of similar interfaces defined by various frameworks.
* Avoids minor discrepancies in method signatures.
* Enables any middleware component to run in any compatible framework.

[pipeline]: https://en.wikipedia.org/wiki/Pipeline_(computing)
[stackphp]: http://stackphp.com/

3. Scope
--------

## 3.1 Goals

* Create a middleware interface that uses HTTP Messages.
* Provide a suggested interface for middleware stack containers.
* Ensure that middleware will not be tied to a specific implementation of HTTP Messages.
* Implement a middleware signature that is based on best practices.

## 3.2 Non-Goals

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

* The middleware is defined as a [callable][php-callable] using `__invoke`.
* The middleware is passed 3 arguments during invocation:
  1. A `RequestInterface` implementation.
  2. A `ResponseInterface` implementation.
  3. A `callable` that receives the request and response to dispatch next middleware.

[php-callable]: http://php.net/manual/language.types.callable.php

A significant number of projects provide and/or use exactly the same interface.
This approach is often referred to as "double pass" in reference to both the
request and response being passed to the middleware.

#### 4.1.1 Projects Using Double Pass

* [mindplay/middleman](https://github.com/mindplay-dk/middleman/blob/1.0.0/src/MiddlewareInterface.php#L24)
* [relay/relay](https://github.com/relayphp/Relay.Relay/blob/1.0.0/src/MiddlewareInterface.php#L24)
* [slim/slim](https://github.com/slimphp/Slim/blob/3.4.0/Slim/MiddlewareAwareTrait.php#L66-L75)
* [zendframework/zend-stratigility](https://github.com/zendframework/zend-stratigility/blob/1.0.0/src/MiddlewarePipe.php#L69-L79)

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
This may be resolved in the future with [functional interfaces][php-functional].

[php-functional]: https://wiki.php.net/rfc/functional-interfaces

### 4.2 Single Pass (Lambda)

The other approach to middleware is much closer to [StackPHP][stackphp] style
and is defined as:


```
fn(request, frame): response
```

Middleware taking this approach generally has the following commonalities:

* The middleware is defined with a specific interface with a method that takes
  the request for processing.
* The middleware is passed 2 arguments during invocation:
  1. A `RequestInterface` implementation.
  2. A `FrameInterface` that receives the request to dispatch next middleware.

In this form, middleware has no access to a response until one is generated by
innermost middleware. Middleware can then modify the response before returning
back up the stack.

This approach is often referred to as "single pass" or "lambda" in reference to
only the request being passed to the middleware.

[php-closure]: http://php.net/closure

#### 4.2.1 Projects Using Single Pass

There are fewer examples of this approach within projects using HTTP Messages,
with a couple of notable exceptions.

[Guzzle middleware](http://docs.guzzlephp.org/en/latest/handlers-and-middleware.html)
uses a rather unique approach to middleware that is based around generators and
promises. Ignoring the generator portion, Guzzle middleware has the signature:

```
function(RequestInterface $request, array $options): ResponseInterface
```

[Laravel middleware](https://laravel.com/docs/master/middleware) does make use
of HTTP Messages but supports middleware with the signature:

```
function handle(Request $request, callable $next): Response
```

[StackPHP][stackphp] is based specifically around [Symfony HttpKernel][httpkernel]
and not HTTP Messages but does use a single pass approach:

```php
handle(Request $request, $type, $catch): Response
```

[httpkernel]: https://symfony.com/doc/2.0/components/http_kernel/introduction.html

### 4.3 Comparison of Approaches

The single pass approach to middleware has been well established in the PHP
community for many years. This is most evident with the large number of packages
that are based around StackPHP.

The double pass approach is much newer but has already been widely adopted by
early adopters of HTTP Messages.


### 4.4 Chosen Approach

The double-pass approach has some significant issues regarding implementation.

The most severe is that passing an empty response has no guarantees that the
response is in a usable state. This is further exacerbated by the fact that a
middleware may modify the response before passing it for further dispatching.

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

Using the lambda approach ensures a more consistent and predictable developer
experience, which helps prevent incorrect or less optimal implementation.
The lambda approach is also somewhat easier to explain and has wider overall
adoption within the ecosystem due to existing implementations.

Using interface declarations instead of `callable` eliminates some flexibility
in favor of better runtime safety and debugging. Using stricter interfaces also
makes it easier for existing implementations to provide wrappers or adapters
to provide forward compatibility with the proposed interfaces.

5. `FrameInterface`
-------------------

The `$next` argument is a callable in most existing middleware systems. However using
an interface allows to improve type safety and IDE support.

Using `__invoke()` as the method name was considered but was not kept. That solution
would have had the benefit of keeping the existing practice of invoking `$next`
directly (`$response = $next($request)`). However many existing middleware systems
already use a class that implements `__invoke()` for the `$next` argument with
a different signature: `$request` *and* `$response`. By selecting a different method,
PSR-15 may be implemented within existing systems in parallel with existing features,
providing a migration path for these libraries and their consumers. In some cases,
existing `__invoke()` implementations could even proxy to the method defined by
PSR-15, or vice versa.

You can read [the relevant discussion in the mailing list](https://groups.google.com/d/topic/php-fig/V12AAcT_SxE/discussion).


6. People
---------

### 6.1 Editor(s)

* Woody Gilk, <woody.gilk@gmail.com>

### 6.2 Sponsors

* Paul M Jones, <pmjones88@gmail.com> (Coordinator)
* Jason Coward, <jason@opengeek.com> (Sponsor)

### 6.3 Contributors

* Rasmus Schultz, <rasmus@mindplay.dk>

7. Votes
--------

* [Entrance Vote](https://groups.google.com/forum/#!topic/php-fig/v9AijALWJhI)
* **Acceptance Vote:** _(not yet taken)_

8. Relevant Links
-----------------

_**Note:** Order descending chronologically._

* [PHP-FIG mailing list thread](https://groups.google.com/d/msg/php-fig/vTtGxdIuBX8/NXKieN9vDQAJ)
* [The PHP League middleware proposal](https://groups.google.com/d/msg/thephpleague/jyztj-Nz_rw/I4lHVFigAAAJ)

9. Errata
---------

...
