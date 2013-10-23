# PSR-4: Autoloader

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Overview

This PSR describes a technique to [autoload][] classes from specified resource paths. It is fully interoperable, and can be used in addition to any other autoloading technique, including [PSR-0][]. This PSR also describes how to name and structure classes to be autoloaded using the described technique.

[autoload]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md


## 2. Definitions

- **class**: The term _class_ refers to PHP classes, interfaces, traits, and similar future resource definitions.

- **namespace**: A PHP namespace, as is syntactically valid after the [PHP `namespace` keyword](http://www.php.net/manual/en/language.namespaces.definition.php). 

- **namespace separator**: The PHP namespace separator symbol `\` (backslash).

- **qualified class name**: A full namespace and class name, such as `Acme\Log\Writer`. The _qualified class name_ is passed into the spl_autoloader by PHP.

- **unqualified class name**: The lowest level node of the _qualified class name_ and the name of the containing file for the class, excluding the file extension.

- **resource**: A class definition, typically a file in a file system.

- **resource base**: A base path to a folder, for example, `/path/to/acme-log/src`.  

- **resource path**: A base path representing the location of a resource, for example, `/path/to/acme-log/src/Writer.php`. 

- **conforming autoloader**: A PHP spl autoloader that implements the definitions contained within this standards recommendation.

## 3. Specification

This is a collection of rules which explain how the _Qualified Class Name_ relates to its _resource path_.

1. A _qualified class name_ MUST have the following structure: `<Namespace>\<Unqualified Class Name>`

    a. A _qualified class name_ MUST have a value for _namespace_.

    b. The _unqualified class name_ MUST be proceeded by a _namespace separator_.

    > **Example:** 
    > The _qualified class name_ could follow this structure: 
    > `Acme\Log\Writer`.

2. _Namespaces_ MUST BE associated with one or more _resource base_ values.
 
    > **Example:** 
    > The _namespace_: `Acme\Log\` is associated with 
    > the _resource base_ `/path/to/acme-log/src/`

3. A _qualified class name_ is constructed using:
<ul>
<li>the _namespace_ associated with a matching _resource base_</li>
<li>followed by the name of each subfolder in the matching _resource base_, each separated by a _namespace separator_</li>
<li>and, finally, the _unqualified class name_.</li>
</ul>

    > **Example:** 
    > Where a _namespace_ of `Acme\Log\` is associated with a 
    > _resource base_ of `/path/to/acme-log/src` 
    > and a _resource path_ of `/path/to/acme-log/src/Writer.php` contains the _unqualified class_ `Writer`, 
    > the _qualified class name_ is `Acme\Log\Writer`.

## 4. Implementations

1. A _conforming autoloader_ MUST NOT interfere with other spl_autoloaders by throwing exceptions or raising errors. In addition, the _conforming autoloader_ SHOULD NOT return a value.

2. The approach used to associate _namespace_ values with _resource base_ values is outside of the scope of this specification.

3. The order in which a _conforming autoloader_ processes multiple _resource base_ values associated with a _namespace_ is also outside the scope of this specification.
 
## 5. Examples

For examples of mapping techniques, resource organiation, and implementations of _conforming autoloaders_, please see the
-[examples file]:[psr-4-autoloader-examples.php]. Example implementations MUST NOT be regarded as part of the
-specification and MAY change at any time.
