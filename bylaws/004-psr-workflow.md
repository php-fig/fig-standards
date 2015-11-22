PSR Review Workflow
===================

This document describes the workflow for proposing a PSR and having it published by the PHP-FIG.

**Note:** Throughout this article when you see "PSR-N", "N" refers to whatever number has been
assigned to the PSR in question.

## 1. Roles

**Editor:** The Editor of a PSR is actively involved in managing and tracking a PSR as it is written.
A proposal may have no more than two Editors at a time, and a single Editor is preferred. The Editor
is responsible for managing the development of a PSR; for representing the PSR in discussions on
the PHP-FIG Mailing List; for coordinating other Contributors; and for working with the Coordinator
to see the PSR through the review process. The Editor(s) are not required to be voting members of
PHP-FIG. If the Editor(s) of a proposal are missing for more than 60 days without notice then
the Sponsors may agree upon a new Editor. An Editor is assumed to also be a Contributor to a PSR.

**Sponsor:** Any one of two voting members who have agreed to Sponsor a proposed PSR.
Each PSR must have two Sponsors. A Sponsor may not be an Editor but may otherwise contribute
in the normal way to a PSR. A Sponsor may step down to become an Editor for a PSR by posting a
message to the Mailing List. In this case, a new replacement Sponsor must be found for the PSR
to continue. Should a vote be underway, and a recorded Sponsor for that PSR objects on the basis
that they are inactive or not a valid Sponsor, this objection SHOULD be made on the Mailing List
and voting for that PSR WILL immediately be invalidated until such time as a replacement Sponsor
has been put in place. A proposal can never progress unless there are two Sponsors actively
Sponsoring the proposed PSR. Each Sponsor must confirm their Sponsorship of a PSR via individual
email to the Mailing List and a PSR will not be deemed Sponsored until those emails are delivered.

A Sponsor may not be the Editor or be listed as a Contributor, but there is of course nothing stopping
a Sponsor from contributing. A Sponsor may step down to become the Editor or a Contributor for a PSR
by posting a message to the Mailing List. In this case, a new Sponsor must be found. Should a vote
be underway with a Sponsor who does not consider themselves active listed in the meta document, they
should raise an objection on the Mailing List. The vote will then be invalidated until a new Sponsor
has been put in place.

> Requiring two Sponsors instead of just one prevents a single Sponsor from making important
> decisions alone.

**Coordinator:** One of the two required Sponsors is the Coordinator, and this must be decided between
the Sponsors early on. The Coordinator is in charge of the voting process. They note the starting and
ending dates, the number of voting members at the start of the vote, and the quorum count needed. They
send out reminders by whatever means they feel appropriate to drive the vote. At the end of the voting
period, they tally the votes, note if quorum was established, and whether or not the application was
accepted. They must coordinate and share responsibility for vote counting with the PHP FIG Secretary.

> **Note:** Copied from [Paul M. Jones' mail](https://groups.google.com/d/msg/php-fig/I0urcaIsEpk/uqQMb4bqlGwJ)

**Contributor:** Anyone who has contributed significantly to the PSR. That may include sending in a pull
request during the Pre-Draft or Draft stages, offered significant and meaningful reviews, former Editors,
etc. In case of dispute, the Editor and Coordinator are responsible for determining whether a particular
individual qualifies as a Contributor. The significance is at the discretion of the Editor(s) and
Sponsors. If somebody feels their contributions are being performed without attribution they should
contact the Editor(s), or a Sponsor, and failing that as a last resort post a thread on the Mailing List
saying so.

## 2. Stages

### 2.1 Pre-Draft

The goal of the Pre-Draft stage is to determine whether a majority of the PHP-FIG is interested in
publishing a PSR for a proposed concept.

Interested parties may discuss a possible proposal, including possible implementations, by
whatever means they feel is appropriate. That includes informal discussion on the PHP-FIG
Mailing List or IRC channel of whether or not the idea has merit and is within the scope
of PHP-FIG's goals.

Once those parties have determined to move forward, they must select an Editor and prepare a proposal
document. The proposal must be published in a fork of the [official PHP-FIG "fig-standards" repo][repo].
The content of the proposal must be placed inside the `/proposed` folder with a simple filename such as
"autoload.md". Along with this document must be a meta document with a suffix of "-meta" before the
extension (e.g. "autoload-meta.md"). GitHub Markdown formatting must be used for both documents.
No PSR number is assigned to the proposal at this point.

The Editor must then locate two voting members to Sponsor the proposal, one of whom agrees to be the
Coordinator. The Editor, Sponsors, and existing additional Contributors if any form the working group
for the proposal.

The proposal is not required to be fully developed at this point, although that is permitted. At
minimum, it must include a statement of the problem to be solved and basic information on the
general approach to be taken. Further revision and expansion is expected during the Draft phase.

The Coordinator must initiate an entrance vote to enquire whether the members of PHP-FIG are generally
interested in publishing a PSR for the proposed subject, even if they disagree with the details of
the proposal. The Coordinator must announce the vote on the Mailing List in a thread titled
"[VOTE][Entrance] Title of the proposal". The vote must adhere to [the voting protocol][voting].

If the vote passes, the proposal officially enters Draft stage. The proposal receives a PSR number
incremented from the highest numbered PSR which has passed the Entrance Vote, regardless of the status of
that PSR. A list of PSRs will be maintained in `index.md` file of the `fig-standards` repo. This
will be included on the PHP-FIG website, on a page called
[Index of PHP Standard Recommendations][psrindex], where the PSR entry is to be maintained by
the Coordinator.

The working group may continue to work on the proposal during the complete voting period.

### 2.2 Draft

The goal of the Draft stage is to discuss and polish a PSR proposal up to the point that it can be
considered for review by the PHP-FIG voting members.

In Draft stage, the Editor(s) and any Contributors may make any changes they see fit via pull requests,
comments on GitHub, Mailing List threads, IRC and similar tools. Change here is not limited by any strict
rules, and fundamental rewrites are possible if supported by the Editor(s). Alternative approaches may be
proposed and discussed at any time. If the Editor and Coordinator are convinced that an alternative proposal
is superior to the original proposal, then the alternative may replace the original. If the alternative builds
upon the original, the Editor(s) of the original proposal and the new alternative will be listed as
Contributors. Otherwise, the Editor(s) of the alternative proposal should be listed as Contributors.

All knowledge gained during Draft stage, such as possible alternative approaches, their implications, pros
and cons etc. as well as the reasons for choosing the proposed approach must be summarized in the meta
document. The purpose of this rule is to prevent circular discussions or alternative proposals from
reappearing once they have been decided on.

When the Editor and Sponsors agree that the proposal is ready and that the meta document is objective and
complete, the Coordinator may promote the proposal to Review stage. The promotion must be announced in a
thread on the Mailing List with the subject "[REVIEW] PSR-N: Title of the proposal". At this point, the
proposal must be merged into the "master" branch of the [official PHP-FIG "fig-standards" repository][repo].

> At this point, the Editor(s) transfer authority over the proposal to the Sponsors. This is to [prevent
> the Editor(s) from blocking changes](https://groups.google.com/d/msg/php-fig/qHOrincccWk/HrjpQMAW4AsJ)
> that the other PHP-FIG members agree on.
>
> If the Editor(s) are not ready yet to transfer authority, they should continue working on the proposal and
> the meta document until they feel confident to do so.

### 2.3 Review

The goal of the Review stage is to involve the majority of the PHP-FIG members in getting familiar with
a proposal and to decide whether it is ready for an acceptance vote. At this stage the Coordinator is in
charge of any decisions to move the proposal forwards or backwards.

The goal is also *not necessarily* to have every PHP-FIG member agree with the approach chosen by the
proposal. The goal however *is* to have all PHP-FIG members agree on the completeness and objectivity of
the meta document.

> Individual members of the PHP-FIG should not be permitted to prevent a PSR from being published.

During Review, changes in both the proposal and the meta document are limited to wording, typos, clarification
etc. The Sponsors should use their own judgement to control the scope of these changes, and must block
anything that is felt to be a fundamental change. The Sponsors must make changes that the majority of the
PHP-FIG members agree on, even if they personally disagree.

> Sponsors must not block the development of the proposal.

In this stage, major additions to the meta document are strictly prohibited. If alternative approaches are
discovered that are not yet listed in the meta document, the Coordinator must abort the Review by
publishing a thread titled "[CANCEL REVIEW] PSR-N: Title of the proposal" on the Mailing List, unless
the acceptance vote has started already. However, the Sponsors may choose to abort the vote (by publishing
a thread on the mailing list) and the Review even after that, if they agree that this is necessary. The
purpose of this rule is to give PHP-FIG members the chance to consider *all* known alternatives during
the Review stage.

Unless a proposal is moved to Draft stage again, it must remain in Review stage for a minimum of two weeks
before an acceptance vote is called. This gives every PHP-FIG Member sufficient time to get familiar
with and influence a proposal before the final vote is called.

When the Editor(s) and Sponsors agree that the proposal is ready to become a PSR, an acceptance vote is
called. The Coordinator must publish a thread on the Mailing List with the subject "[VOTE][Accept] PSR-N:
Title of the proposal" to announce the vote. The vote must adhere to [the voting protocol][voting].

### 2.4 Accepted

If the acceptance vote passes, then the proposal officially becomes an accepted PSR. The proposal
itself is moved from `/proposed` to `/accepted` by a PHP-FIG member with GitHub access and prefixed with
its PSR number, such as "PSR-3-logger-interface.md". Comments must be removed from this document, but a
copy of the commented proposal must be kept in `/accepted/meta`, bearing the suffix "-commented" (e.g.
"PSR-3-logger-interface-commented.md"). The commented version can be used to interpret the rules of the
PSR in case of doubt.

> Reason for having both a commented PSR and a meta document:
>
> The meta document provides the high-level perspective, why an approach was
> taken and what other approaches exist.
>
> The comments in a PSR, on the contrary, provide additional information about
> specific rules in a PSR or explain the intention of a rule in simple words
> (like doc blocks in source code). Comments are mostly useful during Draft and
> Review. With their additional information, other people reading the proposal
> can judge more easily whether they disagree with a rule fundamentally or
> whether they agree, but the Editor just happened to formulate the rule badly.

The meta document of the proposal must also be moved to `/accepted/meta` and prefixed with the PSR number,
for example "PSR-3-logger-interface-meta.md".

## 3. Meta Document

The purpose of the meta document is to provide the high-level perspective of a proposal for the voters
and to give them objective information about both the chosen approach and any alternative approaches in
order to make an informed decision.

### 3.1 Executive Summary

Summarizes the purpose and big picture of the proposal, possibly with a few simple examples of how the
contributors(s) imagine an implementation of the PSR to be used in practice.

### 3.2 Why Bother?

An argument for why the proposed topic should be specified in a PSR at all. Should include a list of
positive and negative implications of releasing this PSR. The purpose of this section is to convince
voters to accept the proposal as draft during the entrance vote.

### 3.3 Scope

A listing of both goals and non-goals that the PSR should achieve. The goals/non-goals should be specific
and measurable.

**Bad:** Make logging easier.

**Better:** Provide an interoperable logger interface.

### 3.4 Approaches

Describes the design decisions that were made in the proposal and *why* they were taken. Most importantly,
this section must objectively list both the positive and negative implications of these decisions. If
possible, links to individual, relevant posts on the Mailing List, IRC logs or similar should be included.

Also lists all known alternative approaches for the PSR proposal. For each of them, the document should describe
an objective list of pros and cons and the reason why that approach is not considered good enough. Should
also include links to Pull Requests, individual posts on the Mailing List, IRC logs or similar, if available.

### 3.5 People

The names of the people involved in creating the PSR proposal, sorted alphabetically by last name in ascending
order. The document should distinguish between the following groups:

* Editors
* Sponsors (indicating which of them was Coordinator)
* Contributors (as defined in Section 1)

If someone considers themselves to be a contributor but is not listed here, they should contact the
Editors(s) and Sponsors, including some proof about their contribution. If the proof is valid, the
contributor must be put on this list by one of the Editors(s) or Sponsors.

### 3.6 Errata

Errata can be used to add clarification on contentious points that arise after a documents formation.
This is limited to non-binding, backwards compatible explanations and must not include new rules.

Errata may only be added to the meta document. To add new Errata to the meta document a vote must be held
on the mailing list, and this vote must adhere to [the voting protocol][voting].

### 3.7 Template

This is an example template that can be used to build a meta document.

    PSR-N Meta Document
    ===================

    1. Summary
    ----------

    The purpose of X is to bla bla bla. More description than might go into the
    summary, with potential prose and a little history might be helpful.

    2. Why Bother?
    --------------

    Specifying X will help libraries to share their mechanisms for bla bla...

    Pros:

    * Frameworks will use a common algorithm

    Cons:

    * Most of the frameworks don't use this algorithm yet

    3. Scope
    --------

    ## 3.1 Goals

    * Autoload namespaced classes
    * Support an implementation capable of loading 1000 classes within 10ms

    ## 3.2 Non-Goals

    * Support PEAR naming conventions

    4. Approaches
    -------------

    ### 4.1 Chosen Approach

    We have decided to build it this way, because we have noticed it to be common practice withing member
    projects to do X, Y and Z.

    Pros:

    * Simple solution
    * Easy to implement in practice

    Cons:

    * Not very efficient
    * Cannot be extended

    ### 4.2 Alternative: Trent Reznor's Foo Proposal

    The idea of this approach is to bla bla bla. Contrary to the chosen approach, we'd do X and not Y etc.

    We decided against this approach because X and Y.

    Pros:

    * ...

    Cons:

    * ...

    ### 4.3 Alternative: Kanye West's Bar Proposal

    This approach differs from the others in that it bla bla.

    Unfortunately the editor disappeared mid-way and no-one else took over the proposal.

    Pros:

    * ...

    Cons:

    * ...

    5. People
    ---------

    ### 5.1 Editor(s)

    * John Smith

    ### 5.2 Sponsors

    * Jimmy Cash
    * Barbra Streisand (Coordinator)

    ### 5.3 Contributors

    * Trent Reznor
    * Jimmie Rodgers
    * Kanye West

    6. Votes
    --------

    * **Entrance Vote: ** http://groups.google.com...
    * **Acceptance Vote:** http://groups.google.com...

    7. Relevant Links
    -----------------

    _**Note:** Order descending chronologically._

    * [Formative IRC Conversation Gist]
    * [Mailing list thread poll to decide if Y should do Z]
    * [IRC Conversation Gist where everyone decided to rewrite things]
    * [Relevant Poll of existing method names in voting projects for new interface]

    8. Errata
    ---------

    1. _[08/23/2013]_ This is an example of a non-binding errata rule that was originally missed
    in the formation of the document. It can include clarification on wording, explanations, etc
    but it cannot create new rules.

[repo]: https://github.com/php-fig/fig-standards/tree/master
[psrindex]: http://php-fig.org/psr/
[voting]: https://github.com/php-fig/fig-standards/blob/master/bylaws/001-voting-protocol.md
