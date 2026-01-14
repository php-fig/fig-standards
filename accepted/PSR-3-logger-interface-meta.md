# Logger Meta Document

## 1. Summary

The logger interface defines a common interface for logging system messages from an application or library.

This metadocument was written post-hoc, as PSR-3 was originally passed before meta-documents were standard practice.

## 2. Design Decisions

### Static log messages

It is the intent of this specification that the message passed to a logging method always be a static value.  Any context-specific variability (such as a username, timestamp, or other information) should be provided via the `$context` array only, and the string should use a placeholder to reference it.

The intent of this design is twofold.  One, the message is then readily available to translation systems to create localized versions of log messages.  Two, context-specific data may contain user input, and thus requires escaping.  That escaping will be necessarily different if the log message is stored in a database for later rendering in HTML, serialized to JSON, serialized to a syslog message string, etc.  It is the responsibility of the logging implementation to ensure that `$context` data that is shown to the user is appropriately escaped. 

## 3. People

### 3.1 Editor(s)

* Jordi Boggiano

## 4. Votes

[Approval vote](https://groups.google.com/g/php-fig/c/d0yPC7jWPAE/m/rhexAfz2T_8J)

## 5. Errata

### 5.1 Type additions

The 2.0 release of the `psr/log` package includes scalar parameter types.  The 3.0 release of the package includes return types.  This structure leverages PHP 7.2 covariance support to allow for a gradual upgrade process, but requires PHP 8.0 for type compatibility.

Implementers MAY add return types to their own packages at their discretion, provided that:

* the return types match those in the 3.0 package.
* the implementation specifies a minimum PHP version of 8.0.0 or later.

Implementers MAY add parameter types to their own packages in a new major release, either at the same time as adding return types or in a subsequent release, provided that:

* the parameter types match those in the 2.0 package.
* the implementation specifies a minimum PHP version of 8.0.0 or later.
* the implementation depends on `"psr/log": "^2.0 || ^3.0"` so as to exclude the untyped 1.0 version.

Implementers are encouraged but not required to transition their packages toward the 3.0 version of the package at their earliest convenience.

### 5.2 Throwable vs. Exception

At the time PSR-3 was written, PHP only had the `Exception` type and the more general `Throwable` interface did not yet exist.

In modern PHP versions, `Throwable` is the common base interface for both `Exception` and `Error`.

Wherever this specification refers to an `Exception` being passed in the `exception` context key, it SHOULD be interpreted as allowing any `Throwable` instance.

Implementations MUST still verify that the value in the `exception` context key is actually a `Throwable` before using it as such.