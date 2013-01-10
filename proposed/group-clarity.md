Bring Clarity Of Purpose To The PSR/FIG Group
=============================================

The original goals of the PHP Standard Recommendation group, which then
morphed into the Framework Interoperability Group were noble.  The standards
which the group originally promoted were largely a formalization of conventions
that were already de-facto standards.

The current PHP-FIG group are the ambassadors for a movement that has by and
large been promoted throughout the PHP community.  Those standards are promoted
by Framework members of the PHP-FIG group, but by and large, developers who
have no affiliation with the PHP-FIG group whatsoever.

It is my opinion, that with the introduction of PSR-3, the original intent
of the PSR (PHP Standard Recommendation) has now been too deeply infused with
the intent and goals of the FIG (Framework Interoperability Group), thus
detracting from the original intent which was formalizing de-facto standards
into an easy to refer to classification system.

That's not to say that the goals of framework interoperability are not noble,
they are just misplaced with respect to PHP standards recommendation.  This
has become evident in the pull request queue where most pull request are
suggesting an "official way" to write code, in the form of interfaces, shared
implementations and the like.  Most of which has never been de-facto and all
of which is highly subjective.

My Proposal
-----------

* Continue on with the group, and it's structure as-is with respect to membership and voting.
* Create and start using two separate monikers for the accepted proposals:
    * PSR-# for PHP Standard Recommendations:  This is for standard recommendations
      in how to write code to make it feel consistent in a given paradigm of programming
    * FIG-# for Framework Interoperability Group: This for accepted code proposals
      that further enforce the idea of "framework operability".
* Move the current PSR-3 into FIG-1, also changing the namespace of such code to exist in the FIG\ namespace
* Create documentation that further enforces the goals of what a PSR and FIG proposals are intended for. 

