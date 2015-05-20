## Introduction

Unfortunately with all software development, security vulnerabilities are a
fact of life that need to be addressed. It is important that when security
vulnerabilities are found that researchers have an easy channel to the
projects in question allowing them to disclose the issue to a controlled
group of people.

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