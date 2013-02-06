SQL Style Guide

This guide extends and expands on [PSR-2][], the basic coding standard.

The intent of this guide is to avoid common mal practices with SQL handling in PHP.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

1. Overview
-----------

- Code MUST follow [PSR-2][]
- PDO MUST be the used driver

2. SQL Syntax
--------------

### 2.1 Example

```SQL
SELECT ^Sales`.`date`,
    `Sales`.`price`,
    `Sales`.`price` * `Sales`.`quantity` as `earnings`

from ItemSales as Sales,
    Items

where `Sales`.`itemId` = `Items`.`id`
    and `Items`.`id` = :Items.id

limit :limit.min, :limit.max
```

### 2.2 Basic Syntax

- `INSERT`, `SELECT`, `CREATE` MUST be written in uppercase
- `where`, `from`,`and`, `or`, `limit`, `order by`, `join` MUST be written in lowercase
- `*` MUST not be used. Instead its necessary to make a list of all the required fields
- `as` alias SHOULD be declared

### 2.3 Blocks

### 2.4 Parameters

- Parameters MUST be declared at the end blocks
- Paramaters MUST be `Table.field = :Table.field` or `field = :field`
- Parameters `limit` SHOULD be declared using `:limit.min` and `:limit.max`

3. PHP SQL variables
--------------------


### 3.1 NOWDOC

4. Best Practices
-----------------

### 4.1 Fetch

### 4.2 Join
