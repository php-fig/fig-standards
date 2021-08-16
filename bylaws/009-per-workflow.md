# PHP Evolving Recommendation Workflow

## Formation

The goal of the Pre-Draft stage is to determine whether a majority of the PHP FIG is interested in establishing a PER Working Group for a proposed concept.

Interested parties may discuss a possible proposal, including possible implementations, by whatever means they feel is appropriate. That includes informal discussion on official FIG discussion mediums of whether or not the idea has merit and is within the scope of the PHP FIG's goals.

Once those parties have determined to move forward, they must form a Limited Working Group.  However, the Core Committee may require that a specific PER requires a Full Working Group in cases of particularly high impact to the larger ecosystem in order to encourage greater community participation.

The proposal is not required to be fully developed at this point, although that is permitted. At minimum, it must include a statement of the problem to be solved, the scope of the PER Working Group, and the artifacts it expects to produce.

The Editor (for a Limited Working Group) or Sponsor (for a Full Working Group) may then call for an Entrance Vote of the Core Committee to enquire whether the Core Committee is generally interested in maintaining a PER for the proposed subject, even if they disagree with the details of the proposal.

If the vote passes, the proposal officially enters Draft stage. The proposal is given a unique descriptive name (such as "Coding Standards", "Documentation", etc.).

## Development

Once established, the PER Working Group may collaborate in whatever way they see fit via pull requests, comments on GitHub, Mailing List threads, real-time chat, and similar tools.  The Working Group must maintain a meta document including considered but rejected approaches, the reasons for various decisions, etc.  The meta document is considered part of the Working Group's output, and must be tagged along with the main artifacts of the Working Group.

Discussions are public and anyone, regardless of FIG affiliation, is welcome to offer constructive input. The Editor has final authority on changes made to the Working Group's output.

## Pre-Releases

Prior to the 1.0.0 release of the PER, the Editor may make alpha, beta, or 0.x releases of any artifact at any time.  These releases are explicitly unstable, and MUST be treated as unsupported by FIG or the Working Group.  They are not subject to Core Committee approval.

## Releases

The Editor of a PER artifact may release bugfix releases at any time.

For any new release that would be minor or major according to Semantic Versioning (whether text or code), the Editor must first call for a Readiness Vote of the Working Group.  If the Readiness Vote passes, the Editor in capacity as Maintainer may inform the Core Committee of an Intent to Release as specified in the Maintainers section.

The Core Committee must approve any major releases from 1.0.0 onwards.
