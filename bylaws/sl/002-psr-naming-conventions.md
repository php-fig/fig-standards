Konvencije poimenovanja za kodo izdano pod PHP-FIG
==================================================

1. Vmesniki MORAJO imeti pripono `Interface`: npr. `Psr\Foo\BarInterface`.
2. Abstraktni razredi MORAJO imeti predpono `Abstract`: npr. `Psr\Foo\AbstractBar`.
3. Lastnosti oz. t.i. Traits MORAJO imeti pripono `Trait`: npr. `Psr\Foo\BarTrait`.
4. PSR-0, 1 in 2 SE MORA upoštevati.
5. Imenski prostor izdelovalca MORA biti `Psr`.
6. Biti MORA paket/drugi-nivo imenski prostor v povezavi s PSR, ki
   pokriva kodo.
7. Composer paket MORA biti imenovan `psr/<package>` npr. `psr/log`. Če
   se zahteva implementacija kot virtualni paket MORA biti imenovan
   `psr/<package>-implementation` in je zahtevano z določeno verzijo kot
   `1.0.0`. Implementatorji tega PSR lahko potem ponudijo
   `"psr/<package>-implementation": "1.0.0"` v njihovem paketu, da zadostijo tej
   zahtevi. Spremembe specifikacije preko nadaljnih PSR-jev bi morale voditi samo v novo
   značko `psr/<package>` paketa in enako izdajo verzije
   implementacije, ki se zahteva.
