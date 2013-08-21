Group 1: Interoperability between DI containers and MVC frameworks
==================================================================

This document is a working group related to [dependency injection containers interoperability](dependency-injection-meta.md).

**Step 1**: Features used by MVC frameworks
------------------------------------------- 

In order to have interoperable DI containers, we must first know how those DI containers are used
by their primary users: MVC frameworks.

The goal of this chapter is to list the requirements of various MVC frameworks, regarding DI containers.

MVC frameworks are listed alphabetically.

**TODO**

###[Splash (Mouf's MVC framework)](http://mouf-php.com/packages/mouf/mvc.splash/index.md)

Splash is requiring the DIC to be searchable, for a given type.
Indeed, it will scan all the instances available in the DIC to see which are implementing the "ControllerInterface" interface.

So at best, Splash needs a `findByType($type)` method to find instances of a given type.
A simple *list of all instances available* could be enough though, since Splash can do the analysis by itself.

**TODO**


**Step 2**: A service locator for DI containers
-----------------------------------------------

TODO (see Proposition 2 from [Interoperability between DI containers](inter-di-interop-meta.md#proposition-2-by-david-négrier-dic-locator))