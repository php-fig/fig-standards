Event Dispatcher
================

Event Dispatching is a common and well-tested mechanism to allow developers to inject logic into an application easily and consistently.

The goal of this PSR is to establish a common mechanism for event-based extension and collaboration so that libraries and components may be reused more freely between various applications and frameworks.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

Having common interfaces for dispatching and handling events allows developers to create libraries that can interact with many frameworks and other libraries in a common fashion.

Some examples:

* A security framework that will prevent saving/accessing data when a user doesn't have permission.
* A common full page caching system.
* Libraries that extend other libraries, regardless of what framework they are both integrated into.
* A logging package to track all actions taken within the application

## Definitions

* **Event** - An Event is a message produced by an *Emitter*.  It may be any arbitrary PHP object.
* **Listener** - A Listener is any PHP callable that expects to be passed an Event.  Zero or more Listeners may be passed the same Event.  A Listener MAY enqueue some other asynchronous behavior if it so chooses.
* **Emitter** - An Emitter is any arbitrary code that wishes to dispatch an Event.  This is also known as the "calling code".  It is not represented by any particular data structure but refers to the use case.
* **Dispatcher** - A Dispatcher is a service object that is given an Event object by an Emitter.  The Dispatcher is responsible for ensuring that the Event is passed to all relevant Listeners, but MUST defer determining the responsible listeners to a Listener Provider.
* **Listener Provider** - A Listener Provider is responsible for determining what Listeners are relevant for a given Event, but MUST NOT call the Listeners itself.  A Listener Provider may specify zero or more relevant Listeners.

## Events

Events are objects that act as the unit of communication between an Emitter and appropriate Listeners.

Event objects MAY be mutable should the use case call for Listeners providing information back to the Emitter.  However, if no such bidirectional communication is needed then it is RECOMMENDED that the Event be defined as immutable; i.e., defined such that it lacks mutator methods.

Implementers MUST assume that the same object will be passed to all Listeners.

It is RECOMMENDED, but NOT REQUIRED, that Event objects support lossless serialization and deserialization; `$event == unserialize(serialize($event))` SHOULD hold true.  Objects MAY leverage PHP’s `Serializable` interface, `__sleep()` or `__wakeup()` magic methods, or similar language functionality if appropriate.

## Stoppable Events

A **Stoppable Event** is a special case of Event that contains additional ways to prevent further Listeners from being called.  It is indicated by implementing the `StoppableEventInterface`.

An Event that implements `StoppableEventInterface` MUST return `true` from `isPropagationStopped()` when whatever Event it represents has been completed.  It is up to the implementer of the class to determine when that is.  For example, an Event that is asking for a PSR-7 `RequestInterface` object to be matched with a corresponding `ResponseInterface` object could have a `setResponse(ResponseInterface $res)` method for a Listener to call, which causes `isPropagationStopped()` to return `true`.

## Listeners

A Listener may be any PHP callable.  A Listener MUST have one and only one parameter, which is the Event to which it responds.  Listeners SHOULD type hint that parameter as specifically as is relevant for their use case; that is, a Listener MAY type hint against an interface to indicate it is compatible with any Event type that implements that interface, or to a specific implementation of that interface.

A Listener SHOULD have a `void` return, and SHOULD type hint that return explicitly.  A Dispatcher MUST ignore return values from Listeners.

A Listener MAY delegate actions to other code. That includes a Listener being a thin wrapper around an object that runs the actual business logic.

A Listener MAY enqueue information from the Event for later processing by a secondary process, using cron, a queue server, or similar techniques.  It MAY serialize the Event object itself to do so; however, care should be taken that not all Event objects may be safely serializable. A secondary process MUST assume that any changes it makes to an Event object will NOT propagate to other Listeners.

## Dispatcher

A Dispatcher is a service object implementing `EventDispatcherInterface`.  It is responsible for retrieving Listeners from a Listener Provider for the Event dispatched, and invoking each Listener with that Event.

A Dispatcher:

* MUST call Listeners synchronously in the order they are returned from a ListenerProvider.
* MUST return the same Event object it was passed after it is done invoking Listeners.
* MUST NOT return to the Emitter until all Listeners have executed.

If passed a Stoppable Event, a Dispatcher

* MUST call `isPropagationStopped()` on the Event before each Listener has been called.  If that method returns `true` it MUST return the Event to the Emitter immediately and MUST NOT call any further Listeners.  This implies that if an Event is passed to the Dispatcher that always returns `true` from `isPropagationStopped()`, zero listeners will be called.

A Dispatcher SHOULD assume that any Listener returned to it from a Listener Provider is type-safe.  That is, the Dispatcher SHOULD assume that calling `$listener($event)` will not produce a `TypeError`.

[Promise object]: https://promisesaplus.com/

### Error handling

An Exception or Error thrown by a Listener MUST block the execution of any further Listeners.  An Exception or Error thrown by a Listener MUST be allowed to propagate back up to the Emitter.

A Dispatcher MAY catch a thrown object to log it, allow additional action to be taken, etc., but then MUST rethrow the original throwable.

## Listener Provider

A Listener Provider is a service object responsible for determining what Listeners are relevant to and should be called for a given Event.  It may determine both what Listeners are relevant and the order in which to return them by whatever means it chooses.  That MAY include:

* Allowing for some form of registration mechanism so that implementers may assign a Listener to an Event in a fixed order.
* Deriving a list of applicable Listeners through reflection based on the type and implemented interfaces of the Event.
* Generating a compiled list of Listeners ahead of time that may be consulted at runtime.
* Implementing some form of access control so that certain Listeners will only be called if the current user has a certain permission.
* Extracting some information from an object referenced by the Event, such as an Entity, and calling pre-defined lifecycle methods on that object.
* Delegating its responsibility to one or more other Listener Providers using some arbitrary logic.

Any combination of the above, or other mechanisms, MAY be used as desired.

Listener Providers SHOULD use the class name of an Event to differentiate one event from another.  They MAY also consider any other information on the event as appropriate.

Listener Providers MUST treat parent types identically to the Event's own type when determining listener applicability.  In the following case:

```php
class A {}

class B extends A {}

$b = new B();

function listener(A $event): void {};
```

A Listener Provider MUST treat `listener()` as an applicable listener for `$b`, as it is type compatible, unless some other criteria prevents it from doing so.

## Object composition

A Dispatcher SHOULD compose a Listener Provider to determine relevant listeners.  It is RECOMMENDED that a Listener Provider be implemented as a distinct object from the Dispatcher but that is NOT REQUIRED.

## Interfaces

```php
namespace Psr\EventDispatcher;

/**
 * Defines a dispatcher for events.
 */
interface EventDispatcherInterface
{
    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object $event
     *   The object to process.
     *
     * @return object
     *   The Event that was passed, now modified by listeners.
     */
    public function dispatch(object $event);
}
```

```php
namespace Psr\EventDispatcher;

/**
 * Mapper from an event to the listeners that are applicable to that event.
 */
interface ListenerProviderInterface
{
    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event) : iterable;
}
```

```php
namespace Psr\EventDispatcher;

/**
 * An Event whose processing may be interrupted when the event has been handled.
 *
 * A Dispatcher implementation MUST check to determine if an Event
 * is marked as stopped after each listener is called.  If it is then it should
 * return immediately without calling any further Listeners.
 */
interface StoppableEventInterface
{
    /**
     * Is propagation stopped?
     *
     * This will typically only be used by the Dispatcher to determine if the
     * previous listener halted propagation.
     *
     * @return bool
     *   True if the Event is complete and no further listeners should be called.
     *   False to continue calling listeners.
     */
    public function isPropagationStopped() : bool;
}
```
