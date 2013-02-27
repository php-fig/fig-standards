# Versioning and Superseding PSRs

Having a settled specification allows those who choose to adopt these
recommendations to have a solid foundation from which to build. The intention of
this bylaw is to add guidelines around how to address changes to our
recommendations.

This document is based (in spirit) on the work of the
[RSS Advisory Board](http://www.rssboard.org/rss-2-0#roadmap) and the patterns
established by the [IETF](http://www.rfc-editor.org).

1. Once a PSR is passed, it is -- for all practical purposes -- frozen.

2. Changes to an existing PSR may be made only for the purpose of clarifying
the specification, not for adding new features or changing the meaning of the
PSR in **any** way. These types of changes MUST be discussed on one of the
sanctioned discussion lists for the group, and MUST have a motion and a second
by 2 or more voting members.
(See "[second voting member agrees](https://github.com/php-fig/fig-standards/pull/56#issuecomment-11905115)".)

3. Subsequent work should happen in an entirely new proposal. Each new proposal
must pass through all appropriate [voting protocols](001-voting-protocol.md) adopted by the group.

4. All new PSRs that pass the vote MUST receive the next subsequent integer for
its identifier (e.g., `PSR-0`, `PSR-1`, `PSR-2`, `PSR-3`, ..., `PSR-n`). The
proposal upon which the PSR is based may cover new ground, or may enhance,
change or otherwise supercede an existing PSR.
(For precedent, see [21st amendment to the U.S. Constitution](https://en.wikisource.org/wiki/Additional_amendments_to_the_United_States_Constitution#Amendment_XXI);
[IETF RFC 1123 Update History](http://www.rfc-editor.org/search/rfc_search_detail.php?rfc=1123).)

5. If the intention of a new proposal is to modify (and thereby supersede) an
existing PSR, the proposal SHOULD clearly explain as much in its introduction.

6. If a superceding proposal (as per paragraph 5) is adopted as a new PSR, the
PSR which was superceded continues to be a valid PSR, although it ceases to be
"recommended". The superseding PSR(s) will inherit the "recommended" status, and
the superseded PSR(s) will be clarified (as per paragraph 2) to direct readers
to the superseding PSR(s).

7. It is possible for one new proposal to update multiple PSRs. (e.g., a future
`PSR-99` might update both `PSR-1` and `PSR-2`.)

8. It is possible for multiple proposals to update a single PSR. (e.g., multiple
authors might work on different ways, or even competing ways, to update an
existing proposal.)
