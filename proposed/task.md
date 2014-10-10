Task interfaces
=======================

Almost every framework includes a toolset for running background tasks, cron jobs, deployment and more.
These usually provide a set of predefined tasks that are configured and combined to achieve some specific goal.
Common examples of such tasks would be copying a file on the filesystem, executing a database query, etc
Each such library provides its own set of guidelines on how new tasks should be added to its disposal,
thus forcing the developer to creating multiple adapters or supporting only a limited subset of libraries.

This proposal presents a simple API for defining tasks, thus making them easily portable.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt

## Specification

A task is an atomic optionally configurable action. Task runner libraries provide means for combining and configuring these tasks to achieve some defined goal.

## Interfaces

### OutputInterface

OutputInterface defines a minimal output stream that SHOULD be used for writing messages by a task.

```php
<?php

namespace Psr\Task;

/**
 * Output stream for writing messages
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

### TaskInterface

TaskInterface defines an API for configuring and running tasks.

```php
<?php

namespace Psr\Task;

/**
 * A specific executable action
 */
interface TaskInterface
{
    /**
     * Executes the task.
     *
     * @param OutputInterface $output Output stream
     *
     * @return void
     */
    public function run(OutputInterface $output);
    
    /**
     * Sets a configuration parameter.
     *
     * @param string $name  Name of the configuration parameter
     * @param mixed  $value Parameter value
     *
     * @return void
     */
    public function setParam($name, $value);

}
```
