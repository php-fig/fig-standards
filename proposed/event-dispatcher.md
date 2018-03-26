Event Dispatcher
================

Event Dispatching is a common and well-tested mechanism to allow developers to inject logic into an application easily and consistently.

The goal of this PSR is to establish a common mechanism for event-based extension and collaboration so that libraries and components may be reused more freely between various applications and frameworks.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

Having common interfaces for dispatching and handling events, allows developers to create libraries that can interact with many frameworks in a common fashion.

Some examples:

* A security framework that will prevent saving/accessing data when a user
doesn't have permission.
* A common full page caching system
* Libraries that extent other specific libraries, regardless of what framework they are both integrated into.
* A logging package to track all actions taken within the application
