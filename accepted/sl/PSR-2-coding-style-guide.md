Vodič stila kode
================

Ta vodič podaljšuje in razširja [PSR-1], osnovni kodni standard.

Namen tega vodiča je zmanjšanje kognitivnega trenja, ko se skenira kodo
različnih avtorjev. To naredi z zagotavljanjem skupnega skupka pravil in
pričakovanj o tem, kako oblikovati PHP kodo.

Pravila stila tu so pridobljena iz skupnih značilnosti med različnimi projekti
članov. Ko različni avtorji sodelujejo med večimi projekti, pomaga
imeti en skupek smernic, ki so uporabljene med vsemi temi projekti. Tako
korist tega vodiča ni v samih pravilih, vendar v deljenju
teh pravil.

Ključne besede "MORA", "NE SME", "ZAHTEVA", "PRIPOROČA", "LAHKO" in "NEOBVEZNO"
v tem dokumentu se tolmačijo, kot je navedeno v
[RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. Pregled
----------

- Koda MORA slediti "vodiču stila kode" PSR [[PSR-1]].

- Koda MORA uporabljati 4 presledke za odstavke in ne tabulatorjev.

- NE SME biti obveznih omejitev na dolžini vrstice; neobvezni limit MORA biti 120
  znakov; vrstice BI MORALE biti dolge 80 znakov ali manj.

- MORA biti ena prazna vrstica za deklaracijo `namespace` in biti
  MORA ena prazna vrstica za blokom deklaracije `use`.

- Odpiranje zavitih oklepajev za razrede MORA iti v naslednjo vrstico in zapiranje zavitih oklepajev MORA
  iti v naslednjo vrstico za telesom.

- Odpiranje zavitih oklepajev za metode MORA iti v novo vrstico in zapiranje zavitih oklepajev MORA
  iti v naslednjo vrstico za telesom.

- Vidnost MORA biti deklarirana na vseh lastnostih in metodah; `abstract` in
  `final` MORATA biti deklarirana pred vidnostjo; `static` MORA biti deklariran
  za vidnostjo.

- Kontrolne strukture ključnih besed MORAJO imeti za njimi en presledek; klic metode in
  funkcije NE SME.

- Odpiranje zavitih oklepajev za kontrolne struktured MORAJO iti na isto vrstico in zapiranje
  zavitih oklepajev MORA iti na naslednjo vrstico za telesom.

- Odpiranje oklepajev za kontrolne strukture NE SME imeti za njimi presledka,
  in zaprtje oklepajev za kontrolne strukture NE SME imeti pred njimi presledka.

### 1.1. Primer

Ta primer zajema nekaj pravil spodaj za hiter pregled:

~~~php
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
        // method body
    }
}
~~~

2. Spošno
---------

### 2.1 Osnovni kodni standard

Koda MORA slediti vsem pravilom opisanim v [PSR-1].

### 2.2 Datoteke

Vse PHP datoteke MORAJO uporabljati Unix LF (linefeed) na koncih vrstic.

Vse PHP datoteke se MORAJO končati s prazno vrstico.

Zapiralna značka `?>` MORA biti izpuščena iz datotek, ki vsebujejo samo PHP.

### 2.3. Vrstice

NE SME biti obvezne omejitve na dolžini vrstice.

Neobvezna omejitev dolžine vrstice MORA biti 120 znakov; avtomatizirani pregledovalniki stilov
MORAJO opozoriti vendar NE SMEJO dati napake na neobveznih omejitvah.

Vrstice NE BI SMELE biti daljše od 80 znakov; vrstice daljše kot to BI MORALE
biti razdeljene v več naknadnih vrstic in ne več ko 80 znakov vsaka.

Ne sme biti zaključnega presledka na koncu ne-praznih vrstic.

Prazne vrstice SO LAHKO dodane, da izboljšajo bralnost in indicirajo povezane
bloke kode.

NE SME biti več kot ena izjava na vrstico.

### 2.4. Odstavki

Koda MORA uporabljati za odstavke 4 presledke in NE SME uporabljati tabulatorjev.

> N.b.: Uporaba samo presledkov in ne mešanje presledkov s tabulatorji pomaga pri izogibu
> problemov z diff-i, patch-i, zgodovino in anotacijami. Uporaba presledkov
> naredi tudi enostavno za vstavljanje dobro razdrobljenih pod-odstavkov za znotraj-vrstično
> poravnavo.

### 2.5. Ključne besede in True/False/Null

PHP [ključne besede] MORAJO biti v malih črkah.

PHP konstante `true`, `false` in `null` MORAJO biti v malih črkah.

[ključne besede]: http://php.net/manual/en/reserved.keywords.php



3. Deklaraciji namespace in use
-------------------------------

Ko je prisotna, MORA biti ena prazna vrstica za `namespace` deklaracijo.

Ko je prisotno, vse deklaracje `use` MORAJO iti za `namespace`
deklaracijo.

Biti MORA ena `use` ključna beseda na deklaracijo.

Biti MORA ena prazna vrstica za blokom `use`.

Na primer:

~~~php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... additional PHP code ...

~~~


4. Razredi, lastnosti in metode
-------------------------------

Izraz "razred" se sklicuje na vse razrede, vmesnike in lastnosti - traits.

### 4.1. Razširitve in implementacije

Ključni besedi `extends` in `implements` MORATA biti deklarirani na isti vrstici kot
ime razreda.

Odpiralni zaviti oklepaj za razred MORA iti na svojo vrstico; zapiralni zaviti oklepaj
za razred MORA iti na naslednjo vrstico za telesom.

~~~php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constants, properties, methods
}
~~~

Seznami z `implements` so LAHKO razdeljeni po večih vrsticah, kjer je vsaka
naknadna vrstica enkrat zamaknjena. Ko se to dela, prvi element v seznamu
MORA iti na naslednjo vrstico in torej MORA biti samo en vmesnik na vrstico.

~~~php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // constants, properties, methods
}
~~~

### 4.2. Lastnosti

Vidnost MORA biti deklarirana na vseh lastnostih.

Ključna beseda `var` NE SME biti uporabljena za deklaracijo lastnosti.

Ne SME BITI več kot ena deklaracija lastnosti na izraz.

Imena lastnosti NE BI SMELA imeti predpon z enim podčrtajem za prikaz
t.i. protected ali private vidnosti.

Deklaracija lastnosti izgleda kot sledeče.

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
~~~

### 4.3. Metode

Vidnost MORA biti deklarirana na vseh metodah.

Imena metod NE BI SMELA imeti predpon z enim podčrtajem za prikaz
t.i. protected ali private vidnosti.

Imena metod NE SMEJO biti deklarirana s presledkom za imenom metode.
Odpiralni zaviti oklepaj MORA biti na svoji vrstici in zapiralni zaviti oklepaj MORA iti na
naslednjo vrstico, ki sledi telesu. NE SME biti presledka za odpiralnim
oklepajem in NE SME biti presledka za zapiralnim oklepajem.

Deklaracija metode izgleda sledeče. Bodite pozorni na postavitev
oklepajec, vejic, presledkov in zavitih oklepajev:

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
~~~

### 4.4. Argumenti metode

V seznamu argumentov NE SME biti presledka pred vsako vejico in
MORA biti en presledek za vsako vejico.

Argumenti metode s privzetimi vrednostmi MORAJO iti na konec seznama
argumentov.

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
~~~

Seznami argumentov so LAHKO razdeljeni med več vrstic, kjer je vsaka naknadna vrstica
enkrat zamaknjena. Ko se dela to, MORA biti prvi element v seznamu na
naslednji vrstici in MORA biti samo en argument na vrstico.

Ko je seznam argumentov razdeljen med več vrstic, MORATA biti zapiralni oklepaj
in odpiralni zaviti oklepaj dana skupaj na svojo vrstico z enim presledkom
med njima.

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // method body
    }
}
~~~

### 4.5. `abstract`, `final` in `static`

Ko so prisotne, `abstract` in `final` deklaracije MORAJO biti pred
deklaracijo vidnosti.

Ko je prisotna, MORA biti deklaracija `static` za deklaracijo
vidnosti.

~~~php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // method body
    }
}
~~~

### 4.6. Klicanje metod in funkcij

Ko izvajate klic metode ali funkcije, NE SME biti presledka med
metodo ali imenom funkcije in odpiralnim oklepajem, NE SME biti presledka
za odpiralnim oklepajem in NE SME biti presledka pred
zapiralnim oklepajem. V seznamu argumentov ne sme biti presledka pred
vsako vejico in MORA biti en presledek za vsako vejico.

~~~php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
~~~

Seznami argumentov so LAHKO razdeljeni v več vrstic, kjer je vsaka naknadna vrstica
enkrat zamaknjena. Ko to delate, MORA biti prvi element v seznamu v
naslednji vrstici in biti MORA samo en argument na vrstico.

~~~php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
~~~

5. Kontrolne strukture
----------------------

Splošni stil pravil za kontrolne strukture je, kot sledi:

- Biti MORA en presledek za ključno besedo kontrolne strukture
- NE SME biti presledka za odpiralnim oklepajem
- NE SME biti presledka pred zapiralnim oklepajem
- Biti MORA en presledek med zapiralnim oklepajem in odpiralnim
  zavitim oklepajem
- Telo strukture MORA biti enkrat zamaknjeno
- Zapiralni zaviti oklepaj MORA biti v naslednji vrstici za telesom

Telo vsake strukture MORA biti zaprto z zavitimi oklepaji. To standardizira, kako
struktura izgleda in zmanjšuje verjetnost uvajanje napak, ko se dodaja nove
vrstice v telo.


### 5.1. `if`, `elseif`, `else`

Struktura `if` izgleda sleče. Bodite pozorni na postavitev oklepajev,
presledkov in zavitih oklepajev; ter na to, da sta `else` in `elseif` v isti vrstici kot
zaviti zaklepaj iz predhodnega telesa.

~~~php
<?php
if ($expr1) {
    // if body
} elseif ($expr2) {
    // elseif body
} else {
    // else body;
}
~~~

Ključna beseda `elseif` BI MORALA biti uporabljena namesto `else if`, da vse kontrolne
ključne besede izgledajo kot enojne besede.


### 5.2. `switch`, `case`

Struktura `switch` izgleda, kot sledi. Bodite pozorni na postavitev
oklepajev, presledkov in zavitih oklepajev. Izraz `case` MORA biti enkrt zamaknjen
od `switch` in `break` ključnih besed (ali ostalih zaključnih ključnih besed) MORA biti
zamaknjen na enakem nivoju, kot je telo `case`. Biti MORA komentar, kot je
`// no break`, ko je prehajanje naprej namensko v nepraznem `case` telesu.

~~~php
<?php
switch ($expr) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Third case, return instead of break';
        return;
    default:
        echo 'Default case';
        break;
}
~~~


### 5.3. `while`, `do while`

Stavek `while` izgleda, kot sledi. Bodite pozorni na postavitev
oklepajev, presledkov in zavitih oklepajev.

~~~php
<?php
while ($expr) {
    // structure body
}
~~~

Podobno stavek `do while` izgleda sledeče. Bodite pozorni
na oklepaje, presledke in zavite oklepaje.

~~~php
<?php
do {
    // structure body;
} while ($expr);
~~~

### 5.4. `for`

Stavek `for` izgleda, kot sledi. Bodite pozorni na oklepaje,
presledke in zavite oklepaje.

~~~php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
~~~

### 5.5. `foreach`

Stavek `foreach` izgleda, kot sledi. Bodite pozorni na postavitev
oklepajev, presledkov in zavitih oklepajev.

~~~php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
~~~

### 5.6. `try`, `catch`

Blok `try catch` izgleda, kot sledi. Bodite pozorni na postavitev
oklepajev, presledkov in zavitih oklepajev.

~~~php
<?php
try {
    // try body
} catch (FirstExceptionType $e) {
    // catch body
} catch (OtherExceptionType $e) {
    // catch body
}
~~~

6. Zaprtja
----------

Zaprtja MORAJO biti deklarirana s presledkom za ključno besedo `function` in
presledkom pred in za ključno besedo `use`.

Odpirajoči zaviti oklepaj MORA iti na isto vrstico in zaviti zaklepaj MORA iti na
naslednjo vrstico, ki sledi telesu.

NE SME biti presledka za odpirajočim oklepajev seznama argumentov
ali seznamom spremenljivk in NE SME biti presledka pred zaklepajem
seznama argumentov ali seznama spremenljivk.

V seznamu argumentov in seznamu spremenljivk NE SME biti presledka pred vsako
vejico in MORA biti presledek za vsako vejico.

Argumenti zaprtij s privzetimi vrednostmi MORAJo iti na konec seznama
argumentov.

Deklaracija zaprtja izgleda, kot sledi. Bodite pozorni na postavitev
oklepajev, vejic, presledkov in zavitih oklepajev:

~~~php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
~~~

Seznami argumentov in seznami spremenljivk so LAHKO razdeljeni po večih vrsticah, kjer
je vsaka naknadna vrstica enkrat zamaknjena. Ko to delate, MORA biti prvi element v
seznamu na naslednji vrstici in biti MORA samo en argument ali spremenljivka
na vrstico.

Ko je zaključni seznam (ali argumentov ali spremenljivk) razdeljen med
več vrstic, MORATA biti zaviti zaklepaj in odpirajoči zaviti oklepaj postavljena
skupaj na svojo vrstico z enim presledkom med njima.

Sledijo primeri zaprtij z ali brez seznama argumentov in
seznama spremenljivk na večih vrsticah.

~~~php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // body
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
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
   // body
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // body
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};
~~~

Bodite pozorni, da pravila oblikovanja tudi veljajo, ko je zaprtje direktno uporabljeno
v klicu funkcije ali metode kot argument.

~~~php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // body
    },
    $arg3
);
~~~


7. Zaključek
------------

Mnogo elementov sloga in praks je namerno izpuščenih v tem
vodiču. Te vključujejo, vendar niso omejeni na:

- Deklaracijo globalnih spremenljivk, globalnih konstant

- Deklaracijo funkcij

- Operatorje in naloge

- Poravnavo notranjih vrstic

- Bloke komentarjev in dokumentacije

- Predpone in pripone imen razredov

- Najboljše prakse

Prihodnje priporočila LAHKO revidirajo in razširjajo ta vodič, da naslovijo te ali
ostale elemente sloga in praks.


Priloga A. Raziskava
--------------------

Pri pisanju tega vodiča stila je skupina izvedla raziskavo projektov članov za
ugotovitev skupnih praks. Raziskava je dana tu za ponazoritev.

### A.1. Podatki raziskave

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

### A.2. Legenda raziskave

`indent_type`:
The type of indenting. `tab` = "Use a tab", `2` or `4` = "number of spaces"

`line_length_limit_soft`:
The "soft" line length limit, in characters. `?` = not discernible or no response, `no` means no limit.

`line_length_limit_hard`:
The "hard" line length limit, in characters. `?` = not discernible or no response, `no` means no limit.

`class_names`:
How classes are named. `lower` = lowercase only, `lower_under` = lowercase with underscore separators, `studly` = StudlyCase.

`class_brace_line`:
Does the opening brace for a class go on the `same` line as the class keyword, or on the `next` line after it?

`constant_names`:
How are class constants named? `upper` = Uppercase with underscore separators.

`true_false_null`:
Are the `true`, `false`, and `null` keywords spelled as all `lower` case, or all `upper` case?

`method_names`:
How are methods named? `camel` = `camelCase`, `lower_under` = lowercase with underscore separators.

`method_brace_line`:
Does the opening brace for a method go on the `same` line as the method name, or on the `next` line?

`control_brace_line`:
Does the opening brace for a control structure go on the `same` line, or on the `next` line?

`control_space_after`:
Is there a space after the control structure keyword?

`always_use_control_braces`:
Do control structures always use braces?

`else_elseif_line`:
When using `else` or `elseif`, does it go on the `same` line as the previous closing brace, or does it go on the `next` line?

`case_break_indent_from_switch`:
How many times are `case` and `break` indented from an opening `switch` statement?

`function_space_after`:
Do function calls have a space after the function name and before the opening parenthesis?

`closing_php_tag_required`:
In files containing only PHP, is the closing `?>` tag required?

`line_endings`:
What type of line ending is used?

`static_or_visibility_first`:
When declaring a method, does `static` come first, or does the visibility come first?

`control_space_parens`:
In a control structure expression, is there a space after the opening parenthesis and a space before the closing parenthesis? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
Is there a blank line after the opening PHP tag?

`class_method_control_brace`:
A summary of what line the opening braces go on for classes, methods, and control structures.

### A.3. Rezultati raziskave

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
