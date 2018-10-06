# Container Meta Document

## 1. Introduction

This document describes the process and discussions that led to the Container PSR.
Its goal is to explain the reasons behind each decision.

## 2. Why bother?

There are dozens of dependency injection containers out there, and these
DI containers have very different ways to store entries.

- Some are based on callbacks (Pimple, Laravel, ...)
- Others are based on configuration (Symfony, ZF, ...), with various formats
  (PHP arrays, YAML files, XML files...)
- Some can leverage factories...
- Some have a PHP API to build entries (PHP-DI, ZF, Symfony, Mouf...)
- Some can do auto-wiring (Laravel, PHP-DI, ...)
- Others can wire entries based on annotations (PHP-DI, JMS Bundle...)
- Some have a graphical user interface (Mouf...)
- Some can compile configuration files to PHP classes (Symfony, ZF...)
- Some can do aliasing...
- Some can use proxies to provide lazy loading of dependencies...

So when you look at the big picture, there is a very large number of ways in
which the DI problem can be tackled, and therefore a big number of different
implementations. However, all the DI containers out there are answering the
same need: they offer a way for the application to retrieve a set of
configured objects (usually services).

By standardizing the way entries are fetched from a container, frameworks and
libraries using the Container PSR could work with any compatible container.
That would allow end users to choose their own container based on their own preferences.

## 3. Scope
### 3.1. Goals

The goal set by the Container PSR is to standardize how frameworks and libraries make use of a
container to obtain objects and parameters.

It is important to distinguish the two usages of a container:

- configuring entries
- fetching entries

Most of the time, those two sides are not used by the same party.
While it is often end users who tend to configure entries, it is generally the framework that fetches
entries to build the application.

This is why this interface focuses only on how entries can be fetched from a container.

### 3.2. Non-goals

How entries are set in the container and how they are configured is out of the
scope of this PSR. This is what makes a container implementation unique. Some
containers have no configuration at all (they rely on autowiring), others rely
on PHP code defined via callback, others on configuration files... This standard
only focuses on how entries are fetched.

Also, naming conventions used for entries are not part of the scope of this
PSR. Indeed, when you look at naming conventions, there are 2 strategies:

- the identifier is the class name, or an interface name (used mostly
  by frameworks with an autowiring capability)
- the identifier is a common name (closer to a variable name), which is
  mostly used by frameworks relying on configuration.

Both strategies have their strengths and weaknesses. The goal of this PSR
is not to choose one convention over the other. Instead, the user can simply
use aliasing to bridge the gap between 2 containers with different naming strategies.

## 4. Recommended usage: Container PSR and the Service Locator

The PSR states that:

> "users SHOULD NOT pass a container into an object, so the object
> can retrieve *its own dependencies*. Users doing so are using the container as a Service Locator.
> Service Locator usage is generally discouraged."

```php
// This is not OK, you are using the container as a service locator
class BadExample
{
    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('db');
    }
}

// Instead, please consider injecting directly the dependencies
class GoodExample
{
    public function __construct($db)
    {
        $this->db = $db;
    }
}
// You can then use the container to inject the $db object into your $goodExample object.
```

In the `BadExample` you should not inject the container because:

- it makes the code **less interoperable**: by injecting the container, you have
  to use a container compatible with the Container PSR. With the other
  option, your code can work with ANY container.
- you are forcing the developer into naming its entry "db". This naming could
  conflict with another package that has the same expectations for another service.
- it is harder to test.
- it is not directly clear from your code that the `BadExample` class will need
  the "db" service. Dependencies are hidden.

Very often, the `ContainerInterface` will be used by other packages. As a end-user
PHP developer using a framework, it is unlikely you will ever need to use containers
or type-hint on the `ContainerInterface` directly.

Whether using the Container PSR into your code is considered a good practice or not boils down to
knowing if the objects you are retrieving are **dependencies** of the object referencing
the container or not. Here are a few more examples:

```php
class RouterExample
{
    // ...

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getRoute($request)
    {
        $controllerName = $this->getContainerEntry($request->getUrl());
        // This is OK, the router is finding the matching controller entry, the controller is
        // not a dependency of the router
        $controller = $this->container->get($controllerName);
        // ...
    }
}
```

In this example, the router is transforming the URL into a controller entry name,
then fetches the controller from the container. A controller is not really a
dependency of the router. As a rule of thumb, if your object is *computing*
the entry name among a list of entries that can vary, your use case is certainly legitimate.

As an exception, factory objects whose only purpose is to create and return new instances may use
the service locator pattern. The factory must then implement an interface so that it can itself
be replaced by another factory using the same interface.

```php
// ok: a factory interface + implementation to create an object
interface FactoryInterface
{
    public function newInstance();
}

class ExampleFactory implements FactoryInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function newInstance()
    {
        return new Example($this->container->get('db'));
    }
}
```

## 5. History

Before submitting the Container PSR to the PHP-FIG, the `ContainerInterface` was
first proposed in a project named [container-interop](https://github.com/container-interop/container-interop/).
The goal of the project was to provide a test-bed for implementing the `ContainerInterface`,
and to pave the way for the Container PSR.

In the rest of this meta document, you will see frequent references to
`container-interop.`

## 6. Interface name

The interface name is the same as the one discussed for `container-interop`
(only the namespace is changed to match the other PSRs).
It has been thoroughly discussed on `container-interop` [[4]](#link_naming_discussion) and was decided by a vote [[5]](#link_naming_vote).

The list of options considered with their respective votes are:

- `ContainerInterface`: +8
- `ProviderInterface`: +2
- `LocatorInterface`: 0
- `ReadableContainerInterface`: -5
- `ServiceLocatorInterface`: -6
- `ObjectFactory`: -6
- `ObjectStore`: -8
- `ConsumerInterface`: -9

## 7. Interface methods

The choice of which methods the interface would contain was made after a statistical analysis of existing containers. [[6]](#link_statistical_analysis).

The summary of the analysis showed that:

- all containers offer a method to get an entry by its id
- a large majority name such method `get()`
- for all containers, the `get()` method has 1 mandatory parameter of type string
- some containers have an optional additional argument for `get()`, but it doesn't have the same purpose between containers
- a large majority of the containers offer a method to test if it can return an entry by its id
- a majority name such method `has()`
- for all containers offering `has()`, the method has exactly 1 parameter of type string
- a large majority of the containers throw an exception rather than returning null when an entry is not found in `get()`
- a large majority of the containers don't implement `ArrayAccess`

The question of whether to include methods to define entries has been discussed at the very start of the container-interop project [[4]](#link_naming_discussion).
It has been judged that such methods do not belong in the interface described here because it is out of its scope
(see the "Goal" section).

As a result, the `ContainerInterface` contains two methods:

- `get()`, returning anything, with one mandatory string parameter. Should throw an exception if the entry is not found.
- `has()`, returning a boolean, with one mandatory string parameter.

### 7.1. Number of parameters in `get()` method

While `ContainerInterface` only defines one mandatory parameter in `get()`, it is not incompatible with
existing containers that have additional optional parameters. PHP allows an implementation to offer more parameters
as long as they are optional, because the implementation *does* satisfy the interface.

Difference with container-interop: [The container-interop spec](https://github.com/container-interop/container-interop/blob/master/docs/ContainerInterface.md) stated that:

> While `ContainerInterface` only defines one mandatory parameter in `get()`, implementations MAY accept additional optional parameters.

This sentence was removed from PSR-11 because:

- It is something that stems from OO principles in PHP, so this is not directly related to PSR-11
- We do not want to encourage implementors to add additional parameters as we recommend coding against the interface and not the implementation

However, some implementations have extra optional parameters; that's technically legal. Such implementations are compatible with PSR-11. [[11]](#link_get_optional_parameters)

### 7.2. Type of the `$id` parameter

The type of the `$id` parameter in `get()` and `has()` has been discussed in the container-interop project.

While `string` is used in all the containers that were analyzed, it was suggested that allowing
anything (such as objects) could allow containers to offer a more advanced query API.

An example given was to use the container as an object builder. The `$id` parameter would then be an
object that would describe how to create an instance.

The conclusion of the discussion [[7]](#link_method_and_parameters_details) was that this was beyond the scope of getting entries from a container without
knowing how the container provided them, and it was more fit for a factory.

### 7.3. Exceptions thrown

This PSR provides 2 interfaces meant to be implemented by container exceptions.

#### 7.3.1 Base exception

The `Psr\Container\ContainerExceptionInterface` is the base interface. It SHOULD be implemented by custom exceptions thrown directly by the container.

It is expected that any exception that is part of the domain of the container implements the `ContainerExceptionInterface`. A few examples:

- if a container relies on a configuration file and if that configuration file is flawed, the container might throw an `InvalidFileException` implementing the `ContainerExceptionInterface`.
- if a cyclic dependency is detected between dependencies, the container might throw an `CyclicDependencyException` implementing the `ContainerExceptionInterface`.

However, if the exception is thrown by some code out of the container's scope (for instance an exception thrown while instantiating an entry), the container is not required to wrap this exception in a custom exception implementing the `ContainerExceptionInterface`.

The usefulness of the base exception interface was questioned: it is not an exception one would typically catch [[8]](#link_base_exception_usefulness).

However, most PHP-FIG members considered it to be a best practice. Base exception interface are implemented in previous PSRs and several member projects. The base exception interface was therefore kept.

#### 7.3.2 Not found exception

A call to the `get` method with a non-existing id must throw an exception implementing the `Psr\Container\NotFoundExceptionInterface`.

For a given identifier:

- if the `has` method returns `false`, then the `get` method MUST throw a `Psr\Container\NotFoundExceptionInterface`.
- if the `has` method returns `true`, this does not mean that the `get` method will succeed and throw no exception. It can even throw a `Psr\Container\NotFoundExceptionInterface` if one of the dependencies of the requested entry is missing.

Therefore, when a user catches the `Psr\Container\NotFoundExceptionInterface`, it has 2 possible meanings [[9]](#link_not_found_behaviour):

- the requested entry does not exist (bad request)
- or a dependency of the requested entry does not exist (i.e. the container is misconfigured)

The user can however easily make a distinction with a call to `has`.

In pseudo-code:

```php
if (!$container->has($id)) {
    // The requested instance does not exist
    return;
}
try {
    $entry = $container->get($id);
} catch (NotFoundExceptionInterface $e) {
    // Since the requested entry DOES exist, a NotFoundExceptionInterface means that the container is misconfigured and a dependency is missing.
}
```

## 8. Implementations

At the time of writing, the following projects already implement and/or consume the `container-interop` version of the interface.

### Implementors
- [Acclimate](https://github.com/jeremeamia/acclimate-container)
- [Aura.DI](https://github.com/auraphp/Aura.Di)
- [dcp-di](https://github.com/estelsmith/dcp-di)
- [League Container](https://github.com/thephpleague/container)
- [Mouf](http://mouf-php.com)
- [Njasm Container](https://github.com/njasm/container)
- [PHP-DI](http://php-di.org)
- [PimpleInterop](https://github.com/moufmouf/pimple-interop)
- [XStatic](https://github.com/jeremeamia/xstatic)
- [Zend ServiceManager](https://github.com/zendframework/zend-servicemanager)

### Middleware
- [Alias-Container](https://github.com/thecodingmachine/alias-container)
- [Prefixer-Container](https://github.com/thecodingmachine/prefixer-container)

### Consumers
- [Behat](https://github.com/Behat/Behat)
- [interop.silex.di](https://github.com/thecodingmachine/interop.silex.di)
- [mindplay/middleman](https://github.com/mindplay-dk/middleman)
- [PHP-DI Invoker](https://github.com/PHP-DI/Invoker)
- [Prophiler](https://github.com/fabfuel/prophiler)
- [Silly](https://github.com/mnapoli/silly)
- [Slim](https://github.com/slimphp/Slim)
- [Splash](http://mouf-php.com/packages/mouf/mvc.splash-common/version/8.0-dev/README.md)
- [Zend Expressive](https://github.com/zendframework/zend-expressive)

This list is not comprehensive and should be only taken as an example showing that there is considerable interest in the PSR.

## 9. People

### 9.1 Editors

* [Matthieu Napoli](https://github.com/mnapoli)
* [David Négrier](https://github.com/moufmouf)

### 9.2 Sponsors

* [Matthew Weier O'Phinney](https://github.com/weierophinney) (Coordinator)
* [Korvin Szanto](https://github.com/KorvinSzanto)

### 9.3 Contributors

Are listed here all people that contributed in the discussions or votes (on container-interop and during migration to PSR-11), by alphabetical order:

* [Alexandru Pătrănescu](https://github.com/drealecs)
* [Amy Stephen](https://github.com/AmyStephen)
* [Ben Peachey](https://github.com/potherca)
* [David Négrier](https://github.com/moufmouf)
* [Don Gilbert](https://github.com/dongilbert)
* [Jason Judge](https://github.com/judgej)
* [Jeremy Lindblom](https://github.com/jeremeamia)
* [Larry Garfield](https://github.com/crell)
* [Marco Pivetta](https://github.com/Ocramius)
* [Matthieu Napoli](https://github.com/mnapoli)
* [Nelson J Morais](https://github.com/njasm)
* [Paul M. Jones](https://github.com/pmjones)
* [Phil Sturgeon](https://github.com/philsturgeon)
* [Stephan Hochdörfer](https://github.com/shochdoerfer)
* [Taylor Otwell](https://github.com/taylorotwell)

## 10. Relevant links

1. [Discussion about the container PSR and the service locator](https://groups.google.com/forum/#!topic/php-fig/pyTXRvLGpsw)
1. [Container-interop's `ContainerInterface.php`](https://github.com/container-interop/container-interop/blob/master/src/Interop/Container/ContainerInterface.php)
1. [List of all issues](https://github.com/container-interop/container-interop/issues?labels=ContainerInterface&milestone=&page=1&state=closed)
1. <a name="link_naming_discussion"></a>[Discussion about the interface name and container-interop scope](https://github.com/container-interop/container-interop/issues/1)
1. <a name="link_naming_vote"></a>[Vote for the interface name](https://github.com/container-interop/container-interop/wiki/%231-interface-name:-Vote)
1. <a name="link_statistical_analysis"></a>[Statistical analysis of existing containers method names](https://gist.github.com/mnapoli/6159681)
1. <a name="link_method_and_parameters_details"></a>[Discussion about the method names and parameters](https://github.com/container-interop/container-interop/issues/6)
1. <a name="link_base_exception_usefulness"></a>[Discussion about the usefulness of the base exception](https://groups.google.com/forum/#!topic/php-fig/_vdn5nLuPBI)
1. <a name="link_not_found_behaviour"></a>[Discussion about the `NotFoundExceptionInterface`](https://groups.google.com/forum/#!topic/php-fig/I1a2Xzv9wN8)
1. <a name="link_get_optional_parameters"></a>Discussion about get optional parameters [in container-interop](https://github.com/container-interop/container-interop/issues/6) and on the [PHP-FIG mailing list](https://groups.google.com/forum/#!topic/php-fig/zY6FAG4-oz8)
