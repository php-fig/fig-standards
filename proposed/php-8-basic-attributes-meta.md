PHP 8 basic attributes
=======================

## 1. Summary

PHP has many annotations with similar meaning. 
The goal of this PSR is to standardize some of them.

### Why Bother?

IDE and static analyzer tools introduce their own attributes (aka annotations)
with slightly different meaning, but with very similar intentions.

Standardized attributes would help to IDE/framework/library maintainers
and end-users in result.

They would have declarative purpose, but developer teams would be able
to force it by checking on git receive hook, for example.

### Basic attributes

This PSR proposes to declare few attributes:
* `@@Final` (class, method);
* `@@Immutable` (class, property);
* `@@ReadOnly` (property);
* `@@Internal`.

Other PSRs might focus on different aspects of the code:
generics, documentation, etc.

