PSR-N Meta Document
===================

1. Summary
----------

The purpose of this PSR is to provide an interface that defines the formal
method signature of a PSR-7 compliant HTTP middleware component based on
existing informal standards.

2. Why Bother?
--------------

The general concept of a reusable request/response middleware was present in
[StackPHP][stackphp]. Since the release of PSR-7, a number of frameworks have
applied the StackPHP concept with a different interface signature. The signature
for these middleware implementations has been almost universally the same.

Agreeing on a formal middleware interface eliminates several problems and
provides a number of benefits:

* Provides a formal standard for middleware developers to commit to.
* Eliminates duplication of similar interfaces defined by various frameworks.
* Avoids minor discrepancies in method signatures.
* Enables any middleware component to run in any compatible framework.

Although many frameworks already use a similar signature, a formal PSR will
help middleware and framework vendors to formally certify compliance.

[stackphp]: http://stackphp.com/

3. Scope
--------

## 3.1 Goals

* Provide a middleware interface that is compatible with PSR-7 as a Composer package.
* Implement an already widely-adopted informal standard.

## 3.2 Non-Goals

* Attempting to define the mechanism by which middleware is managed or dispatched.

4. Approaches
-------------

### 4.1 Chosen Approach

Based on the middleware implementations already used by frameworks that have
adopted PSR-7, the following commonalities are observed:

* The middleware is defined as a [callable][php-callable] using `__invoke`.
* The middleware is passed 3 arguments during invocation:
  1. A `RequestInterface` implementation.
  2. A `ResponseInterface` implementation.
  3. A `callable` that receives the request and response to dispatch next middleware.

[php-callable]: http://php.net/manual/language.types.callable.php

A significant number of projects provide and/or use exactly the same interface.

#### 4.2 Projects Using a Similar Interface

* [mindplay/middleman](https://github.com/mindplay-dk/middleman/blob/1.0.0/src/MiddlewareInterface.php#L24)
* [relay/relay](https://github.com/relayphp/Relay.Relay/blob/1.0.0/src/MiddlewareInterface.php#L24)
* [slim/slim](https://github.com/slimphp/Slim/blob/3.4.0/Slim/MiddlewareAwareTrait.php#L66-L75)
* [zendframework/zend-stratigility](https://github.com/zendframework/zend-stratigility/blob/1.0.0/src/MiddlewarePipe.php#L69-L79)

#### 4.3 Middleware Implementations

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


5. People
---------

### 5.1 Editor(s)

* Woody Gilk, <woody.gilk@gmail.com>

### 5.2 Sponsors

* Paul M Jones, <pmjones88@gmail.com> (Coordinator)
* Jason Coward, <jason@opengeek.com> (Sponsor)

### 5.3 Contributors

* Rasmus Schultz, <rasmus@mindplay.dk>

6. Votes
--------

* [Entrance Vote](https://groups.google.com/forum/#!topic/php-fig/v9AijALWJhI)
* **Acceptance Vote:** _(not yet taken)_

7. Relevant Links
-----------------

_**Note:** Order descending chronologically._

* [PHP-FIG mailing list thread](https://groups.google.com/d/msg/php-fig/vTtGxdIuBX8/NXKieN9vDQAJ)
* [The PHP League middleware proposal](https://groups.google.com/d/msg/thephpleague/jyztj-Nz_rw/I4lHVFigAAAJ)

8. Errata
---------

...
