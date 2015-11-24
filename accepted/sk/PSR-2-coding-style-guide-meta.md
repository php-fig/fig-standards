PSR-2 Meta Dokument
===================

1. Zhrnutie
-----------

Učelom tejto príručky je zníženie poznávacieho trenia pri prezeraní kódu od rôznych autorov. Robí sa to
vymenovaním spoločnej sady pravidiel a očakávaní o tom ako formátovať PHP kód.

Pravidlá štýlovania tu sú ódvodené zo spoločných prvkov medzi rôznymi členskými projektami. Keď rôzny autori
spolupracujú na rozličných projektoch, pomáha mať jednu sadu pravidiel, ktoré sa používajú vo všetkých
projektoch. Teda, výhodou tejto príručky nie sú samotné pravdilá ale zdielanie týchto pravidiel.


2. Hlasy
--------

- **Acceptance Vote:** [ML](https://groups.google.com/d/msg/php-fig/c-QVvnZdMQ0/TdDMdzKFpdIJ)


3. Tlačová chyba
----------------

### 3.1 - VIac riadkové parametre (09/08/2013)

Používaním jedného alebo viacerých viacriadkových parametrov(napr: polia alebo anonymné funkcie) nepredstavujú
samotne rozdelenie zoznamu parametrov, preto Sekcia 4.6 nie je automaticky uplatnovaná. Polia a anonymné
funkcie sú schopné presahovať do viacerých riadkov.

Nasledujúce je úplne platne v PSR-2:

```php
<?php
nejakafunkcia($foo, $bar, [
  // ...
], $baz);

$app->get('/ahoj/{name}', function ($name) use ($app) { 
    return 'Ahoj '.$app->escape($name); 
});
```

### 3.2 - Rozširovanie viacero rozhraní (10/17/2013)

Keď rozširujeme viacero rozhraní, tak so zoznamom rozšírení `extends` BY sa MALO zaobchádzať rovnako ako
so zoznamom `implements`, ako je deklarované v Sekcii 4.1.

