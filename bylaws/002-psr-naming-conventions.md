Naming conventions for code released by PHP-FIG
-----------------------------------------------

1. Interfaces MUST be suffixed by `Interface`: e.g. `PhpFig\Foo\BarInterface`.
2. Abstract classes MUST be prefixed by `Abstract`: e.g. `PhpFig\Foo\AbstractBar`.
3. Traits MUST be suffixed by `Trait`: e.g. `PhpFig\Foo\BarTrait`.
4. PSR-0, 1 and 2 MUST be followed.
5. The vendor namespace MUST be `PhpFig`.
6. There MUST be a package/second-level namespace in relation with the FIG
   recommendation that covers the code.
7. Composer package MUST be named `php-fig/<package>` e.g. `php-fig/log`. If they
   require an implementation as a virtual package it MUST be named
   `php-fig/<package>-implementation` and be required with a specific version like
   `1.0.0`. Implementors of that FIG recommendation can then provide
   `"php-fig/<package>-implementation": "1.0.0"` in their package to satisfy that
   requirement. Specification changes via further FIG recommendations should only
   lead to a new tag of the `php-fig/<package>` package, and an equal version bump
   of the implementation being required.
