# DRAFT: Error and Result Handling

## 1. Overview

This document describes standard interfaces for representing operation results in PHP applications.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](https://tools.ietf.org/html/rfc2119).

## 2. Definitions

- **Result**: The outcome of an operation that may succeed or fail.
- **Success Result**: A result containing a succesful value.
- **Failure Result**: A result containing error information.
- **Error**: Structured information about why an operation failed.

## 3. Interfaces

### 3.1 `ErrorInterface`

```php
<?php

namespace Psr\Error;

/**
 * Represents structured error information.
 */
interface ErrorInterface extends \Throwable
{
    /**
     * Returns a machine-readable error code.
     */
    public function getCode(): string;

    /**
     * Returns human-readable error message.
     */
    public function getMessage(): string;

    /**
     * Returns additional error context.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array;

    /**
     * Returns the underlying/previous error.
     */
    public function getPrevious(): ?ErrorInterface;

    /**
     * Creates a new instance with additional context.
     *
     * @param array<string, mixed> $context
     */
    public function withContext(array $context): self;
}
```

### 3.2 `ResultInterface`

```php
<?php

namespace Psr\Result;

use Psr\Error\ErrorInterface;

/**
 * Represents the result of an operation.
 *
 * Execution rules:
 * - If the result is a failure, `map()` and `then()` MUST NOT call their callbacks.
 * - If the result is a success, `mapError()` MUST NOT call its callback.
 * - `then()` MUST NOT wrap results; it must flatten them.
 *
 * @template TValue
 * @template TError of ErrorInterface
 */
interface ResultInterface
{
    /**
     * Returns true if the operation was successful.
     */
    public function isSuccess(): bool;

    /**
     * Returns true if the operation failed.
     */
    public function isFailure(): bool;

    /**
     * Returns the success value
     *
     * @return TValue
     * @throws \RuntimeException If result is a failure.
     */
    public function getValue(): mixed;

    /**
     * Returns the error if operation failed.
     *
     * @return TError|null
     */
    public function getError(): ?ErrorInterface;

    /**
     * Applies a transformation to the success value.
     *
     * Called only if the result is successful.
     * Failures are propagated unchanged.
     */
    public function map(callable $transform): ResultInterface;

    /**
     * Applies a transformation to the error.
     *
     * Called only if the result is a failure.
     * Success values are propagated unchanged.
     */
    public function mapError(callable $transform): ResultInterface;

    /**
     * Chains another operation that returns a Result.
     *
     * Called only if the result is successful.
     * The returned Result is flattened (no nesting).
     */
    public function then(callable $operation): ResultInterface;

    /**
     * Resolves the result into a single value.
     *
     * Exactly one callback is called.
     * This terminates the Result pipeline.
     */
    public function fold(callable $onSuccess, callable $onFailure): mixed;

    /**
     * Returns the success value or a default if failed.
     *
     * Does not expose the error.
     */
    public function getValueOr(mixed $default): mixed;
}
```

### 3.3 `ResultFactoryInterface`

```php
<?php

namespace Psr\Result;

use Psr\Error\ErrorInterface;

interface ResultFactoryInterface
{
    /**
     * Create a successful result.
     *
     * @template T
     * @param T $value
     * @return ResultInterface<T, ErrorInterface>
     */
    public function success(mixed $value): ResultInterface;

    /**
     * Create a failed result.
     *
     * @template E of ErrorInterface
     * @param E $error
     * @return ResultInterface<mixed, E>
     */
    public function failure(ErrorInterface $error): ResultInterface;

    /**
     * Create result from a callable that may throw.
     *
     * @template T
     * @param callable(): T $operation
     * @param callable(\Throwable): ErrorInterface $errorMapper
     * @return ResultInterface<T, ErrorInterface>
     */
    public function try(callable $operation, callable $errorMapper): ResultInterface;
}
```

## 4. Usage Examples

### 4.1 Basic Usage

```php
$result = $userRepository->findById($id);

if ($result->isSuccess()) {
    $user = $result->getValue();
    echo "Found: " . $user->getName();
} else {
    $error = $result->getError();
    logError($error->getCode(), $error->getContext());
}
```

### 4.2 Functional paradigm

```php
$email = $userRepository->findById($id)
    ->map(fn($user) => $user->getEmail())
    ->getValueOr('default@example.com');
```

### 4.3 Chaining Operations

```php
$result = $validator->validate($input)
    ->then(fn($v) => $repository->save($v))
    ->then(fn($e) => $notifier->notifyCreated($e))
    ->mapError(fn($err) => new PublicError($err->getMessage()));

// At the end...
if ($result->isSuccess()) {
    $finalEntity = $result->getValue(); // From notifier.
} else {
    $publicError = $result->getError(); // Already transformed to PublicError.
}
```
