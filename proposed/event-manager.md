Event Manager
=============

Event Dispatching allows developer to inject logic into an application easily.
Many frameworks implement some form of a event dispatching that allows users to
inject functionality with the need to extend classes.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

Having common interfaces for dispatching and handling events, allows developers
to create libraries that can interact with many frameworks in a common fashion.

Some examples:

* Security framework that will prevent saving/accessing data when a user
doesn't have permission.
* A Common full page caching system
* Logging package to track all actions taken within the application

## Terms

*   **Event** - An action that about to take place (or has taken place).  The
event name MUST only contain the characters `A-Z`, `a-z`, `0-9`, `_`, and '.'.
It is RECOMMENDED that words in event names be separated using '.'
ex. 'foo.bar.baz.bat'

*   **Listener** - A list of callbacks that are passed the EventInterface and
MAY return a result.  Listeners MAY be attached to the EventManager with a
priority.  Listeners MUST BE called based on priority.

## Components

There are 2 interfaces needed for managing events:

1. An event object which contains all the information about the event.
2. The event manager which holds all the listeners

### EventInterface

The EventInterface defines the methods needed to dispatch an event.  Each event
MUST contain a event name in order trigger the listeners. Each event MAY have a
target which is an object that is the context the event is being triggered for.
OPTIONALLY the event can have additional parameters for use within the event.

The event MUST contain a propegation flag that signals the EventManager to stop
passing along the event to other listeners.

~~~php

namespace Psr\EventManager;

/**
 * Representation of an event
 */
interface EventInterface
{
    /**
     * Get event name
     *
     * @return string
     */
    public function getName();

    /**
     * Get target/context from which event was triggered
     *
     * @return null|string|object
     */
    public function getTarget();

    /**
     * Get parameters passed to the event
     *
     * @return array
     */
    public function getParams();

    /**
     * Get a single parameter by name
     *
     * @param  string $name
     * @return mixed
     */
    public function getParam($name);

    /**
     * Set the event name
     *
     * @param  string $name
     * @return void
     */
    public function setName($name);

    /**
     * Set the event target
     *
     * @param  null|string|object $target
     * @return void
     */
    public function setTarget($target);

    /**
     * Set event parameters
     *
     * @param  array $params
     * @return void
     */
    public function setParams(array $params);

    /**
     * Indicate whether or not to stop propagating this event
     *
     * @param  bool $flag
     */
    public function stopPropagation($flag);

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped();
}
~~~

### EventManagerInterface

The EventManager holds all the listeners for a particular event.  Since an
event can have many listeners that each return a result, the EventManager
 MUST return the result from the last listener.

~~~php

namespace Psr\EventManager;

/**
 * Interface for EventManager
 */
interface EventManagerInterface
{
    /**
     * Attaches a listener to an event
     *
     * @param string $event the event to attach too
     * @param callable $callback a callable function
     * @param int $priority the priority at which the $callback executed
     * @return bool true on success false on failure
     */
    public function attach($event, $callback, $priority = 0);

    /**
     * Detaches a listener from an event
     *
     * @param string $event the event to attach too
     * @param callable $callback a callable function
     * @return bool true on success false on failure
     */
    public function detach($event, $callback);

    /**
     * Clear all listeners for a given event
     *
     * @param  string $event
     * @return void
     */
    public function clearListeners($event);

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @param  array|object $argv
     * @return mixed
     */
    public function trigger($event, $target = null, $argv = array());
}
~~~
