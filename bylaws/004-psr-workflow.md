# PSR Review Workflow

This document describes the workflow for proposing a PSR and having it published by the PHP-FIG.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Roles

**Author:** An author is actively involved in writing a PSR. A document MAY have multiple authors. None
of them are REQUIRED to be voting members.

**Sponsor:** A voting member who officially supports a proposal. Each PSR MUST have two sponsors that
MUST NOT include any of the authors.

> Two and not just one to prevent a single sponsor from making important decisions alone.

**Coordinator:** One of the sponsors is the coordinator of a PSR. The coordinator is in charge of the
voting process. He notes the starting and ending dates, the number of voting members at the start of the
vote, and the quorum count needed. He sends out reminders by whatever means he feels appropriate to drive
the vote. At the end of the voting period, he tallies the votes, notes if quorum was established, and
whether or not the application was accepted.

> Note: copied from [Paul M. Jones' mail](https://groups.google.com/d/msg/php-fig/I0urcaIsEpk/uqQMb4bqlGwJ)

**Contributor:** Anyone who feels like they have done a relevant amount of contribution. Includes anyone
sending in a pull request during the Pre-Draft or Draft stages and anyone who feels like their review
tweaks were relevant too. In case of doubt, the voters SHOULD use reasonable judgement to decide whether
a contribution was relevant or not.

## 2. Stages

### 2.1 Pre-Draft

The goal of the Pre-Draft stage is to determine whether a majority of the PHP-FIG is interested in
publishing a PSR for a proposed concept.

The author(s) can work on the proposal anywhere they like, do whatever they like and come up with any
ideas they feel are within the scope of the PHP-FIG.

Once the proposal is considered ready by the author(s), it MUST be published in a fork of the [official
PHP-FIG "fig-standards" repo][repo]. The content of the proposal MUST be placed inside the `/proposed`
folder with a simple filename such as "autoload.md". Along with this document MUST be a meta document with
a suffix of "-meta" before the extension (e.g. "autoload-meta.md"). Markdown formatting MUST be used for
both documents. No PSR number is assigned to the proposal at this point.

With both the proposal and the meta document in the proposed folder, the author(s) MUST find their sponsors,
one of which MUST become the coordinator. Unless the proposed concept was already voted on before,
the coordinator MUST initiate a vote to enquire whether the members of PHP-FIG are generally interested in
publishing a PSR for the proposed subject, even if they disagree with the details of the proposal.
The coordinator MUST announce the vote on the Mailing List in a thread titled "[VOTE] Proposed: Title of
the proposal". The vote MUST adhere to [the voting protocol][voting].

If the vote passes, the proposal receives a temporary name and officially enters Draft stage. The name
SHOULD be composed of the prefix "PSR-" and one or two uppercase letters representing the proposal's name,
such as "PSR-C" for a cache proposal. The names MUST be noted in the `/proposed/_names.md` document of the
[official PHP-FIG "fig-standards" repo][repo]. Names MAY be reused once a proposal was accepted or dropped.

The author(s) MAY continue to work on the proposal during the complete voting period.

### 2.2 Draft

The goal of the Draft stage is to discuss and polish a PSR proposal up to the point that it can be
considered for review by the majority of the PHP-FIG members.

In Draft stage, the author(s) and any contributors MAY make any changes they see fit via pull requests,
comments on GitHub, mailing list threads, IRC and similar tools. Change here is not limited by any strict
rules, and fundamental rewrites are possible if supported by the author(s). Alternative approaches MAY be
proposed and discussed any time.

All knowledge gained during Draft stage, such as possible alternative approaches, their implications, pros
and cons etc. as well as the reasons for choosing the proposed approach MUST be summarized in the meta
document. The purpose of this rule is to prevent discussions or alternative proposals from reappearing once
they have been decided on.

When the author(s) and sponsors agree that the proposal is ready and that the meta document is objective and
complete, the coordinator MAY promote the proposal to Review stage. The promotion MUST be announced in a
thread on the Mailing List with the subject "[REVIEW] PSR-X: Title of the proposal". At this point, the
proposal MUST be merged into the "master" branch of the [official PHP-FIG "fig-standards" repository][repo].

> At this point, the author(s) transfer the ownership of the proposal to the sponsors. This is to [prevent
> the author(s) from blocking changes](https://groups.google.com/d/msg/php-fig/qHOrincccWk/HrjpQMAW4AsJ)
> that the other PHP-FIG members agree on.
>
> If the author(s) are not ready yet to pass ownership, they should continue working on the proposal and
> the meta document until they feel confident to do so.

### 2.3 Review

The goal of the Review stage is to involve the majority of the PHP-FIG members in getting familiar with
a proposal and to decide whether it is ready for an acceptance vote.

The goal is *not* to significantly change or enhance the proposal. If significant changes are needed, the
proposal MUST be moved back to Draft stage. The goal is also *not necessarily* to have every PHP-FIG member
agree with the approach chosen by the proposal. The majority in the final vote wins. The goal however *is*
to have all PHP-FIG members agree on the completeness and objectivity of the meta document.

> Individual members of the PHP-FIG should not be permitted to prevent a PSR from being published.

During Review, changes in both the proposal and the meta document are limited to wording, typos, clarification
etc. The sponsors SHOULD use their own judgement to control the scope of these changes, and MUST block
anything that is felt to be a fundamental change. The sponsors MUST make changes that the majority of the
PHP-FIG members agree on, even if they personally disagree.

> Sponsors must not block the development of the proposal.

In this stage, major additions to the meta document are strictly prohibited. If alternative approaches are
discovered that are not yet listed in the meta document, the coordinator MUST abort the Review by publishing
a thread titled "[CANCEL REVIEW] PSR-X: Title of the proposal" on the Mailing List, unless the final vote
has started already. However, the sponsors MAY choose to abort the vote and the Review even after that,
if they agree that this is necessary. The purpose of this rule is to give every PHP-FIG member the chance
to consider *all* known alternatives during the complete Review stage.

Unless a proposal is moved to Draft stage again, it MUST remain in Review stage for a minimum of two weeks.
This gives every PHP-FIG Member sufficient time to get familiar with and influence a proposal before the final
vote is called.

When the author(s) and sponsors agree that the proposal is ready to become a PSR, a final vote is called.
The coordinator MUST publish a thread on the Mailing List with the subject "[VOTE] PSR-X: Title of the proposal"
to announce the vote. The vote MUST adhere to [the voting protocol][voting].

### 2.4 Accepted

If the final vote passes, then the proposal officially becomes a PSR. At this point, it is assigned a PSR number
by incrementing the previous PSR number. The proposal itself is moved from `/proposed` to `/accepted` by a
PHP-FIG member with GitHub access and prefixed with its PSR number, such as "psr-4-autoloader.md". Comments MUST
be removed from this document, but a copy of the commented proposal MUST be kept in `/accepted/meta`, bearing
the suffix "-commented" (e.g. "psr-4-autoloader-commented.md"). The commented version can be used to interpret
the rules of the PSR in case of doubt.

The meta document of the proposal MUST also be moved to `/accepted/meta` and prefixed with the PSR number, for
example "psr-4-autoloader-meta.md".

## 3. Meta Document

### 3.1 Author(s) and Sponsors

Each PSR MUST contain author(s) and sponsors names listed in the document body. In the event that an author would
like to step down from their position mid-way then the a named author must listing the new author in the meta
document. Sponsors can also quit and have their names moved from the Sponsor section to the Contributor section.
A PSR can never progress unless there are two co-sponsors actively backing the PSR.

This does not need to be policed, if a vote is initiated with the name of an ex-sponsor (or a sponsor who does not
consider themselves active) on the document, that person will have a reasonable window of time to raise their
concern. In the instance that a vote is underway with a sponsor who does not consider themselves active (i.e they
have quit or been listed without permission) then they cans imply raise this on the mailing list and the vote
would be invalidated until a new sponsor has been put in place. This would need a two-week wait since the last vote
before it can be voted upon again - sticking to the two-week wait on votes which is the case for any and all votes,
as specified in the Voting Protocol.

### 3.2 Contributors

Anyone who feels like they have done a relevant amount of contribution should add themselves to the
meta document. Ideally anyone sending in a pull request during the Pre-Draft or Draft stages should go on here,
and anyone who feels like their review tweaks were relevant too. The author can use reasonable judgement for
this.

### 3.3 Example

This is an example template that can be used to build a meta document. 

    # PSR-X Meta Document

    ## Summary

    The purpose of this autoloader is to bla bla bla. More description than might go into the 
    summary, with potential prose and a little history might be helpful.

    ## Chosen Approach

    We have decided to build it this way, because we have noticed it to be common practise withing member 
    projects to do X, Y and Z. 

    ## Alternative Approaches

    ### Trent Reznor's Foo Proposal

    **Pros**

    * Brilliant idea
    * Good implementation

    **Cons**

    * Never left Pre-Draft
    * Author ignored feedback and lost sponsors

    ## Author(s)

    _**Note:** Order by last name. This is only ever one, maybe two people, but the author(s) will know who 
    they are._

    John Smith

    ## Sponsors

    _**Note:** The first two folks to agree to be the Sponsors. Authors add these names in place._

    Jimmy Cash
    Barbra Streisand

    ## Contributors

    _**Note:** Order by last name. Anyone can send in a PR, or authors can add these._

    Trent Reznor
    Jimmie Rodgers
    Kanye West

    ## Votes

    _**Note:** Order descending chronilogically._

    * **Pre-Draft -> Draft: ** http://groups.google.com...
    * **Draft -> Review: ** http://groups.google.com...
    * **Review -> Acceptance: ** http://groups.google.com...

    ## Relevant Links

    _**Note:** Order descending chronilogically._

    * [Formative IRC Conversation Gist]
    * [Mailing list thread poll to decide if Y should do Z]
    * [IRC Conversation Gist where everyone decided to rewrite things]
    * [Relevant Poll of existing method names in voting projects for new interface]

  [repo]: https://github.com/php-fig/fig-standards/tree/master
  [voting]: https://github.com/php-fig/fig-standards/blob/master/bylaws/001-voting-protocol.md
