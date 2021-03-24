# Clock Meta Document

## 1. Summary

The purpose of using the `ClockInterface` is to provide a standard way to access the system 
time, that would allow interopability when testing code that relies on the current time 
rather than relying on installing PHP extensions or use hacks like re-declaring the `time()`
function in other namespaces. 

## 2. Why Bother?

There are currently a few libraries that provide this functionality, however 
there is no interopability between these different libraries, as they ship with their own 
clock interfaces. Symfony provides a package callsed `symfony/phpunit-bridge` that has a
`Symfony\Bridge\PhpUnit\ClockMock` class, which allows mocking PHP's built-in time & date 
functions, however this does not solve mocking calls to `new \DateTimeImmutable()`. It does
not fully mock time when called from other libraries that rely on the system time. 
`Cake\Chronos\Chronos` does provide mocking, however it is set via a global (static class 
property), and this has its own pitfalls as it provides no isolation.

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

## 4. Approaches

### 4.1 Chosen Approach

We have decided to formalize the existing practices, used by several other packages
out in the wild. Some of the popular packages providing this functionality are: 
`lcobucci/clock`, `kreait/clock`, `ergebnis/clock`, and `mangoweb/clock`. Some providing
interfaces, and some relying on overloading (extending) the Clock class to mock the
current time.


### 4.2 Example Implementations

```php
final class SystemClock implements \Psr\Clock\ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}

//

final class UTCClock implements \Psr\Clock\ClockInterface
{
    private \DateTimeZone $utcTimeZone;

    public function __construct()
    {
        $this->utcTimeZone = new \DateTimeZone('UTC');
    }

    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', $this->utcTimeZone);
    }
}

//

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

    public function advance(DateInterval $interval): void
    {
        $this->now = $this->now->add($interval);
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
