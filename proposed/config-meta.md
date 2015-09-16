# Config Meta Document

## 1. Introduction
This document describes the process and discussions that led to the Config PSR. It's goal is to explain the reasons 
behind each decision.

The word `Container` in this document is to be interpreted as the `ContainerInterface` of the PSR Container proposal.

## 2. Why bother?
The configuration of instances via factories is not uniform, uses different ways to retrieve and check the needed 
options and it's not clear which configuration options needs the factory to create the instance.

## 3. Scope

### 3.1 Goals
The goal set by the Config PSR is to standardize how factories uses a configuration to create instances, support for 
auto discovery of needed configuration, to reduce boilerplate code and to make it more readable and easier to understand. 
It can also be used to build the name of the *Container* entry.

### 3.2 Non-goals
* It's not a goal to define a whole project/library configuration structure.
* It's not a goal to validate the configuration option values.

## 4. History
Before submitting the Config PSR to the PHP-FIG, the interfaces was first proposed in a project named 
[interop-config](https://github.com/sandrokeil/interop-config).

The goal of the project was to provide a test-bed for implementing the interfaces and implementation and to pave the way 
for the Config PSR.

## 5. Interfaces
There are four interfaces and some interfaces extends from another interface to ensure the uniform configuration structure. 
If you confused about the naming of the interfaces or methods, note that I have used the Domain Driven Design approach.

### 5.1 HasConfig
This is the main interface and describes the default configuration structure. Since we have Composer more and more 
libraries can be combined to create individual projects. So it is only logical to start the configuration structure 
with a `vendorName`. A vendor has multiple packages so the next structure depth is `packageName`.

As an array it looks like this:

```php
[
    // vendor name
    'doctrine' => [
        // package name
        'connection' => [
        ],
    ],
];
```

### 5.2 HasContainerId
Factories allow to create more than one instance of the same class with another configuration. This can be achieved with 
the third structure depth `containerId`. Now the structure is fully expanded.

As an array it looks like this:

```php
[
    // vendor name
    'doctrine' => [
        // package name
        'connection' => [
            // container id
            'orm_default' => [
            ],
        ],
    ],
];
```

### 5.3 HasMandatoryOptions
Some options for an instance are mandatory. `mandatoryOptions` returns a list of options which are needed to create the 
instance.

As an array it looks like this:

```php
[
    'driverClass',
    'params'
];
```

### 5.4 ObtainsOptions
The factory retrieves the configuration options e.g. from the *Container*. The method `options($config)` extracts the 
configuration options from the given array or an object which implements `ArrayAccess` depending on the implemented 
interfaces and optionally doas a mandatory option check.

This function:

* returns the options depending on the implemented interfaces, optionally mandatory option check
* throws an `OptionNotFoundException` if no options are found
* throws a `MandatoryOptionNotFoundException` if a mandatory option is missing
* throws an `InvalidArgumentException`if the parameter `config` has the wrong type
* throws a `RuntimeException` If the vendor name was not found in configuration

## 6. People

### 6.1 Editors

* [Sandro Keil](https://github.com/sandrokeil)

### 6.2 Contributors

Are listed here all people that contributed in the discussions or votes (on container-interop), by alphabetical order:

* [Alexander Miertsch](https://github.com/codeliner)
* [Lorenzo Fontana](https://github.com/fntlnz)

# 7. Relevant links
* [Implementation of the interop-config interfaces](https://github.com/sandrokeil/interop-config/blob/master/src/ConfigurationTrait.php)
* [List of all issues](https://github.com/sandrokeil/interop-config/issues?q=)
