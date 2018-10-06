# PSR-2 Meta Document

## 1. Summary

The intent of this guide is to reduce cognitive friction when scanning code from different authors. It does so
by enumerating a shared set of rules and expectations about how to format PHP code.

The style rules herein are derived from commonalities among the various member projects. When various authors
collaborate across multiple projects, it helps to have one set of guidelines to be used among all those
projects. Thus, the benefit of this guide is not in the rules themselves, but in the sharing of those rules.

## 2. Votes

- **Acceptance Vote:** [ML](https://groups.google.com/d/msg/php-fig/c-QVvnZdMQ0/TdDMdzKFpdIJ)

## 3. Errata

### 3.1 - Multi-line Arguments (09/08/2013)

Using one or more multi-line arguments (i.e: arrays or anonymous functions) does not constitute
splitting the argument list itself, therefore Section 4.6 is not automatically enforced. Arrays and anonymous
functions are able to span multiple lines.

The following examples are perfectly valid in PSR-2:

~~~php
<?php
somefunction($foo, $bar, [
  // ...
], $baz);

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});
~~~

### 3.2 - Extending Multiple Interfaces (10/17/2013)

When extending multiple interfaces, the list of `extends` should be treated the same as a list
of `implements`, as declared in Section 4.1.
