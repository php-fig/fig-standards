Event Dispatcher
================

Event Dispatching is a common and well-tested mechanism to allow developers to inject logic into an application easily and consistently.

The goal of this PSR is to establish a common mechanism for event-based extension and collaboration so that libraries and components may be reused more freely between various applications and frameworks.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

Having common interfaces for dispatching and handling events, allows developers to create libraries that can interact with many frameworks in a common fashion.

Some examples:

* A security framework that will prevent saving/accessing data when a user doesn't have permission.
* A common full page caching system.
* Libraries that extent other specific libraries, regardless of what framework they are both integrated into.
* A logging package to track all actions taken within the application

## Definitions

* **Event** - An Event is a message produced by an *Emitter*.  It is represented as an object that implements `EventInterface`.
* **Message** - A Message is a specific case of an Event that is unidirectional.  That is, the Emitter has no expectation that it will receive a response back from Listeners nor that Listeners will be called in any particular order.
* **Task** - A Task is a specific case of an Event that is bidirectional.  That is, the Emitter expects Listeners to be called in a logical order and that all will complete before the Task is returned to the Emitter.
* **Listener** - A Listener is any PHP callable that expects to be passed an Event.  Zero or more Listeners may be passed the same Event.  A Listener MAY enqueue some other asynchronous behavior if it so chooses.
* **Emitter** - An Emitter is any arbitrary code that wishes to send an Event.  This is also known as the "calling code".  It is not represented by any particular data structure but refers to the use case.
* **Notifier** - A Notifier is a service object that is given a Message object by an Emitter.  The Notifier is responsible for ensuring that the Message is passed to all relevant Listeners, but MUST defer determining the responsible listeners to a Listener Provider. 
* **Processor** - A Processor is a service object that is given a Task object by an Emitter.  The Processor is responsible for ensuring that the Task is passed to all relevant Listeners, but MUST defer determining the responsible listeners to a Listener Provider.
* **Dispatcher** - A Dispatcher refers to either a Notifier or a Processor.  It is not a discrete object defined by this specification but a collective noun for "A Notifier or Processor" for those cases where they can be addressed collectively.
* **Listener Provider** - A Listener Provider is responsible for determining what Listeners are relevant for a given Event, but MUST NOT call the Listeners itself.  A Listener Provider may specify zero or more relevant Listeners.

## Events

Events are objects that act as the unit of communication between an Emitter and appropriate listeners.

All Events are identified primarily by their PHP type, that is, their class and any interfaces they implement.  Events SHOULD NOT have any other identifier, such as an arbitrary string ID.

### Messages

A Message is an Event where no response from listeners is expected.  Additionally, it is understood that the invocation of listeners may be deferred to a later time (such as an async framework, queue system, etc.) or called concurrently.

Message objects SHOULD be treated as immutable.  While they MAY have mutator methods or evolvable methods on them to facilitate creation, Listeners MUST assume that the object they are passed is immutable and no changes they make will be propagated to any other Listener.

Message objects MUST support lossless serialization and deserialization.  That is, `$m == unserialize(serialize($m))` must always hold true.  Objects MAY leverage PHP’s Serializable interface, `__sleep()` or `__wakeup()` magic methods, or similar language functionality if appropriate.

### Tasks

A Task is an Event where a response from the listeners is permitted, and depending on the use case may be expected.

Task objects MAY be treated as mutable and MAY have mutator methods on them if appropriate, although that is not required.  Implementers SHOULD assume that the same object will be passed to all listeners.

A **Stoppable Task** is a special case of Task that contains additional ways to prevent further Listeners from being called.  It is indicated by implementing the `StoppableTaskInterface`.

A Task that implements `StoppableTaskInterface` MUST return `true` from `isPropagationStopped()` when whatever task it represents has been completed.  It is up to the implementer of the class to determine when that is.  For example, a Task that is asking for a PSR-7 `RequestInterface` object to be matched with a corresponding `ResponseInterface` object MAY have a `setResponse(ResponseInterface $res)` method for a Listener to call, which sets an internal flag that `isPropagationStopped()` will use to return `true` once the response has been set.

It is RECOMMENDED, but NOT REQUIRED, that Task objects support lossless serialization and deserialization.

## Listeners

A Listener may be any PHP callable.  A Listener MUST have one and only one parameter, which is the Event to which it responds.  Listeners SHOULD type hint that parameter as specifically as is relevant for their use case; that is, a Listener MAY type hint against an interface to indicate it is compatible with any Event type that implements that interface.

A Listener MUST have a `void` return, and SHOULD type hint that return explicitly.

A Listener MAY delegate actions to other code.  That includes a Listener being a thin wrapper around retrieving an object from a service container that contains the actual business logic to run, or other similar forms of indirection.  In that case the callable containing the actual business logic SHOULD conform to the same rules as if it were called directly as a Listener.

## Notifier

A Notifier is a service object implementing `MessageNotifierInterface`. It is used for cases where an Emitter wishes to inform relevant Listeners that some action has been taken or some change has occurred, but does not care what action the Listeners wish to take in result.  That is, it is explicitly declining to have any response from the Listeners.

Listeners for a Notify event MUST NOT assume any awareness of other Listeners that may or may not be called.  A Notify Dispatcher MAY take additional steps to enforce such separation but is not required to.  (For example, cloning the message before passing it to each listener.)

A Notifier

* MAY call relevant Listeners in any order it wishes.
* MAY delay calling Listeners until some later point, such as using a queue system.
* MAY call multiple Listeners concurrently (such as in an asynchronous system or a queue system with multiple worker processes).

An Emitter calling a Notifier MUST NOT assume that any listeners have fired yet by the time the Notifier has returned.

A Notifier MUST allow for the case where zero Listeners are found and MUST NOT generate an error condition in that case.

### Error handling

A Notifier MUST ensure that any `\Throwable` generated by a listener does not impact or prevent other listeners from triggering normally.  It is RECOMMENDED that the Notifier log the error event but the only requirement is that one listener's exception MUST NOT block another listener.

## Processor

A Processor is a service object implementing `TaskProcessorInterface`.  It is used for cases where an Emitter wishes to provide data to listeners and receive data back from them.  Examples of Task use cases include:

* Passing an object to a series of Listeners to allow it to be modified before it is saved to a persistence system.
* Passing a collection to a series of Listeners to allow them to register values with it so that the Emitter may act on all of the collected information.
* Passing a collection to a series of Listeners to allow them to modify the collection in some way before the Emitter takes action.
* Passing some contextual information to a series of Listeners so that all of them may "vote" on what action to take, with the Emitter deciding based on the aggregate information provided.
* Passing an object to a series of listeners and allowing one of them to set a value and then prevent further listeners from running.

Tasks passed to a Processor SHOULD have some sort of mutator methods on them to allow Listeners to modify the object.  The nature of those methods is up to each implementation to determine.

A Processor

* MUST call Listeners synchronously in the order they are returned from a ListenerProvider.
* MUST return the Task it was passed after it is done invoking Listeners.
* MUST NOT return to the Emitter until all Listeners have executed.
* As an exception to the previous point, if the Task is a Promise then the the Dispatcher MAY return that Promise before all Listeners have executed.  However, the Promise MUST NOT be treated as fulfilled until all Listeners have executed.

If passed a Stoppable Task, a Processor

* MUST call `isPropagationStopped()` on the Task after each Listener has been called.  If that method returns `true` it MUST return the Task to the Emitter immediately and MUST NOT call any further Listeners.

### Error handling

An Exception or Error thrown by a Listener MUST block the execution of any further Listeners.  An Error or Exception thrown by a Listener MUST be allowed to propagate back up to the caller.

A Processor MAY catch a thrown object to log it, allow additional action to be taken, etc., but then MUST rethrow the original throwable.

## Listener Provider

A Listener Provider is a service object responsible for determining what Listeners are relevant to and should be call for a given Event.  It may determine both what Listeners are relevant and the order in which to return them by whatever means it chooses.  That MAY include

* Allowing for some form of registration mechanism so that implementers may assign a Listener to an Event in a fixed order.
* Deriving a list of applicable Listeners through reflection based on the type and implemented interfaces of the Event.
* Generating a compiled list of Listeners ahead of time that may be consulted at runtime.
* Implementing some form of access control so that certain Listeners will only be called if the current user has a certain permission.
* Extracting some information from an object referenced by the Event, such as an Entity, and calling pre-defined lifecycle methods on that object.

Or some combination of those, or some other mechanism as desired.

All Listeners returned by a Listener Provider MUST be type-compatible with the Event.  That is, calling `$listener($event)` MUST NOT produce a `TypeError`.

## Object composition

A Notifier or Processor SHOULD compose a Listener Provider to determine relevant listeners.  It is RECOMMENDED that a Listener Provider be implemented as a distinct object from the Notifier or Processor but that is NOT REQUIRED.

It is RECOMMENDED that a Notifier and Processor be implemented as distinct objects.  However, an object MAY implement both interfaces if deemed appropriate.

## Interfaces

### General event interfaces

```php
namespace Psr\EventDispatcher;

/**
 * Marker interface indicating an event instance.
 *
 * Event instances may contain zero methods, or as many methods as they
 * want. The interface MUST be implemented, however, to provide type-safety
 * to both listeners as well as listener providers.
 */
interface EventInterface
{
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
     * @param EventInterface $event
     *   An event for which to return the relevant listeners.
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(EventInterface $event) : iterable;
}
```

### Message Interfaces

```php
namespace Psr\EventDispatcher;


/**
 * This is a marker interface used to identify message events.
 */
interface MessageInterface extends EventInterface
{
}
```

```php
namespace Psr\EventDispatcher;

/**
 * Defines a notifier for message events.
 */
interface MessageNotifierInterface
{
    /**
     * Notify listeners of a message event.
     *
     * This method MAY act asynchronously.  Callers SHOULD NOT
     * assume that any action has been taken when this method
     * returns.
     *
     * @param MessageInterface $event
     *   The event to notify listeners of.
     */
    public function notify(MessageInterface $event) : void;
}
```

### Task Interfaces

namespace Psr\EventDispatcher;

```php
/**
 * This is a marker interface used to identify task events.
 */
interface TaskInterface extends EventInterface
{
}
```

```php
namespace Psr\EventDispatcher;

/**
 * A Task event whose processing my be interrupted when a listener has completed processing this event.
 *
 * A Processor implementation MUST check to determine if a Task
 * is marked as stopped after each listener is called.  If it is then it should
 * return immediately without calling any further Listeners.
 */
interface StoppableTaskInterface extends TaskInterface
{
    /**
     * Is propagation stopped?
     *
     * This will typically only be used by the Processor to determine if the
     * previous listener halted propagation.
     *
     * @return bool
     *   True if the Task is complete and no further listeners should be called.
     *   False to continue calling listeners.
     */
    public function isPropagationStopped() : bool;
}
```

```php
/**
 * Defines a processor for task events.
 */
interface TaskProcessorInterface
{
    /**
     * Dispatches a Task to all registered listeners.
     *
     * @param TaskInterface $event
     *  The task to process.
     *
     * @return TaskInterface
     *  The task that was passed, now modified by callers.
     */
    public function process(TaskInterface $event) : TaskInterface;
}
```
