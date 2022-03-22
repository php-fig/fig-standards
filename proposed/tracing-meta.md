# Application Tracing Meta Document

## 1. Summary

Application performance monitoring (APM) tools are becoming increasingly popular, however, the lack of formal interfaces is holding back the ecosystem.
Whilst developers are inherently free to use whichever tools suit their use-cases, this is not the case for framework and library creators/maintainers.

This standard aims to create an intentionally minimalist set of interfaces that can be used to provide tracing signals to 3rd party library's in a unified manner.

## 3. Scope

### 3.1 Goals

* To provide a set of interfaces for library and framework developers to add tracing signals to their codebase.
  This would in turn allow other APM providers to receive the same signals for further processing or analysis.
* To allow traces collected by various APM libraries to be reused by other libraries/tools.
* This PSR may provide a minimal `TraceProvider`, etc. for other APM tools to extend, should they choose.

### 2.1 Non-goals

* This PSR does not provide a comprehensive tracing client
* This PSR does not define the mechanisms used for transmitting the data to 3rd party systems
* This PSR does not cover collecting metrics within a codebase, only traces

## 3 Approaches

To fulfil the requirement of most APM tools, this PSR will be loosely modeled on the [OpenTelemetry Tracing Specification][OTelTrace] and [Tracing API][OtelTraceApi].\
We aim to allow the majority of APM tools to use the PSR interfaces a minimum backwards incompatible changes as to encourage adoption.

[OtelTrace]: https://github.com/open-telemetry/opentelemetry-specification/blob/main/specification/overview.md#tracing-signal
[OtelTraceApi]: https://github.com/open-telemetry/opentelemetry-specification/blob/main/specification/trace/api.md

## 4. People

### 4.1 Editor
* Adam Allport

### 4.1 Sponsor
* Alessandro Chitolina

### Working Group Members
* Alex Bouma
* Ben Edmunds
* Brett McBride
* Timo Michna

## 6. Votes

* [Entrance Vote](TBD)
