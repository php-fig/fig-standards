# PSR Review Workflow

This document describes the workflow for proposing a PSR and having it published by the PHP-FIG.

**Note:** Throughout this article when you see "PSR-N", "N" refers to whatever number has been
assigned to the PSR in question.

## 1. Roles

**Author:** An author is actively involved in writing a PSR. A document may have multiple authors. None
of them are required to be voting members. Authors may step down from their position mid-way, but should
inform the PHP-FIG about this. If the author(s) of a proposal are missing for more than two months
without a note of absence, the sponsors may agree on new author(s).

**Sponsor:** A voting member who officially supports a proposal. Each PSR must have two sponsors that
must not include any of the authors. If a sponsor wants to quit or wants to become an author himself,
he should inform the authors and the PHP-FIG. In this case, a new sponsor need to be found. In the
instance that a vote is underway with a sponsor who does not consider themselves active listed in the
meta document, they should raise this on the Mailing List. The vote will then be invalidated until a
new sponsor has been put in place. A proposal can never progress unless there are two sponsors actively
backing the PSR.

> Two and not just one to prevent a single sponsor from making important decisions alone.

**Coordinator:** One of the sponsors is the coordinator of a PSR. The coordinator is in charge of the
voting process. He notes the starting and ending dates, the number of voting members at the start of the
vote, and the quorum count needed. He sends out reminders by whatever means he feels appropriate to drive
the vote. At the end of the voting period, he tallies the votes, notes if quorum was established, and
whether or not the application was accepted.

> **Note:** Copied from [Paul M. Jones' mail](https://groups.google.com/d/msg/php-fig/I0urcaIsEpk/uqQMb4bqlGwJ)

**Contributor:** Anyone who feels like they have done a relevant amount of contribution. Includes anyone
sending in a pull request during the Pre-Draft or Draft stages, anyone who feels like their review
tweaks were relevant too, former authors and sponsors who stepped down etc. In case of doubt, the voters
should use reasonable judgement to decide whether a contribution was relevant or not.

## 2. Stages

### 2.1 Pre-Draft

The goal of the Pre-Draft stage is to determine whether a majority of the PHP-FIG is interested in
publishing a PSR for a proposed concept.

The author(s) can work on the proposal anywhere they like, do whatever they like and come up with any
ideas they feel are within the scope of the PHP-FIG.

Once the proposal is considered ready by the author(s), it must be published in a fork of the [official
PHP-FIG "fig-standards" repo][repo]. The content of the proposal must be placed inside the `/proposed`
folder with a simple filename such as "autoload.md". Along with this document must be a meta document with
a suffix of "-meta" before the extension (e.g. "autoload-meta.md"). Markdown formatting must be used for
both documents. No PSR number is assigned to the proposal at this point.

With both the proposal and the meta document in the proposed folder, the author(s) must find their sponsors,
one of which must become the coordinator. The coordinator must initiate a vote to enquire whether the 
members of PHP-FIG are generally interested in publishing a PSR for the proposed subject, even if they
disagree with the details of the proposal. The coordinator must announce the vote on the Mailing List in 
a thread titled "[VOTE] Proposed: Title of the proposal". The vote must adhere to [the voting 
protocol][voting].

If the vote passes, the proposal officially enters Draft stage. The proposal receives a PSR number
auto-incremented from the last PSR in the `/accepted` folder of the [official PHP-FIG "fig-standards" 
repo][repo].

The author(s) may continue to work on the proposal during the complete voting period.

### 2.2 Draft

The goal of the Draft stage is to discuss and polish a PSR proposal up to the point that it can be
considered for review by the majority of the PHP-FIG members.

In Draft stage, the author(s) and any contributors may make any changes they see fit via pull requests,
comments on GitHub, Mailing List threads, IRC and similar tools. Change here is not limited by any strict
rules, and fundamental rewrites are possible if supported by the author(s). Alternative approaches may be
proposed and discussed at any time. If sponsors are convinced that an alternative proposal is superior
to the original proposal, then the alternative may replace the original. If the alternative builds upon the
original, authorship will be attributed to both the original author(s) and the author(s) of the alternative.
Otherwise, the author(s) of the alternative proposal become the new main author(s).

All knowledge gained during Draft stage, such as possible alternative approaches, their implications, pros
and cons etc. as well as the reasons for choosing the proposed approach must be summarized in the meta
document. The purpose of this rule is to prevent circular discussions or alternative proposals from 
reappearing once they have been decided on.

When the author(s) and sponsors agree that the proposal is ready and that the meta document is objective and
complete, the coordinator may promote the proposal to Review stage. The promotion must be announced in a
thread on the Mailing List with the subject "[REVIEW] PSR-N: Title of the proposal". At this point, the
proposal must be merged into the "master" branch of the [official PHP-FIG "fig-standards" repository][repo].

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
proposal must be moved back to Draft stage. The goal is also *not necessarily* to have every PHP-FIG member
agree with the approach chosen by the proposal. The majority in the acceptance vote wins. The goal however *is*
to have all PHP-FIG members agree on the completeness and objectivity of the meta document.

> Individual members of the PHP-FIG should not be permitted to prevent a PSR from being published.

During Review, changes in both the proposal and the meta document are limited to wording, typos, clarification
etc. The sponsors should use their own judgement to control the scope of these changes, and must block
anything that is felt to be a fundamental change. The sponsors must make changes that the majority of the
PHP-FIG members agree on, even if they personally disagree.

> Sponsors must not block the development of the proposal.

In this stage, major additions to the meta document are strictly prohibited. If alternative approaches are
discovered that are not yet listed in the meta document, the coordinator must abort the Review by publishing
a thread titled "[CANCEL REVIEW] PSR-N: Title of the proposal" on the Mailing List, unless the acceptance vote
has started already. However, the sponsors may choose to abort the vote and the Review even after that,
if they agree that this is necessary. The purpose of this rule is to give PHP-FIG members the chance
to consider *all* known alternatives during the Review stage.

Unless a proposal is moved to Draft stage again, it must remain in Review stage for a minimum of two weeks.
This gives every PHP-FIG Member sufficient time to get familiar with and influence a proposal before the final
vote is called.

When the author(s) and sponsors agree that the proposal is ready to become a PSR, an acceptance vote is called.
The coordinator must publish a thread on the Mailing List with the subject "[VOTE] PSR-N: Title of the proposal"
to announce the vote. The vote must adhere to [the voting protocol][voting].

### 2.4 Accepted

If the acceptance vote passes, then the proposal officially becomes a PSR. At this point, it is assigned a PSR number
by incrementing the previous PSR number. The proposal itself is moved from `/proposed` to `/accepted` by a
PHP-FIG member with GitHub access and prefixed with its PSR number, such as "PSR-4-autoloader.md". Comments must
be removed from this document, but a copy of the commented proposal must be kept in `/accepted/meta`, bearing
the suffix "-commented" (e.g. "PSR-4-autoloader-commented.md"). The commented version can be used to interpret
the rules of the PSR in case of doubt.

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
> whether they agree, but the author just happened to formulate the rule badly.

The meta document of the proposal must also be moved to `/accepted/meta` and prefixed with the PSR number, for
example "PSR-4-autoloader-meta.md".

## 3. Meta Document

The purpose of the meta document is to provide the high-level perspective of a proposal for the voters and to
give them objective information about both the chosen approach and any alternative approaches in order to make
an informed decision.

### 3.1 Summary

The "too long, didn't read". Summarizes the purpose and big picture of the proposal, possibly with a few
simple examples of how the author(s) imagine an implementation of the PSR to be used in practice.

### 3.2 Why Bother?

An argument for why the proposed topic should be specified in a PSR at all. Should include a list of positive
and negative implications of releasing this PSR. The purpose of this section is to convince voters to accept
the proposal as draft in the Pre-Draft->Draft voting.

### 3.3 Scope

A listing of both goals and non-goals that the PSR should achieve. The goals/non-goals should be specific and
measurable.

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

The names the

* authors
* sponsors (indicating which of them was coordinator)
* contributors (as defined in section 1)

of the PSR proposal, sorted alphabetically by last name in ascending order. If someone considers himself a
contributor but is not listed here, he should contact the author(s) and sponsors, including some proof about
his contribution. If the proof is valid, the contributor must be put on this list by one of the author(s)
or sponsors.

### 3.6 Template

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

    * Simple solution
    * Easy to implement in practice

    ### 4.2 Alternative: Trent Reznor's Foo Proposal

    The idea of this approach is to bla bla bla. Contrary to the chosen approach, we'd do X and not Y etc.

    We decided against this approach because X and Y.

    Pros:

    * ...

    Cons:

    * ...

    5. People
    ---------

    ### 5.1 Author(s)

    * John Smith

    ### 5.2 Sponsors

    * Jimmy Cash
    * Barbra Streisand (coordinator)

    ### 5.3 Contributors

    * Trent Reznor
    * Jimmie Rodgers
    * Kanye West

    6. Votes
    --------

    * **Pre-Draft -> Draft: ** http://groups.google.com...
    * **Acceptance Vote:** http://groups.google.com...

    7. Relevant Links
    -----------------

    _**Note:** Order descending chronologically._

    * [Formative IRC Conversation Gist]
    * [Mailing list thread poll to decide if Y should do Z]
    * [IRC Conversation Gist where everyone decided to rewrite things]
    * [Relevant Poll of existing method names in voting projects for new interface]

[repo]: https://github.com/php-fig/fig-standards/tree/master
[voting]: https://github.com/php-fig/fig-standards/blob/master/bylaws/001-voting-protocol.md
