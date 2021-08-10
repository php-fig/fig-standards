# PER Workflow

A PHP Evolving Recommendation (PER) is a formal definition of best practices, utilities, reference implementations, and similar tools.  They may evolve over time as the PHP ecosystem develops.  

## Pre-Draft

The goal of the Pre-Draft stage is to determine whether a majority of the PHP FIG is interested in establishing a PER Working Group for a proposed concept.

Interested parties may discuss a possible proposal, including possible implementations, by whatever means they feel is appropriate. That includes informal discussion on official FIG discussion mediums of whether or not the idea has merit and is within the scope of the PHP FIG's goals.

Once those parties have determined to move forward, they must form a Working Group. A Working Group consists of, at minimum:

* One Editor
* One Core Committee member who will act as Sponsor
* At least three other individuals. These may include Project Representatives, Secretaries or Core Committee members as well as members of the general community.

The proposal is not required to be fully developed at this point, although that is permitted. At minimum, it must include a statement of the problem to be solved and basic information on the general approach to be taken. Further revision and expansion is expected during the Draft Phase.

The Sponsor may then call for an Entrance Vote of the Core Committee to enquire whether the Core Committee is generally interested in maintaining a PER for the proposed subject, even if they disagree with the details of the proposal.

If the vote passes, the proposal officially enters Draft stage. The proposal is given a unique descriptive name (such as "CodingStandards", "CacheUtil", etc.).

The Working Group may continue to work on the proposal during the complete voting period.

## Draft

The goal of the Draft stage is to discuss and polish a baseline PER proposal up to the point that it can be considered for review by the FIG Core Committee.

In Draft stage, members of the Working Group may make any changes they see fit via pull requests, comments on GitHub, Mailing List threads, real-time chat, and similar tools. Change here is not limited by any strict rules, and fundamental rewrites are possible if supported by the Editor. Alternative approaches may be proposed and discussed at any time. If the Editor and Sponsor are convinced that an alternative proposal is superior to the original proposal, then the alternative may replace the original. Working Group members are expected to remain engaged throughout the Draft Phase. Discussions are public and anyone, regardless of FIG affiliation, is welcome to offer constructive input. During this phase, the Editor has final authority on changes made to the proposed specification.

The Secretaries will ensure that the Working Group is provided with necessary resources to work on the specification, such as a dedicated GitHub repository, mailing list, forum section, chat room or channel, and similar such tools.

All knowledge gained during Draft stage, such as possible alternative approaches, their implications, pros and cons etc. as well as the reasons for choosing the proposed approach must be summarized in the meta document. The purpose of this rule is to prevent circular discussions or alternative proposals from reappearing once they have been decided upon.

When the Editor and Sponsor agree that the proposal is ready and that the meta document is objective and complete, the Editor may call for a Readiness Vote of the Working Group to determine if the specification is substantively complete and ready for trial implementations.

If the vote passes, the proposal officially enters Review Phase. If it does not, it remains in Draft Phase.

## Review

The Review Phase is an opportunity for the community to experiment with a reasonably fixed target to evaluate a proposal's practicality. At this stage, the Sponsor is the final authority on changes to the specification as well as any decisions to move the proposal forward or backward, however, the Editor may veto proposed changes they believe are harmful to the design of the specification.

During this phase, trial implementations of the specification are expected and encouraged. Changes to the specification are limited to those directly informed by trial implementations, wording, typos, clarification, etc. Major changes are not permitted in this phase. If the development of trial implementations demonstrates the need for major changes then the specification must be pushed back to Draft Phase. Any incompatible change that would require significant effort for trial implementations to adjust for qualifies as a major change. Small to moderate incompatible changes do not necessarily mandate a return to Draft Phase.

Unless a proposal is moved to Draft stage again, it must remain in Review stage for a minimum of four weeks and until two independent viable trial implementations can be demonstrated. The responsibility for finding such trial implementations and presenting them to the Core Committee lies with the Working Group, and especially the Editor and Sponsor. As not all specifications are PHP interfaces where the definition of "implementation" is self-evident, the Sponsor should use good faith judgement to determine when that is the case. Any member of the Core Committee may object to a given trial implementation as irrelevant or insufficient with due cause.

Once four weeks have passed and two viable trial implementations can be demonstrated, the Sponsor may call an Acceptance Vote. If the Acceptance Vote fails, the specification may remain in Review.

## Accepted

If the Acceptance Vote passes, then the proposal officially becomes an accepted PER.  It is considered the 1.0.0 release of that PER.

## Evolution

Unlike a PSR, a PER is expected to evolve over time.  Once the initial version of a PER is approved, the Working Group remains active but its minimum requirements drop to an Editor and two additional individuals.  By default, the Working Group at time of first approval continues in that role.  The Working Group may continue to develop the PER over time as the context of the recommendation evolves, such as newly released PHP language versions, additional input from third parties, etc.

For any proposed change to the PER, the Editor must notify the Core Committee via a post to the mailing list of an Intent to Merge a given change.

If no member of the Core Committee objects within seven days, the proposed change is assumed accepted by default.  Any member of the Core Committee may request during that seven-day period that the change be put to a vote.  If any member does so, the change is immediately put to an Acceptance Vote to determine if it is accepted or not.

The Editor is empowered to declare tagged versions of a PER at any time.  Tagged versions MUST follow Semantic Versioning conventions.  However, any change that would necessitate a new Major release MUST be put to an Acceptance Vote by the Core Committee.

## Staffing Changes

The Editor of an approved PER is empowered to add or remove members from the Working Group at any time, with notification to the Core Committee.

Should the Editor of a PER Working Group resign or become inactive, the Core Committee may appoint a new Editor.  Preference will be given to the existing Working Group members at that time.

## Abandoned

A PER Working Group can be marked as Abandoned by Secretaries when it is without an Editor for 60 days. After a period of 6 months without significant activity in a Working Group, the Secretaries may also declare a PER Working Group to be Abandoned.

Should in the future a new Editor wish to establish a new Working Group to continue development of the same PER, the candidate may petition the Core Committee to do so by asking for a new Entrance Vote.  Only the Editor and two additional individuals are required at this time.

## PER Working Group Fast-track

A PER Working Group may be established with a single Entrance Vote and only three members (Editor and two others) in the following circumstances:

1. A new Working Group is taking over an Abandoned PER.
2. A new Working Group wishes to use an existing PSR as the initial version of a PER.  In this case, the assumption is that the subject matter is more suited to a PER than a PSR.
3. A new Working Group wishes to take ownership of existing utility libraries already published that do not yet have clear ownership.

Should a PSR be approved for which its Working Group has already produced one or more utility libraries, the PSR Working Group is automatically converted into a PER Working Group upon approval to cover those libraries, unless the Working Group explicitly states its intent to not do so.  For example, such utility libraries may be better managed by an existing PER for a related topic.

In case of dispute, the scope for a given PER Working Group is determined by the Core Committee via a Decision Vote.
