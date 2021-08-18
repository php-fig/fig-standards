Common Interface for Accessing the Clock
========================================

This document describes a simple interface for reading the system clock.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The final implementations MAY decorate the objects with more
functionality than the one proposed but they MUST implement the indicated
interfaces/functionality first.

[RFC 2119]: https://www.rfc-editor.org/rfc/rfc2119.html

# 1. Specification

## 1.1 Introduction

Creating a standard way of accessing the clock, would allow interopability
during testing, when testing behavior that has timing based side effects.
Common ways to get the current time include calling `\time()` or 
`new DateTimeImmutable('now')`. However, this makes mocking the current time
impossible in some situations.

## 1.2 Definitions

* **Clock** - The clock is able to read the current time and date.

* **Timestamp** - The current time as an integer number of seconds since
Jan 1, 1970 00:00:00 UTC.

### 1.3 Usage

There are some common usage patterns, which are outlined below:

**Get the current timestamp**

This should be done by using the `getTimestamp()` method on the returned `\DateTimeImmutable` like so:
```php
$timestamp = $clock->now()->getTimestamp();
```

# 2. Interfaces

## 2.1 ClockInterface

The clock interface defines the most basic operations to read the current time and date from the clock. 
It MUST return the time as a `DateTimeImmutable`.

~~~php
<?php

namespace Psr\Clock;

interface ClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable Object
     */
    public function now(): \DateTimeImmutable;

}
~~~
