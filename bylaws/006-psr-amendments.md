Amendments
==========

Following the rules of the [workflow bylaw][], once a PSR has been "Accepted" the PSR meaning 
cannot change, backwards compatibility MUST remain at 100%, and any confusion that arises from
original wording MAY be clarified through errata. 

The rules for errata are covered in the [workflow bylaw][], and only allow non-backwards compatible clarification to be added to the meta document. Sometimes, modifications will be necessary in PSR document itself, and this document outlines those cases.

## 1. Deprecation and Replacement

If a PSR is found to require substantive updates or errata is no longer serves as a 
useful resource to clarify confusion, then the PSR must be replaced, following
the workflow set out in [workflow bylaw][].

The original PSR may at some point in time be deprecated, and the new PSR becomes the recommended 
document. Deprecation and recommendation changes must be made with a vote according to the rules 
of the [voting protocol][], with a subject like "[VOTE] Deprecate PSR-X", at which point a PSR-Y should be specified as a recommendation. 

Once a vote has passed with the decision to deprecate a PSR and supersede it 
with another PSR, the deprecated PSR must be marked as such in the original 
document and a link should be placed in the body.

For example:

> **Deprecated** - As of 01/01/2014 PSR-0 has been marked as deprecated. PSR-4 is now recommended 
as an alternative.


## 2. Dependencies 

As documents are expected to be replaced rather than amended, dependencies on 
other PSR's should be avoided whenever possible. For instance, the follow is 
no longer permitted:

> - Namespaces and classes MUST follow PSR-0.

Instead the following example must be used:

> - Namespaces and classes MUST follow an autoloading PSR: [PSR-0].

These square brackets denote a "reference area", which is a list of PSRs that
conform to the rule. These may ONLY be added to, and never removed.

> - Namespaces and classes MUST follow an autoloading PSR: [PSR-0, PSR-4].

As new PSRs are created which satisfy a requirement, they may be added. Even if 
adding a new PSR involves deprecating another PSR, that deprecated PSR will 
remain in the document, as anything else would break backwards compatibility.

## 3. Acceptable Amendments

Other than the updating of dependency references, there are two other potentially 
acceptable amendment scenarios which do not require their own special vote.

### 3.1. Annotations

If Errata is added which is deemed important enough by whoever is initiating the errata vote,
annotations may be placed in or near the offending line so that readers know to view the errata for 
more information. 

> - Something confusing about where brackets go. [cf. [errata](foo-meta.md#anchor)]

This will be done as part of the errata vote, not its own.

### 3.2. Formatting & Typos

If formatting is broken for any reason then changing formatting must not be considered a 
change to the document. These can be merged or pushed without hesitation, as long as they 
don't change anything of any meaning or syntax. Common sense will help here. 

Examples:

1. HTML Tables are currently broken on php-fig.org because of the syntax used.
2. Somebody spelled something wrong and nobody spotted it for a year.
3. Problems with GitHub Markdown


[workflow bylaw]: https://github.com/philsturgeon/fig-standards/blob/master/bylaws/004-psr-workflow.md
[voting protocol]: https://github.com/philsturgeon/fig-standards/blob/master/bylaws/001-voting-protocol.md
