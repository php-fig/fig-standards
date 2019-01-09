Event Dispatcher Meta Document
==============================

## 1. Summary

The purpose of this document is to describe the rationale and logic behind the Event Dispatcher PSR.

## 2. Why Bother?

Many libraries, components, and frameworks have long supported mechanisms for allowing arbitrary third party code to interact with them.  Most are variations on the classic Observer pattern, often mediated through an intermediary object or service.  Others take a more Aspect-Oriented Programming (AOP) approach.  Nonetheless all have the same basic concept: interrupt program flow at a fixed point to provide information to arbitrary third party libraries with information about the action being performed and allow them to either react or influence the program behavior.

This is a well-established model, but a standard mechanism by which libraries do so will allow them to interoperate with more and more varied third party libraries with less effort by both the original developer and extension developers.

## 3. Scope

### 3.1 Goals

* Simplify and standardize the process by which libraries and components may expose themselves to extension via "events" so that they may be more easily incorporated into applications and frameworks.
* Simplify and standardize the process by which libraries and components may register an interest in responding to an event so that they may be more easily incorporated into arbitrary applications and frameworks.
* To the extent feasible, ease the process for existing code bases to transition toward this specification.

### 3.2 Non-Goals

* Asynchronous systems often have a concept of an "event loop" to manage interleaving coroutines.  That is an unrelated matter and explicitly irrelevant to this specification.
* Storage systems implementing an "Event Source" pattern also have a concept of an "event".  That is unrelated to the events discussed here and explicitly out of scope.
* Strict backward compatibility with existing event systems is not a priority and is not expected.
* While this specification will undoubtedly suggest implementation patterns, it does not seek to define One True Event Dispatcher Implementation, only how callers and listeners communicate with that dispatcher.

## 4. Approaches

### 4.1 Use cases considered

The Working Group identified four possible workflows for event passing, based on use cases seen in the wild in various systems.

* One-way notification.  ("I did a thing, if you care.")
* Object enhancement.  ("Here's a thing, please modify it before I do something with it.")
* Collection.  ("Give me all your things, that I may do something with that list.")
* Alternative chain.  ("Here's a thing; the first one of you that can handle it do so, then stop.")

On further review, the Working Goup determined that:

* Collection was a special case of Object enhancement (the collection being the object that is enhanced)
* Alternative chain is similarly a special case of Object enhancement, as the signature is identical and the dispatch workflow is nearly identical, albeit with an extra check included.
* One-way notification is a degenerate case of the others, or can be represented as such.

Although in concept one-way notification can be done asynchronously (including delaying it through a queue), in practice few explicit implementations of that model exist.  That provides fewer places from which to draw guidance on details such as proper error handling.  After much consideration the Working Group elected to not provide an explicitly separate workflow for one-way notification as it could be adequately represented as a degenerate case of the others.

### 4.2 Example applications

* Indicating that some change in system configuration or some user action has occurred and allowing other systems to react in ways that do not affect program flow (such as sending an email or logging the action).
* Passing an object to a series of Listeners to allow it to be modified before it is saved to a persistence system.
* Passing a collection to a series of Listeners to allow them to register values with it or modify existing values so that the Emitter may act on all of the collected information.
* Passing some contextual information to a series of Listeners so that all of them may "vote" on what action to take, with the Emitter deciding based on the aggregate information provided.
* Passing an object to a series of listeners and allowing any listener to terminate the process early before other listeners have completed.

### 4.3 Immutable events

Initially the Working Group wished to define all Events as immutable message objects, similar to PSR-7.  However, that proved problematic in all but the one-way notification case.  In the other scenarios Listeners needed a way to return data to the caller.  In concept there were three possible avenues:

* Make the event mutable and modify it in place.
* Require that events be evolvable (immutable but with `with*()` methods like PSR-7 and PSR-13) and that listeners return the event to pass along.
* Make the Event immutable but aggregate and return the return value from each Listener.

However, Stoppable Events (the alternative chain case) also needed to have a channel by which to indicate that further listeners should not be called.  That could be done either by:

* Modifying the Event (e.g., calling a `stopPropagation()` method)
* Returning a sentinel value from the listener (`true` or `false`) to indicate that propagation should terminate.
* Evolving the Event to be stopped (`withPropagationStopped()`)

Each of these alternatives have drawbacks. The first means that, at least for the purposes of indicating propagation status, events must be mutable. The second requires that listeners return a value, at least when they intend to halt event propagation; this could have ramifications with existing libraries, and potential issues in terms of documentation. The third requires that listeners return the event or mutated event in all cases, and would require dispatchers to test to ensure that the returned value is of the same type as the value passed to the listener; it effectively puts an onus both on consumers and implementers, and thus raising more potential integration issues.

Additionally, a desired feature was the ability to derive whether or not to stop propagation based on some values collected from the listeners.  (For example, to stop when one of them has provided a certain value, or after at least three of them have indicated a "reject this request" flag, or similar.)  While technically possible to implement as an evolvable object, such behavior is intrinsically stateful so would be highly cumbersome for both implementers and users.

Having listeners return evolvable events also posed a challenge.  That pattern is not used by any known implementations in PHP or elsewhere.  It also relies on the listener to remember to return the Event (additional work for the listener author) and to not return some other, new object that might not be fully compatible with later listeners (such as a subclass or superclass of the Event).

Immutable events also rely on the event definer to respect the admonition to be immutable.  Events are, by nature, very loosely designed and the potential for implementers to ignore that part of the spec, even inadvertently, is high.

That left two possible options:

* Allow events to be mutable.
* Require, but be unable to enforce, immutable objects with a high-ceremony interface, more work for listener authors, and a higher potential for breakage that may not be detectable at compile time.

By "high-ceremony", we imply that verbose syntax and/or implementations would be required.  In the former case, listener authors would need to (a) create a new instance with the propagation flag toggled, and (b) return the new instance so that the dispatcher could examine it:

```php
function (SomeEvent $event) : SomeEvent
{
    // do some work
    return $event->withPropagationStopped();
}
```

In the latter case, dispatcher implementations, checks on the return value would be required:

```php
foreach ($provider->getListenersForEvent($event) as $listener) {
    $returnedEvent = $listener($event);
    
    if (! $returnedEvent instanceof $event) {
        // This is an exceptional case!
        // 
        // We now have an event of a different type, or perhaps nothing was
        // returned by the listener. An event of a different type might mean:
        // 
        // - we need to trigger the new event
        // - we have an event mismatch, and should raise an exception
        // - we should attempt to trigger the remaining listeners anyway
        // 
        // In the case of nothing being returned, this could mean any of:
        // 
        // - we should continue triggering, using the original event
        // - we should stop triggering, and treat this as a request to
        //   stop propagation
        // - we should raise an exception, because the listener did not
        //   return what was expected
        //
        // In short, this becomes very hard to specify, or enforce.
    }

    if ($returnedEvent instanceof StoppableEventInterface
        && $returnedEvent->isPropagationStopped()
    ) {
        break;
    }
}
```

In both situations, we would be introducing more potential edge cases, with little benefit, and few language-level mechanisms to guide developers to correct implementation.

Given these options the Working Group felt mutable events were the safer alternative.

That said, *there is no requirement that an Event be mutable*.  Implementers should provide mutator methods on an Event object *if and only if it is necessary* and appropriate to the use case at hand.

### 4.4 Listener registration

Experimentation during development of the specification determined that there were a wide range of viable, legitimate means by which a Dispatcher could be informed of a listener.  A listener:

* could be registered explicitly;
* could be the registered explicitly based on reflection of its signature;
* could be registered with a numeric priority order;
* could be registered using a before/after mechanism to control ordering more precisely;
* could be registered from a service container;
* could use a pre-compile step to generate code;
* could be based on method names on objects in the event itself;
* could be limited to certain situations or contexts based on arbitrarily complex logic (only for certain users, only on certain days, only if certain system settings are present, etc).

These and other mechanisms all exist in the wild today in PHP, all are valid use cases worth supporting, and few if any can be conveniently represented as a special case of another.  That is, standardizing one way, or even a small set of ways, to inform the system of a listener turned out to be impractical if not impossible without cutting off many use cases that should be supported.

The Working Group therefore chose to encapsulate the registration of listeners behind the `ListenerProviderInterface`.  A provider object may have an explicit registration mechanism available, or multiple such mechanisms, or none.  It could also be generated code produced by some compile step.  That is up to the implementer.  However, that also splits the responsibility of managing the process of dispatching an event from the process of mapping an event to listeners.  That way different implementations may be mixed-and-matched with different provider mechanisms as needed.

It is even possible, and potentially advisable, to allow libraries to include their own Providers that get aggregated into a common provider that aggregates their listeners to return to the Dispatcher.  That is one possible way to handle arbitrary listener registration within an arbitrary framework, although the Working Group is clear that is not the only option.

While combining the Dispatcher and Provider into a single object is a valid and permissible degenerate case, it is NOT RECOMMENDED as it reduces the flexibility of system integrators.  Instead, the provider SHOULD be composed as a dependent object.

### 4.5 Return values

Per the spec, a Dispatcher MUST return the event it was passed to the Emitter.  That is done to provide a more ergonomic experience for users.  That is, it allows short-hands similar to the following:

```php
$event = $dispatcher->dispatch(new SomeEvent('some context'));

$items = $dispatcher->dispatch(new ItemCollector())->getItems();
```

The `EventDispatcher::dispatch()` interface, however, has no return type specified.  That is primarily for backward compatibility with existing implementations to make it easier for them to adopt the new interface.  Additionally, as Events can be any arbitrary object the return type could only have been `object`, which would provide only minimal (albeit non-zero) value, as that type declaration would not provide IDEs with any useful information nor would it effectively enforce that the same Event is returned.  The method return was thus left syntactically untyped.  However, returning the same Event object from `dispatch()` is still a requirement and failure to do so is a violation of the specification.

## 5. People

The Event Manager Working Group consisted of:

### 5.1 Editor

* Larry Garfield

### 5.2 Sponsor

Cees-Jan Kiewiet

### Working Group Members

Benjamin Mack
Elizabeth Smith
Ryan Weaver
Matthew Weier O'Phinney

## 6. Votes

* [Entrance vote](https://groups.google.com/d/topic/php-fig/6kQFX-lhuk4/discussion)

7. Relevant Links
-----------------

* [Inspiration Mailing List Thread](https://groups.google.com/forum/#!topic/php-fig/-EJOStgxAwY)
* [Entrance vote](https://groups.google.com/d/topic/php-fig/6kQFX-lhuk4/discussion)
* [Informal poll on package structure](https://docs.google.com/forms/d/1fvhYUH6xvPgJ1UW9I-3pMGPUtxkt5_Ph6_x_3qXHIuM/edit#responses)
* [Informal poll on naming structure](https://docs.google.com/forms/d/1Rs6APuwNx4k2VzJbTgieeNvN48kLu7CG8qn6Dd2FhTw/edit#responses)
