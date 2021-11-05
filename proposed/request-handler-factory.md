# Request Handler Factory

## Summary

In order to be able to work with other routing and request handling libraries, 
I feel that that we need a standard factory method that can be used to build 
the `RequestHandlerInterface` object. 

We have this for `ServerRequestFactoryInterface` and `ResponseFactoryInterface`
which are very helpful. 

## Interface

I propose the following interface:

```php
namespace Psr\Http\Message;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface RequestHandlerFactoryInterface
{
    /**
     * Create a new RequestHandler from the ServerRequest
     *
     * @param ServerRequestInterface $serverRequest
     * @return RequestHandlerInterface
     */
    public function createRequestHandler(ServerRequestInterface $serverRequest): RequestHandlerInterface;
}
```

Here is an example implementation

```php
class RequestHandlerFactory implements RequestHandlerFactoryInterface
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function createRequestHandler(ServerRequestInterface $serverRequest): RequestHandlerInterface
    {
        $route = $this->router->parseRequest($serverRequest);

        $serverRequestHandler = new Dispatcher();
        $serverRequesthandler->addMiddlewares($route->getMiddlewares());

        return $serverRequestHandler->addMiddleware(new RoutingMiddleware($route));
    }
}
```