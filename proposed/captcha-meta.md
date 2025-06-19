CAPTCHA Meta Document
=====================

## 1. Summary

The `CaptchaVerifierInterface` defines a standard, implementation-agnostic way to verify whether a user is human or automated, using vendor-provided challenge-response systems. It enables applications and frameworks to substitute CAPTCHA vendors transparently and with minimal effort, especially in crisis scenarios such as API bans, forced migrations, or vendor lock-in.

The interfaces are intentionally minimal: implementors MAY extend for vendor-specific data, but consumers MUST depend only on functionality guaranteed by the PSR, allowing maximum interoperability and rapid reaction to a changing security landscape, while they MAY depend on functionality, provided by specific implementations, but only if they know for sure this functionality exists (via instanceof/typehinting).

## 2. Why Bother?

There are currently several major CAPTCHA providers (such as Google reCAPTCHA, hCaptcha, Yandex SmartCaptcha, Cloudflare Turnstile, and others), as well as growing open-source and self-hosted alternatives. Many PHP libraries and frameworks ship with their own interfaces or concrete adapters for these services, leading to fragmentation and poor interoperability between codebases.

When a sudden vendor policy change, API discontinuation, cost escalation, or regulatory issue occurs, projects are forced to urgently change CAPTCHA vendors. In practice this results in application-wide refactoring, duplicated logic, vendor-specific workarounds, and the risk of business downtime or security lapses due to a breaking change in a critical security layer.

By providing a PSR for CAPTCHA, we enable PHP applications and frameworks to depend on a single interface, making it trivial to swap underlying implementations with minimal code change — often only at the DI configuration or service wiring level. This dramatically reduces migration risk and business impact during vendor crisis situations, promotes interoperability, and lessens the chance of vendor lock-in.

Pros:
* A universal, vendor-agnostic interface for CAPTCHA verification and response; 
* Rapid provider swap in emergency situations (API shutdowns, region bans, cost changes); 
* Less duplicated code and easier maintenance of libraries and frameworks; 
* Consistent exception handling and response format for all vendors; 
* Promotes extensible, flexible code design.

Cons:
* Slight abstraction overhead compared to using a single provider’s SDK directly; 
* Vendor-specific features that are not part of the interface may require interface extension or custom code; 

## 3. Design Decisions
   
### 3.1 Response Interface vs Boolean

During code-review a question have been asked: `why CaptchaVerifierInterface::verify() returns CaptchaResponseInterface rather than a simple bool`. While the fundamental purpose of a CAPTCHA is a binary distinction (human vs bot), in practice, most modern CAPTCHA providers expose rich additional data, such as "score" for confidence (see: Google reCAPTCHA v3, hCaptcha), messages, challenge metadata, or error diagnostics.

By requiring an interface that supplies at minimum an `isSuccess()`: bool method, this PSR permits codebases to enjoy both vendor-agnostic consumption and the option to access extra data by typehinting implementation extensions. This enables:

* Access to provider-specific scoring/attributes without PSR changes. 
* Future-proofing as vendors add richer response objects. 
* Compatibility with dependency injection and type-centric application design. 
* Shallow-to-deep migration (codebases MAY initially only call `isSuccess()`, later adapt to leverage e.g. `getScore()` if desired). 
* Returning only a boolean would foreclose all extensibility.

### 3.2 Exception Interface

Instead of prescribing a concrete exception class, this PSR defines a CaptchaException interface, explicitly extending \RuntimeException. This follows the precedent set by PSR-18 and others, allowing vendor libraries to inject their own exception type hierarchies under a unified ancestor.

### 3.3 Scoring

The increasing adoption of "scoring" instead of simple pass/fail, particularly for invisible-style CAPTCHAs, required that the PSR not constrain implementations to simply returning booleans. Implementations MAY extend the CaptchaResponseInterface to expose a score or provider raw data, but code depending strictly on the PSR MAY always use the `isSuccess()` boolean.

This pattern supports both simple and advanced applications (where scoring thresholds might be required by regulators or business rules) and ensures implementations MAY expose the full richness of their backend APIs while remaining PSR-compliant.

### 3.4 Zero-Refactor Provider Swap

The overarching design goal is to allow replacing any \Psr\Captcha\CaptchaInterface implementation (e.g. Google, hCaptcha, Yandex, Cloudflare Turnstile, etc) via configuration or dependency injection, without touching application code. This minimizes risk in migration and critical incident response, addressing:

* sudden API or ToS changes by vendors, 
* vendor-bans or unexpected region lockouts, 
* cost spikes, 
* rapid requirement changes (e.g. accessibility mandates).

### 3.5 Error Isolation

CaptchaException MUST be used for errors external to the user; e.g. misconfiguration, lost connectivity to the vendor, failed API authentication, or response parsing errors.
That exception MUST NOT be thrown if CAPTCHA token was actually validated, no matter the result - thus, unsuccessful validation (CAPTCHA provider said that user is a bot) MUST NOT throw an exception as this is not an exceptional case, instead `CaptchaVerifierInterface::isSuccess()` MUST return false
This ensures frontend code able to clearly distinguish between "user failed the challenge" and "site is misconfigured/problematic".
User errors (wrong, missing, or expired CAPTCHA tokens) are always indicated via a negative result from isSuccess().

## 4. People
   
### 4.1 Editor(s)

* [Ilya Saligzhanov](https://github.com/LeTraceurSnork)

### 4.2 Sponsor(s)

* _Vacant_
