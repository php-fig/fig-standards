Module Identification
=====================

This standard declares an unique identification of modules to not run into
conflicts with multiple packages and versions. This PSR
adapts the maven specifications described in [Maven specification][].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[Maven specification]: http://docs.codehaus.org/display/MAVEN/Dependency+Mediation+and+Conflict+Resolution


1. Overview
-----------

- Modules MUST be unique packages targeting one topic.

- Modules MUST be grouped by a groupID.

- Modules MUST have a name unique within their groupId.

- The modules name shall be short and reasonable.

- The version number MUST be part of the modules identification.

- An additional classifier shall be used to divide different bundles of the
  same module.

### 1.1 Example

Group ID = org.mydomain.weblib
Module Name = weblib-core
Version = 1.2
Classifier = phar


2. Modules
----------

### 2.1. Dividing multiple modules

Modules MUST be unique packages and only target one specific topic.

Example: A module called "user-management" SHOULD NOT contain any code for displaying products
within internet shops.

Example: If a project is made on several topics it SHOULD call itself "xxxx-website" and
import/use other modules that are called "xxxx-user-service", "xxxx-news-service",
"xxxx-user-frontend" or "xxxx-administration-backend".

The following wording MUST be used:

- "module" is a unique package identified by a module identification.

- "library" is a module that provides php classes, scripts and resources.

- "application" is an application providing a frontend, for example a cli or web
  frontend.
  
- "framework" is a special kind of library used to build an application. The major difference
  to normal libraries is the requirement that different frameworks SHOULD not be mixed. Example:
  framework A and framework B cannot be used within the same application. But framework A
  bundles a huge set of library modules that MAY be used within framework B.
  
### 2.2. Group ID

Group IDs must always be unique. A Group ID MUST be covered from internet domains the
author has reserved.

Internet domains MUST be used as group names in reverse order.

Example:
Internet domain: www.php.net
Group ID: net.php.www or net.php

The segments of the group ID SHALL only contain the following characters: [a-z0-9] 

### 2.3. Module ID

A module ID identifies the module itself. The module ID MUST be unique within the
Group ID.

A module SHALL be short AND world wide unique although this is nearly impossible.

The module ID SHALL be reasonable.

The module ID SHALL only contain the following characters: [a-zA-Z0-9].

Version numbers SHALL not be part of the module ID. Only exception are major version
numbers required by a product name (for example "Html5Utility")

Good examples:
[Myvendor]InternetWebsite
[Myvendor]CoreLib
[Myvendor]UserService
[Myvendor]IntranetCore

Bad examples:
lib
www
cli

### 2.4. Version numbers

A module will always be identified by Group ID and Module ID.

To identify multiple builds of the same module Version numbering is used.

Version number MUST follow the following international convention:
<major>[.<minor>[.<fixlevel>]][-extra]

If a newer version contains bugfixes tht do not break the API the fixlevel SHALL
be increased. If a newer version contains some API changes or new features the
minor version number SHALL be increased. If a newer version contains a complete
rewrite the major version number SHALL be increased.

The extra portion can contain any extra information on the product, for example the
version system tag. The following extra information MUST NOT be used except in their
desired manner:
- ALPHA MUST be used for versions that are missing major features and still very unstable.
- BETA MUST be used for versions that are containing all major features but MAY still
  have bugs and MAY still be unstable.
- RC MUST be used for release candidates that are known to be stable and that are
  tested for stability. A release candidate MAY result in a final version.
- SNAPSHOT MUST be used for development or daily builds.

Version numbers starting with major version 0 are meant to be not yet released and
MAY be very unstable even if they do not contain the alpha or beta hint.

The extra SHALL contain only the following characters: [0-9a-z-A-Z]. It MUST NOT
contain one of the following characters (brackets, braces, comma, dots): [\[\]\(\)\-,\.]

If minor version number or fixlevel version number is not present they MUST be assumed to
be zero for version comparing.

Examples of version orderings, the newer versions are on the bottom of the list:

0.9.9-pre-release
1
1.0
1.8
1.8.3
1.8.4-commit1456
1.9-SNAPSHOT
2.0-ALPHA
2.0-BETA
2.0-BETA-1
2.0-RC-1
2.0-RC-2
2.0-RC-3-SNAPSHOT-1 (*)
2.0-RC-3-SNAPSHOT-2 (*)
2.0-RC-3
2.0-SNAPSHOT-1 (*)
2.0-SNAPSHOT-2 (*)
2.0
2.1


(*) SNAPSHOT versions SHOULD never be part of the version ordering. An implementor SHOULD
prefer release versions and only use the newest SNAPSHOT versions if they are requested
by the user or as a dependency.
If using SNAPSHOT versions they MUST be always lower than their targeting version as seen above).

### 2.5 Classifiers

Classifiers MUST be used only to specify different packaging types and builds of the same module
version.

They explicitly MUST NOT be used to divide API and IMPL packages. Instead the author MUST use
two different packages to divide API and IMPL if the author wants to.

If not explicitly specified the classifier MUST default to the value "phar". That means the package is
distributed as a phar file.

The following classifiers SHOULD be used in the desired manner:

- "phar" specifies a phar file containing php files or resources. It is the primary classifier. It
  SHOULD only contain the files needed for executing the package. It SHOULD NOT contain any file
  that is covered by the other standard classifiers.
  
- "www" specifies a phar/zip file that SHOULD be extracted to the htdocs folder of an application.
  A module author MAY decide to only deliver a file for "www" classifier but a module lookup code
  MUST NOT assume that a non-present "phar" classifier file falls back to the "www" classifier.
  This file SHOULD only contain the files needed for executing the package. It SHOULD NOT contain
  any file that is covered by the other standard classifiers.
  
- "doc" specifies a document only file. It SHOULD use the phar/zip file format or any other well known
  compression file format.

- "phpdoc" specifies a phar/zip file containing the phpdoc report. The phpdoc report must be located
  in the root folder and use the standard phpdoc folder layout to arrange phpdoc on class files or
  utility scripts.

- "test" specifies a phar file containing the test scripts used to test the module. They SHOULD
  only be used for development and integration testing.

- "site" specifies a phar/zip file containing a project site with various reports (for example
  phpunit test reports).
  
- "debug" / "www-debug" specifies a file containing debug builds of the "phar" or "www" classifier.
  A module author MAY decide to remove additional comments or debug onle code (debug logging may be
  very time consuming) before delivering the module. In this cases a debug version can be delivered.


3. Version ranges
-----------------

### 3.1 Usage

A version range MUST only be used for specifying a version that is searched for. That means to advice
a framework/ tool to
- find a dependency
- install a package

### 3.2 "1.4"

If the version does not contain any special character it is used as an advice. Version 1.4 means:
at least the version 1.4 is recommended but any other version MAY be specified.

An implementor MAY choose to implement a more restrictive lookup code. That means an implementor MAY
decide to fail if version 1.4 is not found and to fail if a newer version is found.

### 3.3 Conflicts with version dependencies or lookups

During conflicts (module A depends on X with version 1.5 and module B depends on X with version 1.6)
a implementor SHOULD print a warning and fail or at least choose one of the following resolutions:
- Use the version number from the first module that is found. This will mean that module A is the most
  important module and module B is minor important --> choose version 1.4 although module B may be broken.
- Use the highest version number (1.6).
- Deactivate one of the conflicting modules (only of of module A and B can be used).

An implementor SHOULD provide a way to let the user decide what to do and how to solve the conflict. 

### 3.4 "(x,y]"

The following mapping MUST be used by an implementor to choose the correct version (x is the target version).

    (,1.4]          x <= 1.4
    (,1.4)          x < 1.4
    [1.0]           x = 1.0 (no advice, hard requirement)
    [1.2,1.3]       1.2 <= x <= 1.3
    [1.0,2.0)       1.0 <= x < 2.0
    [1.5,)          x >= 1.5
    (,1.0],[1.2,)   x <= 1.0 or x >= 1.2
    (,1.1),(1.1,)   x != 1.1 (more detailed x < 1.1 or x > 1.1, used to exclude 1.1 because of known failures)

