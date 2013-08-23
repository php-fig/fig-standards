**Step A**: Common object retrieval interface
=============================================

This document is a working group related to [dependency injection containers interoperability](dependency-injection-meta.md).

Thanks to Matthieu Napoli's work, we already know what is common between DI containers. What we do not know yet is **what
a typical MVC framework needs from the DI Container**.

TODO: maybe we could copy matthieu's GIST here, for reference. It would be easier to fork / modify / pull.

In order to have interoperable DI containers, we must first know how those DI containers are used
by their primary users: MVC frameworks.

The goal of this document is to list the requirements of various MVC frameworks, regarding DI containers.

When we have that list, we can very easily see if most MVC framework share a common list of requested feature or not.
If they share such a set of requested features, we can move on an work on a common interface.
If they do not share a set of requested features, all hope is not lost. We can still work on *step C* on DI containers interoperability,
in order to be able to have several DI containers that can cooperate in the same application. 

MVC frameworks requirement list
-------------------------------

MVC frameworks are listed alphabetically.

**TODO**

###[Splash (Mouf's MVC framework)](http://mouf-php.com/packages/mouf/mvc.splash/index.md)

Splash is requiring the DIC to be searchable, for a given type.
Indeed, it will scan all the instances available in the DIC to see which are implementing the "ControllerInterface" interface.

So at best, Splash needs a `findByType($type)` method to find instances of a given type.
A simple *list of all instances available* could be enough though, since Splash can do the analysis by itself.

**TODO**

