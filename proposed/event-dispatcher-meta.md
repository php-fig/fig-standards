Event Dispatcher Meta Document
==============================

## 1. Summary

The purpose of this document is to describe the rationale and logic behind the Event Dispatcher PSR.

## 2. Why Bother?

Many libraries, components, and frameworks have long supported mechanisms for allowing arbitrary third party code to interact with them.  Most are variations on the classic Observer pattern, often mediated through an intermediary object or service.  Others take a more Aspect-Oriented Programming (AOP) approach.  Nonetheless all have the same basic concept: interupt program flow at a fixed point to provide information to arbitrary third party libraries with information about the action being performed and allow them to either react or influence the program behavior.

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

On further review, it was determined that Collection was a special case of Object enhancement (the collection being the object that is enhanced).  Alternative chain is similarly a special case of Object enhancement, as the signature is identical and the dispatch workflow is nearly identical.  That leaves two relevant workflows workflows:

* Notification
* Modification

Notification can safely be done asynchronously (including delaying it through a queue) but Modificatoin by nature involve passing data back to the caller and thus must be synchronous.  Despite that difference the Working Group determined that the use cases were close enough to be considered in a single PSR.  The two different workflows however are represented by two different but related dispatcher interfaces.

### 4.2 Immutable events

Initially the Working Group wished to define all Events as immutable message objects, similar to PSR-7.  However, that proved problematic in the Modification case.  In both of those cases Listeners needed a way to return data to the caller.  In concept there were three possible avenues:

* Make the event mutable and modify it in place.
* Require that events be evolvable (immutable but with `with*()` methods like PSR-7 and PSR-13) and that listeners return the event to pass along.
* Make the Event immutable but aggregate and return the return value from each Listener.

However, Stoppable Events (the alternative chain case) also needed to have a channel by which to indicate that further listeners should not be called.  That could be done either by:

* Modifying the event (`stopPropagation()`)
* Evolving the event to be stopped (`withPropagationStopped()`)
* Returning a sentinel value from the listener (`true` or `false`) to indicate that propagation should terminate.

Of those, the third would mandate a mutable event object as the return value was already in use.  The first would mandate a mutable event in at least some cases.  And the second seemed unnecessarily ceremonial and pedantic.

Having listeners return evolvable events also posed a challenge.  That pattern is not used by any known implementations in PHP or elsewhere.  It also relies on the listener to remember to return the object (extra work for the listener author) and to not return some other, new object that might not be fully compatible with later listeners (such as a subclass or superclass of the event).

Immutable events also rely on the event definer to respect the admonition to be immutable.  Events are, by nature, very loosely designed and the potential for implementers to ignore that part of the spec, even inadvertently, is high.

That left two possible options:

* Allow events to be mutable.
* Require, but be unable to enforce, immutable objects with a high-ceremony interface, more work for listener authors, and a higher potential for breakage that may not be detectable at compile time.

Given those options the Working Group felt mutable events was the safer alternative.

As the Notification use case would technically allow for immutable events to be viable, however, the specification defines that those events SHOULD be immutable, or at least treated as such, and dispatcher implementers are welcome to take steps to enforce that.

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
