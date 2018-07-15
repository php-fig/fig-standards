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
* **Listener** - A Listener is any PHP callable that expects to be passed an Event.  Zero or more Listeners may be passed the same Event.
* **Emitter** - An Emitter is any arbitrary code that wishes to send an Event.  This is also known as the "calling code".  It is not represented by any particular data structure but refers to the use case.
* **Dispatcher** - A Dispatcher is an object that is given an Event object by an Emitter.  The Dispatcher is responsible for ensuring the Event is passed to all relevant Listeners.
* **Listener Provider** - A Listener Provider is responsible for determining what Listeners are relevant for a given Event, but MUST NOT call the Listeners itself.  A Listener Provider may specify zero or more relevant Listeners.

## Events

Events are messages.  Depending on the use case that message may expect a response or not.  If so, Listeners are expected to modify the Event object in some fashion in order to provide that response.

A **Stoppable Event** is a special case of Event that contains additional ways to prevent further Listeners from being called.  It is indicated by implementing the `StoppableEventInterface`.

It is RECOMMENDED that Stoppable Events invoke `stopPropagation()` on themselves automatically when the answer is provided.  For example, an Event that is asking for a PSR-7 `RequestInterface` object to be matched with a corresponding `ResponseInterface` object MAY have a `setResponse(ResponseInterface $res)` method for a Listener to call, which calls `$this->stopPropagation()` once the response is set.

All Events are identified primarily by their PHP type, that is, their class and any interfaces they implement.  Events SHOULD NOT have any other identifier, such as an arbitrary string ID.

## Listeners

A Listener may be any PHP callable.  A Listener MUST have one and only one parameter, which is the Event to which it responds.  Listeners SHOULD type hint that parameter as specifically as is relevant for their use case; that is, a Listener MAY type hint against an interface to indicate it is compatible with any Event type that implements that interface.

A Listener MUST have a `void` return, and SHOULD type hint that return explicitly.

A Listener MAY delegate actions to other code.  That includes a Listener being a thin wrapper around retrieving an object from a service container that contains the actual business logic to run, or other similar forms of indirection.  In that case the callable containing the actual business logic SHOULD conform to the same rules as if it were called directly as a Listener.

## Dispatchers

This specification defines two (2) general categories of Dispatcher.  Each is represented by a different interface.  Implementers MAY implement both interfaces on a single object if they so choose or implement them on distinct objects.

All Dispatchers MUST allow for the case where zero Listeners are found and MUST NOT generate an error condition in that case.

### Notify Dispatcher

A Notify Dispatcher is used for cases where an Emitter wishes to inform relevant Listeners that some action has been taken or some change has occurred, but does not care what action the Listeners wish to take in result.  That is, it is explicitly declining to have any response from the Listeners.

The Event passed to a Notify Dispatcher SHOULD be implemented in an immutable fashion.  Listeners for a Notify event MUST NOT assume any awareness of other Listeners that may or may not be called.  A Notify Dispatcher MAY take additional steps to enforce such separation but is not required to.  (For example, cloning the event before passing it to each listener.)

A Notify Dispatcher

* MAY call relevant Listeners in any order it wishes.
* MAY delay calling listeners until some later point, such as using a queue system.
* MAY call multiple Listeners concurrently (such as in an asynchronous system or a queue system with multiple worker processes).

An Emitter calling a Notify Dispatcher MUST NOT assume that any listeners have fired yet by the time the Notify Dispatcher has returned.

A Notify Dispatcher is incompatible with Stoppable Events.  If passed a Stoppable Event the Dispatcher MUST throw an `InvalidArgumentException`.

### Modify Dispatcher

A Modify Dispatcher is used for cases where an Emitter wishes to provide data to listeners and receive data back from them.  Examples of modify use cases include:

* Passing an object to a series of Listeners to allow it to be modified before it is saved to a persistence system.
* Passing a collection to a series of Listeners to allow them to register values with it so that the Emitter may act on all of the collected information.
* Passing a collection to a series of Listeners to allow them to modify the collection in some way before the Emitter takes action.
* Passing some contextual information to a series of Listeners so that all of them may "vote" on what action to take, with the Emitter deciding based on the aggregate information provided.
* Passing an object to a series of listeners and allowing one of them to set a value and then prevent further listeners from running.

Events passed to a Modify Dispatcher SHOULD have some sort of mutator methods on them to allow Listeners to modify the object.  The nature of those methods is up to each implementation to determine.

A Modify Dispatcher

* MUST call Listeners synchronously in the order they are returned from a ListenerProvider.
* MUST NOT return to the Emitter until all Listeners have executed.
* As an exception to the previous point, if the Event is a Promise then the the Dispatcher MAY return that Promise before all Listeners have executed.  However, the Promise MUST NOT be treated as fulfilled until all Listeners have executed.

If passed a Stoppable Event, a Modify Dispatcher 

* MUST call `isStopped()` on the event after each Listener has been called.  If that method returns `true` it MUST return the event to the Emitter immediately and MUST NOT call any further Listeners.

## Listener Provider

A Listener Provider is a service object responsible for determining what Listeners are relevant to and should be call for a given Event.  It may determine both what Listeners are relevant and the order in which to return them by whatever means it chooses.  That MAY include

* Allowing for some form of registration mechanism so that implementers may assign a Listener to an Event in a fixed order.
* Deriving a list of applicable Listeners through reflection based on the type and implemented interfaces of the Event.
* Generating a compiled list of Listeners ahead of time that may be consulted at runtime.
* Implementing some form of access control so that certain Listeners will only be called if the current user has a certain permission.

Or some combination of those, or some other mechanism as desired.

All Listeners returned by a Listener Provider MUST be type-compatible with the Event.  That is, calling `$listener($event)` MUST NOT produce a `TypeError`.

It is RECOMMENDED that a Listener Provider be implemented as a distinct object from the Event Dispatcher but that is NOT REQUIRED.

## Interfaces

See [the repository](https://github.com/php-fig/event-dispatcher).  The final interfaces will be copied back here before it goes up for a vote.  For now it's easier to just single-source them.
