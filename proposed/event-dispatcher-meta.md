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
* Simplify and standardize the process by which libraries and components may register an interest in responding to an event so that they may be more easily 
* To the extent feasible, ease the process for existing code bases to transition toward this specification.

### 3.2 Non-Goals

* Asynchronous systems often have a concept of an "event loop" to manage interleaving coroutines.  That is an unrelated matter and explicitly irrelevant to this specification.
* Strict backward compatibility with existing event systems is not a priority and is not expected.
* While this specification will undoubtedly suggest implementation patterns, it does not seek to define One True Event Dispatcher Implementation, only how callers and listeners communicate with that dispatcher.

## 4. Approaches


## 5. People

The Event Manager Working Group consisted of:

### 5.1 Editor

* Larry Garfield

### 5.2 Sponsor

Cees-Jan Kiewiet

### Working Group Members

Elizabeth Smith
Benjamin Mack
Matthew Weier O'Phinney
Ryan Weaver

## 6. Votes

* **Entrance Vote: **  ADD LINK HERE

7. Relevant Links
-----------------

* [Inspiration Mailing List Thread](https://groups.google.com/forum/#!topic/php-fig/-EJOStgxAwY)
