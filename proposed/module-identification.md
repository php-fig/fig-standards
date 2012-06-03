Module Identification
=====================

This standard declares an unique identification of modules to not run into
conflicts with multiple packages and versions.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[Semver]: http://semver.org/

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
<major>.<minor>.<fixlevel>-extra
<major>.<minor>.<fixlevel>+extra

A module identification without version number MUST be used as "The module in any
(possibly the newest) version that is found". It MUST not be used as "Every module
version".


5. Version rules
----------------

The version semantics follows the specification of [Semver][] with the following
extension:

The word snapshot is used for the following build types:

- development builds that may not be covered by version control (builds from local workspaces
  with uncommited changes)
- nightly builds
- builds from build tools or continuous integration servers prior tagging a version

The snapshots SHOULD be named in the following way:
    1.0.0-snapshot.<timestamp>
Where timestamp (in GMT timezone) is:
    YYYYMMDDhhmmss

Following [Semver][] snapshots will be greater than release candidates and less than the
final released version: 1.0.0-rc.1 < 1.0.0-snapshot.20120903101744 < 1.0.0


6. Namespace mapping
--------------------

The VendorID MUST be leading to the namespace name as described in [PSR-0][].

The ModuleID SHOULD be used as the modules namespace below the Vendor namespace if
there are multiple modules published with the same VendorID.

For internet domains used as VendorID the [PSR-0][] is extended in the following
way:
- The dots are replaced by namespace separator (backslash).
- The internet domain is used in reverse order

Examples:
    VendorID = Zend; ModuleID = Framework-Full; Namespace = \Zend
    VendorID = Zend; ModuleID = Framework-Minimal; Namespace = \Zend
    VendorID = Zend; ModuleID = Acl; Namespace = \Zend\Acl
    VendorID = Doctrine; ModuleID = Common; Namespace \Doctrine\Common
    VendorID = mycompany.net; ModuleID = WebLibrary; Namespace \net\mycompany\WebLibrary


    