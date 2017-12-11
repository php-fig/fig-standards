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

**TODO**

*   **Event** - An action that about to take place (or has taken place).  The
event name MUST only contain the characters `A-Z`, `a-z`, `0-9`, `_`, and '.'.
It is RECOMMENDED that words in event names be separated using '.'
ex. 'foo.bar.baz.bat'

*   **Listener** - A list of callbacks that are passed the EventInterface and
MAY return a result.  Listeners MAY be attached to the EventManager with a
priority.  Listeners MUST BE called based on priority.

## Components

**TODO**

### Event

The Event defines base contract needed to dispatch an event. Each event MUST contain a event name in order trigger the listeners. Each event MUST be immutable. Event class OPTIONALLY may be inherited by concrete event class. OPTIONALLY the event can have additional parameters for use within the event.

~~~php

/**
 * Immutable message that represents something took place in the past.
 */
class Event
{
    private $name;
    private $params;

    /**
     * @param string $name
     * @param array $params
     */
    public function __construct($name, array $params = array())
    {
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
~~~

### Hook

The Hook defines base contract for hook message. Unlike an event, hook message is mutable. Hook OPTIONALLY may be inherited by concrete hook class.

~~~php
/**
 * Mutable message that represents a message for hook-point.
 */
class Hook
{
    private $name;
    private $params;
    private $propagationStopped = false;

    /**
     * @param string $name
     * @param array $params
     */
    public function __construct($name, array $params = array())
    {
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return void
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param array $params
     * @return void
     */
    public function mergeParamsWith(array $params)
    {
        $this->params = array_merge($this->params, $params);
    }

    /**
     * @param string $param
     * @param mixed $value
     * @return void
     */
    public function setParam($param, $value)
    {
        $this->params[$param] = $value;
    }

    /**
     * @param string $param
     * @return void
     */
    public function unsetParam($param)
    {
        unset($this->params[$param);
    }

    /**
     * Stop propagating this hook message.
     *
     * @return void
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }

    /**
     * Has this hook message indicated hook propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }
}
~~~

### EventDispatcherInterface

Dispatcher allows to notify listeners about events and send hook messages.

~~~php
/**
 *
 */
interface EventDispatcherInterface
{
    /**
     * Dispatch an event.
     *
     * @param Event $event
     * @return void
     */
    public function dispatch(Event $event);

    /**
     * Initialize a hook-point.
     *
     * @param Hook $hook
     * @return Hook
     */
    public function hook(Hook $hook);
}
~~~

### EventSubscriberInterface

Subscriber allows to manage listeners at runtime.

~~~php
interface EventSubscriberInterface
{
    /**
     * Attaches a listener to an event.
     *
     * @param string $eventName the event to attach too
     * @param callable $callback a callable function
     * @param int $priority the priority at which the $callback executed
     * @return void
     */
    public function attach($eventName, callable $callback, $priority = 0);

    /**
     * Detaches a listener from an event.
     *
     * @param string $eventName the event to attach too
     * @param callable $callback a callable function
     * @return void
     */
    public function detach($eventName, callable $callback);

    /**
     * Clear all listeners for a given event.
     *
     * @param  string $event
     * @return void
     */
    public function clearListeners($event);
}
~~~
