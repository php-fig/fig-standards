# PHP Standard Recommendations

According to the [PSR Workflow Bylaw][workflow] each PSR has a status as it is being worked on. Once a proposal has passed the Entrance Vote it will be listed here as "Draft". Unless a PSR is marked as "Accepted" it is subject to change. Draft can change drastically, but Review will only have minor changes.

As also described in the [PSR Workflow Bylaw][workflow]. The Editor, or editors, of a proposal are the essentially the lead contributors and writers of the PSRs and they are supported by two voting members. Those voting members are the Coordinator who is responsible for managing the review stage and votes; and a second sponsor.

## Index by Status

### Accepted

| Num | Title                          | Editor                         |  Coordinator            | Sponsor          |
|:---:|--------------------------------|--------------------------------|-------------------------|------------------|
| 1   | [Basic Coding Standard][psr1]  | Paul M. Jones                  | _N/A_                   | _N/A_            |
| 2   | [Coding Style Guide][psr2]     | Paul M. Jones                  | _N/A_                   | _N/A_            |
| 3   | [Logger Interface][psr3]       | Jordi Boggiano                 | _N/A_                   | _N/A_            |
| 4   | [Autoloading Standard][psr4]   | Paul M. Jones                  | Phil Sturgeon           | Larry Garfield   |
| 6   | [Caching Interface][psr6]      | Larry Garfield                 | Paul Dragoonis          | Robert Hafner    |
| 7   | [HTTP Message Interface][psr7] | Matthew Weier O'Phinney        | Beau Simensen           | Paul M. Jones    |
| 11  | [Container Interface][psr11]   | Matthieu Napoli, David Négrier | Matthew Weier O'Phinney | Korvin Szanto    |
| 13  | [Hypermedia Links][psr13]      | Larry Garfield                 | Matthew Weier O'Phinney | Marc Alexander   |
| 15  | [HTTP Handlers][psr15]         | Woody Gilk                     | _N/A_                   | Matthew Weier O'Phinney |
| 16  | [Simple Cache][psr16]          | Paul Dragoonis                 | Jordi Boggiano          | Fabien Potencier |

### Review

| Num | Title                                | Editor(s)                      |
|:---:|--------------------------------------|--------------------------------|
| 12  | [Extended Coding Style Guide][psr12] | Korvin Szanto                  |

### Draft

| Num | Title                                | Editor(s)                      |
|:---:|--------------------------------------|--------------------------------|
| 14  | [Event Manager][psr14]               | Larry Garfield                 |
| 17  | [HTTP Factories][psr17]              | Woody Gilk                     |
| 18  | [HTTP Client][psr18]                 | Tobias Nylhom                  |

### Abandoned

| Num | Title                                | Editor(s)                      |
|:---:|--------------------------------------|--------------------------------|
| 5   | [PHPDoc Standard][psr5]              | Mike van Riel                  |
| 8   | [Huggable Interface][psr8]           | Larry Garfield                 |
| 9   | [Security Advisories][psr9]          | Michael Hess                   |
| 10  | [Security Reporting Process][psr10]  | Michael Hess                   |

### Deprecated

| Num | Title                          | Editor                  |
|:---:|--------------------------------|-------------------------|
| 0   | [Autoloading Standard][psr0]   | Matthew Weier O'Phinney |

## Numerical Index

| Status | Num | Title                                | Editor(s)                      |
|--------|:---:|--------------------------------------|--------------------------------|
| X      | 0   | [Autoloading Standard][psr0]         | Matthew Weier O'Phinney        |
| A      | 1   | [Basic Coding Standard][psr1]        | Paul M. Jones                  |
| A      | 2   | [Coding Style Guide][psr2]           | Paul M. Jones                  |
| A      | 3   | [Logger Interface][psr3]             | Jordi Boggiano                 |
| A      | 4   | [Autoloading Standard][psr4]         | Paul M. Jones                  |
| B      | 5   | [PHPDoc Standard][psr5]              | Mike van Riel                  |
| A      | 6   | [Caching Interface][psr6]            | Larry Garfield                 |
| A      | 7   | [HTTP Message Interface][psr7]       | Matthew Weier O'Phinney        |
| B      | 8   | [Huggable Interface][psr8]           | Larry Garfield                 |
| B      | 9   | [Security Advisories][psr9]          | Michael Hess                   |
| B      | 10  | [Security Reporting Process][psr10]  | Michael Hess                   |
| A      | 11  | [Container Interface][psr11]         | Matthieu Napoli, David Négrier |
| R      | 12  | [Extended Coding Style Guide][psr12] | Korvin Szanto                  |
| A      | 13  | [Hypermedia Links][psr13]            | Larry Garfield                 |
| D      | 14  | [Event Manager][psr14]               | Larry Garfield                 |
| A      | 15  | [HTTP Handlers][psr15]               | Woody Gilk                     |
| A      | 16  | [Simple Cache][psr16]                | Paul Dragoonis                 |
| D      | 17  | [HTTP Factories][psr17]              | Woody Gilk                     |
| D      | 18  | [HTTP Client][psr18]                 | Tobias Nyholm                  |

**Legend:** A = Accepted | D = Draft | R = Review | X = Deprecated | B = Abandoned

[workflow]: https://github.com/php-fig/fig-standards/blob/master/bylaws/002-psr-workflow.md
[psr0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[psr1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[psr2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[psr3]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
[psr4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-meta.md
[psr5]: https://github.com/phpDocumentor/fig-standards/tree/master/proposed/phpdoc.md
[psr6]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-6-cache.md
[psr7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md
[psr8]: https://github.com/php-fig/fig-standards/blob/master/proposed/psr-8-hug/
[psr9]: https://github.com/php-fig/fig-standards/blob/master/proposed/security-disclosure-publication.md
[psr10]: https://github.com/php-fig/fig-standards/blob/master/proposed/security-reporting-process.md
[psr11]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md
[psr12]: https://github.com/php-fig/fig-standards/blob/master/proposed/extended-coding-style-guide.md
[psr13]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-13-links.md
[psr14]: https://github.com/php-fig/fig-standards/blob/master/proposed/event-manager.md
[psr15]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-15-request-handlers.md
[psr16]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md
[psr17]: https://github.com/php-fig/fig-standards/tree/master/proposed/http-factory/
[psr18]: https://github.com/php-fig/fig-standards/tree/master/proposed/http-client/
