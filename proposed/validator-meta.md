Validator Meta Document
=======================

## 1. Summary

The SimpleValidatorInterface and ExtendedValidatorInterface define universal, minimalistic contracts for value validation in PHP. They provide a unified way to perform type-agnostic validations with either a simple pass/fail response (suitable for most form or field inputs) or a detailed, structured validation report, supporting error codes for i18n and advanced cases. Inspired by Symfony's Validator and `Respect\Validation`, these interfaces allow maximum interoperability and rapid provider/implementation swap, while enabling framework- and library-level integration without tight coupling.

Applications MAY depend only on these interfaces and swap implementations (or chains/compositions of validators) via DI, reducing coupling and simplifying maintenance, testing, and future migrations.

## 2. Why Bother?

Form and value validation is a ubiquitous problem across all PHP applications. Today, each framework (Symfony, Laravel, Yii, etc.) exposes its own interfaces, making it difficult to share validators, migrate between frameworks or libraries, or compose validation pipelines with userland tools.

A universal validator contract allows:

Rapid replacement of validation engines or libraries (vendor-agnostic interface).
Interchangeable custom and vendor-specific validators (e.g., open-source and proprietary).
Testability and integration with dependency injection containers.
Consistent error format for user feedback, translation, or error mapping.
Clean separation of simple (bool) and extended (structured, multi-error) validation.
This pattern avoids having to deeply couple code to a specific validator ecosystem (as in Symfony) and dramatically reduces refactoring when switching stacks or updating validation strategies.

#### Pros:

- Plug-and-play with any validator implementations (no hard dependency on a specific package)
- Extensible (vendors can add extra methods, but must comply with the common interface)
- Clean and decoupled design 
- Advanced: error codes, rich violation objects

#### Cons:

- Slight abstraction overhead (vs. using one concrete implementation)
- Vendor- or application-specific error codes are not enforced by spec (but spec supports them)
- Contextual or cross-field validation must be built upon (not in base interfaces)

## 3. Design Decisions

### 3.1. Simple vs. Extended

To satisfy both lightweight and complex needs, two interfaces are provided:

- `SimpleValidatorInterface` — exposes only `isValid(mixed $value): bool`. Suitable for basic needs (is it a valid phone? email? int in range?...) and maximizing performance. 
- `ExtendedValidatorInterface` — exposes `validate(mixed $value): ValidatorResponseInterface`, for structured results (validation status, errors, error codes/messages/etc.).
This separation enables applications to pick the level of detail they need. Many use cases will use only the simple interface.

### 3.2. ValidatorResponseInterface

A response from ExtendedValidatorInterface must expose:

- `isValid(): bool` — Was validation successful? 
- `getViolations(): array<ViolationInterface>` — Why did it fail, with detailed error objects.

### 3.3. ViolationInterface

Every violation contains:

- Machine-readable code (for programmatic matching, i18n, etc.). 
- Human-readable message (for fallback user feedback). 
- Optionally: parameters for message templates, offending value, etc. 
  Spec only mandates code and message; implementations may extend.

### 3.4. Exception Handling

Validation failures (value rejected) are NOT exceptional: response returned with errors.

Exceptions are for implementation/configuration/usage errors — not for negative validation.

### 3.5. Swappability & DI

Core purpose — to allow hot-swapping validator engines and compositions via DI or configuration, zero code refactor.

### 3.6. Scope

Only single-value validation in scope; not object/collection/nested.
Contextual validation may be layered by implementors using extensions.

## 4. People

### 4.1 Editor(s)

- TBA

### 4.2 Working Group members

- TBA
