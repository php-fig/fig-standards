Руководство по стилю кода
=========================

Данное руководство расширяет и дополняет основной стандарт кодирования [PSR-1].

Цель данного руководства — уменьшить когнитивное сопротивление при
визуальном восприятии кода, написанного разными авторами. Для этого составлен
список распространённых правил и ожиданий относительно форматирования
PHP-кода.

Представленные здесь стилистические правила получены на основе обобщения опыта
различных проектов. При сотрудничестве разных авторов над множеством проектов,
полезно применять единый набор руководящих принципов для этих проектов.
Таким образом, польза данного руководства не в правилах, как таковых,
а в их распространённости.

Ключевые слова «НЕОБХОДИМО»/«ДОЛЖНО» («MUST»), «НЕДОПУСТИМО»/«НЕ ДОЛЖНО» («MUST NOT»), «ТРЕБУЕТСЯ»
(«REQUIRED»), «НУЖНО» («SHALL»), «НЕ ПОЗВОЛЯЕТСЯ» («SHALL NOT»), «СЛЕДУЕТ»
(«SHOULD»), «НЕ СЛЕДУЕТ» («SHOULD NOT»), «РЕКОМЕНДУЕТСЯ» («RECOMMENDED»),
«ВОЗМОЖНО» («MAY») и «НЕОБЯЗАТЕЛЬНО» («OPTIONAL»)
в этом документе должны расцениваться так, как описано в [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. Обзор
--------

- НЕОБХОДИМО следовать "руководству по стилю кода" PSR [[PSR-1]].

- Для отступов НЕОБХОДИМО использовать 4 пробела, а не табы.

- НЕДОПУСТИМО жёстко ограничивать длину строки; мягкое ограничение длины строки ДОЛЖНО быть 120
  символов; строки СЛЕДУЕТ делать не длиннее 80 символов.

- НЕОБХОДИМО оставлять одну пустую строку после объявления пространства имён (`namespace`), и 
  НЕОБХОДИМО оставлять одну пустую строку после блока операторов `use`.

- Открывающие фигурные скобки классов НЕОБХОДИМО переносить на следующую строку, а закрывающие фигурные скобки
  переносить на следующую строку после тела.

- Открывающие фигурные скобки методов НЕОБХОДИМО переносить на следующую строку, а закрывающие фигурные скобки
  переносить на следующую строку после тела.

- Видимость НЕОБХОДИМО объявлять для всех свойств и методов; `abstract` и
  `final` НЕОБХОДИМО ставить перед модификатором видимости; `static` НЕОБХОДИМО ставить
  после модификатора видимости.
  
- После ключевых слов управляющих структур НЕОБХОДИМО ставить один пробел;
  а после названий функций и методов НЕДОПУСТИМО.

- Открывающие фигурные скобки управляющих структур НЕОБХОДИМО оставлять на той же строке, а закрывающие фигурные скобки
  переносить на следующую строку после тела.

- НЕДОПУСТИМО ставить пробел после открывающих круглых скобок управляющих структур,
  и НЕДОПУСТИМО ставить пробел перед закрывающими круглыми скобками управляющих структур.

### 1.1. Пример

Этот пример даёт краткое представление о некоторых правилах, описанных ниже:

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
        // тело метода
    }
}
```

2. Общие положения
------------------

### 2.1 Основной стандарт кодирования

В коде НЕОБХОДИМО следовать правилам, описанным в [PSR-1].

### 2.2 Файлы

Во всех PHP файлах НЕОБХОДИМО использовать окончания строк LF (\n).

Все PHP файлы НЕОБХОДИМО заканчивать одной пустой строкой.

Закрывающий тэг `?>` НЕОБХОДИМО удалять из файлов, содержащих только PHP.

### 2.3. Строки

НЕДОПУСТИМО жёстко ограничивать длину строки.

Мягкое ограничение длины строки ДОЛЖНО быть 120 символов; автоматизированные
проверятели стиля ДОЛЖНЫ предупреждать о нарушении мягкого ограничения,
но не считать это ошибкой.

НЕ СЛЕДУЕТ делать строки длиннее 80 символов; те строки, что длиннее СЛЕДУЕТ
разбивать на несколько строк, не более 80 символов в каждой.

НЕДОПУСТИМО оставлять пробелы в конце не пустых строк.

МОЖНО использовать пустые строки для улучшения читаемости и обозначения связанных блоков кода.

НЕДОПУСТИМО писать на одной строке более одной инструкции.

### 2.4. Отступы

В коде НЕОБХОДИМО использовать отступ в 4 пробела, и НЕДОПУСТИМО применять табы для отступов.

> N.b.: Использование одних пробелов, без примеси табов, помогает избежать
> проблем с диффами, патчами, историей и авторством строк. Использование пробелов
> так же позволяет легко и точно сделать меж строчное выравнивание.

### 2.5. Ключевые слова и True/False/Null

[Ключевые слова PHP] НЕОБХОДИМО писать в нижнем регистре.

Константы PHP: `true`, `false`, и `null` НЕОБХОДИМО писать в нижнем регистре.

[Ключевые слова PHP]: http://php.net/manual/en/reserved.keywords.php



3. Пространства имён и оператор use
---------------------------------

При наличии пространства имён (`namespace`), после его объявления необходимо оставить одну пустую строку.

При наличии операторов `use`, НЕОБХОДИМО располагать их после объявления пространства имён.

НЕОБХОДИМО использовать один оператор `use` на одно объявление (импорт или создание псевдонима).

НЕОБХОДИМО оставлять одну пустую строку после блока операторов `use`.

Например:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... additional PHP code ...

```


4. Классы, свойства и методы
-----------------------------------

Слово "класс" относится ко всем классам, интерфейсам и трейтам.

### 4.1. Extends и implements

Ключевые слова `extends` и `implements` НЕОБХОДИМО располагать на одной
строке с именем класса.

Открывающую фигурную скобку класса НЕОБХОДИМО переносить на следующую строку;
закрывающую фигурную скобку класса НЕОБХОДИМО располагать на следующей строке
после тела класса.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // константы, свойства, методы
}
```

Список `implements` МОЖНО разбить на несколько строк, каждая из которых
с одним отступом. При этом, первый интерфейс в списке НЕОБХОДИМО перенести
на следующую строку, и в каждой строке НЕОБХОДИМО указать только один интерфейс.

```php
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
    // константы, свойства, методы
}
```

### 4.2. Свойства

Видимость НЕОБХОДИМО объявлять для всех свойств;

Использовать ключевое слово `var` для объявления свойств НЕДОПУСТИМО.

НЕДОПУСТИМО в одном объявлении указывать более одного свойства.

НЕ СЛЕДУЕТ начинать название свойства с подчёркивания для обозначения приватной
или защищённой видимости.

Объявление свойства выглядит как показано ниже.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. Методы

Видимость НЕОБХОДИМО объявлять для всех методов;

НЕ СЛЕДУЕТ начинать название метода с подчёркивания для обозначения приватной
или защищённой видимости.

НЕДОПУСТИМО объявлять методы с пробелом после названия метода. Открывающую
фигурную скобку НЕОБХОДИМО располагать на отдельной строке; закрывающую
фигурную скобку НЕОБХОДИМО располагать на следующей строке после тела метода.
НЕДОПУСТИМО оставлять пробел после открывающей круглой скобки и перед закрывающей.

Объявление метода выглядит как показано ниже. Обратите внимание на расположение
запятых, пробелов, круглых, квадратных и фигурных скобок:

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // тело метода
    }
}
```    

### 4.4. Аргументы методов

В списке аргументов НЕДОПУСТИМЫ пробелы перед запятыми, и НЕОБХОДИМ один пробел
после каждой запятой.

Аргументы метода со значениями по-умолчанию НЕОБХОДИМО располагать в конце
списка аргументов.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // тело метода
    }
}
```

Список аргументов МОЖНО разбить на несколько строк, каждая из которых
с одним отступом. При этом, первый аргумент в списке НЕОБХОДИМО перенести
на следующую строку, и в каждой строке НЕОБХОДИМО указать только один аргумент.

Когда список аргументов разбит на несколько строк, закрывающую круглую скобку
и открывающую фигурную НЕОБХОДИМО располагать на одной отдельной строке, с
одним пробелом между ними.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // тело метода
    }
}
```

### 4.5. `abstract`, `final` и `static`

При наличии, ключевых слов `abstract` и `final`, НЕОБХОДИМО чтобы они
предшествовали модификаторам видимости.

При наличии, ключевого слова `static`, НЕОБХОДИМО чтобы оно следовало за
модификатором видимости.

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // тело метода
    }
}
```

### 4.6. Вызовы функций и методов

При вызове метода или функции НЕДОПУСТИМЫ пробелы между названием метода или
функции открывающей круглой скобкой, а так же НЕДОПУСТИМЫ пробелы после
открывающей круглой скобки и перед закрывающей. В списке аргументов НЕДОПУСТИМЫ
пробелы перед запятыми, и НЕОБХОДИМ один пробел после каждой запятой.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

Список аргументов МОЖНО разбить на несколько строк, каждая из которых
с одним отступом. При этом, первый аргумент в списке НЕОБХОДИМО перенести
на следующую строку, и в каждой строке НЕОБХОДИМО указать только один аргумент.

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. Управляющие структуры
---------------------

Общие правила стиля для управляющих структур таковы:

- После ключевого слова управляющей структуры НЕОБХОДИМ один пробел
- После открывающей круглой скобки пробел НЕДОПУСТИМ
- Перед закрывающей круглой скобкой пробел НЕДОПУСТИМ
- НЕОБХОДИМ один пробел между закрывающей круглой скобкой и открывающей
  фигурной скобкой
- Тело управляющей структуры НЕОБХОДИМО смещать на один отступ
- Закрывающую фигурную скобку НЕОБХОДИМО располагать на следующей строке после тела

Тело каждой управляющей структуры НЕОБХОДИМО заключать в фигурные скобки. Это
стандартизирует вид управляющих структур и уменьшает вероятность возникновения
ошибок при добавлении новых строк в тело.


### 5.1. `if`, `elseif`, `else`

Управляющая структура `if` выглядит как показано ниже. Обратите внимание
на расположение пробелов, круглых и фигурных скобок; и что `else` и `elseif`
расположены на одной строке с закрывающей фигурной скобкой предыдущего тела.

```php
<?php
if ($expr1) {
    // тело if
} elseif ($expr2) {
    // тело elseif
} else {
    // тело else;
}
```

Вместо `else if` СЛЕДУЕТ использовать `elseif` чтобы все ключевые слова
управляющих структур выглядели как одно слово.


### 5.2. `switch`, `case`

Управляющая структура `switch` выглядит как показано ниже. Обратите внимание
на расположение пробелов, круглых и фигурных скобок: Выражение `case`
НЕОБХОДИМО смещать на один отступ от `switch`, а ключевое слово `break` (или
другое завершающее ключевое слово) НЕОБХОДИМО смещать на тот же уровень, что
и тело `case`. При умышленном проваливании из не пустого `case` НЕОБХОДИМ 
комментарий вроде `// no break`.

```php
<?php
switch ($expr) {
    case 0:
        echo 'Первый case, заканчивается на break';
        break;
    case 1:
        echo 'Второй case, с умышленным проваливанием';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Третий case, завершается словом return вместо break';
        return;
    default:
        echo 'По-умолчанию';
        break;
}
```


### 5.3. `while`, `do while`

Управляющая структура `while` выглядит как показано ниже. Обратите внимание
на расположение пробелов, круглых и фигурных скобок:

```php
<?php
while ($expr) {
    // structure body
}
```

Аналогично, управляющая структура `do while` выглядит как показано ниже.
Обратите внимание на расположение пробелов, круглых и фигурных скобок:

```php
<?php
do {
    // structure body;
} while ($expr);
```

### 5.4. `for`

Управляющая структура `for` выглядит как показано ниже. Обратите внимание
на расположение пробелов, круглых и фигурных скобок:

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // тело for
}
```

### 5.5. `foreach`
    
Управляющая структура `foreach` выглядит как показано ниже. Обратите внимание
на расположение пробелов, круглых и фигурных скобок:

```php
<?php
foreach ($iterable as $key => $value) {
    // тело foreach
}
```

### 5.6. `try`, `catch`

Блок `try catch` выглядит как показано ниже. Обратите внимание на расположение
пробелов, круглых и фигурных скобок:

```php
<?php
try {
     // тело try
} catch (FirstExceptionType $e) {
    // тело catch
} catch (OtherExceptionType $e) {
    // catch body
}
```

6. Замыкания
-----------

В объявлении замыкания НЕОБХОДИМ пробел после ключевого слова `function`,
а так же до и после ключевого слова `use`.

Открывающую фигурную скобку НЕОБХОДИМО располагать на той же строке;
закрывающую фигурную скобку НЕОБХОДИМО располагать на следующей строке после
тела замыкания.

НЕДОПУСТИМЫ пробелы после открывающей круглой скобки списка аргументов или
переменных, и НЕДОПУСТИМЫ пробелы перед закрывающей круглой скобкой списка
аргументов или переменных.

В списке аргументов и в списке переменных НЕДОПУСТИМЫ пробелы перед запятыми,
и НЕОБХОДИМ один пробел после каждой запятой.

Аргументы замыкания со значениями по-умолчанию НЕОБХОДИМО располагать в конце
списка аргументов.

Объявление замыкания выглядит как показано ниже. Обратите внимание на
расположение запятых, пробелов, круглых, квадратных и фигурных скобок:

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // тело
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

Список аргументов и список переменных МОЖНО разбить на несколько строк, каждая
из которых с одним отступом. При этом, первый элемент в списке НЕОБХОДИМО
перенести на следующую строку, и в каждой строке НЕОБХОДИМО указать только один
аргумент или одну переменную.

Когда последний список (или аргументов, или переменных) разбит на несколько
строк, закрывающую круглую скобку и открывающую фигурную НЕОБХОДИМО
располагать на одной отдельной строке, с одним пробелом между ними.

Ниже показаны примеры замыканий с аргументами и без, а так же со списком
свободных переменных на несколько строк.

```php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // тело
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // тело
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
   // тело
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // тело
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};
```

Обратите внимание, правила форматирования так же применяются, когда замыкание
используется напрямую в качестве аргумента при вызове метода или функции.

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // body
    },
    $arg3
);
```


7. Заключение
--------------

Многие элементы стиля и практики были преднамеренно опущены в данном руководстве.
Вот некоторые из них:

- Объявление глобальных переменных и глобальных констант

- Объявление функций

- Операторы и присваивание

- Выравнивание внутри строк

- Комментарии и блоки документации

- Префиксы и суффиксы в названиях классов

- Лучшие практики

Будущие рекомендации МОГУТ исправлять и расширять данное руководство,
обращаясь к этим или другим элементам стиля и практикам.


Дополнение A. Исследование
------------------

При написании данного руководства, группа авторов провела исследование по
выявлению общих практик в проектах участниках.  Это исследование сохранено
здесь для потомков.

### A.1. Данные исследования

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

### A.2. Легенда Исследования

`indent_type`:
Тип отступов. `tab` = "Используется таб", `2` or `4` = "число пробелов"

`line_length_limit_soft`:
"Мягкий" лимит длины строки в символах. `?` = не определён, `no` нет лимита.

`line_length_limit_hard`:
"Жёсткий" лимит длины строки в символах. `?` = не определён, `no` нет лимита.

`class_names`:
Названия классов. `lower` = только нижний регистр, `lower_under` = нижний регистр и подчёркивания, `studly` = StudlyCase.

`class_brace_line`:
Открывающая фигурная скобка класса идёт на той же (`same`) строке, что и слово class, или на следующей (`next`)?

`constant_names`:
Как пишутся названия констант классов? `upper` = В верхнем регистре с подчёркиваниями.

`true_false_null`:
Ключевые слова `true`, `false` и `null` пишутся в нижнем (`lower`) или верхнем (`upper`) регистре?

`method_names`:
Как пишутся названия методов? `camel` = `camelCase`, `lower_under` = в нижнем регистре с подчёркиваниями.

`method_brace_line`:
Открывающая фигурная скобка метода идёт на той же (`same`) строке, что и слово class, или на следующей (`next`)?

`control_brace_line`:
Открывающая фигурная скобка управляющей структуры идёт на той же (`same`) строке, что и слово class, или на следующей (`next`)?

`control_space_after`:
Есть ли пробел после ключевого слова управляющей структуры?

`always_use_control_braces`:
Всегда ли управляющие структуры используют фигурные скобки?

`else_elseif_line`:
При использовании `else` или `elseif`, они располагаются на одной (`same`)
строке с закрывающей фигурной скобкой или на следующей (`next`)?

`case_break_indent_from_switch`:
Сколько горизонтальных отступов у `case` и `break` относительно `switch`?

`function_space_after`:
Есть ли у вызова функции пробел между названием функции и открывающей круглой скобкой?

`closing_php_tag_required`:
Требуется ли закрывающий тэг `?>` в файлах, содержащих только PHP?

`line_endings`:
Тип окончаний строк?

`static_or_visibility_first`:
При объявлении метода впереди идёт `static` или модификатор видимости? 

`control_space_parens`:
Есть ли пробелы внутри скобок управляющих структур? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
Есть ли пустая строка после открывающего тэга PHP?

`class_method_control_brace`:
На какой строке пишется открывающая фигурная скобка класса, метода и управляющей структуры?

### A.3. Результат исследования

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
