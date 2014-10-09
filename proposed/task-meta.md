  
Task Meta Document
==========================

1. Summary
----------

Almost every framework includes a toolset for running background tasks, cron jobs, deployment and more.
These usually provide a set of predefined tasks that are configured and combined to achieve some specific goal.
Common examples of such tasks would be copying a file on the filesystem, executing a database query, etc.
Each such library provides its own set of guidelines on how new tasks should be added to its disposal,
thus forcing the developer to creating multiple adapters or supporting only a limited subset of libraries.

2. Why Bother?
--------------

This proposal presents a simple API that task runner libraries might expect the tasks to implement.
It will also attempt to make these tasks easily portable and pluggable, but this is not the primary goal.

3. Scope
--------

## 3.1 Goals

* Provide the interface for runnable tasks
* Keep the interfaces as minimal as possible.
* Attempt at achieving a pluggable interface allowing for easier integration. This is not the primary goal though.

## 3.2 Non-Goals

* This proposal does not define any rules for how the tasks are to be configured or grouped into jobs.

4. Design Decisions
-------------------

Right now we have a number of task runners that already share similarities in their API.
Further standardizing it will allow tasks to become reusable across all such implementations.

These are the classes used as a source of inspiration:

* https://github.com/phingofficial/phing/blob/master/classes/phing/Task.php
* https://github.com/bldr-io/bldr/blob/master/src/Model/Task.php
* https://github.com/Codegyre/Robo/blob/master/src/Task/Shared/TaskInterface.php
* https://github.com/bldr-io/bldr/blob/master/src/Registry/TaskRegistry.php
* http://symfony.com/doc/current/components/console/introduction.html

It seems apparent that the tasks also need some standardized way of outputting data. 
Instead on relying on 'echo' and library specific implementations this proposal will try adopting
the Symfony2 Command way of passing an OutputInterface

## 4.1 Important questions

* Do we need OutputInterface included in this PSR ?
* What about InputInterface ?
* Should run() return anything ?
* Do we need an interfce for a TaskRepository ? It might be useful for example if one of the tasks depends on
a different task and would like to run it internally. But this might be too convoluted.

...To be continued..
