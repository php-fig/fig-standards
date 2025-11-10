# PSR-AI: Common Interface for AI Interaction in PHP

## Table Of Contents

- [1. Introduction](#1-introduction)
- [2. Conventions Used In This Document](#2-conventions-used-in-this-document)
- [3. Definitions](#3-definitions)
- [4. Basic Principles](#4-basic-principles)
- [5. The AI Client Abstraction](#5-the-ai-client-abstraction)
  - [5.1 The Client Interface](#51-the-client-interface)
  - [5.2 The Message Interface](#52-the-message-interface)
  - [5.3 The Stream Interface](#53-the-stream-interface)
  - [5.4 The Tool Interface](#54-the-tool-interface)
  - [5.5 The Response Interface](#55-the-response-interface)
- [6. Example Usage](#6-example-usage)
- [Appendix A. ABNF Definitions](#appendix-a)
- [Appendix B. References](#appendix-b)

## 1. Introduction

The purpose of this PSR is to define a common interface for interacting with artificial intelligence models in PHP, such as large language models, multimodal models, and reasoning engines.

This PSR aims to standardize how PHP applications and frameworks:
- Send messages to AI models.
- Receive structured and streamed responses.
- Register callable tools or functions for model invocation.
- Maintain interoperability with the [Model Context Protocol (MCP)](https://modelcontextprotocol.io/).

As with prior PSRs (such as PSR-3 and PSR-18), the goal is not to mandate a particular implementation, but to provide an interoperable interface layer allowing frameworks, SDKs, and providers to integrate seamlessly.

## 2. Conventions Used In This Document

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](https://tools.ietf.org/html/rfc2119).

## 3. Definitions

- **Model**: A computational system capable of processing input and producing structured or natural-language output. Examples include OpenAI's GPT models, Anthropic's Claude, or local models served via Ollama.
- **Client**: An implementation of the `Psr\Ai\ClientInterface` responsible for communicating with a model provider or local runtime.
- **Message**: A discrete input or output unit representing a message exchanged with the model. A message MAY include role metadata (e.g., `system`, `user`, `assistant`) and MAY encapsulate data objects, code, or binary content.
- **Stream**: A sequence of partial output tokens, chunks, or structured updates emitted during a model's response generation.
- **Tool**: A callable function or operation that the model MAY invoke during reasoning or message generation. Tools MUST be discoverable and serializable in a format compatible with MCP.
- **Response**: The final or intermediate result from a model call. Responses MAY include text, structured data, tool calls, or cost metrics.
- **Session**: A series of related interactions forming a context (e.g., a multi-turn chat or reasoning session). Session handling is out of scope for this PSR, but MAY be implemented on top of it.

## 4. Basic Principles

1. All AI interactions MUST occur via a `Psr\Ai\ClientInterface`.
2. Model inputs and outputs MUST be represented by standardized `MessageInterface` and `ResponseInterface` objects.
3. Streaming outputs MUST conform to the `StreamInterface` contract, ensuring consistent event-driven consumption.
4. Callable tools MUST implement the `ToolInterface` and MUST be compatible with MCP serialization.
5. Implementations SHOULD remain framework-agnostic and transport-neutral.
6. The PSR MUST NOT prescribe any particular provider API, HTTP format, or runtime behavior.

## 5. The AI Client Abstraction

### 5.1. The Client Interface

The ClientInterface represents the entry point for AI interaction.

An implementation MUST provide at least the following methods:

```php
namespace Psr\Ai;

interface ClientInterface
{
    public function complete(MessageInterface $message): ResponseInterface;

    public function stream(MessageInterface $message): StreamInterface;

    public function invokeTool(ToolInterface $tool, array $arguments): mixed;
}
```

**Rules:**
- `complete()` MUST perform a synchronous model call and return a finalized `ResponseInterface`.
- `stream()` MUST return a `StreamInterface` that yields incremental output as available.
- `invokeTool()` MUST execute a callable tool in the same process or over MCP.

### 5.2. The Message Interface

The MessageInterface defines a structured input or output exchanged between client and model.

```php
namespace Psr\Ai;

interface MessageInterface
{
    public function role(): string;
    public function content(): string|array;
    public function metadata(): array;
}
```

**Rules**:
- `role()` MUST return a string such as system, user, or assistant.
- `content()` MAY contain plain text or structured data (e.g., JSON, array).
- Implementations MAY define message classes (e.g., `UserMessage`, `AssistantMessage`) for typed interactions.
- Message instances MUST be immutable once created.

### 5.3. The Stream Interface

The StreamInterface enables incremental consumption of model output.

```php
namespace Psr\Ai;

interface StreamInterface extends \Traversable
{
    public function onToken(callable $callback): void;
    public function onError(callable $callback): void;
    public function onComplete(callable $callback): void;
}
```

**Rules:**
- Implementations MUST emit partial output (e.g., tokens, chunks) through `onToken()`.
- `onError()` MUST emit any transport or model errors.
- `onComplete()` MUST be called when the stream has ended.
- Streams SHOULD be compatible with PSR-7 stream semantics where applicable.

### 5.4. The Tool Interface 

The ToolInterface standardizes callable functions the model may request via tool calling.

```php
namespace Psr\Ai;

interface ToolInterface
{
    public function name(): string;
    public function description(): ?string;
    public function schema(): array;
    public function execute(array $arguments): mixed;
}
```

**Rules:**
- Tools MUST define their input/output schema for validation and serialization.
- Implementations SHOULD align schema definitions with the [Model Context Protocol](https://modelcontextprotocol.io/).
- `execute()` MUST return serializable output or throw an exception.

### 5.5. The Response Interface

The ResponseInterface provides a normalized representation of model responses.

```php
namespace Psr\Ai;

interface ResponseInterface
{
    public function message(): MessageInterface;
    public function metadata(): array;
}
```

**Rules:**
- The `message()` MUST represent the model's returned message.
- `metadata()` SHOULD include token usage, cost, latency, or provider-specific data.
- Responses MAY include nested tool calls or structured outputs.

## 6. Example Usage

```php
use Psr\Ai\{ClientInterface, MessageInterface};

$message = new UserMessage('What is the capital of France?');

$response = $client->complete($message);

echo $response->message()->content(); // "Paris"
```

Streaming example:

```php
$stream = $client->stream(new UserMessage('Write a haiku about PHP.'));

$stream->onToken(fn($token) => echo $token);
$stream->onComplete(fn() => echo "\nDone.");
```

Tool registration example:

```php
class WeatherTool implements ToolInterface
{
    public function name(): string
    {
        return 'getWeather';
    }

    public function schema(): array
    {
        return [
            'location' => 'string',
        ];
    }

    public function execute(array $args): array
    {
        return [
            'temp' => 18,
            'unit' => 'C',
        ];
    }
}
```

## Appendix A. ABNF Definitions


The ABNF below defines general communication semantics:

```
message = role ":" content
role    = "system" / "user" / "assistant" / "tool"
content = *CHAR
```

Streaming token flow:

```
stream = 1*(token / event)
token  = 1*CHAR
event  = "[" event-type ":" data "]"
```

## Appendix B. References

- [RFC 2119](https://tools.ietf.org/html/rfc2119). Key words for use in RFCs to indicate requirement levels. Defines "MUST", "SHOULD", "MAY", etc., for consistent interpretation of requirements.
- [RFC 5234](https://tools.ietf.org/html/rfc5234). Augmented Backus-Naur Form (ABNF). Defines the ABNF grammar notation used in Appendix A.
- [PSR-3: Logger Interface](https://www.php-fig.org/psr/psr-3/). Reference for a widely adopted interface standard, demonstrating framework-agnostic design.
- [PSR-7: HTTP Message Interface](https://www.php-fig.org/psr/psr-7/). Provides streaming and message abstraction patterns referenced in StreamInterface.
- [PSR-14: Event Dispatcher](https://www.php-fig.org/psr/psr-14/). Illustrates middleware/event pipelines for streaming token handling.
- [PSR-18: HTTP Client Interface](https://www.php-fig.org/psr/psr-18/). Example of a provider-agnostic client abstraction.
- [Model Context Protocol (MCP)](https://modelcontextprotocol.io/). Defines a cross-language protocol for tools, structured data, and context integration in AI workflows.
- [OpenAI API](https://platform.openai.com/docs/api-reference/introduction). Industry-standard example of a large language model API with streaming and tool-calling features.
- [Anthropic API.](https://docs.anthropic.com/). Provides examples of structured LLM interactions and multi-turn session concepts.
- [Ollama](https://ollama.com/). Demonstrates locally-hosted model integrations for PHP and other ecosystems.
- [Fluent Interface Pattern](https://en.wikipedia.org/wiki/Fluent_interface). References common design patterns used in PHP SDKs and streaming APIs.
- [PHP Iterables](https://www.php.net/manual/en/language.types.iterable.php). Defines iterable types used in StreamInterface and message pipelines.
- [PHP Resources](https://www.php.net/manual/en/language.types.resource.php). Guidance on representing external streams or handles in PHP.
- [PHP Callables](https://www.php.net/manual/en/language.types.callable.php). Used in ToolInterface::execute() for callable definitions.
- [Late Static Binding in PHP](https://www.php.net/manual/en/language.oop5.late-static-bindings.php). Covers use of static in message and response class hierarchies.
- [DeFacto PHPDoc Specification](http://www.phpdoc.org/docs/latest/index.html). Serves as a reference for structured documentation and interface design.
