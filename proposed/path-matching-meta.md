PSR-? Path Matching Meta Document
=================================

Meta Document for [PSR-? Path Matching](path-matching.md).

1. Summary
----------

Finds file system paths for logical paths, such as FQCNs ("\Acme\Demo\Parser")
or URI paths ("/acme/demo-package/show.html.php"). The separator character can
be chosen by the implementation.

Example:

```php
$sep = '\\';
$mapping = array(
   '\\Acme\\Blog\\' ='src/blog',
   '\\Acme\\Demo\\Parser.php' ='src/Parser.php',
);

echo match_path('\\Acme\\Blog\\ShowController.php', $mapping, $sep);
// ="src/blog/ShowController.php"

echo match_path('\\Acme\\Demo\\Parser.php', $mapping, $sep);
// ="src/Parser.php"
```

The algorithm does not care about file suffixes or the distinction between
files and directories. Restrictions in this regard can be made by PSRs
using this algorithm (i.e. PSR-X, PSR-R and others).

2. Why Bother?
--------------

### 2.1 Reasons for/against basing PSR-X Autoloading on PSR Path Mapping

Currently, two PSR proposals can be expected to be based on this PSR:

* [PSR-X Autoloading][psr-x]
* [PSR-R Resource Location][psr-r]

PSR-R is still very young, so it can be based onto this PSR without major
problems. PSR-X, however, has been discussed for a long time and is considered
to be in his final stages. Nevertheless, we propose to *change* the *wording* of
PSR-X to base onto this PSR for the following reasons:

* PSR-R needs a more precise specification than the [current version of PSR-X]
  (https://github.com/php-fig/fig-standards/blob/5a536a0b03caceabbf4690c668dbc1e570bac336/proposed/autoloader.md)
  since it is not restricted by PHP's syntax of class identifiers. Even if both
  specifications *should* be semantically equivalent, using different rule
  wording creates a potential for incompatibilities.
* The more precise formulation of PSR Path Mapping will help implementors of
  PSR-X to create compliant implementations.
* Reviewing and releasing this PSR before PSR-X will make sure that this PSR
  is actually compatible with the intentions of the PSR-X authors.
* It will be obvious that PSR-X and PSR-R share the same algorithm.
* PSR-X and PSR-R will be shorter and simpler formulated.
* It will be easier to implement code that complies both with PSR-X and PSR-R.
* The same path mapping can be used for both PSR-X and PSR-R implementations
  (e.g. defined in composer.json).

Reasons against changing PSR-X:

* PSR-X will be delayed after PSR Path Mapping. However, we don't expect a large
  delay, because PSR Path Mapping has grown out of PSR-X. It has a different
  wording, but the same semantics, so PSR Path Mapping should be acceptable for
  anyone who is satisfied with the current state of PSR-X.

### 2.2 Impact on the wording of PSR-X

The wording of PSR-X would change to the following:

- A FQCN MUST begin with a top-level namespace name (the *vendor namespace*),
  which MUST be followed by zero or more sub-namespace names, and MUST end in
  a class name.

- The path matching algorithm described in PSR-? MUST be used to find a
  matching file for a FQCN, using a backslash ("\") as separator. The input
  for the algorithm MUST be the FQCN suffixed with `.php`.

- If a matching file was found, the registered autoloader MUST include or
  require it.

- The registered autoloader callback MUST NOT throw exceptions, MUST NOT
  raise errors of any level, and SHOULD NOT return a value.

3. Scope
--------

### 3.1 Goals

* Deterministically map logical paths, such as `\A\B\C\D`, to physical paths,
  such as `/src/C/D`

### 3.2 Non-Goals

* Wildcard file search (glob)

4. Approaches
-------------

### 4.1 Chosen Approach

The approach for path mapping was extracted from the PSR-X proposal. It is a
more generic version for mapping paths with custom separators to file system
paths than what the autoloader does.

Pros:

* compatibility with PSR-X
* compatibility with the current version of the PSR-R proposal

Cons:

* none known so far

5. People
---------

### 5.1 Authors

* Bernhard Schussek

### 5.2 Sponsors

* none yet

### 5.3 Contributors

* Andreas Hennings
* Paul M. Jones
* Beau Simensen
* Amy Stephen

6. Votes
--------

* none yet

7. Relevant Links
-----------------

* [PSR-X Autoloader proposal][psr-x]
* [PSR-R Resource Location proposal][psr-r]
* [Original Google Groups discussion on PSR Path Mapping]
  (https://groups.google.com/d/msg/php-fig/WMaKNNhHZJw/nbj4eR_QeTYJ)
* [Current Google Groups discussion on PSR Path Mapping]
  (https://groups.google.com/d/msg/php-fig/ACrNd8Drz6g/L6LeNEcYTzMJ)
* [Example formulation of PSR-X when based on PSR Path Mapping (outdated version)]
  (https://gist.github.com/simensen/0129bdf5ee07fe896c2c)
* [Example implementation of PSR-X, PSR-R and PSR Path Matching]
  (https://github.com/simensen/psr-match)


[psr-x]: https://github.com/php-fig/fig-standards/blob/master/proposed/autoloader.md
[psr-r]: resource-location.md
