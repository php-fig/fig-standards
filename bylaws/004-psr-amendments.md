PSR Amendments
==============

This bylaw governs the amending of PHP Standard Recommendations (PSRs).

Definitions
-----------

<dl>
    <dt><strong>Informational PSR</strong></dt>
    <dd>
        A PSR which addresses information, general guidelines, practices or
        desirable behaviour which is likely to evolve over time or be based on
        a majority preference.
    </dd>
    <dt><strong>Functional PSR</strong></dt>
    <dd>
        A PSR which addresses interfaces, source code or implementation details
        which may not evolve over time due to backwards compatibility concerns.
    </dd>
    <dt><strong>Conflicting Change</strong></dt>
    <dd>
        An amendment to the content of a PSR which would render all previously
        compliant implementations or adoptions non-compliant with the revised
        PSR.
    </dd>
    <dt><strong>Non-Conflicting Change</strong></dt>
    <dd>
        An amendment to the content of a PSR where previously compliant
        implementations or adoptions would remain compliant with the revised
        PSR.
    </dd>
    <dt><strong>Trivial Change</strong></dt>
    <dd>
        A Non-Conflicting Change made to address spelling, notices of a
        superceding PSR, bugs in source code examples, and other
        such minimal changes as may be determined by PHP-FIG to be in compliance
        with this bylaw.
    </dd>
</dl>

PSR Categorisation
------------------

Upon adoption of this bylaw, all existing and future PSRs must be categorised as
either an Informational PSR or a Functional PSR. Future PSRs must be
categorised prior to their adoption by vote by the proposing individuals. Past
PSRs must be categorised as Functional PSRs unless a vote by PHP-FIG is 
undertaken to re-categorise them as Informational PSRs in accordance with
the [Voting Protocol bylaw][voting].

PSR Amendments
--------------

All amendments to a PSR, regardless of their nature, are subject to a vote by
PHP-FIG in accordance with the [Voting Protocol bylaw][voting]. An amendment to
a PSR may be included within any other proposal, including a proposal of a new
PSR or Bylaw, for voting purposes.

Conflicting Changes
-------------------

Conflicting Changes to a PSR are not allowed for Functional PSRs. Where
a Conflicting Change remains desireable, a new PSR may be proposed to supercede 
another. If a superceding PSR is adopted by PHP-FIG, the preceding PSR must be 
amended to include a notice that it has been superceded. The preceding PSR must 
remain at its original URI and may not be taken offline.

Conflicting Changes to a PSR are allowed for Informational PSRs which are
expected to evolve over time or where a majority preference change is expressed
by a vote in accordance with the [Voting Protocol bylaw][voting]. Any such
Conflicting Change must be justified by the proposer as arising from substantial
and evidenced changes in the underlying subject of the PSR since its publication
date. When no such substantial change can be evidenced, the Conflicting Change
should be rejected, subject to a vote in accordance with the [Voting Protocol
bylaw][voting], to avoid any undue inconvenience for parties currently compliant
with the PSR.

A Conflicting Change may arise when clarifying an apparent ambiguity or when 
correcting grammatical errors. Should such a situation arise, careful
consideration should be given to restating the Conflicting Change as a Non-
Conflicting Change which ensures backwards compatibility for all previously 
compliant parties.

Where a Non-Conflicting Change or Trivial Change is found to have introduced a
Conflicting Change in error, the change may be disregarded when interpreting the
PSR.

Non-Conflicting Changes
-----------------------

Non-Conflicting Changes to a PSR are allowed for both Informational and
Functional PSRs subject to a vote in accordance with the [Voting
Protocol bylaw][voting].

Trivial Changes
---------------

Trivial Changes to a PSR are allowed for both Informational and
Functional PSRs subject to a vote in accordance with the [Voting
Protocol bylaw][voting].

[voting]: https://github.com/php-fig/fig-standards/blob/master/bylaws/001-voting-protocol.md