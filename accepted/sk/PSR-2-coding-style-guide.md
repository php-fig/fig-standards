Príručka štýlovaním kódu
========================

Táto príručka rozširuje [PSR-1], Základné štandardy kódovania.

Účelom tejto príručky je skrátiť spoznávaciu fázu, pri prezeraní kódu od iných autorov.
Robí to tak vymenovaním sady zdielaných pravidiel a očakávaniami o tom ako sa formátuje PHP kód.

Pravidlá štýlov tu uvedených sú odvodené od spoločných znakov medzi 
rôznymi členskými projektami. Keď rôzny autori spolupracujú na mnohých projektoch, 
pomáha mať jednu sadu pravidiel na všetkých tychto projektoch. Z toho dôvodu, výhodou 
tejto príručky nie sú pravidlá samotné ale zdielanie týchto pravidiel.

Kľúčové slová "MUSÍ", "NESMIE", "POTREBNÉ", "SMIE", "NESMIE", "MALO BY",
"NEMALO BY", "ODPORÚČANÉ", "MôŽE", and "NEPOVINNÉ" v tomto dokumente sú vo význame
ako opísané v [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. Prehľad
----------

- Kód MUSÍ dodržiavať Základné štandardy kódovania PSR [[PSR-1]].

- Kód MUSÍ byť odsadený použitím 4 medzier, nie s tabulátorom.

- Dĺžka riadkov MESMIE byť limitované natvrdo; mäkký limit MUSÍ byť 120 znakov; 
  Riadky BY MALI mať 80 znakov alebo menej.

- Po deklarácii `namespace` MUSÍ nasledovať jeden prázdny riadok, a po bloku 
  `use` deklarácii MUSÍ nasledovat jeden prázdný riadok.

- Otvárajúce zátvorky `{` pre triedy MUSIA ísť na ďaľší riadok a zatvárajúce zátvorky `}` MUSIA
  ísť na ďalší riadok za telom triedy.

- Otvárajúce zátvorky `{` pre metódy MUSIA ísť na ďaľší riadok a zatvárajúce zátvorky `}` MUSIA
  ísť na ďalší riadok za telom metódy.

- Visibilita MUSÍ byť deklarovaná na všetkých vlastnostiach a metódach; `abstract` a
  `final` MUSIA byť deklarované pred visibilitou; `static` MUSÍ byť deklarované za visibilitou.
  
- Klúčové slová pre riadiace štruktúry MUSIA mať jednu medzeru za nimi; metódy a
  volania funkcií NEMUSIA mať medzeru za nimi.

- Otvárajúce zátvorky `{` pre riadiace štruktúry MUSIA ísť na rovnaký riadok 
  a zatvárajúce zátvorky `}` MUSIA ísť na ďaľší riadok za telom.

- Otvárajúce zátvorky `{` pre riadiace štruktúry NEMôŽU mať medzeru za nimi,
  a zatvárajúce zátvorky `}` pre riadiace štruktúry NESMÚ mať medzeru pred nimi.

### 1.1. Príklad

Tento príklad zahŕňa niektoré pravidlá nižšie pre rýchly prehľad:

```php
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class Foo extends Bar implements FooInterface
{
    public function sampleFunction($a, $b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // telo metódy
    }
}
```

2. Všeobecne
------------

### 2.1 Základné štandardy kódovania

Kód musí dodržovať všetky pravidlá načrtnuté v [PSR-1].

### 2.2 Súbory

Všetky PHP súbory MUSIA používať Unix LF(riadkovanie) na konci riadku.

Všetky PHP súbory MUSIA končiť s jednným prázdnym riadkom.

Zatvárajúci `?>` tag MUSÍ byť vynechaný zo súborov obsahujúcich iba PHP.

### 2.3. Riadky

Riadky NEMôŽU mať natvrdo nastavenú dĺžku riadku.

Mäkký limit na dĺžku riadkov MUSÍ byť 120 znakov; automatizované testy 
štýlov MUSIA varovať, ale NESMÚ ohlásiť chybu pri prekročení mäkkého limitu.

Riadky BY NEMALI byť dlhšie ako 80 znakov; riadky dlhšie ako to by MALI
byť rozdelené do viacerých nasledujúcich riadkov, kde ani jeden z riadkov 
neprekročí 80 znakov.

Na riadku, ktorý nie je prázdny, NESMÚ byť na konci medzery.

Prázdne riadky SMÚ byť pridané, aby sa vylepšila čitateľnosť a aby poukázali 
na spolu suvisiace bloky kódu.

Na jednom riadku NESMIE byt viac ako jeden príkaz.

### 2.4. Odsadenie

Kód MUSÍ byť odsadený použitím 4 medzier a nie s tabulátorom.

> Pozn.: Používaním iba medzier a nemixovaním medzier s tabulátorom, pomáha predíst
> problémom s porovnávaním rozdielov, záplat, histórie a vysvetlivkám. Použite medzier
> tiež uľahčuje vkladanie jemnejšieho odsadzovania pre medzi riadkové zarovnania.

### 2.5. Kľúčové slová a True/False/Null

PHP [kľúčové slová] MUSIA byť v malých písmenách.

PHP konštanty `true`, `false`, a `null` MUSIA byť v malých písmenách.

[kľúčové slová]: http://php.net/manual/en/reserved.keywords.php



3. Menné priestory a deklarácie Use
-----------------------------------

Keď je `namespace` deklarácia prítomná, tak po nej MUSÍ nasledovať jeden prázdny riadok.

Keď je `use` deklarované, tak MUSÍ nasledovať po deklarácii `namespace`.

Každá deklarácia `use`, MUSÍ obsahovať klúčové slovo `use`.

Po bloku `use` deklarácií MUSÍ nasledovať jeden prázdny riadok.

Napríklad:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... ďaľší PHP kód ...

```


4. Triedy, Vlastnosti a Metódy
------------------------------

Pojmom "trieda" máme na mysli všetky triedy, rozhrania a traits.

### 4.1. Extends a Implements

Klúčové slová rozšírenia `extends` a implementácie `implements` MUSIA byť deklarované
na rovnakom riadku ako meno triedy.

Otvárajúca hranatá zátvorka pre triedu MUSÍ ísť na svôj vlastný riadok; zatvárajúca 
hranatá zátvorka pre triedu MUSÍ ísť na ďaľší riadok za telom triedy.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class MenoTriedy extends ParentClass implements \ArrayAccess, \Countable
{
    // konštanty, vlastnosti, metódy
}
```

Zoznam implementacií `implements` MôŽE byť rozdelený na viacerých riadkoch,
pričom každý ďaľší riadok je odsadený raz. Keď použijete tento spôsob,
tak prvá položka implementácie v zozname MUSÍ byť na ďaľšom riadkua MUSÍ
byť definované po jednom rozhraní na jeden riadok.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class MenoTriedy extends RodicovskaTrieda implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // konštanty, vlastnosti, metódy
}
```

### 4.2. Vlastnosti

Visibilita MUSÍ byť deklarovaná pre všetky vlastnosti.

Kľúčové slovo `var` NESMIE byť použité na deklarovanie vlastnosti.

V jednej deklarácii sa NESMIE nastaviť viac ako jedna vlastnosť.

Mená vlastností BY NEMALI mať predponu s jedným podtržítkom na naznačenie,
že sa jedná o protected alebo private visibilitu.

Deklarácia vlastnosti vypadá napríklad nasledovne:

```php
<?php
namespace Vendor\Package;

class MenoTriedy
{
    public $vlastnost = null;
}
```

### 4.3. Metódy

Visibilita MUSÍ byť deklarovaná na všetkých metódach.

Mená metód BY NEMALI mať predponu s jedným podtržítkom na naznačenie,
že sa jedná o protected alebo private visibilitu.

Mená metód NESMÚ byť deklarované s medzerou po mene metódy. Otvárajúca 
hranatá zátvorka `{` MUSÍ ísť na svoj vlastný riadok a zatvárajúca hranatá zátvorka `}`
MUSÍ ísť na svoj vlastný riadok a zároveň zatvárajúca hranatá zátvorka `{` MUSÍ 
ísť na ďaľší riadok za telom metódy. Za otvárajucou obyčajnou zátvorkou `(`
NESMIE byť medzera a tiež medzera NESMIE byť pred zatvárajúcou obyčajnou zátvorkou `)`.

Deklarácoa metódy vypadá nasledobne. Všimnite si umiestnenie zátvoriek, 
čiariek, medzier a hranatých zátvoriek:

```php
<?php
namespace Vendor\Package;

class MenoTriedy
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // telo metódy
    }
}
```    

### 4.4. Parametre Metódy

V liste parametrov NESMIE byť medzera pred žiadnou čiarkou a každá čiarka 
MUSÍ mať jednu medzeru za sebou.

Parametre metódy s predvolenmi hodnotami MUSIA ísť na koniec zoznamu 
parametrov.

```php
<?php
namespace Vendor\Package;

class MenoTriedy
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // telo metódy
    }
}
```

Zoznam parametrov MôŽE byť rozdelený na viacerých riadkoch, kde každý ďaľší
riadok je odsadený raz. Keď robíte tak, prvá položka na zozname MUSÍ byť
na ďaľšom riadku, a na každóm riadku MUSÍ byť práve jeden parameter.

Keď je zoznam parametrov rozdelený na viacerých riadkoch, zatvárajúca 
zátvorka `)` a otvárajúca hranatá zátvorka `}` musia byť spolu na jednom
vlastnom riadku s jednou medzerou medzi nimi.

```php
<?php
namespace Vendor\Package;

class MenoTriedy
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // telo metódy
    }
}
```

### 4.5. `abstract`, `final`, a `static`

Ak je prítomná deklarácia `abstract` a `final`, tak MUSÍ predchádzať 
deklaráciu visibility.

Keď je prítomná deklarácia `static`, tak MUSÍ byť za deklaráciou 
visibility.

```php
<?php
namespace Vendor\Package;

abstract class MenoTriedy
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // telo metódy
    }
}
```

### 4.6. Volania metod a funkcií

Keď voláme metódu alebo funkciu tak:
- medzi metódou alebo funkciou a otvárajúcou zátvorkou `(` NESMIE byť medzera
- za otvárajúcou zátvorkou `(` NESMIE byť medzera
- NESMIE byť medzera pred zatvárajúcou zátvorkou `)`.
- v zozname parametrov NESMIE byt medzera pred čiarkou a MUSÍ byť 
  jedna medzera za každou čiarkou. 

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

Zoznam parametrov MôŽE byť rozdelený na viacero riadkov, kde každý 
ďaľší riadok je odsadený raz. V takom prípade, prvá položka v zozname 
MUSÍ byt na ďaľšom riadku a každý parameter MUSÍ byt na vlastnom riadku

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. Riadiace štruktúry
---------------------
  
Všeobecné štýlové pravidlá pre riadiace štruktúry sú nasledovné:

- Za kľúčovým slovom riadiacej štruktúry MUSÍ byť jedna medzera
- Za otvárajúcou zátvorkou `(` MUSÍ byť medzera
- Pred zatvárajúcou zátvorkou `)` NESMIE byť medzera
- Medzi zatvárajúcou zátvorkou `)` a otvárajúcou hranatou 
  zátvorkou `{` MUSÍ byť jedna medzera
- Telo štruktúry MUSÍ byť odsadené raz
- Zatvárajúca hranatá zátvorka `}` MUSÍ byť na ďaľšom riadku za telom

Telo každej štruktúry MUSÍ byť uzavreté do hranatých zátvoriek `{ }`. Toto 
štandardizuje, ako štruktúry vypadajú a znižuje možnost zavedenia nových chýb,
keď sa nové riadky pridajú do tela.


### 5.1. `if`, `elseif`, `else`

Štruktúra ak `if` vypadá nasledovne. Všimnite si umiestnenie zátvoriek, medzier,
hranatých zátvoriek; a tiež že `else` a `elseif` sú na rovnakom riadku 
ako zatvárajúce hranaté zátvorky predošlého tela.

```php
<?php
if ($expr1) {
    // tela pre if
} elseif ($expr2) {
    // telo pre elseif
} else {
    // telo pre else;
}
```

Klúčové slovo `elseif` BY MALO byť použité namiesto `else if`, 
takže riadiace klúčové slovo vyzerá ako jedno slovo.


### 5.2. `switch`, `case`

Štruktúra `switch` vyzerá nasledovne. Všimnite si umiestnenie zátvoriek, 
medzier a hranatých zátvoriek. Kľúčové slovo `case` MUSÍ byť odsadené raz
od `switch`, a kľúčové slovo `break` (alebo ostatné ukončovacie kľúčové slová) 
MUSIA byť odsadené na rovnakej úrovni ako telo `case`. Ak telo `case`
nie je prázdne a zámerne nemá `break`, tak MUSÍ obsahovať komment
ako `// no break`.

```php
<?php
switch ($vyraz) {
    case 0:
        echo 'Prvý prípad s breakom';
        break;
    case 1:
        echo 'Druhý prípad s prechodom';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Tretí prípad s returnom namiesto breaku';
        return;
    default:
        echo 'Štandardný prípad';
        break;
}
```


### 5.3. `while`, `do while`

Kľúčové slovo `while` vypadá nasledovne. Všimnite si
umiestnenie zátvoriek, medzier a hranatých zátvoriek.

```php
<?php
while ($vyraz) {
    // telo štruktúry
}
```

Podobne, štruktúra `do while` vypadá nasledovne. Všimnite si umiestnenie
zátvoriek, medzier a hranatých zátvoriek.

```php
<?php
do {
    // telo štruktúry;
} while ($vyraz);
```

### 5.4. `for`

Povel `for` vypadá nasledovne. Všimnite si umiestnenie
zátvoriek, medzier a hranatých zátvoriek.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // telo
}
```

### 5.5. `foreach`
    
Povel `foreach` vyzerá nasledovne. Všimnite si umiestnenie
zátvoriek, medzier a hranatých zátvoriek.

```php
<?php
foreach ($iterable as $key => $value) {
    // telo foreach
}
```

### 5.6. `try`, `catch`

BLok `try catch` vyzerá nasledovne. Všimnite si umiestnenie
zátvoriek, medzier a hranatých zátvoriek.

```php
<?php
try {
    // telo try
} catch (FirstExceptionType $e) {
    // telo catch
} catch (OtherExceptionType $e) {
    // telo catch
}
```

6. Uzavretia `Closures`
-----------------------

Uzavretia MUSIA byť deklarované s medzerou za kľúčovým slovom `function` a
s medzerou pred a po kľúčovom slove `use`.

Otvárajúce hranaté zátvorky `{` MUSIA ísť na rovnaký riadok a zatvárajúce 
hranaté zátvorky `}` MUSIA ísť na samostatný riadok nasledujúci po tele.

Po otvárajúcej zátvorke `(` zoznamu parametrov alebo zoznamu premenných 
NESMIE byť medzera. Pred zatvárajúcou zátvorkou `)` zoznamu parametrov 
alebo premenných NESMIE byť medzera.

V zozname parametrov a premenných NESMIE byť medzera pred každou čiarkou a
za každou čiarkou MUSÍ byť jedna medzera.

Parametry uzavretia s prednastavenými hodnotami MUSIA ísť na koniec 
zoznamu parametrov.

Deklarácia Uzavretia vypadá nasledovne.Všimnite si umiestnenie
zátvoriek, medzier, čiarok a hranatých zátvoriek.

```php
<?php
$uzavretieSArgumentami = function ($arg1, $arg2) {
    // telo
};

$uzavretieSArgumentamiAPremennymi = function ($arg1, $arg2) use ($var1, $var2) {
    // telo
};
```

Zoznam parametrov a premenných MOŽE byť rozdelený na viacero riadkov, kde 
každý ďaľší riadok je odsadený raz. V takomto prípade, prvá položka na zozname
MUSÍ byt na ďaľšom riadku a na riadku MUSÍ byť iba jeden parameter
alebo premenná.

Keď je zoznam (buď parametrov alebo premenných)rozdelený na viacero riadkov, 
tak na konci zoznamu MUSÍ byť uzatvárajúca zátvorka a otvárajúca hranatá 
zátvorka umiestnená spolu na jednom riadku s jednou medzerou medzi nimi.

Nasledujúce príklady uzavretí s alebo bez zoznamu argumentov a premenných
rozdelených na viacero riadkov:

```php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // telo
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // telo
};

$longArgs_longVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // telo
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // telo
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // telo
};
```

Všimnite si že pravidlá formátovania tiež platia keď je uzavretie
použité priamo vo volaní funkcie alebo metódy ako parameter:

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // telo
    },
    $arg3
);
```


7. Záver
--------

Mnohé elementy štýlovania a cvičenia sú zámerne vynechané v tejto
príručke. Okrem iných sú to napríklad aj tieto:

- Deklarácia globálnych premenných a globálnych konštánt

- Deklarácia funkcií

- Operátori a priradenia

- Zarovnanie medzi riadkami

- Komentáre a bloky dokumentácie

- Predpony a prípony mien tried.

- Najlepšie postupy

Budúce odporúčania MôŽU prepracovať alebo rozšíriť túto príručku 
o uvedené ale iné elementy štýlovania a postupov.


Dodatok A. Prieskum
-------------------

Pri písaní tejto príručky, skupina použila prieskum členských 
projektov na určenie spoločných postupov. In writing this style guide, the group took a survey of member projects to
determine common practices. Vysledky prieskumu sú tu ponechané pre potomstvo.

### A.1. Dáta priskumu

    url,http://www.horde.org/apps/horde/docs/CODING_STANDARDS,http://pear.php.net/manual/en/standards.php,http://solarphp.com/manual/appendix-standards.style,http://framework.zend.com/manual/en/coding-standard.html,http://symfony.com/doc/2.0/contributing/code/standards.html,http://www.ppi.io/docs/coding-standards.html,https://github.com/ezsystems/ezp-next/wiki/codingstandards,http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html,https://github.com/UnionOfRAD/lithium/wiki/Spec%3A-Coding,http://drupal.org/coding-standards,http://code.google.com/p/sabredav/,http://area51.phpbb.com/docs/31x/coding-guidelines.html,https://docs.google.com/a/zikula.org/document/edit?authkey=CPCU0Us&hgd=1&id=1fcqb93Sn-hR9c0mkN6m_tyWnmEvoswKBtSc0tKkZmJA,http://www.chisimba.com,n/a,https://github.com/Respect/project-info/blob/master/coding-standards-sample.php,n/a,Object Calisthenics for PHP,http://doc.nette.org/en/coding-standard,http://flow3.typo3.org,https://github.com/propelorm/Propel2/wiki/Coding-Standards,http://developer.joomla.org/coding-standards.html
    voting,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,no,no,no,?,yes,no,yes
    indent_type,4,4,4,4,4,tab,4,tab,tab,2,4,tab,4,4,4,4,4,4,tab,tab,4,tab
    line_length_limit_soft,75,75,75,75,no,85,120,120,80,80,80,no,100,80,80,?,?,120,80,120,no,150
    line_length_limit_hard,85,85,85,85,no,no,no,no,100,?,no,no,no,100,100,?,120,120,no,no,no,no
    class_names,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,lower_under,studly,lower,studly,studly,studly,studly,?,studly,studly,studly
    class_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,next,next,next,next,next,next,same,next,next
    constant_names,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper
    true_false_null,lower,lower,lower,lower,lower,lower,lower,lower,lower,upper,lower,lower,lower,upper,lower,lower,lower,lower,lower,upper,lower,lower
    method_names,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,lower_under,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel
    method_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,same,next,next,next,next,next,same,next,next
    control_brace_line,same,same,same,same,same,same,next,same,same,same,same,next,same,same,next,same,same,same,same,same,same,next
    control_space_after,yes,yes,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes
    always_use_control_braces,yes,yes,yes,yes,yes,yes,no,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes
    else_elseif_line,same,same,same,same,same,same,next,same,same,next,same,next,same,next,next,same,same,same,same,same,same,next
    case_break_indent_from_switch,0/1,0/1,0/1,1/2,1/2,1/2,1/2,1/1,1/1,1/2,1/2,1/1,1/2,1/2,1/2,1/2,1/2,1/2,0/1,1/1,1/2,1/2
    function_space_after,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no
    closing_php_tag_required,no,no,no,no,no,no,no,no,yes,no,no,no,no,yes,no,no,no,no,no,yes,no,no
    line_endings,LF,LF,LF,LF,LF,LF,LF,LF,?,LF,?,LF,LF,LF,LF,?,,LF,?,LF,LF,LF
    static_or_visibility_first,static,?,static,either,either,either,visibility,visibility,visibility,either,static,either,?,visibility,?,?,either,either,visibility,visibility,static,?
    control_space_parens,no,no,no,no,no,no,yes,no,no,no,no,no,no,yes,?,no,no,no,no,no,no,no
    blank_line_after_php,no,no,no,no,yes,no,no,no,no,yes,yes,no,no,yes,?,yes,yes,no,yes,no,yes,no
    class_method_control_brace,next/next/same,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/next,same/same/same,same/same/same,same/same/same,same/same/same,next/next/next,next/next/same,next/same/same,next/next/next,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/same,next/next/next

### A.2. Vysvetlivky prieskumu

`indent_type`:
Typ odsadenia. `tab` = "Používanie tabu", `2` alebo `4` = "počet medzier"

`line_length_limit_soft`:
Limit pre "mäkkú" dĺžku riadku v znakoch. `?` = nerozonavať alebo neriešiť, `no` znamená bez limitu.

`line_length_limit_hard`:
Limit pre "tvrdú" dĺžku riadku v znakoch. `?` = nerozonavať alebo neriešiť, `no` znamená bez limitu.

`class_names`:
Ako sú triedy menované. `lower` = iba malé písmená, `lower_under` = malé písmená s podtržítkami, `studly` = StudlyCase.

`class_brace_line`:
Majú otvárajúce hranaté zátvorky ísť na `same` riadok ako kľúčové slovo triedy, alebo na `next` riadok za ním?

`constant_names`:
Ako sú konštanty pomenované? `upper` = Veľké písmená s podrtžítkami.

`true_false_null`:
Sú `true`, `false`, a `null` klúčové slová použivané ako `lower` písmenami, alebo `upper` písmenami?

`method_names`:
Ako sú metódy pomenované? `camel` = `camelCase`, `lower_under` = malými písmenami s podtržítkami.

`method_brace_line`:
Sú otvárajúce hranaté zátvorky metódy na `same` rovnakom riadku ako meno metódy alebo na `next` riadku?

`control_brace_line`:
Sú otvárajúce hranaté zátvorky riadiacej štruktúry na `same` riadku, alebo na `next` riadku?

`control_space_after`:
Je medzera po klǔčovom slove riadiacej štruktúry?

`always_use_control_braces`:
Používajú riadiace štruktúry vždy hranaté zátvorky?

`else_elseif_line`:
Keď použivate `else` alebo `elseif`, idú na `same` riadok ako predchádzajúce zatvárajúce hranaté zátvorky alebo idú na `next` riadok?

`case_break_indent_from_switch`:
Koľko krát sú `case` a `break` odsadené od otvárajúceho povelu `switch`?

`function_space_after`:
Majú volania funkcií medzeru po mene funkcie a pred otvárajúcou zátvorkou?

`closing_php_tag_required`:
Je v súboroch obsahujúcich len PHP potrebný uzatvárajúci `?>` tag?

`line_endings`:
Aký typ ukončenia riadku používať?

`static_or_visibility_first`:
Keď sa deklaruje metóda, je `static` prvé alebo je visibilita prvá?

`control_space_parens`:
Je vo výraze riadiacej štruktúre medzera po otvárajúcej zátvorke a medzera pred zatvárajúcou zátvorkou? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
Je prázdny riadok za PHP otvárajúcim tagom?

`class_method_control_brace`:
Zhrnutie na ktorý riadok idú otvárajúce hranaté zátvorky pre triedy, metódy a riadiace štruktury.

### A.3. Survey Results

    indent_type:
        tab: 7
        2: 1
        4: 14
    line_length_limit_soft:
        ?: 2
        no: 3
        75: 4
        80: 6
        85: 1
        100: 1
        120: 4
        150: 1
    line_length_limit_hard:
        ?: 2
        no: 11
        85: 4
        100: 3
        120: 2
    class_names:
        ?: 1
        lower: 1
        lower_under: 1
        studly: 19
    class_brace_line:
        next: 16
        same: 6
    constant_names:
        upper: 22
    true_false_null:
        lower: 19
        upper: 3
    method_names:
        camel: 21
        lower_under: 1
    method_brace_line:
        next: 15
        same: 7
    control_brace_line:
        next: 4
        same: 18
    control_space_after:
        no: 2
        yes: 20
    always_use_control_braces:
        no: 3
        yes: 19
    else_elseif_line:
        next: 6
        same: 16
    case_break_indent_from_switch:
        0/1: 4
        1/1: 4
        1/2: 14
    function_space_after:
        no: 22
    closing_php_tag_required:
        no: 19
        yes: 3
    line_endings:
        ?: 5
        LF: 17
    static_or_visibility_first:
        ?: 5
        either: 7
        static: 4
        visibility: 6
    control_space_parens:
        ?: 1
        no: 19
        yes: 2
    blank_line_after_php:
        ?: 1
        no: 13
        yes: 8
    class_method_control_brace:
        next/next/next: 4
        next/next/same: 11
        next/same/same: 1
        same/same/same: 6
