# Error handler meta document

## 1. Goals

- To raise awareness of the existing climate of incompatible error handling
  strategies.
- To help protect code consumers from unknowingly mixing incompatible libraries,
  packages, frameworks, components, etc.
- To help code producers specify their error handling requirements.
- To promote improved interoperability by encouraging the adoption of
  exception-based error handling as the standard for new projects.

## 2. Why bother?

### 2.1. A brief history of error handling in PHP

#### 2.1.1. Before exceptions

Before the introduction of exceptions, error conditions in PHP were handled
through *error messages*. These error messages were differentiated by
*severity*; most notably errors, warnings, and notices (and eventually
deprecation messages). The severity of errors was used to determine whether
script execution should continue after the error was handled, or if execution
should halt for a serious error.

In addition to their primary role, error messages were typically logged. This
often lead to the error system being harnessed as a simple logging system.

#### 2.1.2. The introduction of exceptions

Shortly after PHP 5 came about, exceptions were introduced as a first-class
feature. Exceptions provided much greater control over how error conditions were
handled. In short, they allowed the developer to anticipate potential problems,
and handle them gracefully. Something which was much more challenging prior to
their introduction.

Despite exceptions being available, PHP retained its existing error message
system alongside the exception system. A special exception designed to represent
an error message was introduced, however. The [ErrorException] class was
specifically designed to contain the information that would normally be
expressed in an error message.

#### 2.1.3. Adoption of exception-throwing error handlers

As PHP 5 matured, developers began to explore using error handlers that utilized
thrown [ErrorException] instances to replace traditional error handling
strategies. This approach proved successful and popular over time, and is the
typical approach found in today's major frameworks.

### 2.2. The current state of error handling in PHP

PHP is in a state of limbo with regards to error handling. In general, code is
either written to expect a runtime environment where exceptions are thrown to
represent errors, or it is written to expect traditional error handling. Code
that is designed to work identically in either environment is exceedingly rare.
This leads to a hidden, and potentially dangerous, interoperability issue.

#### 2.2.1. Expecting traditional errors

To illustrate the point further, let's use an example scenario. The following
code would be suitable for an environment where *traditional* error handling is
in use:

```php
$path = '/path/to/important/file';
$stream = fopen($path, 'rb');
if (!$stream) {
    mail('jbond@mi5.gov.uk', 'Important file missing', 'Commence operation.');
    throw new FileReadException($path);
}
everythingIsOkay();
```

In case of a problem opening the file, `fopen()` will raise a PHP warning, but
execution will continue, and `fopen()` will return `false`. The code will
correctly identify the error condition, send an important warning email, and
throw a `FileReadException` as expected. Importantly, the `everythingIsOkay()`
function will not be called.

However, what happens in the same situation in an environment where *error
exceptions* are thrown instead? The call to `fopen()` would result in an
[ErrorException] being thrown. Without an appropriate `catch` statement, the
important warning email will never be sent. Additionally, the [ErrorException]
will not be caught by any code expecting a `FileReadException`, which will most
likely result in execution being halted. At least `everythingIsOkay()` is not
called.

#### 2.2.2. Expecting error exceptions

The same example designed for an environment where *error exceptions* are thrown
would look something like:

```php
$path = '/path/to/important/file';
try {
    $stream = fopen($path, 'rb');
} catch (ErrorException $e) {
    mail('jbond@mi5.gov.uk', 'Important file missing', 'Commence operation.');
    throw new FileReadException($path, $e);
}
everythingIsOkay();
```

In case of a problem opening the file, `fopen()` would result in an
[ErrorException] being thrown. The code will correctly identify the error
condition by catching the exception, send an important warning email, and throw
a `FileReadException` as expected (with the [ErrorException] as the 'previous'
exception). Importantly, the `everythingIsOkay()` function will not be called.

However, what happens in the same situation in an environment where
*traditional* error handling is in use? The call to `fopen()` will raise a PHP
warning, but execution will continue. No [ErrorException] is thrown, so the
`catch` statement will have no effect. No warning email will be sent, no
`FileReadException` is thrown, and worst of all, `everythingIsOkay()` will be
called. Everything is definitely *not* okay.

#### 2.2.3. Expecting both exceptions and errors

Let's take a look at the code necessary to handle the same example when
accounting for both error handling approaches as possibilities:

```php
$path = '/path/to/important/file';
$e = null;
try {
    $stream = fopen($path, 'rb');
} catch (ErrorException $e) {
    $stream = false;
}
if (!$stream) {
    mail('jbond@mi5.gov.uk', 'Important file missing', 'Commence operation.');
    throw new FileReadException($path, $e);
}
everythingIsOkay();
```

From this example, it's immediately obvious that accounting for both situations
is extremely tedious. The code is also harder to understand, and will require
increased effort to unit test all possible code branches.

### 2.3. Not having a standard is hurting everyone

Without a formal specification for error handling, few PHP projects are designed
to be truly interoperable in the way they deal with errors, and projects rarely
specify the type of error handling they expect from their runtime environment.
This leads to the situation where consumers of code can unknowingly rely on code
that is fundamentally incompatible with the environment in which it will be run.

Code producers also suffer. The added burden of designing code to work with
multiple possible error handlers is tedious. Incompatible error handling
strategies hinder code re-use, and foster mistrust in the code of others.

## 3. What can be done to address the issue?

### 3.1. Requirements for an effective solution

- Packages / projects / frameworks / components need a way to specify the types
  of error handling they support.
- Consumers who pull in these packages as dependencies need a way to ensure that
  they have chosen compatible packages.
- Error handlers form a part of the PHP runtime environment. Ideally, a
  spec-conformant error handler should be installed before any other code is
  executed, even before any class loaders are registered.

This document will suggest two possible options for addressing these
requirements. Both revolve around the dependency manager [Composer], although
similar solutions could easily be extrapolated for other package/dependency
management applications.

### 3.2. Solution A: Error handling management as a first-class Composer feature

This solution involves implementing error handling management by introducing new
Composer features. It's obviously not this document's place to prescribe
features to the Composer project. This is simply intended as an example of an
ideal situation.

A new optional property `error-handling` would be added to the Composer package
configuration schema. This property would allow one of three string values:
`PSR-X`, `traditional`, or `any`, with `any` being the default.

- A value of `PSR-X` would indicate that the package expects a `PSR-X`
  conformant error handler to be installed, where `PSR-X` is the PSR number of
  the error handler specification associated with this meta document.
- A value of `traditional` would indicate that the package expects the error
  handler to behave in the same manner as the built-in PHP handler.
- A value of `any` would indicate that the package is capable of functioning
  under either error handling strategy.

Another optional property `use-error-handling` would be added under the
project-only section ([config]) of the Composer package configuration schema.
This property, used only in root packages, would allow one of two string values:
`PSR-X`, or `traditional`, with `PSR-X` being the default.

- A value of `PSR-X` would indicate that Composer should install a `PSR-X`
  conformant error handler before setting up the class loader.
- A value of `traditional` would indicate that Composer should not install an
  error handler.

During the normal process of dependency resolution, components that require
incompatible error handling strategies would be highlighted as conflicts. This
brings the problem to the attention of the package developer, and allows them to
make informed decisions about how to address the conflict.

In addition to these new properties, some mechanism may have to be introduced to
allow package developers to ignore conflicts.

#### 3.2.1. Pros of solution A

- Error handling requirements are expressed clearly and succinctly.
- The error handler is guaranteed to be installed before any errors occur.
- First-class support in Composer would allow for clearer conflict messages.

#### 3.2.2. Cons of solution A

- Introducing new Composer features would take time and effort.

#### 3.2.3. Example package configurations for solution A

Package requiring PSR-X error handling:

```json
{
    "name": "vendor/package",
    "require": {
        "php": ">=5.3",
        "psr/log": "~1"
    },
    "autoload": {
        "psr-4": {
            "Vendor\\Package\\": "src"
        }
    },
    "error-handling": "PSR-X"
}
```

Root package capable of working with either handling strategy, but opting to
use traditional error handling:

```json
{
    "name": "vendor/project",
    "require": {
        "php": ">=5.3",
        "psr/log": "~1"
    },
    "autoload": {
        "psr-4": {
            "Vendor\\Project\\": "src"
        }
    },
    "config": {
        "use-error-handling": "traditional"
    }
}
```

### 3.3. Solution B: Error handling management via existing Composer features

This solution involves implementing error handling management by harnessing the
existing Composer dependency resolution system and 'virtual packages'. This
solution could be implemented with very little effort.

Package developers would add special virtual packages to their Composer
configuration's [require] section to specify their error handling requirements.
Two virtual packages would be officially sanctioned by the FIG:
`psr/error-exceptions` and `psr/traditional-errors`.

- Requiring `psr/error-exceptions` would indicate that the package expects a
  `PSR-X` conformant error handler to be installed, where `PSR-X` is the PSR
  number of the error handler specification associated with this meta document.
- Requiring `psr/traditional-errors` would indicate that the package expects the
  error handler to behave in the same manner as the built-in PHP handler.
- Requiring neither of the virtual packages would implicitly indicate that the
  package is capable of functioning under either error handling strategy.

Root package developers would specify the type of error handling strategy in use
by adding one of the virtual packages to their Composer configuration's
[provide] section.

- Providing `psr/error-exceptions` would indicate that a `PSR-X` conformant
  error handler will be installed.
- Providing `psr/traditional-errors` would indicate that the installed error
  handler will behave like the in-built PHP handler.

It is then up to the root package developer to make good on their guarantee, and
install any appropriate error handler implementations.

During the normal process of dependency resolution, components that require
incompatible error handling strategies would be highlighted as conflicts. This
brings the problem to the attention of the package developer, and allows them to
make informed decisions about how to address the conflict.

If root package developers need to ignore conflicts, they can simply provide
both `psr/error-exceptions` and `psr/traditional-errors`.

This solution could also be achieved by adding formal virtual packages to
Composer, in the same fashion as the `php` virtual package currently allows
the package to specify its PHP version requirements. In this case, the `psr/`
prefix could likely be dropped from the package names.

#### 3.3.1. Pros of solution B

- Little effort is required to implement.
- Requires no new Composer features.

#### 3.3.2. Cons of solution B

- Error handling requirements are not expressed as clearly as solution A.
- Stating that a package provides a particular handler is a weak guarantee. The
  actual handler installation becomes the responsibility of the developer,
  making it more prone to human error.
- Conflict messages produced by Composer may be unclear.

#### 3.3.3. Example package configurations for solution B

Package requiring PSR-X error handling:

```json
{
    "name": "vendor/package",
    "require": {
        "php": ">=5.3",
        "psr/error-exceptions": "*",
        "psr/log": "~1"
    },
    "autoload": {
        "psr-4": {
            "Vendor\\Package\\": "src"
        }
    }
}
```

Root package providing PSR-X error handling:

```json
{
    "name": "vendor/project",
    "require": {
        "php": ">=5.3",
        "psr/log": "~1"
    },
    "provide": {
        "psr/error-exceptions": "1.0.0"
    },
    "autoload": {
        "psr-4": {
            "Vendor\\Project\\": "src"
        }
    }
}
```

Root package providing traditional error handling:

```json
{
    "name": "vendor/project",
    "require": {
        "php": ">=5.3",
        "psr/log": "~1"
    },
    "provide": {
        "psr/traditional-errors": "1.0.0"
    },
    "autoload": {
        "psr-4": {
            "Vendor\\Project\\": "src"
        }
    }
}
```

## 4. Justification for design decisions

### 4.1. Why not ignore notices/warnings?

Severity is a poor indicator of how an error should be handled. Aside from
deprecation messages, all traditional PHP error messages indicate that something
is wrong with the code.

The problem with severity is that the decision of whether an error condition can
safely by ignored is pushed onto the developer producing the error. The exact
same error may be catastrophic in one circumstance, and trivial in another.
Hence the decision of 'severity' should be made by the error consumer, not the
producer.

Severity can also be abused. Developers may use a notice, when a warning is
more appropriate. Whether from inexperience, or in a misguided attempt to make
code 'easier' to use, the outcome is that severity is a very loose metric on
which to base decisions.

Treating all severities as equal puts control in the hands of the error
consumer, where it is needed most. In addition, severity can still be manually
inspected by the error consumer if absolutely necessary.

### 4.2. Why ignore deprecation messages?

Deprecation messages are not run-time errors. Notices, warnings, and errors
usually indicate problems that arise due to the program's execution state.
Deprecation messages are distinct in that they point to issues that can only be
addressed by making modifications to source code.

### 4.3. Why support the error control operator (`@` suppression)?

Adding support for the error control operator is trivial, and improves
interoperability with existing code. Code like the following is still fairly
common:

```php
if (!$stream = @fopen('/path/to/file', 'rb')) {
    // handle error condition
}
```

This approach can be a quick-and-dirty way to support both error handling
strategies. This does **not** mean that use of the error control operator is
recommended. Preferable alternatives to error suppression will be discussed in
another part of this document.

Unfortunately there are still some rare situations where error suppression may
be the only viable solution to a genuine problem. For example, an internal PHP
function that raises a notice before performing an important part of its
execution. Under a `PSR-X` error handler, these notices are thrown as
exceptions, causing the internal function's execution to be cut short. Arguably,
these situations should be raised as bugs and fixed in PHP, but from a pragmatic
standpoint, an immediate solution is sometimes required, and error suppression
fits this bill.

#### 4.3.1. Performance considerations

It is often stated that the error control operator is 'slow', but this is not
necessarily true. Performance problems *can* arise when using the in-built PHP
error handler in tandem with `@` suppression, and error logging. This is because
each suppressed error still results in an entry in the error log. When a
suppressed error occurs many times (inside a loop for example), the I/O cost can
become significant.

It is for this reason that the error handler specification recommends that no
logging, or other performance-intensive operations are performed when error
suppression is enabled.

## 5. Best practices going forward

### 5.1. Handling errors under `PSR-X`

Handling errors when using a `PSR-X` handler is simple. Simply surround the
error-producing statement with a `try`/`catch` statement that handles
[ErrorException] instances:

```php
$path = '/path/to/file';
try {
    $stream = fopen($path, 'rb');
} catch (ErrorException $e) {
    throw new FileReadException($path, $e);
}
```

Notice that in this example, the error exception is passed to the newly created
`FileReadException` as the 'previous' exception. This is called 'exception
chaining', and allows inspection of the exception to determine the root cause of
a problem.

### 5.2. Throw exceptions instead of raising errors

Instead of raising errors with `trigger_error()`, throw a custom exception that
clearly expresses the error condition. Avoid re-using the same exception class
when your code can produce multiple different error conditions, as it makes
handling them more difficult.

If you need to group multiple related exceptions so that they can be caught by a
single `catch` statement, make each of the exception types implement a common
interface and use that interface in the `catch` statement.

### 5.3. Avoid creating exceptions in non-exceptional circumstances

As a general rule, both errors and exceptions should be avoided if the condition
they represent occurs during regular execution. In many cases where there is a
single common failure condition, use of a boolean type is sufficient to indicate
whether an operation was successful.

Consider an object that wraps an array, and throws exceptions when an undefined
index is requested:

```php
class ArrayAccessor
{
    public function get($index)
    {
        if (!array_key_exists($index, $this->values)) {
            throw new UndefinedIndexException($index);
        }

        return $this->values[$index];
    }

    public $values;
}

$accessor = new ArrayAccessor($array);
try {
    $value = $accessor->get(0);
    // index 0 exists
} catch (UndefinedIndexException $e) {
    // index 0 does not exist
}
```

If this code is called often, and it is common for the index to be undefined, a
better solution might be to use a boolean return type, and a pass-by-reference
argument:

```php
class ArrayAccessor
{
    public function get($index, &$value)
    {
        $value = null;
        if (array_key_exists($index, $this->values)) {
            $value = $this->values[$index];

            return true;
        }

        return false;
    }

    public $values;
}

$accessor = new ArrayAccessor($array);
if ($accessor->get(0, $value)) {
    // index 0 exists
} else {
    // index 0 does not exist
}
```

### 5.4. Avoid error suppression

Error suppression is almost always a bad idea. There are exceptions to this
rule, especially when using traditional error handling, but in general they
should be avoided if at all possible. For example, avoiding warnings when using
`fopen()` is a common use-case for error suppression:

```php
if (!$stream = @fopen('/path/to/file', 'rb')) {
    // handle error condition
}
```

These warnings cannot be completely avoided just by checking that the file can
be read beforehand. There is still the possibility that the file may be deleted
in between checking for readability, and trying to open it.

A `PSR-X` conformant error handler offers a better way to handle these
situations. The following code handles unreadable files in all situations:

```php
try {
    $stream = fopen('/path/to/file', 'rb');
} catch (ErrorException $e) {
    // handle error condition
}
```

## 6. Conclusion

This document has tried to anticipate and address some of the questions that are
likely to be raised concerning the `PSR-X` document itself, but there is still
much discussion to be had. Please direct such discussion to the PHP-FIG [mailing
list].

<!-- References -->

[Composer]: https://getcomposer.org/
[config]: https://getcomposer.org/doc/04-schema.md#config
[ErrorException]: http://php.net/manual/en/class.errorexception.php
[Packagist]: https://packagist.org/
[provide]: https://getcomposer.org/doc/04-schema.md#provide
[require]: https://getcomposer.org/doc/04-schema.md#require
[mailing list]: https://groups.google.com/forum/?fromgroups#!forum/php-fig
