SQL Style Guide
===============

This guide extends and expands on [PSR-2][], the basic coding standard.

The intent of this guide is to avoid common mal practices with SQL handling in PHP.

*Notice: Currently this Guide only intends to help on static queries and lacks of information on dynamic queries*

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
    `Items`.`price`,
    `Items`.`price` * `Sales`.`quantity` as `earnings`

From ItemSales as Sales,
    Items

Where `Sales`.`itemId` = `Items`.`id`
    and `Items`.`id` = :Items_id

Limit :limit_min, :limit_max
```

### 2.2 Basic Syntax

> #### 2.2.1 Keywords
> 
> The keywords are divided in three types
> 
> - Definition keyword: Define the type or query `INSERT`, `SELECT`, `CREATE`, `UPDATE`
> - Block keyworkds: Are used at most once and separate the query in sections `where`, `from`, `limit`, `order by`, `join`
> - Operation keywords: Are using inside operations `and`, `or`, `not`, `null`

- Definition keywords MUST be written in UPPERCASE
- Block keywords MUST be written Capitalized
- Operational keywords MUST be written in lowercase
- `*` asterisk MUST NOT be used. Instead its necessary to make a list of all the required fields
- There MUST be a line break after every field or table used on `SELECT`, `INSERT` and `from` blocks
- There MUST be a line break after every condition used on the `where`, `having` and `group by` blocks
- `as` alias SHOULD be declared
- ` grave accent SOULD be used around each table and field

### 2.3 Blocks

SQL blocks are to be understood as sections of the query divided by SQL keywords such as `from`, `where`, `order by`, etc.

- There MUST be a blankl line separating blocks
- Elements inside blocks MUST be indented

### 2.4 Parameters

- Parameters MUST be declared at the end blocks
- Paramaters MUST be have the name of the field `:field` or `:Table_field` if more than one table uses a field using that name
- Parameters `limit` SHOULD be declared using `:limit_min` and `:limit_max`

3. PHP SQL variables
--------------------

```PHP
class ClassName
{
    private static $query = <<<'SQL'
SELECT `Sales`.`id` as SalesId
    `Sales`.`date`,
    `Items`.`price`,
    `Items`.`price` * `Sales`.`quantity` as `earnings`

From ItemSales as Sales,
    Items

Where `Sales`.`itemId` = `Items`.`id`
    and `Items`.`id` = :Items_id

Limit :limit_min, :limit_max
SQL;
}
```

- .sql files MUST NOT be used inside the DOCUMENT ROOT
- Query declaration MUST use single quotes
- Query declaration SHOULD use `nowdoc` for PHP 5.3 using SQL to add syntax higlight functionality
- When the query is executed inside a class method the query variables SHOULD be stored in private propertys. This will allow to keep the queries separated from the methods definition
- When used inside a function, queries SHOULD be declared at the beggining of the function definition. This will allow to keep the queries separated from the function definition

4. Best Practices
-----------------

### 4.1 Fetch

The PDOStatement::fetchAll method SHOULD NOT be used.

Using this method to fetch large result sets will result in a heavy demand on system and possibly network resources. Rather than retrieving all of the data and manipulating it in PHP, consider using the database server to manipulate the result sets.

The class `PDOStatement` implements the `Transversable` interface which allow it to be used inside loops like `foreach`.

### 4.2 Join

Try to use joins instead of having multiple queries and joining the information using PHP.

5. Survey
---------

### 5.1 Survey proposed questions

`keyword_types`: Separate the keywords in types as proposed in 2.2.1

`keywords`: How are the keywords capitalized. `upper` = Uppercase, `lower` = lowercase, `capital` = Uppercase only for the first letter

`asterisk`: Is the use of asterisk permitted? `yes`, `no`

`linebreak_after_field`: Linebreak after declaring a field, conditional or table?

`block_line_separation`: Separate blocks with a blank line

`sql_comments`: Allow SQL comments?

`alias_declaration`: Alias should be declared? `yes` it should be declared, `no` it should not be declared, `?` no recomendation

`grave_accent`: Should grave accent be used? `yes` it should be used, `?` no recomendation

`block_separation`: There must be a blank line of separatation betweeen blocks?

`block_indentation`: Elements inside blocks should be indented?

`sql_file` .sql files MUST NOT be used inside the DOCUMENT ROOT?

`single_quotes` Query declaration MUST use single quotes?

`nowdoc` Query declaration SHOULD use nowdoc?

`readonly_property` When the query is executed inside a class method the query variables SHOULD be stored in private read-only property?

`variable_function_start` When used inside a function, queries SHOULD be declared at the start of the function definition. This will allow to keep the queries separated from the function definition
