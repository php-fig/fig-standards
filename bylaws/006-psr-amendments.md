Amendments
==========

Following the rules of the [workflow bylaw], once a PSR has been "Accepted" the PSR meaning
cannot change, backwards compatibility must remain at 100%, and any confusion that arises from
original wording can be clarified through errata.

The rules for errata are covered in the [workflow bylaw], and only allow non-backwards compatible
clarification to be added to the meta document. Sometimes, modifications will be necessary in PSR
document itself, and this document outlines those cases.

## 1. Deprecation and Replacement

If a PSR is found to require substantive updates or errata is no longer able to clarify confusion,
then the PSR must be replaced, following the workflow set out in [workflow bylaw].

The original PSR may at some point in time be deprecated, as specified in the [votes bylaw].

Once a vote to deprecate a PSR and supersede it with another PSR has passed, the deprecated PSR must
be marked as such in the original document and a link should be placed in the body.

For example, the following Markdown be placed at the very top of the relevant standard file in the
official PHP FIG GitHub repo `fig-standards`.

> **Deprecated** - As of 2014-12-30 PSR-0 has been marked as deprecated. [PSR-4] is now recommended
as an alternative.
> [PSR-4]: http://php-fig.org/psr/psr-4

## 2. Dependencies

As documents are expected to be replaced rather than amended, dependencies on
other PSR's should be avoided whenever possible. For instance, the following is
no longer permitted:

> - Namespaces and classes MUST follow PSR-0.

Instead - if a dependency is considered necessary by the working group creating it - then the following
example can be used:

> - Namespaces and classes MUST follow an autoloading PSR: [ [PSR-0] ].

The outer set of square brackets denote a "dependency list", which is a list of PSRs
that are considered a compatible dependency.

When more PSR's are added to the "dependency list" the same example would look like this:

> - Namespaces and classes MUST follow an autoloading PSR: [ [PSR-0], [PSR-4] ].

New PSR's can be added to the "dependency list", but old PSR's can never be removed as this would break
backwards compatability.

## 3. Acceptable Amendments

Other than updating the "dependency list", there are two other potentially acceptable amendment scenarios
which do not require their own special vote.

### 3.1. Annotations

If Errata is added which is deemed important enough by whoever is initiating the errata vote,
annotations may be placed in or near the offending line so that readers know to view the errata for
more information, with a link containing an anchor to that specific piece of errata.

> - Something confusing about where brackets go. [cf. [errata](foo-meta.md#errata-1-foo)]

This will be done as part of the errata vote, not its own.

### 3.2. Formatting & Typos

If formatting is broken for any reason then changing formatting must not be considered a
change to the document. These can be merged or pushed without hesitation by a secretary, as long as they
don't change anything of any meaning or syntax.

Some typos as trivial as a misplaced comma could have a subtle impact on meaning. Take special care not to
alter backwards compatibility and create a vote if unsure. Common sense will help here.

Examples:

1. HTML Tables are currently broken on php-fig.org because of the syntax used.
2. Somebody spelled something wrong and nobody spotted it for a year.
3. Problems with GitHub Markdown

[workflow bylaw]: https://github.com/php-fig/fig-standards/blob/master/bylaws/002-psr-workflow.md
[votes bylaw]: https://github.com/php-fig/fig-standards/blob/master/bylaws/003-votes.md
