SQL Style Guide
===============

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
SELECT `Sales`.`id` as SalesId
    `Sales`.`date`,
    `Sales`.`price`,
    `Sales`.`price` * `Sales`.`quantity` as `earnings`

from ItemSales as Sales,
    Items

where `Sales`.`itemId` = `Items`.`id`
    and `Items`.`id` = :Items_id

limit :limit.min, :limit.max
```

### 2.2 Basic Syntax

> #### 2.2.1 Keywords
> 
> The keywords are divided in three types
> 
> - Definition keyword: Define the type or query `INSERT`, `SELECT`, `CREATE`, `UPDATE`
> - Block keyworkds: Are used at most once and separate the query in sections `where`, `from`, `limit`, `order by`, `join`
> - Operation keywords: Are using inside operations `and`, `or`, `null`

- Definition and block kewyords MUST be written in uppercase
- Operational keywords MUST be written in lowercase
- `*` MUST NOT be used. Instead its necessary to make a list of all the required fields
- There MUST be a line break after every field or table used on `SELECT`, `INSERT` and `from` blocks
- There MUST be a line break after every condition used on the `where`, `having` and `group by` blocks
- `as` alias SHOULD be declared
- The symbol ` SOULD be used around each table and field

### 2.3 Blocks

SQL blocks are to be understood as sections of the query divided by SQL keywords such as `from`, `where`, `order by`, etc.

- There MUST be a line of separation between blocks.
- Elements inside blocks MUST be indented

### 2.4 Parameters

- Parameters MUST be declared at the end blocks
- Paramaters MUST be have the name of the field `:field` or `:Table_field` if more than one table uses a field with that name
- Parameters `limit` SHOULD be declared using `:limit_min` and `:limit_max`

3. PHP SQL variables
--------------------


### 3.1 NOWDOC

4. Best Practices
-----------------

### 4.1 Fetch

### 4.2 Join
