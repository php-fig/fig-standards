Common Interface for Accessing the Clock
========================================

This document describes a simple yet extensible interface for a cache item and
a cache driver.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The final implementations MAY decorate the objects with more
functionality than the one proposed but they MUST implement the indicated
interfaces/functionality first.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

# 1. Specification

## 1.1 Introduction

Creating a standard way of accessing the clock, would allow interopability
during testing, when testing behavior that has timing based side affects.
Common ways to get the current time include calling `\time()` or 
`new DateTimeImmutable('now')` however this makes mocking the current time
impossible in some situations.

## 1.2 Definitions

* **Clock** - The clock is able to read the current time and date.

* **Timestamp** - The current time as an integer number of seconds since
Jan 1, 1970 00:00:00 UTC.

# 2. Interfaces

## 2.1 ClockInterface

The clock interface defines the most basic operations to read the current time and date from the clock. 
It MUST return the current time as a DateTimeImmutable, DateTime, timestamp integer or timestamp float.

~~~php
<?php

namespace Psr\Clock;

interface ClockInterface
{
    /**
     * Reads the current time as a unix timestamp
     *
     * @return int The current time as a unix timestamp
     */
    public function timestamp():int;
    
    /**
     * Reads the current time as a DateTimeImmutable Object
     *
     * @return \DateTimeImmutable The current time as a DateTimeImmutable Object
     */
    public function immutable():\DateTimeImmutable;
    
    
    /**
     * Reads the current time as a DateTime Object
     *
     * @return \DateTime The current time as a DateTime Object
     */
    public function datetime():\DateTime

    /**
     * Reads the current time as with microsecond resolution
     *
     * @return float The current time as a float with microsecond resolution
     */
     public function microtime():float
     
    /**
     * Retreieves the current time zone
     *
     * @return \DateTimeZone The current timezone as a DateTimeZone object
     */
     public function timezone():\DateTimeZone

}
~~~
