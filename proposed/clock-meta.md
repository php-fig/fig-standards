# Clock Meta Document

## 1. Summary

Getting the current time in applications is typically achieved using the `time()` or `microtime` functions, or by using a `new \DateTimeImmutable()` class.

Due to the nature of time progression these methods cannot be used when predictable results are needed, such as during testing.

This `ClockInterface` aims to provide a standard way to consume time that allows interoperability not only when consuming the "real" time but also when predictable results need to be available. This avoids the need to use PHP extensions for testing or redeclare the `time()` function in a local namespace. 

## 2. Why Bother?

There are currently a few libraries that provide this functionality, however there is no interopability between these different libraries, as they ship with their own clock interfaces. 

Symfony provides a package called `symfony/phpunit-bridge` that has a `Symfony\Bridge\PhpUnit\ClockMock` class, which allows mocking PHP's built-in time and date functions, however this does not solve mocking calls to `new \DateTimeImmutable()`. It also does not fully mock time when called from other libraries that rely on the system time.

`Carbon\Carbon`, and its fork `Cake\Chronos\Chronos`, do provide mocking via a static `setTestNow()` method, but this provides no isolation and must be called again to stop mocking.

Pros:

* Consistent interface to get the current time;
* Easy to mock the wall clock time for repeatablility.

Cons:

* Extra overhead and developer effort to get the current time, not as simple as
calling `time()` or `date()`.

## 3. Scope

### 3.1 Goals

* Provide a simple and mockable way to read the current time;
* Allow interoperability between libraries when reading the clock.

### 3.2 Non-Goals

* This PSR does not provide a recommendation on how and when to use the concepts
  described in this document, so it is not a coding standard;
* This PSR does not provide a recommendation on how to handle timezones when 
  retrieving the current time. This is left up to the implementation.
* This PSR does not handle any scheduling methods like `sleep()` or `wait()` because such methods are not related to retrieving the current time.

## 4. Approaches

### 4.1 Chosen Approach

We have decided to formalize the existing practices used by several other packages. Some popular packages providing this functionality are: 

* [`lcobucci/clock`](https://packagist.org/packages/lcobucci/clock)
* [`kreait/clock`](https://packagist.org/packages/kreait/clock)
* [`ergebnis/clock`](https://packagist.org/packages/ergebnis/clock)
* [`mangoweb/clock`](https://packagist.org/packages/mangoweb/clock)

(This list is not exhaustive!)

Some of these provide interfaces and some rely on extending a clock class to mock the
current time.

These implementations all provide a `now()` method which returns a `DateTimeImmutable` object. As the `DateTimeImmutable` object allows retrieving the Unix timestamp, by calling `getTimestamp()` or `format('u.U')`, this interface does not define any special methods to retrieve a Unix timestamp or any other time information that is not available from a `DateTimeImmutable` object. 

### 4.2 Timezones

Time by now is defined by interaction of electromagnetic radiation with the excited states of certain atoms where the SI defines one second as the duration of 9192631770 cycles of radiation corresponding to the transition between two energy levels of the ground state of the caesium-133 atom at 0K. This means that retrieving the current time will always return the same time, no matter where it is observed. While the timezone defines *where* the time was observed it does not modify the actual "slice" of time.

This means that for the sake of this PSR the timezone is considered an implementation detail of the interface. 

It is up to the implementation to make sure that the timezone is handled according to the business logic of the application. That is either by making sure that a call to `now()` will only return a `DateTimeImmutable` object with a known timezone (implicit contract) or by explicitly changing the timezone to be correct for the application. This can be done by calling `setTimezone()` to create a new `DateTimeImmutable` object with the given timezone. 

These are though actions that are not part of the contract which this interface defines.


### 4.2 Example Implementations

```php
final class SystemClock implements \Psr\Clock\ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}

final class FrozenClock implements \Psr\Clock\ClockInterface
{
    private \DateTimeImmutable $now;

    public function __construct(\DateTimeImmutable $now)
    {
        $this->now = $now;
    }

    public function now(): \DateTimeImmutable
    {
        return clone $this->now;
    }
}

```

## 5. People

### 5.1 Editor

 * Chris Seufert

### 5.2 Sponsor

 * Chuck Burgess

### 5.3 Working group members

 * Luís Cobucci
 * Pol Dellaiera
 * Ben Edmunds
 * Jérôme Gamez
 * Andreas Heigl
 * Andreas Möller

## 6. Votes

* [Entrance Vote](https://groups.google.com/g/php-fig/c/hIKqd0an-GI)

## 7. Relevant Links

* https://github.com/ergebnis/clock/blob/main/src/Clock.php
* https://github.com/icecave/chrono/blob/master/src/Clock/ClockInterface.php
* https://github.com/Kdyby/DateTimeProvider/blob/master/src/DateTimeProviderInterface.php
* https://github.com/kreait/clock-php/blob/main/src/Clock.php
* https://github.com/lcobucci/clock/blob/2.1.x/src/Clock.php
* https://github.com/mangoweb-backend/clock/blob/master/src/Clock.php
* https://martinfowler.com/bliki/ClockWrapper.html

## 8. Past contributors

This document stems from the work of many people in previous years, we recognize their effort:

 * 
_**Note:** Order descending chronologically._
