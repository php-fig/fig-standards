Základné štandardy kódovania
============================

Táto sekcia štandardu obsahuje, čo by malo byť považované za štandardné prvky kódovania,
ktoré je potrebné dodržovať, aby sa zaručila vysoká technická úroveň pri zdielanom PHP kóde.

Kľúčové slová "MUSÍ", "NESMIE", "POTREBNÉ", "SMIE", "NESMIE", "MALO BY",
"NEMALO BY", "ODPORÚČANÉ", "MôŽE", and "NEPOVINNÉ" v tomto dokumente sú vo význame
ako opísané v [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


1. Prehľad
----------

- Súbory MUSIA používať iba `<?php` a `<?=` tagy.

- Súbory MUSIA požívať iba UTF-8 bez BOM(Označenie poradia bajtu) pre PHP kód.

- Súbory BY MALI deklarovať *buď* symboly (triedy, funkcie, konštanty, atď.)
  *alebo* spôsovovať vedlajšie účinky (generovať výpis, zmeniť .ini nastavenia, atď.)
  ale NEMALI BY robiť obe.

- Menné priestory a triedy MUSIA dodržovať samonačítávacie PSR: [[PSR-0], [PSR-4]].

- Mená triéd MUSIA by deklarované s `VelkymiKapitalnymiPismenami`.

- Konštanty v triedach MUSIA byť deklarované so všetkými písmenami veľkými s podtržítkovými oddelovačmi.

- Mená metód MUSIA byť deklarované v `camelCase`.


2. Súbory
--------

### 2.1. PHP Tagy

PHP kód MUSÍ používať dlhé `<?php ?>` tagy alebo krátke echo `<?= ?>` tagy; 
NESMIE používať iné variácie tagov.

### 2.2. Znaková sada

PHP kód MUSÍ používať iba UT-8 bez BOM(Označenie poradia bajtu).

### 2.3. Vedľajšie efekty

Súbor BY MAL deklarovať nové symboly (triedy, funkcie, konštanty, atď.) 
a nespôsobobať ďaľšie vedľajšie účinky, alebo BY MAL vykonávať logiku s vedľajšími účinkami, 
ale NEMAL BY  robiť oboje.

Fráza "vedľajšie efekty" znamená vykonanie logiky, 
ktorá nesúvisí priamo s deklaráciou, triéd, funkcií, konštánt, atď, *iba ich zahŕňa zo súborov*.


"Vedľajšie efekty" zahŕňajú, ale nie sú limitované na: generovanie výpisu, výslovné 
použitie `require` alebo `include`, pripájanie sa na externé služby, upravovanie .ini nastavení, 
ohlasovanie chýb a výnimiek, upravovanie globálnych alebo statických premenných,
čítanie z alebo písanie do súboru, atď.

Nasledujúce je príklad súboru s obidvomi deklaráciami a vedľajšími efektami, 
to znamená príklad čomu sa vyvarovať:

```php
<?php
// vedľajší efekt: zmena ini nastavenia
ini_set('error_reporting', E_ALL);

// vedľajší efekt: načítanie súboru
include "subor.php";

// vedľajší efekt: generovanie výpisu
echo "<html>\n";

// deklarácia
function foo()
{
    // function body
}
```

Nasledujúci je príklad súboru, ktorý obsahuje deklarácie bez vedľajších efektov;
to znamená, príklad čoho sa držať:

```php
<?php
// deklarácia
function foo()
{
    // telo funkcie
}

// podmienená deklarácia *nie* je vedľajší efekt
if (! function_exists('bar')) {
    function bar()
    {
        // telo funkcie
    }
}
```


3. Menný priestor a mená tried 
------------------------------

Menné priestory a triedy MUSIA dodržiavať samonačítacie PSR: [[PSR-0], [PSR-4]].

To znamená ze každá trieda je v súbore sama o sebe a je v mennom priestore 
aspoň jednej úrovne, a to v najvyššej úrovni menného priestora balíka.

Meno triedy MUSÍ byť deklarované s `VelkymiKapitalnymiPismenami`.

Kód napísaný pre PHP 5.3 a vyšší MUSI používať formálne menné priestory.

Napríklad:

```php
<?php
// PHP 5.3 a vyššie:
namespace Vendor\Model;

class Foo
{
}
```

Kód napísaný pre 5.2.x a nižšie BY MAL používať dohodnuté pseudo-menné priestory
s predponou `Vendor_` v mene triedy.

```php
<?php
// PHP 5.2.x a nižšie:
class Vendor_Model_Foo
{
}
```

4. Konštanty, vlastnosti a metódy triéd
---------------------------------------

Pojmom "trieda" máme na mysli všetky triedy, rozhrania a traits.

### 4.1. Konštanty

Konštanty triéd MUSIA byť deklarované iba s veľkými písmenami a podtržníkovými oddelovačmi..
Napríklad:

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. Vlastnosti

Toto odporúčanie sa zámerne vyhýba hociakému odporúčaniu ohľadne používania
`$StudlyCaps`, `$camelCase`, alebo `$under_score` názvov vlastností.

Akúkolvek mennú konvenciu použijete MALA BY byť aplikovaná konzistentne 
v danom rozmedzí. Toto rozmedzie MôŽE byt na úrovni knižnice, balíka, triedy alebo metódy.

### 4.3. Metódy

Mená metód MUSIA byť deklarované v `camelCase()`.
