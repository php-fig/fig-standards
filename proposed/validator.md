Validator PSR: Specification Draft
==================================

This document describes common interfaces to validate values in PHP, in both simple and structured modes.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## 1. Specification

### 1.1. Definitions

- Validation: Process of checking if input value satisfies certain constraints (type, format, range, etc.). 
- Violation: Individual reason why a value fails validation (error). 
- Error Code: Machine-readable string/code for a validation failure, suitable for mapping, i18n or programmatic branching.

## 2. Interfaces

### 2.1. SimpleValidatorInterface

```php
namespace Psr\Validator;

/**
 * Minimalistic validator interface.
 */
interface SimpleValidatorInterface
{
    /**
     * Validates supplied value.
     * MUST return true if $value passes.
     * MUST return false if $value fails.
     * MUST NOT throw exceptions if $value fails.
     * SHOULD throw ValidatorException if the Validator itself could not tell if the value passes or not (e.g. Validator misconfigured)
     *
     * @param mixed $value
     *                    
     * @throws ValidatorException
     *
     * @return bool
     */
    public function isValid(mixed $value): bool;
}
```

## 2.2. ExtendedValidatorInterface

```php
namespace Psr\Validator;

/**
 * Extended validator interface returning structured response.
 */
interface ExtendedValidatorInterface
{
    /**
     * Validates supplied value and returns a ValidatorResponseInterface instance.
     * MUST return ValidatorResponseInterface if $value was validated at all (with any result).
     * MUST NOT throw exceptions if $value fails.
     * SHOULD throw ValidatorException if the Validator itself could not tell if the value passes or not (e.g. Validator misconfigured)
     *
     * @param mixed $value
     * @param array $context
     *
     * @throws ValidatorException
     *
     * @return ValidatorResponseInterface
     */
    public function validate(mixed $value, array $context = []): ValidatorResponseInterface;
}
```

## 2.3. ValidatorResponseInterface

```php
namespace Psr\Validator;

/**
 * Wraps the result of validation including status and violations list.
 */
interface ValidatorResponseInterface
{
    /**
     * MUST return true if $value passed, and false otherwise.
     * MUST be immutable.
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Returns all violations (validation errors) as ViolationInterface objects.
     *
     * @return ViolationInterface[]
     */
    public function getViolations(): array;
}
```

## 2.4. ViolationInterface

```php
namespace Psr\Validator;

/**
 * A single validation violation.
 */
interface ViolationInterface
{
    /**
     * Machine-readable error code (for mapping, i18n, client logic).
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Error message (human-readable, MAY be locale-dependent).
     *
     * @return string
     */
    public function getMessage(): string;
}
```

## 2.5. ValidatorException

```php
namespace Psr\Validator;

/**
 * Thrown ONLY if validator is misconfigured or cannot process request.
 * MUST NOT be thrown if $value was actually tested, no matter the result.
 */
interface ValidatorException
{
}
```
