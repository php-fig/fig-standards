## Introduction

Unfortunately with all software development, security vulnerabilities are a
fact of life that need to be addressed. It is important that when security
vulnerabilities are found that researchers have an easy channel to the
projects in question allowing them to disclose the issue to a controlled
group of people. Furthermore the process how a reported issues is then
solved and the solution and information provided to the general public must
be clear to all parties involved: the researcher, the project leads and
the user base. Especially today where PHP developers are sharing code across
projects more than ever, it also adds another dimension: how to deal with
upstream projects to ensure they have sufficient time to prepare themselves.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

The goal of this PSR is to give researchers, project leads, upstream project
leads and end user a defined and structured process for disclosing security
vulnerabilities.

## Security Disclosure Process Discovery

Every project MUST provide a link to its security disclosure process in
an obvious place. Ideally this should be on the main domain of the given
project. This MAY be a sub-domain in case it is a sub-project of a larger
initiative. The link MAY use the custom link relation
``php-disclosure-process``, ie. for example
``<link rel="php-disclosure-process" href="http://example.org/security"/>``.

Projects SHOULD ideally make the location prominent itself
by either creating a dedicated sub-domain like ``http://security.example.org``
or by making it a top level directory like ``http://example.org/security``.
Alternatively projects MAY also simply reference this document, ie. PSR-9.

Note that projects MAY not have a dedicated domain. For example a project
hosted on Github, Bitbucket or other service should still ensure that the
process is referenced on the landing page, ie. for example
http://github.com/example/somelib should ensure that the default branch
has a README file which references the procedures used so that it is
automatically displayed.

If necessary projects MAY have different disclosure process
for different major version number. In this case one URL SHOULD be provided
for each major version. In the case a major version is no longer receiving
security fixes, instead of an URL a project MAY opt to instead simply
note that the version is no longer receiving security fixes.

## Security Disclosure Process

Every project MUST provide an email address in their security disclosure
process description. If not specified otherwise, this email address is
``security@[project domain]``. Projects SHALL NOT use contact forms.

...?

## Disclosure Discovery

Every project MUST provide a link to its security vulnerability database in
an obvious place. Ideally this should be on the main domain of the given
project. This MAY be a sub-domain in case it is a sub-project of a larger
initiative. If the project has a dedicated page for its disclosure process
discovery then this is also considered a good place for this link.
The link MAY use the custom link relation ``php-vuln-disclosures``,
ie. for example
``<link rel="php-vuln-disclosures" href="http://example.org/disclosures"/>``.

Note that projects MAY choose to host their disclosure files on a domain
other than their main project page. It is RECOMMENDED to not store the
disclosures in a VCS as this can lead to the confusions about which branch
is the relevant branch. If a VCS is used then additional steps should be taken
to clearly document to users which branch contains all vulnerabilities for
all versions. If necessary projects MAY however split vulnerability disclosure
files by major version number. In this case

## Disclosure Format

The disclosure format is based on Atom [1], which in turn is based on XML. It
leverages the "The Common Vulnerability Reporting Framework (CVRF) v1.1" [2].
Specifically it leverages its dictionary [3] as its base terminology.

The Atom extensions allow a structured description of the vulnerability to
enable automated tools to determine if installed is likely affected by the
vulnerability. However human readability is considered highly important and as
such not the full CVRF is used.

Note that for each vulnerability only a single entry should be created. In case
any information changes the original file MUST be updated along with the last
update field.

Any disclosure uses ``entryType`` using the following tags from the Atom namespace:

* title (short description of the vulnerability and affected versions)
* summary (description of the vulnerability)
* author (contact information)
* published (initial publication date)
* updated (date of the last update)
* link (to reference more information)
* id (project specific vulnerability id)

In addition the following tags are added:

* name (name of the product)
* cve (unique CVE ID)
* cwe (unique CWE ID)
* severity (low, medium high)
* affected (version(s) using composer syntax [4])
* status (open, in progress, disputed, completed)
* remediation (textual description for how to fix an affected system)
* remediationType (workaround, mitigation, vendor fix, none available, will not fix)
* remediationLink (URL to give additional information for remediation)

[1] https://tools.ietf.org/html/rfc4287
[2] http://www.icasi.org/cvrf-1.1
[3] http://www.icasi.org/cvrf-1.1-dictionary
[4] https://getcomposer.org/doc/01-basic-usage.md#package-versions