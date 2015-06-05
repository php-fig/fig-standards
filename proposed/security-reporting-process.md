## Introduction

There are two aspects with dealing with security issues: One is the process
by which security issues are reported and fixed in projects, the other
is how the general public is informed about the issues and any remedies
available. While PSR-10 addresses the later, this PSR, ie. PSR-9, deals with
the former. So the goal of PSR-9 is to define the process by which security
researchers and report security vulnerabilities to projects. It is important
that when security vulnerabilities are found that researchers have an easy
channel to the projects in question allowing them to disclose the issue to a
controlled group of people.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

The goal of this PSR is to give researchers, project leads, upstream project
leads and end users a defined and structured process for disclosing security
vulnerabilities.

## Security Disclosure Process Discovery

Every project MUST provide a link to its security disclosure process in
an obvious place. Ideally this should be on the root page the main domain of
the given project. This MAY be a sub-domain in case it is a sub-project of a
larger initiative. The link MAY use the custom link relation
``php-vuln-reporting``, ie. for example
``<link rel="php-vuln-reporting" href="http://example.org/security"/>``.

Projects SHOULD ideally make the location prominent itself
by either creating a dedicated sub-domain like ``http://security.example.org``
or by making it a top level directory like ``http://example.org/security``.
Alternatively projects MAY also simply reference this document, ie. PSR-9.
By referencing PSR-9 a project basically states that they follow the
default procedures as noted in the section "Default Procedures" towards
the end of this document. Projects MUST list the variables noted at the start
of that section in this reference (ie. project name, project domain, etc.).
Projects MAY choose to list any part of the procedures that is not a MUST
which they choose to omit.

Note that projects MAY not have a dedicated domain. For example a project
hosted on Github, Bitbucket or other service should still ensure that the
process is referenced on the landing page, ie. for example
http://github.com/example/somelib should ensure that the default branch
has a README file which references the procedures used so that it is
automatically displayed.

If necessary projects MAY have different disclosure process
for different major version number. In this case one URL MUST be provided
for each major version. In the case a major version is no longer receiving
security fixes, instead of an URL a project MAY opt to instead simply
note that the version is no longer receiving security fixes.

## Security Disclosure Process

Every project MUST provide an email address in their security disclosure
process description as the ``contact email address``. Projects SHALL NOT
use contact forms.

**TODO**: Add more things found here https://groups.google.com/d/msg/php-fig-psr-9-discussion/puGV_X0bj_M/Jr_IAS40StsJ?

## Default Procedures

* ``[project name]`` denotes the name on which the project uses to identify itself.
* ``[project domain]`` denotes the main (sub)domain on which the project relies.

If not specified otherwise, the ``contact email address`` is ``security@[project domain]``.

**TODO**: Add more things noted in the previous section