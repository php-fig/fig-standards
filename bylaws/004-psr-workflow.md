# PSR Review Workflow

Each PSR must have an original author and potentially a co-author in some cases, neither of which are 
required to be a voting member. Each PSR must also have two co-sponsors, both of whom are voting members 
and one of whom is a "coordinator" (they can switch if needs be).

### 0.) Pre-Draft

The author(s) can work on this anywhere they like, do whatever they like and come up with any ideas they 
feel are within the scope of the PHP-FIG. Once they are ready to try and get this voted into being a "Draft" 
PSR they should post on the Mailing List and try to find their two sponsors.

### 1.) Draft

The author(s) must now set up a fork of the official PHP-FIG ["fig-standards" repo][repo] and place the 
content of this PSR into the `/proposed` folder with a simple filename. A nickname like "autoload.md" should 
suffice, and markdown formatting must be used. No number is assigned to the PSR at this point.

This is also the stage in which a meta-document must be built. See below for examples and explanations.

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

Each PSR must contain author(s) and sponsors names listed in the document body. These can be changed if 
people swap out, but a PSR can never progress unless there are two co-sponsors actively backing the PSR. 

This does not need to be policed, if a vote is initiated with the name of a sponsor on the document, 
that sponsor will have plenty of time to raise their concern. In these instances a vote would be invalidated 
and a new sponsor must be found before it can be voted upon again - sticking to the two-week wait on votes 
which is the case for any and all votes, as specified in the Voting Protocol.

### Contributors

Anyone who feels like they have done a relevant amount of contribution should add themselves to the 
meta document. Ideally anyone sending in a pull request during the Pre-Draft or Draft stages should go on here,
and anyone who feels like their review tweaks were relevant too. The author can use reasonable judgement for 
this.

Also if a sponsors drops support but has been working on the PSR for a while, it would be common sense for them or 
an author to move that sponsors name to Contributors.

### Example

This is an example template that can be used to build a meta document. 

    # PSR-X Meta Document

    ## Summary

    The purpose of this autoloader is to bla bla bla. More description than might go into the 
    summary, with potential prose and a little history might be helpful.

    ## Author(s)

    John Smith

    ## Sponsors

    Jimmy Cash
    Barbra Streisand

    ## Contributors

    Trent Reznor
    Jimmie Rodgers
    Kanye West

    ## Votes

    * **Pre-Draft -> Draft: ** http://groups.google.com...
    * **Draft -> Review: ** http://groups.google.com...
    * **Review -> Acceptance: ** http://groups.google.com...

    ## Relevant Links

    _Note: Order this stuff by date._

    * [Formative IRC Conversation Gist]
    * [Mailing list thread poll to decide if Y should do Z]
    * [IRC Conversation Gist where everyone decided to rewrite things]


  [repo]: https://github.com/php-fig/fig-standards/tree/master
  [voting]: https://github.com/php-fig/fig-standards/blob/master/bylaws/001-voting-protocol.md
