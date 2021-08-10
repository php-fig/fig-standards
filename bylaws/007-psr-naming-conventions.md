Naming conventions for code released by PHP FIG
===============================================

1. Interfaces MUST be suffixed by `Interface`: e.g. `Psr\Foo\BarInterface`.
2. Abstract classes MUST be prefixed by `Abstract`: e.g. `Psr\Foo\AbstractBar`.
3. Traits MUST be suffixed by `Trait`: e.g. `Psr\Foo\BarTrait`.
4. PSR-1, 4, and 12 MUST be followed.
5. The vendor namespace for code released as part of a PSR MUST be `Psr`.
6. The vendor namespace for code released as part of a PER MUST be `Fig`.
7. There MUST be a package/second-level namespace in relation with the PSR or PER that
   covers the code.
8. Composer packages for PSRs MUST be named `psr/<package>` e.g. `psr/log`. Composer packages for PERs MUST be named `fig/<package>`.  If they
   require an implementation as a virtual package it MUST be named
   `psr/<package>-implementation` and be required with a specific version like
   `1.0.0`. Implementors of that PSR can then provide
   `"psr/<package>-implementation": "1.0.0"` in their package to satisfy that
   requirement. Specification changes via further PSRs should only lead to a new
   tag of the `psr/<package>` package, and an equal version bump of the
   implementation being required.
9. Special lightweight utility packages MAY be produced alongside PSRs and
   interfaces and be maintained and updated after the PSR has been accepted. These
   MUST be under the vendor namespace `Fig`.
