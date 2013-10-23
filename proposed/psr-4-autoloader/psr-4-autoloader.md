# PSR-4: Autoloader

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Overview

This PSR describes a technique to [autoload][] classes from specified resource
paths. It is fully interoperable, and can be used in addition to any other
autoloading technique, including [PSR-0][]. This PSR also describes how to
name and structure classes to be autoloaded using the described technique.

[autoload]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md


## 2. Definitions

- **class**: The term _class_ refers to PHP classes, interfaces, traits, and
  similar future resource definitions.

- **namespace**: A PHP namespace, as is syntactically valid after the
  [PHP `namespace` keyword](http://www.php.net/manual/en/language.namespaces.definition.php). Sometimes
  referred as a "namespace name" by PHP.

- **namespace separator**: The PHP namespace separator symbol `\` (backslash).

- **qualified class name**: A full namespace and class name, such as
  `Acme\Log\Writer\FileWriter` excluding a leading namespace
  separator. The QCN is passed into the spl_autoloader by PHP.

- **sub-namespace**: PHP namespaces can specify a hierarchy of namespace names. 
   Given a _qualified class name_ of
  `Acme\Log\Writer\FileWriter`, a PHP _sub-namespace_ may be `Acme\`,
  `Acme\Log\`, or `Acme\Log\Writer\`. Within this PSR, a sub-namespace includes 
  a trailing namespace separator.

- **unqualified class name**: The lowest level _namespace name_ of the _qualified class name_ and the 
name of the containing file for the class, excluding the file extension.

- **resource**: A class definition, typically a file in a file system.

- **resource base**: A base path to a folder, for example, 
   `/path/to/acme-log/src`.  

- **resource path**: A base path representing the location of a resource, for example, 
   `/path/to/acme-log/src/Writer/FileWriter.php`. 

- **conforming autoloader**: A PHP SPL autoloader that implements the definitions contained within this standards recommendation.

## 3. Specification

This is a collection of rules which explain how the _Qualified Class Name_ relates to  
a _sub-namespace_ and a _resource path_.

1. A _qualified class name_ MUST have the following structure: '<Sub-namespace(s)>\<Unqualified Class Name>'

    a. The _sub-namespace_ MUST have one or more _namespace names_.
    
    b. Each _namespace name_ must be separated by a _namespace separator_.
    
    c. The _unqualified class name_ must be proceeded by a _namespace separator_.
    
    d. It is RECOMMENDED that the first _namespace name_, be a unique value identifying the "vendor name".
        
    e. In cases where a vendor has multiple packages, it is RECOMMENDED that the second _namespace name_, 
    identify the "package name".
    
    f. Additional namespace names MAY follow the optional "vendor name" and "package name" _namespace names_.

    > **Example:** The _qualified class name_ could follow this structure: 
    `<Vendor Name>\(<Package Name>)\(<Namespace Name(s)>\)<Unqualified Class Name>`.

2. _Sub-namespaces_ MUST BE associated with one or more _resource base_ values.
 
3. A _qualified class name_ is constructed using a _sub-namespace_, a _namespace name_ for each subfolder name 
of the matching _resource base_, followed by the _unqualified class name_.

    > **Example:** The _sub-namespace_ of: 
    `Acme\Log\`
    associated with a _resource base_ of `/path/to/acme-log/src/`
    and a _resource path_ of  `/path/to/acme-log/src/FileWriter.php`
    resolves to a _qualified class name_ of 
    `Acme\Log\FileWriter`

## 4. Implementations

1. A _conforming autoloader_ will not interfere with other spl_autoloaders, and as
such MUST NOT throw exceptions or raise errors of any level, and SHOULD NOT
return a value.

2. The approach used to associate _sub-namespace_ values with _resource bases_ is 
outside of the scope of this specification.

3. The order in which a _conforming autoloader_ processes multiple _resource base_ values 
which are associated with a _sub-namespace_ is also outside the scope of this specification.

4. The order in which a _conforming autoloader_ processes multiple _resource paths_ associated 
with a _qualified class name_ is outside the scope of this specification.


## 5. Examples

For examples of mapping techniques, resource organiation, and implementations of _conforming autoloaders_, please see the
[examples file][]. Example implementations MUST NOT be regarded as part of the
specification and MAY change at any time.

[examples file]: psr-4-autoloader-examples.php
