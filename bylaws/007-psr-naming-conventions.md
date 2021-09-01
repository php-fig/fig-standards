Naming conventions for code released by PHP FIG
===============================================

1. Interfaces MUST be suffixed by `Interface`: e.g. `Psr\Foo\BarInterface`.
2. Abstract classes MUST be prefixed by `Abstract`: e.g. `Psr\Foo\AbstractBar`.
3. Traits MUST be suffixed by `Trait`: e.g. `Psr\Foo\BarTrait`.
4. PSR-1, 4, and 12 MUST be followed.
5. For code released as part of a PSR, the vendor namespace MUST be `Psr` and the Composer package name MUST be `psr/<package>` (e.g., `psr/log`).
6. For code released as part of a PER or any other Auxiliary Resources, the vendor namespace MUST be `Fig` and the Composer package name MUST be `fig/<package>` (e.g., `fig/cache-util`).
7. There MUST be a package/second-level namespace in relation with the PSR or PER that  covers the code.
8. Implementations of a given PSR or PER SHOULD declare a `provides` key in their `composer.json` file in the form `psr/<package>-implementation` with a version number that matches the PSR being implemented.  For example, `"psr/<package>-implementation": "1.0.0"`.