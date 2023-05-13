# Application Tracing

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMEND", "MAY", and "OPTIONAL" in this document are to
interpreted as described in [RFC 2119][].

The final implementations MAY decorate the objects with more functionality than
covered in the recommendation however they MUST implement the indicated
interfaces and functionality first.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## 1. Definitions

* **Framework** - An application framework (or micro-framework) that runs a developers code.  
  Eg. Laravel, Symphony, CakePHP, Slim
* **Library** - Any library that developers may include that adds additional functionality.
  Eg. Image Manipulation, HTTP Clients, 3rd Party SDKs
* **Provider** - An organization that offers APM (Application Performance Monitoring) as a service. Typically, via a
  composer package

## 2. Interfaces

## 2.1 Span

See: [AAllport/psr-tracing - SpanInterface.php](https://github.com/AAllport/psr-tracing/blob/main/src/SpanInterface.php)

A span represents a single operation within a trace. A trace is a collection of spans.
A fully formed span will consist of the following data:

- Name
- Attributes, an associative array representing key-value pairs
- Start Time
- End Time
- Outcome
- Exceptions

Upon creation, a new span will not be activated or have any attributes set.
This allows the user to populate the span with data.
It is RECOMMENDED that users supply a span with all relevant attributes shortly after creation, notably before
activation.
However, Providers SHOULD make every effort to persist attribute data if provided after a span is activated.

To track the time a piece of work takes, a user will call `SpanInterface::activate()`.
This will set the start time and push the current span into whichever context propagation system the provider chooses.
Users MAY call `SpanInterface::start()` to populate the span with a start time, but not pushed into context propagation.
Once complete, the user would call SpanInterface::finish() to set the end-time and “pop” the span from the current
stack.

Spans MUST support creating a child span via `SpanInterface::createChild(string $spanName): SpanInterface`.
This SHOULD use `TracerInterface::createSpan()`, then set the subsequent span's parent appropriately.

A typical use would look like the following:

```php
function imgResize($size=100) {
    $span = $this->tracer->createSpan('image.resize')
        ->setAttribute('size',$size)
        ->activate();

    try{
      //Resize the image
      return $resizedImage;
    
    } catch (Exception $e) {
        // Ideally, you would attach the exception to the span here
        $span->setStatus(SpanInterface::STATUS_ERROR)
             ->addException($e);
    } finally {
        $span->finish();
    }    
}
```

This PSR does not dictate how a span’s internals should be represented, other than it MUST implement the SpanInterface.
However, it is RECOMMENDED to conform to the [W3C TraceContext specification](https://www.w3.org/TR/trace-context/).
Providers MUST implement the `SpanInterface::toTraceContextHeaders()` method.
At a minimum, this should return an empty array.
Most commonly, providers will return traceParent and baggage headers to pass on to child services.

Providers MUST add the following function signatures to allow data retrieval from their span:
- `getAttribute(string $key): null|string|int|float|bool|Stringable;`
- `getAttributes(): iterable;`
- `getParent(): SpanInterface;`
- `getChildren(): array;`

The purpose of these functions is to allow data to be read from providers’ spans in a clear, uniform manner.
`SpanInterface::getParent` MAY return null if no parent is present or the span has been instantiated outside the relevant adapter’s methods.
However, a reasonable attempt SHOULD be made to return a `SpanInterface` where possible.
Similarly, `SpanInterface::getChildren()` MAY return an empty array if child spans have been created outside the relevant adapter's methods.
For continual use of a secondary provider, it is RECOMMENDED to create a separate Tracer and use the `MultiTracer` described rather than relying on these methods.

## 2.2 Tracer

See: [AAllport/psr-tracing - TracerInterface.php](https://github.com/AAllport/psr-tracing/blob/main/src/TracerInterface.php)

SDKs are expected to provide a concrete Tracer via whichever methods are most appropriate for the frameworks they support (eg, PSR-11 container).
Libraries using this PSR SHOULD implement [TracerAwareTrait](#31-tracerawaretrait).

When providing a tracer, it is RECOMMENDED to check whether a different Tracer has already been provided.
This PSR provides a MultiTracer via the `psr/tracing-utils` package, which MAY be used in the event of a tracer already being provided.
It is out of the scope of this PSR to dictate whether a Tracer should replace the already provided tracer.

Providers should consider relocate the Tracer from the container to allow other providers to listen to any spans added to the Tracer (eg, if replaced with a MultiTracer).

A tracer implementation MUST provide a method for creating a [Span](#21-span) via the signature `createSpan(string $spanName): SpanInterface`.

## 3 Traits

## 3.1 TracerAwareTrait

See: [AAllport/psr-tracing - TracerAwareTrait.php](https://github.com/AAllport/psr-tracing/blob/main/src/TracerAwareTrait.php)
