  
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

## 4.2 Reducting configuration redundancy

Now I have a more ambitious idea (wild dreams ahead):

The problem with this setup, and with many task runner libraries out there is that they often suffer from configuration duplication. Lets say in your cron job configuration you need to:

1. Copy files to remote server over FTP
2. Run a SSH command on that server
3. Copy some more files over FTP

For both steps `1` and `3` you would have to define your FTP login credentials, thus getting some redundancy.

What if we could separate task configuration ( which would be FTP credentials ) from the actual task Command ( copy this file over there ).
This would require a CommandInterface and changing TaskInterface::run signature to TaskInterface::run(OutputInterface $output, CommandInterface $command)

## 4.3 Implementation in popular libraries

This section describes steps that would have to be taken to make popular libraries comply with this PSR.
The purpose is to show how easily this could be done.

### Phing

 * Task::main() would have to be renamed to Task::run, or ::run could act as a proxy for ::main for backwards compatibility
 * When setting configuration values instead of setSourceDir('/') setProperty('sourceDir', '/') would be called

### Symfony 2 Commands

 * Command::execute would have to be renamed to Command::run
 * Symfony2 commands serve a different purpose alltogether, being standalone actions not meant as "builidingc blocks" for jobs. So I guess they are actually out of the scope of this PSR. However they can be easily made to comply with TaskInterface and thus become interpolable.

### Yii

Yii 2 console commands are actually controllers and as with Symfony2 present standalone complete actions, not "building block" style like Phing. So the PSR wouldnt apply here

Yii 1 however has a CConsoleCommand class which already has a ::run($args) method. The only diference between it and the PSR is that the arguments are passed separately instead passed to ::run. This would allow any PSR compliant TaskInterface task to instantly become a valid Yii Command.

### Bldr

Is mostly compliant anyway. All it needs is for CallInterface to extend TaskInterface, and some tweaks with passing parameters.
There is no need to change internal nomenclature and rename CallInterface to TaskInterface internally.

### Robo

Robos' TaskInterface already defines ::run(), but would be required to also pass OutputInterface to it, instead of relying on static Runner::getPrinter() call

### Taskphp

Realies on Symfony2 Command so most of the things i said about Symfony 2 apply here too.




