# Error & Result Handling Meta Document

## 1. Summary

This PSR proposes standard interfaces for representing operation results and errors in a type-safe, composable manner. It defines:

- A `ResultInterface` representing the outcome of an operation (success or failure and the actual result value)
- An `ErrorInterface` representing detailed error information
- Standard patterns for chaining, transforming, and inspecting results

This actually enables libraries to return predictabe, structured outcomes without exceptions while maintaining interoperability.

Note: Error messages follow PSR-3 placeholder semantics. This enables deferred formatting, localization, and structured validation output without imposing a formatter or translator.

Note #2: `ResultFactoryInterface` is OPTIONAL. Libraries and apps MAY use direct construction or static named constructors (like Result::success()), if they control the implementation. The factory interface exists only to enable interoperable creation when implementations are abstracted.

## 2. Why Bother?

### Current Problems

1. Libraries use mixed approaches (exceptions, error codes, null returns)
2. PHP's type system cant distinguish between valid returns and errors
3. Simple `false` or `null` returns don't carry error details
4. Each framework implements its own result/error objects
5. Not all failures are exceptional; some are expected business cases

### Benefits

- Chain operations without try-catch nesting
- Preserve error context across bondaries
- Libraries can share error semantics
- Avoids exception overhead for expected failures
- Distinguishes between technical errors and business rule violations
- _PHPStan/PHPCS/Psalm can verify error handling_

## 3. Scope

### 3.1 Goals

- Define standard interfaces for operation results
- Provide base implementation for common use cases
- Enable interoperability between error-aware libraries
- Support both synchronous and asynchronous patterns
- Integrate with existing PSRs (PSR-3 logging, PSR-14 events)
- Be compatible with PHP 7.4+ type systems

### 3.2 Non-Goals

- **Not** replacing exceptions for truly exceptional conditions
- **Not** prescribing logging or monitoring implementation
- **Not** definng transport/serialization formats
- **Not** handling global error/exception handlers
- **Not** replacing HTTP status codes in PSR-7/15/18

## 4. Approaches

### 4.1 Chosen Approach: Tagged Union with Monadic Methods

**Why this approach:**

- PHP's type system supports it via `isSuccess()`/`isFailure()` discrimination
- Provides both imperative `if` and functional `map()` access patterns
- Familiar to developers from modern languges (Rust, Kotlin, Swift...)
- Maintains PHP's pragmatic balance between OOP and functional patterns
- Can be extended for async/await patterns when Fibers mature

## 5. Backward Compatibility

For gradual adoption, libraries MAY:

1. Add new methods returning `ResultInterface` alongside old methods
2. Provide adapters from exceptions to results

## 6. Error Classification

Implementations MAY extend error types:

- `ValidationError` (business rule violations)
- `NotFoundError` (missing resources)
- `ConflictError` (state conflicts)
- `AuthenticationError` (auth failures)
- `AuthorizationError` (permission issues)
- `InfrastructureError` (system failures)

## 7. Typing and Generics

Since PHP does not currently support generics at the language level:

- This PSR uses phpdoc-based generics (@template) to express the relationship between success values and error values, following established practice in the PHP ecosystem.
- Implementations are expected to enforce the invariant that a Result contains either a success value or an error, but never both.
- Consumers SHOULD rely on `isSuccess()` / `isFailure()` (or equivalent terminal operations such as `fold()`) before accessing the contained value.
- This PSR does not require nor encourage implementors to create separate result classes for each possible value or error type.

## 8. Namespaces

- `ErrorInterface` is defined in the `Psr\Error` namespace to allow error objects to be reused independently of `ResultInterface`.
- Errors in this PSR are plain value objects that may be created, transformed, transported, logged, or rendered without necessarily being wrapped in a `Result`.
- `ResultInterface` composes an error but does not own the error abstraction. Keeping these concerns in separate namespaces avoids coupling error representation to a single control-flow mechanism and preserves flexibility for future use-cases.

## 9. People

- **PHP-FIG team**
- **Proposer:** Yousha Aleayoub - [blog](https://yousha.blog.ir)
- **Discussion Group:** https://groups.google.com/g/php-fig/c/OpEuvGERM5A
