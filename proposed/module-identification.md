Module Identification
=====================

This standard declares an unique identification of modules to not run into
conflicts with multiple packages and versions.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md


1. Overview
-----------

- Modules MUST be unique packages targeting one topic.

- Modules MUST be grouped by a VendorID.

- Modules MUST have a name unique within their VendorID.

- The modules name shall be short and reasonable.

- The version number MUST be part of the modules identification.

- An additional classifier shall be used to divide different bundles of the
  same module.

### 1.1 Example

Vendor ID = Zend
Module ID = Framework
Version = 2.1


2. Wording
----------

### 2.1 Module

A module is a bundled package of files (f.e. php classes, php scripts, documents, web files,
templates).

The following wording for types of modules is used:
- "Library" is a bundle of php classes.
- "Application" is set of classes and scripts that provides user access (f.e. cli, web)

### 2.2 Vendor

A vendor is a  unique identification of a modules author. See [PSR-0][].

### 2.3 Module Identification

The set of rules to identify a module.

### 2.4 Framework

Framework is a special kind of module used to build an application. The major difference
to normal libraries is the requirement that different frameworks SHOULD not be mixed. Example:
framework A and framework B SHOULD not be used within the same application. But framework A
bundles a huge set of library modules that MAY be used within framework B.

### 2.5 Version

A version is a specific build for a module. It is identified by the version number.

Snapshots are a special kind of version that is explained below. They may be used in
- developer builds
- revision builds
- daily builds
- pre-release/pre-tagging testing build (f.e. on continuous integration servers)

Special build variants of regular releases are:
- alpha
- beta
- release candidate
- final release


3. Dividing multiple modules
----------------------------

Modules MUST be unique packages and only target one specific topic.

Example: A module called "user-management" SHOULD NOT contain any code for displaying products
within internet shops.

If a project is made on several topics it SHOULD call itself "xxxx-website" and
import/use other modules that are called "xxxx-user-service", "xxxx-news-service",
"xxxx-user-frontend" or "xxxx-administration-backend".

Framework or distributions that bundle multiple topics SHOULD provide their functionality
or libraries in additional modules so that a user can choose to use only some modules or
the complete framework.

Module names SHOULD be short and responsible.


4. The identification
---------------------

### 4.1 VendorID

A VendorID MUST be unique. The VendorID MUST either be a well known project name or
an internet domain.

In case of well known project names the VendorID MUST match the following rule:
[A-Za-z][A-Za-z0-9]*
See [PSR-0][].

In case of internet domains the VendorID must match the following rules:
- lower cased
- only of a subset that will be allowed in php namespaces.

### 4.2 ModuleID

A ModuleID identifies the module itself. The ModuleID MUST be unique within the
VendorID.

The ModuleID SHALL be short and reasonable.

The ModuleID MUST only match the following rule:
[a-zA-Z][a-zA-Z_0-9]*
See examples in [PSR-0][].

Version numbers SHOULD not be part of the ModuleID except they are well known and accepted
world wide. Example: "Html5Utility"


### 4.3. Version

A module will always be identified by VendorID and ModuleID.

To identify multiple builds of the same module the version numbering is used.

Version number MUST follow the following international convention:
<major>[.<minor>[.<fixlevel>]][-extra]
where
<major> is [0-9]+
<minor> is [0-9]+
<fixlevel> is [0-9]+
<extra> is .+

A module identification without version number MUST be used as "The module in any
(possibly the newest) version that is found". It MUST not be used as "Every module
version".


5. Version rules
----------------

### 5.1 Version increments

If a newer version contains bugfixes that do not break the API the fixlevel SHOULD
be increased.

If a newer version contains some API changes or new features the minor version number
SHOULD be increased.

If a newer version contains a complete rewrite the major version number SHOULD
be increased.

### 5.2 The extra information

The extra information MAY contain any information on the product, for example the
version system tag. Some of the extra information are expected by the release cycle.

The extra information SHOULD be lower cased instead of those having special meanings:
- ALPHA
- BETA
- RC
- SNAPSHOT

### 5.3 ALPHA

Extra information in the format "ALPHA-[0-9]*" MUST be used for versions
that are missing major features and still very unstable. The version number
is meant as the target version for the next release.

Example:
2.0.0-ALPHA-3 is the 3rd alpha build of the upcoming 2.0.0 release.

### 5.4 BETA

Extra information in the format "BETA-[0-9]*" MUST be used for versions that are
containing all major features but MAY still have bugs and MAY still be unstable.

Example:
1.4.4-BETA-1 is the 1st beta build of the upcoming 1.4.4 maintenance release.

### 5.5 RC

Extra information in the format "RC-[0-9]*" MUST be used for versions that represent
a release candidate. That is a version known to be very stable and to be tested
as the upcoming release. It MAY be tagged/rebuilt as the release version without
additional changes.

Example:
3.0.0-RC-4 is the 4th release candidate and is identical to 3.0.0 final release
because no more bugs were reported.

### 5.6 SNAPSHOT

Extra information in the format "SNAPSHOT-[0-9]*" MUST be used for snapshot builds.

Snapshot builds are known to be unstable. They MAY not be built from version system.

Examples for situations a SNAPSHOT is built:
- daily build cycle from trunk
- built from dveeloper workstation including uncommited changes
- pre-release built from CI tools to test against before tagging.

### 5.7 Version ordering

Examples of version ordering:
    0.9.9-pre-release
    1
    1.0
    1.8-rev4711
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
by the user (while installing or while requesting a dependency).

If using SNAPSHOT versions they MUST be always lower than their targeting version as seen above).

As soon as the SNAPSHOT reaches the final version no more new SNAPSHOT versions are allowed.
Instead as soon as the SNAPSHOT results in a release version number SHOULD be incremented (in case
of ALPHA/BETA/RC the extra information can be increased and in case of final releases the
fixlevel/minor/major can be increased).
