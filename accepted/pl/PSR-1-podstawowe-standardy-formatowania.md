Podstawowe standardy formatowania
=================================

Poniższa sekcja standardów zawiera reguły, które są uznawane za konieczne 
dla zachowania wysokiego poziomu interoperacyjności kodu PHP pochodzącego z różnych źródeł.

Następujące słowa "MUSI", "NIE WOLNO", "WYMAGANE", "POWINNO", "NIE POWINNO", "REKOMENDWANE", "MOŻE" oraz 
"OPCJONALNE" powinny być interpretowane tak jak opisano to w [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


1. Podsumowanie
---------------

- Pliki z kodem php MUSZĄ korzystać z tagów `<?php` i `<?=`.

- Pliki MUSZĄ korzystać z kodowania UTF-8 bez BOM.

- Pliki POWINNY zawierać deklaracje struktur języka php (klas, funkcji, stałych itp.) LUB definować 
  zachowanie czyli tak zwane skutki uboczne (np. generowanie wyjścia, zmiana parametrów konfiguracyjnych .ini itp.), 
  lecz NIE POWINNY robić tych dwóch rzeczy naraz.
  
- Przestrzenie nazw oraz klasy MUSZĄ stosować się do standardów PSR dotyczących automatycznego ładowania: [[PSR-0], [PSR-4]].

- Nazwy klas MUSZĄ być zapisywane w notacji `UpperCamelCase`.

- Stałe w klasach MUSZĄ być deklarowane wielkimi literami, ze znakiem podkreślenia 
 (`_`) używanym jako separator.

- Nazwy metod MUSZĄ być tworzone w notacji `camelCase`.


2. Pliki
--------

### 2.1. Tagi PHP

Kod PHP MUSI korzystać z "długich" tagów `<?php ?>` lub z krótkich `<?= ?>`, 
NIE WOLNO korzystać z innych tagów otwierających/zamykających.

### 2.2. Kodowanie znaków

Kod php MUSI być zapisywany w kodowaniu UTF-8 bez BOM.

### 2.3. Skutki uboczne

W programowaniu fraza "skutek/efekt uboczny" tyczy się wyrażenia, wywołania funkcji lub metody, 
który wykracza poza zwrócenie wartości, np. interakcja z systemem operacyjnym, lub zmiana wartości 
zmiennej globalnej.

Pojedynczy plik POWINIEN zawierać w sobie deklaracje "obiektów" języka PHP (klasy, funkcje, stałe itp.) 
bez jakichkolwiek skutków ubocznych lub wykonywać logikę programu, która wiąże się z efektami ubocznymi. 

Plik NIE POWINIEN wykonywać tych dwóch rzeczy na raz. Efekty uboczne w php to m.in. generowanie wyjścia, 
używanie `require` lub `include`, podłączenie do zewnętrznej usługi, modyfikacja parametrów ini, 
rzucanie błędów lub wyjątków, modyfikacja globalnych lub statycznych zmiennych, 
czytanie lub zapis z/do pliku itd.

Poniższy przykład pliku php posiada zarówno deklaracje jak i skutki uboczne, należy unikać takich zapisów:

```php
<?php
// skutek uboczny: zmiana ustawień ini
ini_set('error_reporting', E_ALL);

// skutek uboczny: ładowanie pliku
include "file.php";

// skutek uboczny: generowanie wyjścia
echo "<html>\n";

// deklaracja
function foo()
{
    // ciało funkcji
}
```

Kolejny przykład pliku php zawiera w sobie tylko deklaracje (tutaj funkcji), 
czyli zapis, który należy naśladować:

```php
<?php
// deklaracja
function foo()
{
    // ciało funkcji
}

// instrukcja warunkowa *nie* jest skutkiem ubocznym
if (! function_exists('bar')) {
    function bar()
    {
        // ciało funkcji
    }
}
```


3. Przestrzenie nazw oraz nazwy klas
------------------------------------

Standardy tworzenia przestrzeni nazw oraz klas MUSZĄ podążać za PSRami dotyczącymi automatycznego ładowania klas: [[PSR-0], [PSR-4]].

Deklaracji pojedynczej klasy odpowiada jeden plik, a jej przestrzeń nazw znajduje się na 
najniższym poziomie, gdzie na najwyższym poziomie znajduje się nazwa vendora.

Nazwa klasy MUSI być zadeklarowana w notacji `UpperCamelCase`.

Kod napisany w PHP 5.3 i późniejszych wersjach MUSI używać przestrzeni nazw.

Na przykład:

```php
<?php
// PHP wersja 5.3 i późniejsze:
namespace Vendor\Model;

class Foo
{
}
```

Kod napisany dla wersji PHP 5.2.x oraz niższych, 
POWINIEN używać konwencji prefiksów (np. `Vendor_` ) dla symulacji przestrzeni nazw w oparciu o nazwy klas.

```php
<?php
// PHP wersja 5.2.x i wcześniejsze:
class Vendor_Model_Foo
{
}
```

4. Stałe klas, właściwości i metody
-----------------------------------

Termin "klasa" odnosi się poniżej do wszystkich klas, interfejsów i traitów.

### 4.1. Stałe

Stałe klas MUSZĄ być deklarowane wielkimi literami, ze znakiem podkreślenia (underscore) 
używanym jako separator. 
Przykład:

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. Właściwości

Powyższy przewodnik celowo nie rekomenduje żadnych standardów dotyczących nazewnictwa właściwości 
klas (np. `$camelCase`, `$UpperCamelCase` czy `$znak_podkreslenia`).

Jakakolwiek forma nazewnictwa właściwości jest używana – POWINNA być stosowana 
konsekwentnie dla danego obszaru kodu. Obszar ten może zostać określony na poziomie 
vendora, paczki, klasy czy metody.

### 4.3. Metody

Nazwy metod MUSZĄ być deklarowane w notacji `camelCase()`.
