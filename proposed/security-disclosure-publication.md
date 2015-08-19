## Introduction

There are two aspects with dealing with security issues: One is the process
by which security issues are reported and fixed in projects, the other
is how the general public is informed about the issues and any remedies
available. While PSR-9 addresses the former, this PSR, ie. PSR-10, deals with
the later. So the goal of PSR-10 is to define how security issues are disclosed
to the public and what format such disclosures should follow. Especially today
where PHP developers are sharing code across projects more than ever, this PSR
aims to ease the challenges in keeping an overview of security issues in all
dependencies and the steps required to address them.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

The goal of this PSR is to give project leads a clearly defined approach
to enabling end users to discover security disclosures using a clearly
defined structured format for these disclosures.

## Disclosure Discovery

Every project MUST provide a link to its security vulnerability database in
an obvious place. Ideally this should be on the root page of the main domain of the given
project. This MAY be a sub-domain in case it is a sub-project of a larger
initiative. If the project has a dedicated page for its disclosure process
discovery then this is also considered a good place for this link.
The link MAY use the custom link relation ``php-vuln-disclosures``,
ie. for example
``<link rel="php-vuln-disclosures" href="http://example.org/disclosures"/>``.

Note that projects MAY choose to host their disclosure files on a domain
other than their main project page. It is RECOMMENDED to not store the
disclosures in a VCS as this can lead to the confusions about which branch
is the relevant branch. If a VCS is used then additional steps SHOULD be taken
to clearly document to users which branch contains all vulnerabilities for
all versions. If necessary projects MAY however split vulnerability disclosure
files by major version number. In this case again this SHOULD be clearly
documented.

## Disclosure Format

The disclosure format is based on Atom [1], which in turn is based on XML. It
leverages the "The Common Vulnerability Reporting Framework (CVRF) v1.1" [2].
Specifically it leverages its dictionary [3] as its base terminology.

**TODO**: Should we also provide a JSON serialization to lower the bar for projects.
Aggregation services can then spring up to provide an Atom representation of
these disclosures in JSON format.

The Atom extensions [4] allow a structured description of the vulnerability to
enable automated tools to determine if installed is likely affected by the
vulnerability. However human readability is considered highly important and as
such not the full CVRF is used.

**TODO**: Review the Atom format and the supplied XSD

Note that for each vulnerability only a single entry MUST be created. In case
any information changes the original file MUST be updated along with the last
update field.

Any disclosure uses ``entryType`` using the following tags from the Atom
namespace (required tags are labeled with "MUST"):

* title (short description of the vulnerability and affected versions, MUST)
* summary (description of the vulnerability)
* author (contact information, MUST)
* published (initial publication date, MUST)
* updated (date of the last update)
* link (to reference more information)
* id (project specific vulnerability id)

In addition the following tags are added:

* reported (initial report date)
* reportedBy (contact information for the persons or entity that initially reported the vulnerability)
* resolvedBy (contact information for the persons or entity that resolved the vulnerability)
* name (name of the product, MUST)
* cve (unique CVE ID)
* cwe (unique CWE ID)
* severity (low, medium high)
* affected (version(s) using composer syntax [5])
* status (open, in progress, disputed, completed, MUST)
* remediation (textual description for how to fix an affected system)
* remediationType (workaround, mitigation, vendor fix, none available, will not fix)
* remediationLink (URL to give additional information for remediation)

[1] https://tools.ietf.org/html/rfc4287
[2] http://www.icasi.org/cvrf-1.1
[3] http://www.icasi.org/cvrf-1.1-dictionary
[4] security-disclosure-publication.xsd
[5] https://getcomposer.org/doc/01-basic-usage.md#package-versions
