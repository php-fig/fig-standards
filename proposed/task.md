Task interfaces
=======================

This document describes common task interfaces for representing runnable actions.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[RFC 7230]: http://www.ietf.org/rfc/rfc7230.txt

1. Specification
----------------

### Definitions

*    **Task** - An atomic configurable action
*    **Job**  - A combination of specifically configured tasks that are to be executed in a specific order.

2. Interfaces
-------------

### 2.1 `Psr\Task\OutputInterface`

```php
<?php

namespace Psr\Task;

/**
 * Output stream
 */
interface OutputInterface
{
    /**
     * Writes a message to the output.
     *
     * @param string $message The message
     *
     * @return void
     */
    function write($message);

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string $message The message
     *
     * @return void
     */
    function writeln($message);

}
```

### 2.2 `Psr\Task\TaskInterface`

```php
<?php

namespace Psr\Task;

/**
 * A specific executable action
 */
interface TaskInterface
{
    /**
     * Executes the task
     *
     * @param OutputInterface $output Output stream
     *
     * @return void
     */
    public function run(OutputInterface $output);

}
```
