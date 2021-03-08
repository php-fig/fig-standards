# PHPDoc Meta Document

## 1. Summary

The purpose of using the ClockInterface would allow mocking time in many situations where
you can't easily install PHP extensions or use hacks like re-declaring the time() function
in other namespaces.

## 2. Why Bother?

There are currently a few libraries that do provide the functionality on packagist, however 
there is no interopability between these different libraries, as they ship with their own 
clock interfaces. Symphony has a TimeMock library which uses namespace hacks to override the 
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

* This PSR does not provide a recommendation on how and when to use the concepts described in this document, so it is
  not a coding standard.
* This PSR does not provide a reccomendation on how to handle timezones when retrieving the current time.

## 4. Approaches

### 4.1 Chosen Approach

We have decided to formalize the existing practices, use by several other packages out in the wild. Some of the popular
packages providing this functionality are: lcobucci/clock, kreait/clock, ergebnis/clock, and mangoweb/clock. Some 
providing interfaces, some relying on overloading a class to mock the current time.

## 5. People

### 5.1 Editor

 * Chris Seufert

### 5.2 Sponsor

 * 

### 5.3 Working group members

 * 

## 6. Votes

* 

## 7. Relevant Links

* https://github.com/lcobucci/clock/blob/2.1.x/src/Clock.php
* https://github.com/kreait/clock-php/blob/main/src/Clock.php
* https://github.com/ergebnis/clock/blob/main/src/Clock.php
* https://github.com/mangoweb-backend/clock/blob/master/src/Clock.php
* https://github.com/icecave/chrono/blob/master/src/Clock/ClockInterface.php
* https://github.com/Kdyby/DateTimeProvider/blob/master/src/DateTimeProviderInterface.php

## 8. Past contributors

Since this document stems from the work of a lot of people in previous years, we should recognize their effort:

 * 
_**Note:** Order descending chronologically._
