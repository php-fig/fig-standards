Common Interface for Providing a PDO Connection
================================================

This document describes a simple interface for providing a PDO database
connection.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## 1. Specification

### 1.1 Introduction

Libraries and components that require a database connection typically depend
directly on `\PDO`. This creates an implicit assumption that the connection is
already established at the time the dependency is injected. It also makes it
impossible for the caller to control when and how the connection is opened,
verified, or recreated.

A standard provider interface decouples the act of consuming a connection from
the act of managing it. The library receives a provider and calls
`getConnection()` only when it actually needs the connection. The
implementation decides whether to open a new connection, reuse an existing
one, or reconnect after a timeout.

### 1.2 Definitions

* **Connection** - A `\PDO` instance representing a link to a database server.
* **Provider** - An object that supplies a connection on demand via
  `getConnection()`.
* **Implementer** - A library or project that implements `PdoProviderInterface`
  to supply connections.
* **Consumer** - A library or project that calls `getConnection()` on a
  `PdoProviderInterface` instance to obtain a connection.

### 1.3 Usage

A consumer should call `getConnection()` each time it needs a connection rather
than storing the returned `\PDO` instance for later reuse, to allow the provider
to manage the connection lifecycle, including reconnection after a timeout:

```php
$statement = $provider->getConnection()->prepare('SELECT * FROM users');
```

## 2. Interface

### 2.1 PdoProviderInterface

```php
<?php

namespace Psr\Pdo;

interface PdoProviderInterface
{
    /**
     * Returns a PDO connection.
     */
    public function getConnection(): \PDO;
}
```
