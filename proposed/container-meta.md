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

Also, some frameworks rely on very specific features offered only by their
own container. For these use cases, the Container PSR offers to implement the
"delegate lookup" feature that allows one container to fetch entries into
another container, therefore allowing 2 containers (or more) to run side-by-side.

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
on PHP code defined via callback, others on configuration files... this is very
good and does not need to be standardized. We only focus on how entries are
fetched.

Also, naming conventions used for entries are not part of the scope of this
PSR. Indeed, when you look at naming conventions, there are 2 strategies:

- the identifier is the class name, or an interface name (used mostly
  by frameworks with an autowiring capability)
- the identifier is a common name (closer to a variable name), which is
  mostly used by frameworks relying on configuration.

Both strategies have their strengths and weaknesses. The goal of this PSR
is not to choose one convention over the other. Instead, the user can simply
use aliasing to bridge the gap between 2 containers with different naming strategies.


## 4. History

Before submitting the Container PSR to the PHP-FIG, the `ContainerInterface` was
first proposed in a project named [container-interop](https://github.com/container-interop/container-interop/).
The goal of the project was to provide a test-bed for implementing the `ContainerInterface`,
and to pave the way for the Container PSR.

In the rest of this meta document, you will see frequent references to
`container-interop.`

## 5. Interface name

The interface name is the same as the one discussed for `container-interop`
(only the namespace is changed to match the other PSRs).
It has been thoroughly discussed on `container-interop` and was decided by a vote.

The list of options considered with their respective votes are:

- `ContainerInterface`: +8
- `ProviderInterface`: +2
- `LocatorInterface`: 0
- `ReadableContainerInterface`: -5
- `ServiceLocatorInterface`: -6
- `ObjectFactory`: -6
- `ObjectStore`: -8
- `ConsumerInterface`: -9

[Full results of the vote](https://github.com/container-interop/container-interop/wiki/%231-interface-name:-Vote)

The complete discussion can be read in [container-interop's issue #1](https://github.com/container-interop/container-interop/issues/1).

## 6. Interface methods

The choice of which methods the interface would contain was made after a statistical analysis of existing containers.
The results of this analysis are available [in this document](https://gist.github.com/mnapoli/6159681).

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

The question of whether to include methods to define entries has been discussed in
[container-interop's issue #1](https://github.com/container-interop/container-interop/issues/1).
It has been judged that such methods do not belong in the interface described here because it is out of its scope
(see the "Goal" section).

As a result, the `ContainerInterface` contains two methods:

- `get()`, returning anything, with one mandatory string parameter. Should throw an exception if the entry is not found.
- `has()`, returning a boolean, with one mandatory string parameter.

### 6.1. Number of parameters in `get()` method

While `ContainerInterface` only defines one mandatory parameter in `get()`, it is not incompatible with
existing containers that have additional optional parameters. PHP allows an implementation to offer more parameters
as long as they are optional, because the implementation *does* satisfy the interface.

This issue has been discussed in [container-interop's issue #6](https://github.com/container-interop/container-interop/issues/6).

### 6.2. Type of the `$id` parameter

The type of the `$id` parameter in `get()` and `has()` has been discussed in
[container-interop's issue #6](https://github.com/container-interop/container-interop/issues/6).
While `string` is used in all the containers that were analyzed, it was suggested that allowing
anything (such as objects) could allow containers to offer a more advanced query API.

An example given was to use the container as an object builder. The `$id` parameter would then be an
object that would describe how to create an instance.

The conclusion of the discussion was that this was beyond the scope of getting entries from a container without
knowing how the container provided them, and it was more fit for a factory.

## 7. Delegate lookup feature

### 7.1. Purpose of the delegate lookup feature

The `ContainerInterface` is also enough if we want to have several containers side-by-side in the same
application. For instance, this is what the [CompositeContainer](https://github.com/jeremeamia/acclimate-container/blob/master/src/CompositeContainer.php)
class of [Acclimate](https://github.com/jeremeamia/acclimate-container) is designed for:

![Side by side containers](images/side_by_side_containers.png)

However, an instance in container 1 cannot reference an instance in container 2.

It would be better if an instance of container 1 could reference an instance in container 2,
and the opposite should be true.

![Interoperating containers](images/interoperating_containers.png)

In the sample above, entry 1 in container 1 is referencing entry 3 in container 2.

### 7.2. Chosen Approach

Containers implementing this feature can perform dependency lookups in other containers.

A container implementing this feature:

- must implement the `ContainerInterface`
- must provide a way to register a *delegate container* (using a constructor parameter, or a setter, or any
possible way). The *delegate container* must implement the `ContainerInterface`.

When a *delegate container* is configured on a container:

- Calls to the `get` method should only return an entry if the entry is part of the container.
If the entry is not part of the container, an exception should be thrown (as required in the `ContainerInterface`).
- Calls to the `has` method should only return *true* if the entry is part of the container.
If the entry is not part of the container, *false* should be returned.
 - Finally, the important part: if the entry we are fetching has dependencies,
**instead** of performing the dependency lookup in the container, the lookup is performed on the *delegate container*.

Important! By default, the lookup should be performed on the delegate container **only**, not on the container itself.

It is however allowed for containers to provide exception cases for special entries, and a way to lookup into
the same container (or another container) instead of the delegate container.

### 7.3. Typical usage

The *delegate container* will usually be a composite container. A composite container is a container that
contains several other containers. When performing a lookup on a composite container, the inner containers are
queried until one container returns an entry.
An inner container implementing the *delegate lookup feature* will return entries it contains, but if these
entries have dependencies, the dependencies lookup calls will be performed on the composite container, giving
a chance to all containers to answer.

Interestingly enough, the order in which containers are added in the composite container matters. Indeed,
the first containers to be added in the composite container can "override" the entries of containers with
lower priority.

![Containers priority](images/priority.png)

In the example above, "container 2" contains a controller "myController" and the controller is referencing an
"entityManager" entry. "Container 1" contains also an entry named "entityManager".
Without the *delegate lookup* feature, when requesting the "myController" instance to container 2, it would take
in charge the instantiation of both entries.

However, using the *delegate lookup* feature, here is what happens when we ask the composite container for the
"myController" instance:

- The composite container asks container 1 if it contains the "myController" instance. The answer is no.
- The composite container asks container 2 if it contains the "myController" instance. The answer is yes.
- The composite container performs a `get` call on container 2 for the "myController" instance.
- Container 2 sees that "myController" has a dependency on "entityManager".
- Container 2 delegates the lookup of "entityManager" to the composite container.
- The composite container asks container 1 if it contains the "entityManager" instance. The answer is yes.
- The composite container performs a `get` call on container 1 for the "entityManager" instance.

In the end, we get a controller instantiated by container 2 that references an *entityManager* instantiated
by container 1.

### 7.4. Alternative: the fallback strategy

The first proposed approach we tried was to perform all the lookups in the "local" container,
and if a lookup fails in the container, to use the delegate container. In this scenario, the
delegate container is used in "fallback" mode.

This strategy has been described in @moufmouf blog post: http://mouf-php.com/container-interop-whats-next (solution 1).
It was also discussed [here](https://github.com/container-interop/container-interop/pull/8#issuecomment-33570697) and
[here](https://github.com/container-interop/container-interop/pull/20#issuecomment-56599631).

Problems with this strategy:

- Heavy problem regarding infinite loops
- Unable to overload a container entry with the delegate container entry

### 7.5. Alternative: force implementing an interface

A proposal was made on *container-interop* to develop a `ParentAwareContainerInterface` interface.
It was proposed here: https://github.com/container-interop/container-interop/pull/8

The interface would have had the behaviour of the delegate lookup feature but would have forced the addition of
a `setParentContainter` method:

~~~php
interface ParentAwareContainerInterface extends ReadableContainerInterface {
    /**
     * Sets the parent container associated to that container. This container will call
     * the parent container to fetch dependencies.
     *
     * @param ContainerInterface $container
     */
    public function setParentContainer(ContainerInterface $container);
}
~~~

The interface idea was first questioned by @Ocramius [here](https://github.com/container-interop/container-interop/pull/8#issuecomment-51721777).
@Ocramius expressed the idea that an interface should not contain setters, otherwise, it is forcing implementation
details on the class implementing the interface.
Then @mnapoli made a proposal for a "convention" [here](https://github.com/container-interop/container-interop/pull/8#issuecomment-51841079),
this idea was further discussed until all participants in the discussion agreed to remove the interface idea
and replace it with a "standard" feature.

**Pros:**

If we had had an interface, we could have delegated the registration of the delegate/composite container to the
delegate/composite container itself.
For instance:

~~~php
$containerA = new ContainerA();
$containerB = new ContainerB();

$compositeContainer = new CompositeContainer([$containerA, $containerB]);

// The call to 'setParentContainer' is delegated to the CompositeContainer
// It is not the responsibility of the user anymore.
class CompositeContainer {
  ...

  public function __construct($containers) {
    foreach ($containers as $container) {
      if ($container instanceof ParentAwareContainerInterface) {
        $container->setParentContainer($this);
      }
    }
    ...
  }
}

~~~

**Cons:**

Cons have been extensively discussed [here](https://github.com/container-interop/container-interop/pull/8#issuecomment-51721777).
Basically, forcing a setter into an interface is a bad idea. Setters are similar to constructor arguments,
and it's a bad idea to standardize a constructor: how the delegate container is configured into a container is an
implementation detail. This outweighs the benefits of the interface.

### 7.6 Alternative: no exception case for delegate lookups

Originally, the proposed wording for delegate lookup calls was:

> Important! The lookup MUST be performed on the delegate container **only**, not on the container itself.

This was later replaced by:

> Important! By default, the lookup SHOULD be performed on the delegate container **only**, not on the container itself.
>
> It is however allowed for containers to provide exception cases for special entries, and a way to lookup
> into the same container (or another container) instead of the delegate container.

Exception cases have been allowed to avoid breaking dependencies with some services that must be provided
by the container (on @njasm proposal). This was proposed here: https://github.com/container-interop/container-interop/pull/20#issuecomment-56597235

### 7.7. Alternative: having one of the containers act as the composite container

In real-life scenarios, we usually have a big framework (Symfony 2, Zend Framework 2, etc...) and we want to
add another DI container to this container. Most of the time, the "big" framework will be responsible for
creating the controller's instances, using it's own DI container. Until the Container PSR is fully adopted,
the "big" framework will not be aware of the existence of a composite container that it should use instead
of its own container.

For this real-life use cases, @mnapoli and @moufmouf proposed to extend the "big" framework's DI container
to make it act as a composite container.

This has been discussed [here](https://github.com/container-interop/container-interop/pull/8#issuecomment-40367194)
and [here](http://mouf-php.com/container-interop-whats-next#solution4).

This was implemented in Symfony 2 using:

- [interop.symfony.di](https://github.com/thecodingmachine/interop.symfony.di/tree/v0.1.0)
- [framework interop](https://github.com/mnapoli/framework-interop/)

This was implemented in Silex using:

- [interop.silex.di](https://github.com/thecodingmachine/interop.silex.di)

Having a container act as the composite container is not part of the delegate lookup standard because it is
simply a temporary design pattern used to make existing frameworks that do not support yet the Container PSR
play nice with other DI containers.


8. Implementations
------------------

The following projects already implement the `container-interop` version of the interface and
therefore would be willing to switch to a Container PSR as soon as it is available.

### Projects implementing `ContainerInterface`

- [Acclimate](https://github.com/jeremeamia/acclimate-container): Adapters for
  Aura.Di, Laravel, Nette DI, Pimple, Symfony DI, ZF2 Service manager, ZF2
  Dependency injection and any container using `ArrayAccess`
- [Aura.DI](https://github.com/auraphp/Aura.Di) (v3+)
- [dcp-di](https://github.com/estelsmith/dcp-di)
- [Mouf](http://mouf-php.com)
- [Njasm Container](https://github.com/njasm/container)
- [PHP-DI](http://php-di.org)
- [PimpleInterop](https://github.com/moufmouf/pimple-interop)
- [XStatic](https://github.com/jeremeamia/xstatic)

### Projects implementing the *delegate lookup* feature

- [Aura.DI](https://github.com/auraphp/Aura.Di)
- [Mouf](http://mouf-php.com)
- [PHP-DI](http://php-di.org)
- [PimpleInterop](https://github.com/moufmouf/pimple-interop)

### Middlewares implementing `ContainerInterface`

- [Alias-Container](https://github.com/thecodingmachine/alias-container): add
  aliases support to any container
- [Prefixer-Container](https://github.com/thecodingmachine/prefixer-container):
  dynamically prefix identifiers

### Projects using `ContainerInterface`

- [Slim Framework](https://github.com/slimphp/Slim/tree/develop) (v3+): a PHP micro-framework
  that helps you quickly write simple yet powerful web applications and APIs
- [interop.silex.di](https://github.com/thecodingmachine/interop.silex.di): an
  extension to [Silex](http://silex.sensiolabs.org/) that adds support for any
  *container-interop* compatible container
- [Woohoo Labs. API Framework](https://github.com/woohoolabs/api-framework): a
  micro-framework for writing APIs
- [Invoker](https://github.com/mnapoli/Invoker): a generic and extensible callable invoker.

9. People
---------
### 9.1 Editors

* [Matthieu Napoli](https://github.com/mnapoli)
* [David Négrier](https://github.com/moufmouf)

### 9.2 Sponsors

* [Paul M. Jones](https://github.com/pmjones) (Coordinator)
* [Jeremy Lindblom](https://github.com/jeremeamia)

### 9.3 Contributors

Are listed here all people that contributed in the discussions or votes (on container-interop), by alphabetical order:

- [Alexandru Pătrănescu](https://github.com/drealecs)
- [Amy Stephen](https://github.com/AmyStephen)
- [Ben Peachey](https://github.com/potherca)
- [David Négrier](https://github.com/moufmouf)
- [Don Gilbert](https://github.com/dongilbert)
- [Jason Judge](https://github.com/judgej)
- [Jeremy Lindblom](https://github.com/jeremeamia)
- [Marco Pivetta](https://github.com/Ocramius)
- [Matthieu Napoli](https://github.com/mnapoli)
- [Nelson J Morais](https://github.com/njasm)
- [Paul M. Jones](https://github.com/pmjones)
- [Phil Sturgeon](https://github.com/philsturgeon)
- [Stephan Hochdörfer](https://github.com/shochdoerfer)
- [Taylor Otwell](https://github.com/taylorotwell)

10. Relevant links
------------------

- [Container-interop's `ContainerInterface.php`](https://github.com/container-interop/container-interop/blob/master/src/Interop/Container/ContainerInterface.php)
- [List of all issues](https://github.com/container-interop/container-interop/issues?labels=ContainerInterface&milestone=&page=1&state=closed)
- [Vote for the interface name](https://github.com/container-interop/container-interop/wiki/%231-interface-name:-Vote)
- [Original article exposing the delegate lookup idea along many others](http://mouf-php.com/container-interop-whats-next)
