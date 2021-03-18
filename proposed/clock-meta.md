# Clock Meta Document

## 1. Summary

The purpose of using the `ClockInterface` is to provide a standard way to access the system 
time, that would allow interopability when testing code that relies on the current time 
rather than relying installing PHP extensions or use hacks like re-declaring the `time()`
function in other namespaces. 

## 2. Why Bother?

There are currently a few libraries that do provide the functionality on packagist, however 
there is no interopability between these different libraries, as they ship with their own 
clock interfaces. Symfony has a TimeMock library which uses namespace hacks to override the 
`time()`, `date()`, `microtime()`, etc functions, however this does not solve mocking calls to 
`new \DateTime()`

Pros:

* Consistent interface to get the current time
* Easy to mock the wall clock time for repeatablility.

Cons:

* Extra overhead and developer effort to get the current time, not as simple as
calling `time()` or `date()`.

## 3. Scope

### 3.1 Goals

* Provide a simple and mockable way to read the current time
* Allow interoperability between libraries when reading the clock

### 3.2 Non-Goals

* This PSR does not provide a recommendation on how and when to use the concepts
  described in this document, so it is not a coding standard.
* This PSR does not provide a recommendation on how to handle timezones when 
  retrieving the current time. This is left up to the implementation.

## 4. Approaches

### 4.1 Chosen Approach

We have decided to formalize the existing practices, used by several other packages
out in the wild. Some of the popular packages providing this functionality are: 
`lcobucci/clock`, `kreait/clock`, `ergebnis/clock`, and `mangoweb/clock`. Some providing
interfaces, and some relying on overloading (extending) the Clock class to mock the
current time.


### 4.2 Example Implemntations

```php
final class TimeZoneAwareClock implements \Psr\Clock\ClockInterface
{
    private \DateTimeZone $timeZone;

    public function __construct(\DateTimeZone $timeZone)
    {
        $this->timeZone = $timeZone;
    }

    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', $this->timeZone);
    }
}

//

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
    private TimeZoneAwareClock $inner;

    public function __construct()
    {
        $this->inner = new TimeZoneAwareClock(new \DateTimeZone('UTC'));
    }

    public function now(): \DateTimeImmutable
    {
        return $this->inner->now();
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
        return $this->now;
    }
}
```

## 5. People

### 5.1 Editor

 * Chris Seufert

### 5.2 Sponsor

 * Chuck Burgess

### 5.3 Working group members

 * Pol Dellaiera
 * Ben Edmunds
 * Jérôme Gamez
 * Andreas Heigl
 * Andreas Möller
 * Luís Cobucci

## 6. Votes

* 

## 7. Relevant Links

* https://github.com/ergebnis/clock/blob/main/src/Clock.php
* https://github.com/icecave/chrono/blob/master/src/Clock/ClockInterface.php
* https://github.com/Kdyby/DateTimeProvider/blob/master/src/DateTimeProviderInterface.php
* https://github.com/kreait/clock-php/blob/main/src/Clock.php
* https://github.com/lcobucci/clock/blob/2.1.x/src/Clock.php
* https://github.com/mangoweb-backend/clock/blob/master/src/Clock.php
* https://martinfowler.com/bliki/ClockWrapper.html

## 8. Past contributors

Since this document stems from the work of a lot of people in previous years, we should recognize their effort:

 * 
_**Note:** Order descending chronologically._
