# PSR evolution

## Scope and objectives

A PSR is often comprised of text and code, more specifically interfaces. Those interfaces are pieces of code that are released and tagged at a specific moment in time, but the PHP language doesn't stand still... it evolves over time.

This means that those interfaces need to withstand that evolution, and sometimes they need to be updated, to leverage new language features that could help better enforce the behaviors exposed in the PSR itself.

At the same time, a PSR cannot be changed after its release (only erratas allowed), to protect a package that declared compatibility from becoming de-facto not compatible anymore.

This document defines a process to be followed in updating PSR interfaces, in a way that is not breaking in regard to behavior for end users, and with an appropriate upgrade path for the packages.

## New releases

A new minor release of a PHP-FIG package containing interfaces for a PSR MUST follow these rules:
 * the new release MUST follow [Semantic Versioning](https://semver.org/) rules;
 * the PSR behavior MUST NOT be altered;
 * the packages that implement the interfaces or users that are consuming them MUST NOT suffer breaking changes;
 * the PHP version constraint of the PHP-FIG package MAY be altered to require newer language features that would aid cross-compatibility;
 * the PHP version constraint of the PHP-FIG package MUST NOT be altered to use newer language features that would create cross-compatibility issues.
 
A new major release of a PHP-FIG package containing interfaces for a PSR MUST follow the same rules, with this exception:
 * the new major version of the package MAY contain breaking changes if the implementing packages have a reasonable upgrade path, like the possibility of releasing a cross-compatible implementation with the previous releases;
 * the new major version of the package MAY refer to a new, superseding PSR.

### Workflow

Since releasing new versions of the interfaces MUST NOT alter the PSR in its behavior, those releases can be voted in with the same process as errata changes. The new releases MUST be declared and embedded in the PSR document, with eventual indications on the upgrade path in the meta document.

### Practical example

A common case for an upgrade in the interfaces is to add types for parameters and returns, since they are new language features introduced by PHP 7, and many PSR interfaces predate that release. We'll use a method from PSR-11 as an example of how a PSR interface could be updated.

#### PSR-11: the interface

PSR-11 is released with the [`psr/container` package](https://packagist.org/packages/psr/container) and it holds the `ContainerInterface`, which has this method:
```php
    /**
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id);
```
This method could be updated with a new minor release that adds the argument type for `$id`:
```php
public function has(string $id);
```
This change would technically be a breaking change, but thanks to the [limited contravariance possible in PHP 7.2](https://wiki.php.net/rfc/parameter-no-type-variance), we can avoid that. This means that just by requiring `"php": "^7.2"` in the `psr/container` `composer.json`, we could tag this change as a minor release, and have all the implementors be automatically cross-compatible, provided that they declare `"psr/container": "^1.0"` (or equivalent) as a constraint, which they should.

After this intermediate step, it would be possible to release a new major version, that would add the return type:
```php
public function has(string $id): bool;
```
This must be released as a new major version of `psr/container` (2.0); any package that would implement this would be able to declare `"psr/container": "^1.1 || ^2.0"`, since backward compatibility to the first release would be impossible, due to the sum of covariance and contravariance rules.

#### PSR-11: the implementation

On the other side, the implementing packages would be able to do a release cycle in the opposite fashion. The first release looks like this:
```php
public function has($id);
```
The second release would adds the return type, maintaining compatibility with the original interface:
```php
public function has($id): bool;
```
A third release would be tagged then, adding the argument type too:
```php
public function has(string $id): bool;
```
