# PHP Standard Recommendations

According to the [PSR Workflow Bylaw][workflow] each PSR has a status as it is being worked on. Once a proposal has passed the Entrance Vote it will be listed here as "Draft". Unless a PSR is marked as "Accepted" it is subject to change. Draft can change drastically, but Review will only have minor changes.

As also described in the [PSR Workflow Bylaw][workflow]. The Editor, or editors, of a proposal are the essentially the lead contributors and writers of the PSRs and they are supported by two voting members. Those voting members are the Coordinator who is responsible for managing the review stage and votes; and a second sponsor.

## Index by Status

### Accepted

| Num | Title                          | Editor                  |  Coordinator  | Sponsor        |
|:---:|--------------------------------|-------------------------|---------------|----------------|
| 1   | [Basic Coding Standard][psr1]  | Paul M. Jones           | _N/A_         | _N/A_          |
| 2   | [Coding Style Guide][psr2]     | Paul M. Jones           | _N/A_         | _N/A_          |
| 3   | [Logger Interface][psr3]       | Jordi Boggiano          | _N/A_         | _N/A_          |
| 4   | [Autoloading Standard][psr4]   | Paul M. Jones           | Phil Sturgeon | Larry Garfield |
| 6   | [Caching Interface][psr6]      | Larry Garfield          | Paul Dragoonis | Robert Hafner |
| 7   | [HTTP Message Interface][psr7] | Matthew Weier O'Phinney | Beau Simensen | Paul M. Jones  |

### Review

| Num | Title                          | Editor                  |  Coordinator   | Sponsor       |
|:---:|--------------------------------|-------------------------|----------------|---------------|

### Draft

| Num | Title                                | Editor(s)                      |  Coordinator            | Sponsor           |
|:---:|--------------------------------------|--------------------------------|-------------------------|-------------------|
| 5   | [PHPDoc Standard][psr5]              | Mike van Riel                  | Vacant                  | Vacant            |
| 8   | [Huggable Interface][psr8]           | Larry Garfield                 | Vacant                  | Paul M. Jones     |
| 9   | [Security Advisories][psr9]          | Lukas Kahwe Smith              | Korvin Szanto           | Larry Garfield    |
| 10  | [Security Reporting Process][psr10]  | Lukas Kahwe Smith              | Larry Garfield          | Korvin Szanto     |
| 11  | [Container Interface][psr11]         | Matthieu Napoli, David Négrier | Paul M. Jones           | Vacant            |
| 12  | [Extended Coding Style Guide][psr12] | Korvin Szanto                  | Alexander Makarov       | Robert Deutz      |
| 13  | [Hypermedia Links][psr13]            | Larry Garfield                 | Matthew Weier O'Phinney | Evert Pot         |

### Deprecated

| Num | Title                          | Editor                  |  Coordinator  | Sponsor        |
|:---:|--------------------------------|-------------------------|---------------|----------------|
| 0   | [Autoloading Standard][psr0]   | Matthew Weier O'Phinney | _N/A_         | _N/A_          |

## Numerical Index

| Status | Num | Title                                | Editor(s)                      |  Coordinator            | Sponsor           |
|--------|:---:|--------------------------------------|--------------------------------|-------------------------|-------------------|
| X      | 0   | [Autoloading Standard][psr0]         | _N/A_                          | _N/A_                   | _N/A_             |
| A      | 1   | [Basic Coding Standard][psr1]        | _N/A_                          | _N/A_                   | _N/A_             |
| A      | 2   | [Coding Style Guide][psr2]           | _N/A_                          | _N/A_                   | _N/A_             |
| A      | 3   | [Logger Interface][psr3]             | Jordi Boggiano                 | _N/A_                   | _N/A_             |
| A      | 4   | [Autoloading Standard][psr4]         | Paul M. Jones                  | Phil Sturgeon           | Larry Garfield    |
| D      | 5   | [PHPDoc Standard][psr5]              | Mike van Riel                  | Vacant                  | Vacant            |
| A      | 6   | [Caching Interface][psr6]            | Larry Garfield                 | Paul Dragoonis          | Robert Hafner     |
| A      | 7   | [HTTP Message Interface][psr7]       | Matthew Weier O'Phinney        | Beau Simensen           | Paul M. Jones     |
| D      | 8   | [Huggable Interface][psr8]           | Larry Garfield                 | Vacant                  | Paul M. Jones     |
| D      | 9   | [Security Advisories][psr9]          | Lukas Kahwe Smith              | Korvin Szanto           | Larry Garfield    |
| D      | 10  | [Security Reporting Process][psr10]  | Lukas Kahwe Smith              | Larry Garfield          | Korvin Szanto     |
| D      | 11  | [Container Interface][psr11]         | Matthieu Napoli, David Négrier | Paul M. Jones           | Vacant            |
| D      | 12  | [Extended Coding Style Guide][psr12] | Korvin Szanto                  | Alexander Makarov       | Robert Deutz      |
| D      | 13  | [Hypermedia Links][psr13]            | Larry Garfield                 | Matthew Weier O'Phinney | Evert Pot         |

_**Legend:** A = Accepted | D = Draft | R = Review | X = Deprecated_

[workflow]: http://www.php-fig.org/bylaws/psr-workflow/
[psr0]: /psr/psr-0/
[psr1]: /psr/psr-1/
[psr2]: /psr/psr-2/
[psr3]: /psr/psr-3/
[psr4]: /psr/psr-4/
[psr5]: https://github.com/phpDocumentor/fig-standards/tree/master/proposed
[psr6]: /psr/psr-6/
[psr7]: /psr/psr-7/
[psr8]: https://github.com/php-fig/fig-standards/blob/master/proposed/psr-8-hug/psr-8-hug.md
[psr9]: https://github.com/php-fig/fig-standards/blob/master/proposed/security-disclosure-publication.md
[psr10]: https://github.com/php-fig/fig-standards/blob/master/proposed/security-reporting-process.md
[psr11]: https://github.com/container-interop/fig-standards/blob/master/proposed/container.md
[psr12]: https://github.com/php-fig/fig-standards/blob/master/proposed/extended-coding-style-guide.md
[psr13]: https://github.com/php-fig/fig-standards/blob/master/proposed/links.md
