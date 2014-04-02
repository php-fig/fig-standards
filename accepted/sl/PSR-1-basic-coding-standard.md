Osnovni kodni standard
======================

Ta sekcija standarda obsega, kar bi moralo šteti za standard
kodnih elementov, ki so potrebni za zagotovitev visokega nivoja tehnične
interoperabilnosti med skupno PHP kodo.

Ključne besede "MORA", "NE SME", "ZAHTEVANO", "SE", "SE NE", "BI",
"NE BI", "PRIPOROČLJIVO", "LAHKO" in "OPCIJSKO" se v tem dokumentu
interpretira kot je opisano v [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


1. Pregled
----------

- Datoteke MORAJO uporabljati samo `<?php` in `<?=` značke.

- Datoteke MORAJO uporabljati samo UTF-8 brez BOM za PHP kodo.

- Datoteke BI MORALE *bodisi* razglasiti simbole (razrede, funkcije, konstante itd.)
  *ali* povrzočiti stranske učinke (npr. generirati izpis, spremeniti .ini nastavitve itd.)
  vendar NE BI SMELE početi obojega.

- Imenski prostori in razredi MORAJO slediti "avtomatskemu nalagalniku" PSR: [[PSR-0], [PSR-4]].

- Imena razredov MORAJO biti deklarirana v `StudlyCaps`.

- Konstante razreda MORAJO biti deklarirane v celoti z veliki črkami z ločilom podčrtaja.

- Imena metod MORAJO biti deklarirana v obliki `camelCase`.


2. Datoteke
-----------

### 2.1. PHP značke

PHP koda MORA uporabljati dolge `<?php ?>` značke ali kratke izpisne `<?= ?>` značke; NE SME uporabljati drugih različic značk.

### 2.2. Kodiranje znakov

PHP koda MORA uporabljati samo UTF-8 brez BOM.

### 2.3. Stranski učinki

Datoteka MORA deklarirati nove simbole (razrede, funkcije, konstante
itd.) in ne povročati drugih stranskih učinkov, ali MORA izvrševati logiko s stranskimi
učinki, vendar NE BI SMELA delati obojega.

Fraza "stranski učinki" pomeni izvrševanje logike, ki ni direktno povezana z
deklaracijo razredov, funkcij, konstant itd., *le iz vključevanja
datoteke*.

"Stranski učinki" vključujejo, vendar niso omejeni na: generiranje izpisa, eksplicitno
uporabo `require` ali `include`, povezavo z zunanjimi storitvami, spreminjanje ini
nastavitev, oddajo napak ali izjem, spreminjanje globalnih ali statičnih spremenljivk,
branje iz ali pisanje v datoteko in tako naprej. 

Sledeči primer je datoteka, ki vljučuje tako deklaracijo in stranske učinke;
t.j. primer, ki se ga je potrebno izogibati:

```php
<?php
// side effect: change ini settings
ini_set('error_reporting', E_ALL);

// side effect: loads a file
include "file.php";

// side effect: generates output
echo "<html>\n";

// declaration
function foo()
{
    // function body
}
```

Sledeči primer je datoteka, ki vključuje deklaracijo brez stranskih
učinkov; t.j. primer, ki ga je dobro posnemati:

```php
<?php
// declaration
function foo()
{
    // function body
}

// conditional declaration is *not* a side effect
if (! function_exists('bar')) {
    function bar()
    {
        // function body
    }
}
```


3. Imenski prostori in imena razredov
-------------------------------------

Imenski prostor in razredi MORAJO slediti [PSR-0].

To pomeni, da je vsak razred v samostojni datoteki in je znotraj imenskega prostora
vsaj enega nivoja: vrhnje ime izdelovalca.

Imena razredov MORAJO biti deklarirana v `StudlyCaps`.

Koda napisana za PHP 5.3 in kasnejše MORA uporabljati formalne imenske prostore.

Na primer:

```php
<?php
// PHP 5.3 and later:
namespace Vendor\Model;

class Foo
{
}
```

Koda napisana za 5.2.x in prej BI MORALA uporabljati konvencijo pseudo-imenskih prostorov z `Vendor_` predponami na imenih razredov.

```php
<?php
// PHP 5.2.x and earlier:
class Vendor_Model_Foo
{
}
```

4. Konstante razredov, lastnosti in metode
------------------------------------------

Izraz "razred" se nanaša na vse razrede, vmesnike in lastnosti (traits).

### 4.1. Konstante

Konstante razredov MORAJO biti deklarirane v celoti z velikimi črkami z ločilom podčrtajev.
Na primer:

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. Lastnosti

Ta vodič se namensko izogiba kakršnim koli priporočilom glede uporabe
`$StudlyCaps`, `$camelCase` ali `$under_score` imenom lastnosti.

Kakršnokoli ime konvencije je uporabljeno, BI MORALO biti uporabljeno konsistentno znotraj
razumnega področja. To področje je lahko na nivoju izdelovalca, nivoju paketa, nivoju razreda ali nivoju metode.

### 4.3. Metode

Imena metod MORAJO biti deklarirana v `camelCase()`.
