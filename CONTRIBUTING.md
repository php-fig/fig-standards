# Contributing to the PHP-FIG

Anybody who subscribes to the Google Group, is part of the PHP-FIG. As soon as
you subscribe to the [mailing list](http://groups.google.com/group/php-fig/)
and/or join the [IRC channel](https://www.php-fig.org/irc/) you are a PHP-FIG
Community Member, who can influence standards, make suggestions, give feedback,
etc. Only PHP-FIG Voting Members can start or participate in votes, but the
discussion and formation stages involve everyone.

See the [PHP-FIG FAQ](https://www.php-fig.org/faqs/) for more information.

## Licensing

By contributing code you agree to license your contribution under the MIT
license.

By contributing documentation, examples, or any other non-code assets you agree
to license your contribution under the CC BY 3.0 license. Attribution shall be
given according to the current bylaws of this group.

## Merge & Access Policy

All Editors, Coordinators and Sponsors of specifications in draft & review stage
have access to push to this (php-fig/fig-standards) repository; subject to
secretary discretion

All Editors, Coordinators and Sponsors of specifications have push access to utility
and interface repositories and retrain this even after acceptance; subject to secretary
discretion.

The master branch of all repositories are protected and therefore cannot be forced
pushed to.

Secretaries have, and are the only ones to have, full administrative access to all
repositories and to the github organisation.

### Guidelines for merging

* Never force push to any branch on a repository in the php-fig organisation
* All changes must go through pull requests, a commit should never be pushed
directly (excluding initial commits on new interface/util repositories) to master
* All pull requests relating to a draft PSR must be approved (with a formal +1
comment) or merged by the PSR Editor, except in the review phase when the coordinator
should seek comment from the editor, but merging is at the coordinator's discretion
* You must never merge a pull request that affects any file in the repository
other than those you are on a working group for; you should request a secretary
or member of that working group (mention @php-fig/psr-x) do so
* You should never merge your own pull request
* A change should never be merged to an accepted PSR without approval from
secretaries, who will attempt to seek confirmation from the former Editors
* A change to bylaws shouldn't be merged by anyone other than a secretary
* Pull requests may be triaged (have labels applied) by anyone with push access,
no matter which PSR they are on the working group for or which PSR it affects; but
they cannot close a pull request or issue affecting other PSRs
* After approval of a specification, all merges to an interface or utility repository
must be approved by a secretary; who is required to give suitable notice and seek
comment from the working group team, but it is not required that they approve
* Tags on utility and interface repositories should be created and PGP signed by
Secretaries, who should publish their PGP public keys and register them on github

These guidelines are subject to exceptions and changes at secretaries discretion.
Should you have any questions please contact the secretaries on info [at] php-fig
[dot] org. Failure to comply with guidelines will result in revokation of merge
access. Merge access is a privilege and not a right.

## Tagging

Tagging on utility and interface repository should be done regularly, ideally after
every merge, or every batch of merges after PSR approval.

Versioning should follow semantic versioning and primarily just be simple patch
fix increments (following semantic versioning). The first 1.0.0 tag should be
created on PSR approval.

All tags should be PGP signed.

A changelog should be provided including a list of changes, crediting the
contributor and referencing the pull request/issue.
