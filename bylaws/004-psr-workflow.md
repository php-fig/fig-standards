# PSR Review Workflow

Each PSR must have an original author and potentially a co-author in some cases, neither of which are 
required to be a voting member. Each PSR must also have two co-sponsors, both of whom are voting members 
and one of whom is a "coordinator" (they can switch if needs be).

### 0.) Pre-Draft

The author(s) can work on this anywhere they like, do whatever they like and come up with any ideas they 
feel are within the scope of the PHP-FIG. Once the Pre-Draft content is considered ready by the author(s)
a new fork of the official PHP-FIG ["fig-standards" repo][repo] should be created.

The content of this Pre-Draft PSR should be placed inside the `/proposed` folder with a simple filename. 
A nickname like "autoload.md" should suffice, and markdown formatting must be used. No number is assigned 
to the PSR at this point.

Along with this PSR file (Eg: "autoload.md") must be a meta document with a suffix of `-meta` before the 
extension (Eg: "autoload-meta.md"). 

With both of these documents in the proposed folder, the author(s) can start to look for their sponsors, who 
will then initiate a vote to make it reach "Draft".

### 1.) Draft

The author(s) and any contributors can make any changes they see fit via pull requests, comments on GitHub, 
mailing list threads and IRC. Change here is not limited by any strict rules, and funamental rewrites are 
possible if supported by the author(s).

### 2.) Review

Once a PSR has reached Review the author must create a Pull Request on the official PHP-FIG ["fig-standards" 
repo][repo] against the `master` and the file will remain in the `/proposed` folder with the same name.
PSR at this point.

While a PSR remains in Review, changes are limited to wording, typos, clarification, minimal rule addition, 
etc. The author(s) and sponsors may use their own judgement to control the scope of these changes, and 
block anything that is felt to be a fundamental change.

### 3.) Accepted

An Acceptance vote is called when the coordinator feels the Review document is ready to have it's final vote. 
If this vote passes then the Pull Request is merged, and the document is moved by a FIG member with 
GitHub access from `/proposed` to `/accepted` and assigned a PSR number by incrementing the preview PSR number.

## Progress

Initially a vote must be called by the "coordinator" to get the PSR into Draft status, before that it's just 
a third-party concept.

To progress from Draft to Review, and later Review to Accepted, a vote must be called by the "coordinator" 
for each stage. If this vote is passed it will progress forwards to the next stage.

Each vote must stick to the [Voting Protocol][voting].

If the vote fails the author(s) can use feedback to improve and keep it in Review, then try again for 
another vote later on. The author(s) also have the option of going back to the drawing board if their ideas 
are slammed multiple times, meaning they need to tell folks they are back in Draft status.

## Meta Document

Each PSR must also contain a "Meta Document" to hold relevant information such as who the author(s) are, 
who the sponsors are, which one is the "coordinator" and list any relevant contributors.

### Author(s) and Sponsors

Each PSR must contain author(s) and sponsors names listed in the document body. In the event that an author would 
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

### Contributors

Anyone who feels like they have done a relevant amount of contribution should add themselves to the 
meta document. Ideally anyone sending in a pull request during the Pre-Draft or Draft stages should go on here,
and anyone who feels like their review tweaks were relevant too. The author can use reasonable judgement for 
this.

### Example

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
