Przewodnik po standardach formatowania
======================================

Poniższy przewodnik rozszerza i rozwija PSR-1 - podstawowe standardy formatowania.

Dokument powstał w celu zredukowania zaburzeń procesów poznawczych (pol. "co autor miał na myśli?") podczas
przeglądania kodu php pochodzącego od różnych autorów. W ramach poniższej pracy stworzono listę zasad i
oczekiwań dotyczących formatowania kodu PHP.

Poniższy zestaw formatów został opracowany na podstawie podobieństw wśród projektów prowadzonych przez
członków PHP-FIG.  Kiedy różni autorzy współpracują w ramach kilku projektów, jeden, taki sam zestaw
wytycznych używany we wszystkich tych projektach jest bardzo pomocny. Dlatego też, główną korzyścią tego
poradnika nie są zasady same w sobie, lecz dzielenie się nimi.

Następujące słowa "MUSI", "NIE WOLNO", "WYMAGANE", "POWINNO", "NIE POWINNO",
"REKOMENDWANE", "MOŻE" oraz "OPCJONALNE" będą interpretowane tak jak opisano to w [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. Podsumowanie
---------------

- Kod php MUSI stosować się do zasad podstawowych standardów kodowania PSR [[PSR-1]].

- Kod MUSI używać 4 spacji jako wcięcie, a nie znaków tabulacji.

- Linia POWINNA mieć 80 znaków lub mniej, jeśli chcemy używać limitu na liczbę znaków w linii
MUSI być to 120 znaków, NIE WOLNO ustalać sztywnych limitów na długość linii.

- Po deklaracji `namespace` MUSI istnieć jedna linia odstępu, to samo tyczy się sytuacji,
kiedy mamy blok deklaracji `use`.

- Otwierający nawias klamrowy dla klas MUSI rozpoczynać się od nowej linii, a zamykający
MUSI znajdować się jedną linię poniżej kodu ciała klasy.

- Otwierający nawias klamrowy dla metod MUSI rozpoczynać się od nowej linii, a zamykający
MUSI znajdować się jedną linię poniżej kodu ciała metody.

- Widoczność MUSI być deklarowana dla wszystkich właściwości i metod; deklaracje
`abstract` i `final` MUSZĄ znajdować się przed widocznością, deklaracja
`static` MUSI znajdować się po widoczności.

- Słowa kluczowe definiujące instrukcje sterujące MUSZĄ posiadać jedną spację po ich deklaracji,
NIE WOLNO stosować tej przerwy dla wywołań metod i funkcji.

- Otwierający nawias klamrowy dla instrukcji sterujących MUSI rozpoczynać się od tej samej linii,
a zamykający MUSI znajdować się jedną linię poniżej kodu ciała instrukcji sterującej.

- NIE WOLNO dodawać żadnej spacji po nawiasie otwierającym instrukcji sterującej,
NIE WOLNO dodawać żadnej spacji przed nawiasem zamykającym instrukcji sterującej.

### 1.1. Przykład

Poniższy przykład zawiera w sobie niektóre zasady opisane powyżej:

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
        // ciało metody
    }
}
~~~

2. Zasady ogólne
----------------

### 2.1 Podstawowe standardy formatowania

Kod php MUSI stosować się do zasad podstawowych standardów formatowania [PSR-1].

### 2.2 Pliki

Wszystkie pliki php MUSZĄ używać unixowego kodu końca linii - LF (linefeed).

Wszystkie pliki php MUSZĄ kończyć się jedną pustą linią.

Tag zamykający `?>` MUSI zostać pominięty w plikach zawierających tylko kod php.

### 2.3. Linie

NIE WOLNO ustalać sztywnych limitów na długość linii.

"Miękki" limit na liczbę znaków w linii MUSI być równy 120 znaków, narzędzie do sprawdzania
stylów MUSI wyświetlić wtedy ostrzeżenie o przekroczeniu "miękkiego limitu", jednakże
NIE WOLNO mu wyświetlić błędu.

Linia NIE POWINNA być dłuższa niż 80 znaków, linie przekraczające tą wielkość POWINNY być
rozbite na pomniejsze linie nieprzekraczające 80 znaków.

NIE WOLNO dodawać spacji na końcu niepustej linii.

Puste linie MOGĄ być dodawane, aby zwiększyć czytelność i uwidocznić
powiązane ze sobą bloki kodu.

NIE WOLNO używać więcej niż jednego wyrażenia na linię.

### 2.4. Wcięcia

Kod php MUSI używać 4 spacji jako wcięcie, NIE WOLNO używać znaków tabulacji do wcięć.

> Notabene: Używanie tylko spacji oraz nie mieszanie tego podejścia ze znakami tabulacji, pomaga uniknąć
> problemów z porównywaniem plików, patche'ami, historią oraz adnotacjami. Używanie spacji pozwala w łatwy
> sposób dodawać niewielkie wcięcia dla dodatkowego wyrównania w linii.

### 2.5. Znaki specjalne oraz True/False/Null

[Znaki specjalne] w php MUSZĄ być zapisywane małymi literami.

Stałe php `true`, `false` i `null` MUSZĄ także być zapisywane małymi literami.

[Znaki specjalne]: http://php.net/manual/en/reserved.keywords.php



3. Przestrzenie nazw i deklaracja Use
-------------------------------------

Kiedy używamy deklaracji `namespace`, MUSI występować po niej jedna pusta linia.

Kiedy używamy deklaracji `use`, wszystkie deklaracje `use`
MUSZĄ występować poniżej deklaracji `namespace`.

Znak specjalny `use` MUSI być używany dla każdej deklaracji z osobna.

Po bloku deklaracji `use`, MUSI występować jedna pusta linia.

Na przykład:

~~~php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... dodatkowy kod php ...

~~~


4. Klasy, właściwości i metody
------------------------------

Termin "klasa" odnosi się poniżej do wszystkich klas, interfejsów i traitów.

### 4.1. Rozszerzanie i implementacja

Słowa kluczowe `extends` i `implements` MUSZĄ być deklarowane w tej samej linii, co nazwa klasy.

Otwierający nawias klamrowy klasy MUSI znajdować się w nowej linii, zamykający nawias klamrowy
MUSI znajdować się jedną linię poniżej kodu ciała klasy.

~~~php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // stałe, właściwości, metody
}
~~~

Lista implementowanych interfejsów MOŻE być rozbita na pojedyncze linie,
gdzie każda linia posiada jedno wcięcie. Kiedy używamy tego podejścia,
każdy implementowany interfejs MUSI znajdować się w nowej linii.

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
    // stałe, właściwości, metody
}
~~~

### 4.2. Właściwości

Widoczność MUSI być deklarowana dla wszystkich właściwości.

NIE WOLNO używać słowa kluczowego `var` przy deklaracji właściwości.

NIE WOLNO deklarować więcej niż jednej właściwości na wyrażenie.

Nazwa właściwości NIE POWINNA rozpoczynać się od znaku podkreślenia,
aby zaznaczyć chronioną lub prywatną widoczność.

Deklaracja właściwości wygląda tak jak w poniższym przykładzie.

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
~~~

### 4.3. Metody

Widoczność MUSI być deklarowana dla wszystkich metod.

Nazwa metody NIE POWINNA rozpoczynać się od znaku podkreślenia,
aby zaznaczyć chronioną lub prywatną widoczność.

NIE WOLNO deklarować metod ze spacją po nazwie metody. Otwierający nawias klamrowy
metody MUSI znajdować się w nowej linii, zamykający nawias klamrowy MUSI znajdować się
jedną linię poniżej kodu ciała metody. NIE WOLNO dodawać żadnej spacji po nawiasie otwierającym
metody, NIE WOLNO dodawać żadnej spacji przed nawiasem zamykającym metody.

Deklaracja metody wygląda tak jak w poniższym przykładzie. Należy zapamiętać miejsce nawiasów, przecinków, spacji oraz nawiasów klamrowych.

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // ciało metody
    }
}
~~~

### 4.4. Argumenty metod

NIE WOLNO dodawać spacji przed przecinkami w liście argumentów,
po każdym przecinku MUSI znajdować się jeden znak spacji.

Argumenty metody z domyślną wartością MUSZĄ znajdować się na końcu
listy argumentów.

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // ciało metody
    }
}
~~~

Lista argumentów MOŻE być rozdzielona na kilka linii, gdzie każda nowa linia
posiada pojedyncze wcięcie. Kiedy używamy wielu linii do prezentacji argumentów,
każdy z argumentów MUSI znajdować się w osobnej linii.

Jeżeli używamy wieloliniowych argumentów, nawias zamykający oraz nawias klamrowy otwierający metody MUSZĄ znajdować się w jednej linii, ze znakiem spacji pomiędzy nimi.

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
        // ciało metody
    }
}
~~~

### 4.5. `abstract`, `final` i `static`

Kiedy używamy słów kluczowych `abstract` lub `final`, ich deklaracje MUSZĄ
znajdować się przed deklaracjami widoczności.

Kiedy używamy słowa kluczowego `static`, jego deklaracja MUSI znajdować
się zawsze za deklaracją widoczności.

~~~php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // ciało metody
    }
}
~~~

### 4.6. Wywołania metod i funkcji

Kiedy wywołujemy metodę lub funkcję, NIE WOLNO dodawać spacji między nazwą metody
a nawiasem otwierającym. NIE WOLNO dodawać znaku spacji po nawiasie otwierającym,
podobnie dla nawiasu zamykającego – NIE WOLNO dodawać przed nim znaku spacji.
NIE WOLNO dodawać spacji przed przecinkami w liście argumentów, po każdym przecinku
MUSI znajdować się jeden znak spacji.

~~~php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
~~~

Lista argumentów MOŻE być rozdzielona na kilka linii, gdzie każda nowa linia
posiada pojedyncze wcięcie. Kiedy używamy wielu linii do prezentacji argumentów,
każdy z argumentów MUSI znajdować się w osobnej linii.

~~~php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
~~~

5. Instrukcje sterujące
-----------------------

Ogólne zasady stylowania instrukcji sterujących:

- Po słowie kluczowym instrukcji sterującej MUSI znajdować się jeden znak spacji
- NIE WOLNO dodawać znaku spacji po nawiasie otwierającym
- NIE WOLNO dodawać znaku spacji przed nawiasem zamykającym
- Pomiędzy nawiasem zamykającym a nawiasem klamrowym otwierającym, MUSI znajdować
się jeden znak spacji
- Ciało instrukcji sterującej POWINNO posiadać jedno wcięcie
- Zamykający nawias klamrowy MUSI znajdować się jedną linię poniżej ciała instrukcji sterującej

Ciało każdej instrukcji sterującej MUSI być otoczone nawiasami klamrowymi.
Ta zasada nadaje jedną formę strukturom oraz zmniejsza prawdopodobieństwo błędów
wynikających z niepoprawnego dodania kodu do ciała instrukcji.


### 5.1. `if`, `elseif`, `else`

Instrukcja `if` wygląda jak w przykładzie poniżej. Zapamiętaj pozycję nawiasów,
spacji oraz nawiasów klamrowych oraz to, że `else` oraz `elseif` znajdują się w
tej samej linii co zamykający nawias klamrowy poprzedniego ciała instrukcji.

~~~php
<?php
if ($expr1) {
    // ciało if
} elseif ($expr2) {
    // ciało elseif
} else {
    // ciało else
}
~~~
Słowo kluczowe `elseif` POWINNO być używane zamiast `else if`, tak aby wszystkie
słowa kluczowe instrukcji sterujących składały się z jednej frazy.


### 5.2. `switch`, `case`

Instrukcja `switch` wygląda jak w przykładzie poniżej. Zapamiętaj pozycję nawiasów,
spacji oraz nawiasów klamrowych. Wyrażenie `case` MUSI posiadać jedno wcięcie w
odniesieniu do instrukcji `switch`. Słowo kluczowe `break` (lub inne słowo kluczowe
kończące działanie switch'a) MUSI posiadać wcięcie na tym samym poziomie co ciało
wyrażenia `case`. Jeśli intencją programisty jest wywołanie kolejnych case'ów po nie
pustym wyrażeniu `case` – MUSI zostać dodany komentarz taki jak np. `// no break`.

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

Pętla `while` wygląda jak w przykładzie poniżej.
Zapamiętaj pozycję nawiasów, spacji oraz nawiasów klamrowych.

~~~php
<?php
while ($expr) {
    // ciało struktury
}
~~~

Analogicznie, pętla `do while` wygląda jak w przykładzie poniżej.
Zapamiętaj pozycję nawiasów, spacji oraz nawiasów klamrowych.

~~~php
<?php
do {
    // ciało struktury
} while ($expr);
~~~

### 5.4. `for`

Pętla `for` wygląda jak w przykładzie poniżej. Zapamiętaj pozycję nawiasów,
spacji oraz nawiasów klamrowych.

~~~php
<?php
for ($i = 0; $i < 10; $i++) {
    // ciało pętli for
}
~~~

### 5.5. `foreach`

Pętla `foreach` wygląda jak w przykładzie poniżej. Zapamiętaj pozycję nawiasów,
spacji oraz nawiasów klamrowych.

~~~php
<?php
foreach ($iterable as $key => $value) {
    // ciało pętli foreach
}
~~~

### 5.6. `try`, `catch`

Blok `try catch` wygląda jak w przykładzie poniżej. Zapamiętaj pozycję
nawiasów, spacji oraz nawiasów klamrowych.

~~~php
<?php
try {
    // ciało bloku try
} catch (FirstExceptionType $e) {
    // ciało bloku catch
} catch (OtherExceptionType $e) {
    // ciało bloku catch
}
~~~

6. Funkcje anonimowe
--------------------

Funkcje anonimowe MUSZĄ być deklarowane ze znakiem spacji po słowie
kluczowym `function`, oraz ze spacją przed i po słowie kluczowym `use`.

Otwierający nawias klamrowy MUSI znajdować się w tej samej linii,
a zamykający nawias klamrowy MUSI znajdować się w następnej linii za ciałem funkcji.

NIE WOLNO dodawać znaku spacji po nawiasie otwierającym listy argumentów i
listy zmiennych oraz NIE WOLNO dodawać znaku spacji przed nawiasem zamykającym
listy argumentów i listy zmiennych.

NIE WOLNO dodawać znaku spacji przed przecinkami w listach argumentów i
listach zmiennych, natomiast po każdym  przecinku MUSI znajdować się jeden znak spacji.

Argumenty funkcji anonimowej posiadające domyślne właściwości MUSZĄ znajdować się
na końcu listy argumentów.

Funkcje anonimowe wyglądają jak w przykładzie poniżej. Zapamiętaj pozycję nawiasów,
przecinków, spacji oraz nawiasów klamrowych:

~~~php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // ciało
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // ciało
};
~~~

Lista argumentów i lista zmiennych MOŻE być rozdzielona na kilka linii, gdzie
każda nowa linia posiada pojedyncze wcięcie. Kiedy używamy wielu linii do
prezentacji argumentów lub zmiennych, każdy z argumentów lub każda ze
zmiennych MUSZĄ znajdować się w osobnej linii.

Jeśli ostatnia lista (niezależnie czy jest to lista argumentów czy wartości)
jest rozdzielona na kilka linii, nawias zamykający i nawias klamrowy
otwierający MUSZĄ znajdować się razem w nowej linii ze znakiem spacji między nimi.

Poniżej kilka przykładów wieloliniowych funkcji anonimowych,
wykorzystujących lub nie listy argumentów i listy zmiennych.

~~~php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // ciało
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // ciało
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
   // ciało
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // ciało
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // ciało
};
~~~

Zapamiętaj, że zasady formatowania obowiązują także wtedy,
jeśli funkcja anonimowa użyta jest jako argument w wywołaniu funkcji lub metody.

~~~php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // ciało
    },
    $arg3
);
~~~


7. Wnioski
----------

Istnieje wiele rodzajów stylów i praktyk celowo pominiętych w powyższym przewodniku.
Mowa tutaj m.in. o:

- Deklaracjach zmiennych globalnych i stałych globalnych

- Deklaracjach funkcji

- Operatorach i znakach przypisania

- Wyrównaniach w linii

- Komentarzach i blokach dokumentacji

- Przedrostkach i przyrostkach w nazwach klas

- Najlepszych praktykach

Przyszłe rekomendacje MOGĄ poprawić i rozszerzyć ten przewodnik o nowe pozycje –
także o takie, które nie zostały opisane w powyższej liście.


Dodatek A. Ankieta
------------------

Podczas pisania tego poradnika, grupa PHP-FIG utworzyła ankietę, pomocną w określeniu popularnych praktyk wśród członków grupy. Wyniki ankiety zostały opublikowane poniżej dla przyszłych pokoleń.

### A.1. Dane ankiety

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

### A.2. Legenda ankiety

`indent_type`:
Typ wcięcia. `tab` = "Użycie tabulatora", `2` lub `4` = "liczba spacji".

`line_length_limit_soft`:
"Miękki" limit długości linii, w znakach. `?` – nieczytelne lub brak odpowiedzi , `no` – brak limitu.

`line_length_limit_hard`:
Sztywny limit długości linii, w znakach. `?` – nieczytelne lub brak odpowiedzi , `no` – brak limitu.

`class_names`:
Z jakich liter zbudowana jest nazwa klasy. `lower` = tylko małe, `lower_under` = małe litery
ze znakiem podkreślenia jako separator, `studly` = UpperCamelCase.

`class_brace_line`:
Czy otwierający nawias klamrowy klasy znajduje się w tej samej (`same`) czy nowej linii (`next`),
co słowo kluczowe class.

`constant_names`:
Z jakich liter zbudowane są stale klasy? `upper` – wielkie litery ze znakiem podkreślenia, jako separator.

`true_false_null`:
Słowa kluczowe `true`, `false`, i `null` są zapisywane małymi czy dużymi literami?

`method_names`:
Jak formatowane są nazwy metod? `camel` = camelCase, `lower_under` = małe litery ze znakiem
pokreślenia jako separator.

`method_brace_line`:
Czy nawias klamrowy otwierający znajduje się w tej samej linii, co nazwa metody, czy w następnej?

`control_brace_line`:
Czy nawias klamrowy otwierający znajduje się w tej samej linii, co słowo kluczowe instrukcji
sterującej, czy w następnej?

`control_space_after`:
Czy po słowie kluczowym określającym instrukcję sterującą, znajduje się spacja?

`always_use_control_braces`:
Czy instrukcje sterujące używają zawsze nawiasów klamrowych?

`else_elseif_line`:
Czy podczas używania słów kluczowych `else` lub `elseif` znajdują się one w tej samej linii co wcześniejszy nawias klamrowy zamykający, czy w następnej?

`case_break_indent_from_switch`:
Jak wiele wcięć w instrukcji `switch` posiadają `case` i `break`?

`function_space_after`:
Czy wywołania funkcji posiadają znak spacji po nazwie funkcji a przed nawiasem otwierającym?

`closing_php_tag_required`:
Czy tag zamykający `?>` jest wymagany w plikach zawierających tylko PHP?

`line_endings`:
Jaki jest kod końca linii?

`static_or_visibility_first`:
Co jest pierwsze podczas deklarowania metody – widoczność czy słowo kluczowe `static`?

`control_space_parens`:
Czy w instrukcjach sterujących znajdują się spacje po nawiasie otwierającym i przed
nawiasem zamykającym? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
Czy po otwierającym tagu PHP istnieje pusta linia?

`class_method_control_brace`:
Podsumowanie - w którym miejscu znajduje się otwierający nawias klamrowy dla metod,
klas oraz instrukcji sterujących.

### A.3. Wyniki ankiety

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
