# PSR Acceptance and Revision Guidelines

* **Author(s):** Ryan Parman, Justin Hileman
* **Status:** Proposed
* **Initially proposed:** 2013-02-27


The PHP Framework Interoperability Group provides recommendations and guidelines
for FIG member projects specifically, and to the PHP community in general.
Proposals which are passed according to the [Voting Protocol](001-voting-protocol.md)
become FIG recommendations. The intention of this bylaw is to provide guidelines
for the proposal process, as well as addressing revisions to our recommendations
as the PHP language and our community changes over time. This process is loosely
based on the Python community's [PEP process](http://www.python.org/dev/peps/pep-0001/).


## PSR Types

There are two types of PSRs:

 * A _Recommendation Track_ PSR describes a new standard, a common interface,
   or recommendation for the FIG.

 * An _Informational_ PSR describes a PHP design issue, puts forth a coding
   standard, describes a best practice, or provides general guidelines to the
   FIG and PHP community. It does not provide interfaces or concrete
   implementations. While no PSRs are binding, an Informational PSR does not
   necessarily represent a FIG consensus or recommendation, so members and users
   are free to ignore Informational PSRs or follow their advice.


## PSR Workflow

1. To be considered by the FIG, a PSR draft MUST prepared and proposed for
discussion and review on the php-fig mailing list. The PSR status at this time
is _Proposed_. Draft authors SHOULD collect community feedback. Depending on the
proposal, they MAY conduct polls, or compile relevant statistics on current
implementations and prior art. Drafts SHOULD be discussed, revised, and amended
according to the feedback recieved.

2. During the discussion phase, the draft proposal MUST be designated as either
an _Informational_ proposal, or a _Recommendation Track_ proposal.

3. After sufficient discussion has occured, and draft revision is finalized, a
voting member MAY call for a vote. The voting process MUST then be conducted
according to [the Voting Protocol bylaws](001-voting-protocol.md).

4. Once a PSR designated as _Informational_ is passed by a vote, it is assigned
the _Active_ status, and receives the next available `PSR` identifier.

    4.1\. A PSR designated as _Informational_ MAY be adapted over time by
    drafting a new revision, proposing it, and going through the full discussion
    and voting cycle. The revised PSR MUST maintain the same PSR number, and is
    considered an up-to-date revision of the PSR. The history of each PSR can be
    viewed via version control.

    4.2\. An _Informational_ PSR MAY remain in _Active_ status,
    and SHOULD be adjusted over time to stay relevant. This could be forever, or
    it could just be until it is deprecated by new PSR.

    4.3\. A PSR designated as _Informational_ MAY eventually be designated as
    _Final_, indicating that no future maintenance is expected. This SHOULD
    happen in the event that a previous Informational PSR is superseded or
    deprecated by another PSR.

5. When a _Recommendation Track_ PSR completes the proposal process and is
accepted by vote, it is assigned the _Accepted_ status, and receives the next
available `PSR` identifier. It is now considered a recommendation of the FIG.
This status is analogous to a "code freeze". Reference implementations MAY now
be implemented, and frameworks and libraries SHOULD begin adopting and
implementing the recommendation.

    5.1\. Once a _Recommendation Track_ PSR is designated as _Accepted_, it will
    be implemented and learned from of over a period of months. In the event
    that something unexpected turns up, we MAY reword things for clarity. We MAY
    adjust actual details, too, if the situation warrants it. But we SHOULD do
    them with the same amount of care as we would changing an API after a code
    freeze. All revisions to an _Accepted_ PSR MUST follow the standard proposal
    and voting cycle.

    5.2\. Once the pre-defined period of time has expired, the _Accepted_ PSR
    moves into a _Final_ state. In general, _Recommendation Track_ PSRs are no
    longer maintained after they have reached the _Final_ state. At this point,
    it's canon. Any further changes SHOULD be proposed and accepted in a
    superseding PSR, which deprecates this one and replaces it with
    something new.

6. PSRs which were passed by vote before this bylaw was put into effect SHOULD
adopt an _Informational_ or _Recommendation Track_ designation by through the
standard proposal and voting process. Future changes SHOULD be made as outlined
in this bylaw.
