# PSR Workflow

## Pre-Draft

The goal of the Pre-Draft stage is to determine whether a majority of the PHP FIG is interested in publishing a PSR for a proposed concept.

Interested parties may discuss a possible proposal, including possible implementations, by whatever means they feel is appropriate. That includes informal discussion on official FIG discussion mediums of whether or not the idea has merit and is within the scope of the PHP FIG's goals.

Once those parties have determined to move forward, they must form a Working Group. A Working Group consists of, at minimum:

* One Editor
* One Core Committee member who will act as Sponsor
* At least three other individuals. These may include Project Representatives, Secretaries or Core Committee members as well as members of the general community.

The proposal is not required to be fully developed at this point, although that is permitted. At minimum, it must include a statement of the problem to be solved and basic information on the general approach to be taken. Further revision and expansion is expected during the Draft Phase.

The Sponsor may then call for an Entrance Vote of the Core Committee to enquire whether the Core Committee is generally interested in publishing a PSR for the proposed subject, even if they disagree with the details of the proposal.

If the vote passes, the proposal officially enters Draft stage. The proposal receives a PSR number incremented from the highest numbered PSR which has passed the Entrance Vote, regardless of the status of that PSR.

The Working Group may continue to work on the proposal during the complete voting period.

## Draft

The goal of the Draft stage is to discuss and polish a PSR proposal up to the point that it can be considered for review by the FIG Core Committee.

In Draft stage, members of the Working Group may make any changes they see fit via pull requests, comments on GitHub, Mailing List threads, IRC and similar tools. Change here is not limited by any strict rules, and fundamental rewrites are possible if supported by the Editor. Alternative approaches may be proposed and discussed at any time. If the Editor and Sponsor are convinced that an alternative proposal is superior to the original proposal, then the alternative may replace the original. Working Group members are expected to remain engaged throughout the Draft Phase. Discussions are public and anyone, regardless of FIG affiliation, is welcome to offer constructive input. During this phase, the Editor has final authority on changes made to the proposed specification.

The Secretaries will ensure that the Working Group is provided with necessary resources to work on the specification, such as a dedicated GitHub repository, mailing list, forum section, and similar such tools.

All knowledge gained during Draft stage, such as possible alternative approaches, their implications, pros and cons etc. as well as the reasons for choosing the proposed approach must be summarized in the meta document. The purpose of this rule is to prevent circular discussions or alternative proposals from reappearing once they have been decided upon.

When the Editor and Sponsor agree that the proposal is ready and that the meta document is objective and complete, the Editor may call for a Readiness Vote of the Working Group to determine if the specification is substantively complete and ready for trial implementations.

If the vote passes, the proposal officially enters Review Phase. If it does not, it remains in Draft Phase.

## Review

The Review Phase is an opportunity for the community to experiment with a reasonably fixed target to evaluate a proposal's practicality. At this stage, the Sponsor is the final authority on changes to the specification as well as any decisions to move the proposal forward or backward, however, the Editor may veto proposed changes they believe are harmful to the design of the specification.

During this phase, trial implementations of the specification are expected and encouraged. Changes to the specification are limited to those directly informed by trial implementations, wording, typos, clarification, etc. Major changes are not permitted in this phase. If the development of trial implementations demonstrates the need for major changes then the specification must be pushed back to Draft Phase. Any incompatible change that would require significant effort for trial implementations to adjust for qualifies as a major change. Small to moderate incompatible changes do not necessarily mandate a return to Draft Phase.

Unless a proposal is moved to Draft stage again, it must remain in Review stage for a minimum of four weeks and until two independent viable trial implementations can be demonstrated. The responsibility for finding such trial implementations and presenting them to the Core Committee lies with the Working Group, and especially the Editor and Sponsor. As not all specifications are PHP interfaces where the definition of "implementation" is self-evident, the Sponsor should use good faith judgement to determine when that is the case. Any member of the Core Committee may object to a given trial implementation as irrelevant or insufficient with due cause.

Once four weeks have passed and two viable trial implementations can be demonstrated, the Sponsor may call an Acceptance Vote. If the Acceptance Vote fails, the specification may remain in Review.

## Accepted

If the Acceptance Vote passes, then the proposal officially becomes an accepted PSR. At this time the Working Group is automatically dissolved, however the Editor's input (or a nominated individual communicated to the secretaries) may be called upon in the future should typos, changes or Errata on the specification be raised.

## Deprecated

A Deprecated PSR is one that has been approved, but is no longer considered relevant or recommended. Typically this is due to the PSR being superseded by a new version, but that is not required.

A PSR may be Deprecated explicitly as part of the Acceptance Vote for another PSR. Alternatively, it may be marked Deprecated by a Deprecation Vote.

## Abandoned

An Abandoned PSR is one that is not actively being worked upon. A PSR will can be marked as Abandoned by Secretaries when it is without an Editor for 60 days or a Sponsor for 60 days. After a period of 6 months without significant activity in a Working Group, the Secretaries may also change a PSR to be "Abandoned". A PSR can also be triggered to move to "Abandoned" upon an Abandonment vote of the Core Committee which may be requested by the Working Group by petitioning a Core Committee member or Secretary.

At this time the Working Group is automatically dissolved.

Once a PSR is in "Abandoned" stage it may only once again be moved to Draft after a fresh Entrance vote by the Core Committee following the same procedure as if it was a pre-draft, except it may retain its previously assigned number. If the aims of the PSR differ from the original entrance vote, it is up to the discretion of the Core Committee whether or not it should be considered a fresh PSR or a restart of activity on the Abandoned PSR.

## Project Referendum

At any time the Editor of a PSR in Draft or Review Phase may call for a non-binding Referendum of Project Representatives on the current state of a PSR.  Such a Referendum may be called multiple times if appropriate as the PSR evolves, or never, at the Editor's discretion.  The Core Committee may also require such a Referendum as a condition of an Acceptance Vote if appropriate.  Referendum results are non-binding but the Working Group and Core Committee are expected to give the results due consideration.
