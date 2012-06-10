Module Identification
=====================

This standard declares an unique identification of modules to not run into
conflicts with multiple packages and versions.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[Semver 2.0.0-rc.1]: https://github.com/mojombo/semver/blob/3c7f2e8df747ea0ca15208fdfc90e3275240184f/semver.md
[Composer]: http://getcomposer.org/doc

1. Overview
-----------

- Modules MUST be unique packages targeting one topic.

- Modules MUST be grouped by a VendorID.

- Modules MUST have a name unique within their VendorID.

- The modules name SHALL be short and reasonable.

- The version number MUST be part of the modules identification and
  MUST follow [Semver 2.0.0-rc.1][].

### 1.1 Examples

    VendorID = Zend
    ModuleID = Framework
    Version = 2.1.0
    
    VendorId = org.mycompany
    ModuleId = WebsiteLibrary
    Version = 1.0.0-beta.2
    
    VendorId = SomeVendor
    ModuleId = SomeModule
    Version = 2.4.1-dev.1


2. Wording
----------

### 2.1 Module/ Package

A module or package is a bundled package of files (f.e. php classes, php scripts, documents, web files,
templates).

The following wording for types of modules is used (see [Composer][]):
- "Library" is a bundle of php classes.
- "Metapackage" is a bundle of other libraries that does not have any php classes on its own.
- "Installer" is a package to extend the installer functionality and tell the installer how
  to handle a specific module type.
- "Application" is set of classes and scripts that provides user access (f.e. cli, web)

### 2.2 Vendor

A vendor is a unique identification of a modules author. See [PSR-0][].

### 2.3 Module Identification

The unique identification of a module. A set of rules for identifying a module.

### 2.4 Framework

Framework is a special kind of module used to build an application. The major difference
to normal libraries is the requirement that different frameworks SHOULD not be mixed. Example:
framework A and framework B SHOULD not be used within the same application. But framework A
bundles a huge set of library modules that MAY be used within framework B.

### 2.5 Version

A version is a specific build for a module. It is identified by the version number.

Development builds are a special kind of version that is explained below. They may be used in
- builds from developers workstations that may contain resources outside version control
- non-tagged builds
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

A VendorID SHOULD be unique. The VendorID SHOULD either be a well known project name or
an internet domain or any other namespace compatible name.

In case of well known project names the VendorID MUST match the following rule:
[A-Za-z][A-Za-z0-9]*
See [PSR-0][].

In case of internet domains the VendorID must match the following rules:
- lower cased
- only of a subset that will be allowed in php namespaces and by namespace mappings.

### 4.2 ModuleID

A ModuleID identifies the module itself. The ModuleID MUST be unique within the
VendorID.

The ModuleID SHALL be short and reasonable.

The ModuleID MUST match the following rule:
[a-zA-Z][a-zA-Z_0-9]*
See examples in [PSR-0][].

Version numbers SHOULD not be part of the ModuleID except they are well known and accepted
world wide. Example: "Html5Utility"


### 4.3. Version

A module will always be identified by VendorID and ModuleID.

To identify multiple builds of the same module the version numbering is used.

Version number MUST follow the following convention from [Semver 2.0.0-rc.1][]:
<major>.<minor>.<fixlevel>-extra
<major>.<minor>.<fixlevel>+extra


5. Version rules
----------------

The version semantics follows the specification of [Semver 2.0.0-rc.1][] with the following
extension for development builds:

The dev SHOULD be named in the following way:
    1.0.0-dev.<timestamp>
Where timestamp (in GMT timezone) is:
    YYYYMMDDhhmmss

Developer builds SHOULD not be respected while ordering/comparing versions. A packager/
repository implementation SHOULD let the user choose which staging to use. It SHOULD always
prefer release builds prior development builds.

The [Semver 2.0.0-rc.1][] rules say that the extra is compared lexically (see rule #12). You may use
any string, for example "1.0.0-revision.3224" or "1.0.0-build.3344" in your version number
but be aware that:
- using names other than "alpha", "beta", "rc" and "dev" may confuse people and may break frameworks
  and package managers.
- build tools may order your version on wrong positions.

To have a build number applied to a stable release you can use the plus sign, for example:
"1.0.0+build.3344". See examples in [Semver 2.0.0-rc.1][] rule #11.


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


    