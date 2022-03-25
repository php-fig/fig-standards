# Application Tracing Meta Document

## 1. Summary

Application performance monitoring (APM) tools are becoming increasingly popular, however, the lack of formal interfaces is holding back the ecosystem.
Whilst developers are inherently free to use whichever tools suit their use-cases, this is not the case for framework and library creators/maintainers.

This standard aims to create an intentionally minimalist set of interfaces that can be used to provide tracing signals to 3rd party libraries in a unified manner.

### 1.1 Definitions

The following definitions apply for this document:
* Framework - An application framework (or micro-framework) that runs a developers code.  
  Eg. Laravel, Symphony, CakePHP, Slim
* Library - Any library that developers may include that adds additional functionality  
  Eg. Image Manipulation, HTTP Clients, 3rd Party SDK's 
* Provider - An organization that offers APM as a service. Typically via a composer package

## 2. Why Bother?

The [OpenTelemetry][] team are in the process of releasing a set of SDK's that would allow frameworks and libraries to send signals to providers in a uniformed manner.
However, there is a perceived expectation of a 1-way flow of responsibility, where Frameworks and Libraries are expected to accept Jagger traces[^1].

This PSR provides a bridge between Frameworks and Libraries who want to provide tracing signals, and Providers, without the requirement to make large scale changes to their infrastructure, etc.  
By taking transmission mechanisms [out of scope](#31-non-goals) for this PSR, we drastically increase the simple adoptability of tracing for all parties involved

[^2.1]: Seeking confirmation
[OpenTelemetry]: https://opentelemetry.io/

## 3. Scope

### 3.1 Goals

* To provide a set of interfaces for library and framework developers to add tracing signals to their codebase.  
  This would in turn allow other libraries (Eg: [Clockwork], [Laravel-Debugbar]) to receive the same signals for further processing or analysis.
* To allow traces collected by various providers to be reused by other providers.
* This PSR may provide a minimal `TraceProvider`, etc. for other providers to extend, should they choose.

[Clockwork]: https://underground.works/clockwork/
[Laravel-Debugbar]: https://github.com/barryvdh/laravel-debugbar

### 3.1 Non-goals

* This PSR does not provide a comprehensive tracing client
* This PSR does not define the mechanisms used for transmitting the data to 3rd party systems
* This PSR does not cover collecting metrics within a codebase, only traces

## 4. Approaches

To fulfil the requirement of most providers, this PSR will be loosely modeled on the [OpenTelemetry Tracing Specification][OTelTrace] and [Tracing API][OtelTraceApi].  
We aim to allow the majority of providers to use the PSR interfaces with minimum backwards incompatible changes to encourage adoption.

[OtelTrace]: https://github.com/open-telemetry/opentelemetry-specification/blob/main/specification/overview.md#tracing-signal
[OtelTraceApi]: https://github.com/open-telemetry/opentelemetry-specification/blob/main/specification/trace/api.md

## 5. People

### 5.1 Editor
* Adam Allport

### 5.1 Sponsor
* Alessandro Chitolina

### Working Group Members
* Alex Bouma
* Ben Edmunds
* Brett McBride
* Timo Michna

## 6. Votes

* [Entrance Vote](TBD)
