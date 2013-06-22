Path Matching Meta Document
===========================

1. Summary
----------

### 1.1 TL;DR

Finds actual paths on a file system for logical paths, such as FQCNs
("\Acme\Demo\Parser") or URI paths ("/acme/demo-package/show.html.php"),
using a mapping of logical paths to file system paths. The separator
character can be chosen by the implementation.

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

### 1.2 Reasons for/against basing PSR-X Autoloading on PSR Path Mapping

Currently, two PSR proposals can be expected to be based on this PSR:

* PSR-X Autoloading
* PSR-R Resource Location

PSR-R is still very young, so it can be based onto this PSR without major
problems. PSR-X, however, has been discussed for a long time and is considered
to be in his final stages. Nevertheless, we propose to *change* the *wording* of
PSR-X to base onto this PSR for the following reasons:

* It will be easier to implement code that complies both with PSR-X and PSR-R.
* PSR-X and PSR-R will be easier to formulate.
* It will be obvious that PSR-X and PSR-R share the same algorithm.
* Reviewing and releasing this PSR before PSR-X will make sure that this PSR
  is actually compatible with the intentions of the PSR-X authors.
* Basing PSR-X onto PSR Path Mapping is expected to only change the wording of
  PSR-X, not the semantics.

Reasons against changing PSR-X:

* PSR-X will be delayed after PSR Path Mapping. However, we don't expect a large
  delay, because PSR Path Mapping has grown out of PSR-X. It has a different
  wording, but the same semantics, so PSR Path Mapping should be acceptable for
  anyone who is satisfied with the current state of PSR-X.

### 1.3 Impact on the wording of PSR-X

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

2. Goals
--------

* Deterministically map logical paths, such as `\A\B\C\D`, to physical paths,
  such as `/src/C/D`

3. Non-Goals
------------

* Wildcard file search (glob)

4. Chosen Approach
------------------

TODO

5. Alternative Approaches
-------------------------

TODO

6. Authors
----------

* Bernhard Schussek

7. Sponsors
-----------

none yet

8. Contributors
---------------

* Andreas Hennings
* Paul M. Jones
* Beau Simensen
* Amy Stephen

9. Votes
--------

none yet

10. Relevant Links
------------------

TODO
