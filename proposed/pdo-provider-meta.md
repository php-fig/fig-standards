PDO Provider Meta Document
==========================

## 1. Summary

Libraries and components that require a database connection typically depend
directly on `\PDO`. This creates two problems: passing a `\PDO` instance is
passing a fixed connection, not a live access to one; and there is no
standardized contract for passing a connection between libraries.

`PdoProviderInterface` aims to solve both problems by providing a minimal
interface that abstracts the source of a PDO connection, enabling
interoperability between implementers and consumers.

## 2. Why Bother?

### 2.1 Passing `\PDO` surrenders connection lifecycle control

A `\PDO` instance represents a connection that was established at the moment of
instantiation. When a library accepts a `\PDO` directly, the caller loses all
control over when and how the connection is opened, verified, or recreated. If
the connection drops after the `wait_timeout` of the database server, the
library holding the `\PDO` instance has no way to reconnect transparently.
Dropped connections are a common issue in long-running PHP processes such as
daemons or queue workers.

This is analogous to passing a `\DateTimeImmutable` to represent "the current
time". The object captures a moment in time; it does not provide access to the
present moment. PSR-20 solved this problem for clocks by introducing
`ClockInterface`. `PdoProviderInterface` solves the same problem for database
connections: instead of passing a snapshot, the caller passes a provider that
returns the current, live connection on demand.

### 2.2 No standardized contract for passing a connection between libraries

Widely-used libraries such as `doctrine/dbal`, `illuminate/database`,
`nette/database`, and `cakephp/database` each wrap PDO in their own connection
object. Libraries that consume a PDO connection (query builders, migration
tools, etc.) accept `\PDO` directly. There is no common contract that allows a
managing library to hand off its connection to a consuming library in an
interoperable way.

Without a standard, every application that integrates two such libraries must
write its own glue code. Every library that wants to support multiple connection
managers must either duplicate that logic or force the caller to extract the raw
`\PDO` instance from the manager. In either case, the connection manager's
lifecycle control is bypassed entirely.

## 3. Scope

### 3.1 Goals

* Provide a minimal interface that decouples connection consumption from
  connection management.
* Allow interoperability between implementers and consumers.

### 3.2 Non-Goals

* This PSR does not abstract PDO itself. `\PDO` remains the concrete type
  returned by `getConnection()`.
* This PSR does not define how connections are created, pooled, or validated.
  These are implementation concerns.
* This PSR does not cover `\PDOStatement` or query execution.
* This PSR does not replace existing database abstraction libraries.

## 4. Approaches

### 4.1 Chosen Approach

`PdoProviderInterface` exposes a single method, `getConnection(): \PDO`. This
is the minimal contract that allows a consumer to obtain a connection without
knowing anything about how it is managed.

The analogy with PSR-20's `ClockInterface` is intentional and structural, not
merely rhetorical. PSR-20 does not abstract `\DateTimeImmutable`; it abstracts
the source of the current time. `PdoProviderInterface` does not abstract
`\PDO`; it abstracts the source of the current connection.

Implementers (`doctrine/dbal`, `illuminate/database`,
`nette/database`, `cakephp/database`, `atlas/orm`, `aura/sql`) can implement
`PdoProviderInterface` on their connection objects. Consumers
(`envms/fluentpdo`, `clancats/hydrahon`, `paragonie/easydb`) can
depend on `PdoProviderInterface` instead of `\PDO` directly.

### 4.2 Rejected Approaches

**`PdoInterface` mirror**

Defining a `PdoInterface` that mirrors the full PDO API was considered. This
approach was rejected for several reasons:

* It can be done in userland, which is a common criterion for rejection at both
  PHP internals and PHP-FIG. `aura/sql` already ships a
  [`PdoInterface`](https://github.com/auraphp/Aura.Sql/blob/4.x/src/PdoInterface.php)
  that mirrors the PDO API.
* `\DateTimeInterface` was deliberately locked to prevent userland
  implementations, which is the opposite of what a `PdoInterface` mirror would
  seek to do.
* Lazy loading, a common motivation for this approach, is already achievable
  via inheritance. [`lazypdo/lazypdo`](https://github.com/lazypdo/lazypdo)
  proves this.
* It addresses the interoperability problem only at the cost of a large
  interface. `PdoProviderInterface` solves the same problem with a single
  method.

**`PDOProvider` with metadata methods**

In January 2019, Rasmus Schultz proposed a `PDOProvider` interface on the
PHP-FIG mailing list that included `getPDO()`, `getProtocol()`, `getSetting()`,
`getUsername()`, and `getAttributes()`. The proposal did not advance. In the
discussion, Larry Garfield asked for concrete use cases, and the proposer
himself acknowledged that the interface was trying to do too much.

`PdoProviderInterface` addresses the same core problem with `getConnection()`
while discarding the metadata methods, which are not needed for the
interoperability use case and would make the interface unnecessarily
opinionated.

**`PdoFactoryInterface`**

A factory interface (`createConnection(): \PDO`) was considered. This was
rejected because a factory implies a new instance on every call. A provider
makes no such guarantee: it may return an existing connection, reuse one from a
pool, or reconnect after a failure. The provider pattern is more flexible and
matches the actual behavior of existing connection managers.

### 4.3 Example Implementations

A minimal provider wrapping a lazily-instantiated `\PDO`:

```php
final class LazyPdoProvider implements \Psr\Pdo\PdoProviderInterface
{
    private ?\PDO $pdo = null;

    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private readonly string $password,
        private readonly array $options = [],
    ) {}

    public function getConnection(): \PDO
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        }

        return $this->pdo;
    }
}
```

A provider with automatic reconnection after a dropped connection:

```php
final class ReconnectingPdoProvider implements \Psr\Pdo\PdoProviderInterface
{
    private ?\PDO $pdo = null;

    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private readonly string $password,
        private readonly array $options = [],
    ) {}

    public function getConnection(): \PDO
    {
        try {
            if ($this->pdo === null) {
                $this->connect();
            } else {
                $this->pdo->query('SELECT 1');
            }
        } catch (\PDOException) {
            $this->connect();
        }

        return $this->pdo;
    }

    private function connect(): void
    {
        $this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
    }
}
```

### 4.4 Interoperability Example

Consider an application that uses `doctrine/dbal` for schema management and
migrations, and `envms/fluentpdo` as a query builder. Without
`PdoProviderInterface`, the only way to pass the connection to FluentPDO is to
extract the raw `\PDO` instance from DBAL, bypassing its connection lifecycle
management entirely:

```php
// Without PdoProviderInterface
$dbalConnection = DriverManager::getConnection($params);
$pdo = $dbalConnection->getNativeConnection(); // snapshot; DBAL no longer manages it
$fluent = new FluentPDO($pdo);
```

If DBAL implements `PdoProviderInterface` on its connection object, and
FluentPDO accepts a `PdoProviderInterface` instead of `\PDO` directly, the
integration becomes:

```php
// With PdoProviderInterface
$dbalConnection = DriverManager::getConnection($params); // implements PdoProviderInterface
$fluent = new FluentPDO($dbalConnection);
```

FluentPDO calls `$dbalConnection->getConnection()` when it needs the `\PDO`
instance. DBAL retains control over the connection lifecycle. Neither library
needs to know about the other.

## 5. People

### 5.1 Editor

* Maxime Gosselin

### 5.2 Sponsor

* TBD

### 5.3 Working Group Members

* TBD

## 6. Votes

* Entrance Vote: TBD

## 7. Relevant Links

**Prior art**

* [php/php-src#2657](https://github.com/php/php-src/pull/2657) — Closed PR
  proposing `PDOInterface` and `PDOStatementInterface` at the language level
  (2017)
* [PHP-FIG mailing list: Proposal idea: PDO Provider](https://groups.google.com/g/php-fig/c/1_V02TlJXBQ/m/yLVp1-IkBQAJ) —
  Prior proposal on the PHP-FIG mailing list (2019)
* [In Search of the Missing PDO Interface](https://maximegosselin.com/posts/in-search-of-the-missing-pdo-interface/) —
  Article advocating for a `PdoInterface` mirror; the community response to it
  led to this proposal

**Libraries**

* [doctrine/dbal](https://github.com/doctrine/dbal)
* [illuminate/database](https://github.com/illuminate/database)
* [nette/database](https://github.com/nette/database)
* [cakephp/database](https://github.com/cakephp/database)
* [paragonie/easydb](https://github.com/paragonie/easydb)
* [atlas/orm](https://github.com/atlasphp/Atlas.Orm)
* [aura/sql](https://github.com/auraphp/Aura.Sql)
* [envms/fluentpdo](https://github.com/envms/fluentpdo)
* [clancats/hydrahon](https://github.com/ClanCats/Hydrahon)
