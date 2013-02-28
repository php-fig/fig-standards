# Versioning and Superseding PSRs

* **Author(s):** Ryan Parman, Justin Hileman
* **Status:** Proposed
* **Initially proposed:** 2013-02-27

Proposals which are passed according to the [voting protocol](001-voting-protocol.md)
become official PSR recommendations. The intention of this bylaw is to add
guidelines around how to address changes to our recommendations as the PHP
language and our community changes over time. This process is loosely based on
the [PEP process](http://www.python.org/dev/peps/pep-0001/) from the Python community.

1. During the discussion phase of a proposal, it should be determined whether
the proposal is more of a _Current Best Practice_, or something that should
eventually settle on a _Finalized_ state. (For example, proposals that don't have
very much consensus should err on the side of _Current Best Practice_.)

2. When a PSR designated as a _Current Best Practice_ is passed by a vote, it is
assigned a status of _Active_, and receives the next available `PSR` identifier.

    2.1\. A PSR designated as a _Current Best Practice_ MAY be adapted over time
    by drafting a new revision, proposing it, and going through the full cycle
    of the voting process. The revised PSR will maintain its same PSR number,
    but is considered the most up-to-date revision of the PSR. (The history of
    the PSR can be viewed via version control.)

    2.2\. A PSR designated as a _Current Best Practice_ stays in _Active_ status,
    being gradually adjusted to stay relevant. This could be forever, or it
    could just be until it's deprecated by a brand new and different PSR.

3. When a PSR designated as _To Be Finalized_ is passed by a vote, it is
assigned a status of _Accepted_, and receives the next available `PSR` identifier.
This status is analogous to a "code freeze", and signals to the community a
green light to begin using it. (To clarify, everything that we could predict
would go wrong has been covered. The wording all makes sense to us.)

    3.1\. A PSR designated as _To Be Finalized_ is implemented and learned from
    of over a period of months. In the event that something unexpected turns up,
    we can reword things for clarity. We might adjust actual details, too, if the
    situation warrants it. But we'll do them with the same amount of care as we
    would changing an API after a code freeze.

    3.2\. Once the pre-defined period of time has expired, the _Accepted_ PSR
    moves into a _Final_ state. At this point, it's canon. Any further changes
    would require a superseding PSR to deprecate this one and replace it with
    something new.

4. PSRs which were passed by vote before this bylaw was put into effect MAY
adopt a _Current Best Practice_ or _To Be Finalized_ designation by going
through the full cycle of the voting process, then following the process
outlined in this bylaw.
