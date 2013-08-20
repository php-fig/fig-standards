Group 2: Interoperability between DI containers
===============================================

This document is a working group related to [dependency injection containers interoperability](dependency-injection-meta.md).

The goal of this group is to propose a mechanism to have multiple active DI containers. We could have multiple DI containers 
(with a different set of features) speaking to each other. The goal would be to enable one DI container to fetch an instance 
that it has no knowledge of in another DI container.

Proposition 1 (by David Négrier): chaining
------------------------------------------

This is the easiest implementation.
If one DI container 1 knows about DI container 2, it should be possible to request an instance from DIC1, and if DIC1
does not know the instance, it will ask to DIC2.

Using this approach, there is no need to standardize anything. However, there is a flaw. DIC2 cannot refer to DIC1
(unless it is chained to DIC1, but then, there is a risk of infinite loop).

It is however very useful to provide "overloading" of instances (League\Di is using this technique), but this
is not what we are looking for.

I talked about this option because it was mentionned on the mailing list, but my preference goes to propositon 2.

Proposition 2 (by David Négrier): DIC locator
---------------------------------------------

I understand this can be very controversial, but I feel it could be really invaluable.
In this proposition, I would like to propose a **standardized singleton** (I know, it sounds bad but wait...)

The idea would be to mimic the way `spl_autoload_register` works.
Basically, each DIC would be allowed to register itself to a global DIC locator (this might be a singleton,
the only one in your whole application!)

Of course, the global DIC container could also implement the `ContainerInterface`.

A **pseudo-code implementation** would look like this:

```php
class DICLocator {
	
	// List of containers
	private $containers = array();
	
	public static function getInstance() {
		// Returns the singleton instance for that object.
	}
	
	public function register(ContainerInterface $container) {
		// Registers a new container
	}
	
	public function get($identifier) {
		// Call in turns all the containers, and returns the first
		// object to be returned by a container.
	}
}
```

This has a number of nice benefits, one of them being that it becomes really easy to implement cross-container look-up.

Indeed, let's imagine you fetch instance "A" from container "DIC1". Instance A requires instance "B".
DIC1 is performing a look-up and does not find instance "B". Instead of returning "null", it can query the DICLocator, using
its "get" function. This function will query all registered DICs. If instance B is in DIC2, the DICLocator will
be able to find it and return it to DIC1.

Know, I wonder if this would really be possible, or if this might break caching / optimisations performed by some DI containers.
Any idea?

**TODO**