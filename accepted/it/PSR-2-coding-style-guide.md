Coding Style Guide
==================

Questa guida estende ed integra il [PSR-1][], lo standard elementare di scrittura del codice.

L'intento di questa guida è di ridurre l'attrito cognitivo quando il codice
viene esaminato da diversi autori. Tutto questo è ottenuto grazie ad una serie
di regole e aspettative condivise su come formattare il codice PHP.

Le regole di stile qui riportate sono derivate dalla condivisione tra i vari
membri di progetti differenti. Quando vari autori collaborano su progetti multipli,
è di grande aiuto avere un insieme di linee guida da usare in tutti i progetti.
Perciò il beneficio di questa guida non è determinato dalle regole in sé, ma dalla
condivisione delle stesse.

Le parole "DEVE/DEVONO/NECESSARIO(I)" ("MUST", "SHALL" O "REQUIRED"),
"NON DEVE/NON DEVONO" ("MUST NOT" O "SHALL NOT"), "DOVREBBE/DOVREBBERO/RACCOMANDATO(I)"
("SHOULD") "NON DOVREBBE/NON DOVREBBERO" ("SHOULD NOT"), "PUO'/POSSONO" ("MAY") e
"OPZIONALE" ("OPTIONAL") in questo documento devono essere interpretate come
descritto nella [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. Panoramica
-----------

- Il codice DEVE seguire il [PSR-1][].

- Il codice DEVE usare 4 spazi per l'indentazione, non le tabulazioni.

- NON DEVE esserci un limite rigido alla lunghezza della riga; il limite
  debole DEVE essere di 120 caratteri; le righe DOVREBBERO essere di 80 caratteri o meno.

- Ci DEVE essere una riga vuota dopo la dichiarazione del `namespace`, e ci
  DEVE essere una riga vuota dopo il blocco delle dichiarazioni `use`.

- Le graffe di apertura per le classi DEVE andare su una nuova riga, e le graffe di chiusura DEVONO
  andare su una nuova linea dopo il corpo.

- Le graffe di apertura per i metodi DEVONO andare su una nuova riga, e le graffe di chiusura DEVONO
  andare su una nuova linea dopo il corpo.

- La visibilità DEVE essere dichiarata su tutte le proprietà e i metodi; `abstract` e
  `final` DEVONO essere dichiarate prima della visibilità; `static` DEVE essere dichiarata dopo
  la visibilità.
  
- Le keyword delle strutture di controllo DEVONO avere uno spazio a seguire; i metodi e le chiamate
  a funzioni NON DEVONO.

- Le graffe di apertura per le strutture di controllo DEVONO andare sulla stessa riga, e le graffe
  di chiusura DEVONO andare su una nuova riga dopo il corpo.

- Le graffe di apertura per le strutture di controllo NON DEVONO avere uno spazio a seguire, e le graffe
  di chiusura per le strutture di controllo NON DEVONO avere uno spazio a precedere.

### 1.1. Esempio

Questo esempio comprende alcune delle regole riportate come panoramica:

```php
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use AltroVendor\AltroPackage\BazClass;

class Foo extends Bar implements FooInterface
{
    public function metodoEsempio($a, $b = null)
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
        // corpo del metodo
    }
}
```

2. Regole Generali
-------------------

### 2.1 Standard elementare di scrittura codice

Il codice DEVE seguire tutte le regole delineane nel [PSR-1][].

### 2.2 I file

Tutti i file PHP DEVONO usare lo Unix LF (linefeed) per terminare la riga.

Tutti i file PHP DEVONO finire con una singola riga vuota.

Il tag `?>` di chiusura DEVE essere omesso dai file che contengono soltanto PHP.

### 2.3. Le righe

NON DEVE esserci un limite rigito nella lunghezza delle righe.

Il limite debole sulla lunghezza delle righe DEVE essere di 120 caratteri; correttori
automatici di sile DEVONO avvertire ma NON DEVONO andare in errore al raggiungimento
del limite debole.

Le righe NON DOVREBBERO essere più lunghe di 80 caratteri; righe più lunghe DOVREBBERO
essere divise in righe multiple successive, ognuna lunga non più di 80 caratteri.

NON DEVONO esserci spazi alla fine di righe non vuote.

Righe vuote POSSONO essere aggiunte per migliorare la leggibilità e per indicare
blocchi di codice correlati.

NON PUO' esserci più di una dichiarazione per riga.

### 2.4. Indentazione

Il codice DEVE usare un'indentazione di 4 spazi, e NON DEVE usare tabulazioni per
l'indentazione.

> N.b.: Usando soltanto spazi, senza mischiare spazi e tabulazioni, aiuta
> ad evitare con i diff, le patch, la cronologia e le annotazioni. L'uso di
> spazi rende peraltro facile l'inserimento di sotto-indentazione a grana fine
> per l'allineamento inter-riga.

### 2.5. Keyword e True/False/Null

Le [keywords][] PHP DEVONO essere minuscole.

Le costanti PHP `true`, `false`, e `null` DEVONO essere minuscole.

[keywords]: http://php.net/manual/en/reserved.keywords.php



3. Namespace e dichiarazioni Use
---------------------------------

Quando presente, DEVE esserci una linea vuota dopo la dichiarazione `namespace`.

Quando presenti, tutte le dichiarazioni `use` DEVONO andare dopo la dichiarazione
`namespace`.

Ci DEVE essere una keyword `use` per dichiarazione.

Ci DEVE essere una riga vuota dopo il blocco `use`.

Per esempio:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use AltroVendor\AltroPackage\BazClass;

// ... ulteriore codice PHP ...

```


4. Classi, Propertà e Metodi
-----------------------------

Il termine "classe" si riferisce a tutte le classi, interfacce e trait.

### 4.1. Extends e Implements

Le keyword `extends` e `implements` DEVONO essere dichiarate sulla stessa riga in cui si trova il nome
della classe.

La graffa di apertura della classe DEVE andare su una nuova riga; la graffa
di chiusura per la classe DEVE andare su una nuova riga dopo il corpo.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use AltroVendor\AltroPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // costanti, proprietà, metodi
}
```

Liste di `implements` POSSONO essere divise su righe multiple, dove ogni
linea seguente è indentata una volta. In questo caso, il primo elemento
nella lista DEVE andare su una nuova riga e ci DEVE essere una sola interfaccia
per riga.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use AltroVendor\AltroPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // costanti, proprietà, metodi
}
```

### 4.2. Proprietà

La visibilità DEVE essere dichiarata su tutte le proprietà.

La keyword `var` NON DEVE essere usata per dichiarare una proprietà.

NON PUO' esserci più di una proprietà per dichiarazione.

I nomi delle proprietà NON DOVREBBERO avere un underscore come prefisso ad indicare
visibilità protetta o privata.

La dichiarazione di una proprietà dovrebbe essere simile alla seguente:

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. Metodi

La visibilità DEVE essere dichiarata su tutti i metodi.

I nomi dei metodi NON DOVREBBERO avere un singolo underscore come prefisso
per indicare visibilità protetta o privata.

I nomi dei metodi NON DEVONO essere dichiarati con uno spazio dopo il nome
del metodo. La graffa di apertura DEVE andare su una nuova riga, e la
graffa di chiusura DEVE andare su una nuova riga dopo il corpo.
NON DEVE esserci uno spazio dopo la parentesi di apertura, e NON DEVE esserci
uno spazio prima della parentesi di chiusura.

La dichiarazione di un metodo dovrebbe essere simile alla seguente.
Da notare il posizionamento delle parentesi, delle virgole, degli spazi e
delle graffe:

```php
<?php
namespace Vendor\Package;

class NomeClass
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // corpo del metodo
    }
}
```    

### 4.4. Argomenti dei metodi

Nella lista di argomenti, NON PUO' esserci uno spazio prima di ogni virgola, ma
DEVE esserci uno spazio dopo ogni virgola.

Gli argomenti dei metodi con valori di default DEVONO andare alla fine della lista
di argomenti.

```php
<?php
namespace Vendor\Package;

class NomeClasse
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // corpo del metodo
    }
}
```

Le liste di argomenti POSSONO essere divise su righe multiple, dove ogni riga
seguente è indentata una volta. In questo caso, il primo elemento della lista
DEVE essere su una nuova riga e ci DEVE essere un solo argomento per riga.

Quando la lista di argomenti è divisa su righe multiple, le parentesi di chiusura
e la graffa di apertura DEVONO essere sulla stessa riga con uno spazio a
separarle.

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
        // corpo del metodo
    }
}
```

### 4.5. `abstract`, `final`, r `static`

Quando presente, le dichiarazioni `abstract` e `final` DEVONO precedere
le dichiarazioni di visibilità.

Quando presente, la dichiarazione `static` DEVE andare dopo la dichiarazione
di visibilità.

```php
<?php
namespace Vendor\Package;

abstract class NomeClasse
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // corpo del metodo
    }
}
```

### 4.6. Chiamate a metodi e funzioni

When making a method or function call, there MUST NOT be a space between the
method or function name and the opening parenthesis, there MUST NOT be a space
after the opening parenthesis, and there MUST NOT be a space before the
closing parenthesis. In the argument list, there MUST NOT be a space before
each comma, and there MUST be one space after each comma.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

Argument lists MAY be split across multiple lines, where each subsequent line
is indented once. When doing so, the first item in the list MUST be on the
next line, and there MUST be only one argument per line.

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. Control Structures
---------------------

The general style rules for control structures are as follows:

- There MUST be one space after the control structure keyword
- There MUST NOT be a space after the opening parenthesis
- There MUST NOT be a space before the closing parenthesis
- There MUST be one space between the closing parenthesis and the opening
  brace
- The structure body MUST be indented once
- The closing brace MUST be on the next line after the body

The body of each structure MUST be enclosed by braces. This standardizes how
the structures look, and reduces the likelihood of introducing errors as new
lines get added to the body.


### 5.1. `if`, `elseif`, `else`

An `if` structure looks like the following. Note the placement of parentheses,
spaces, and braces; and that `else` and `elseif` are on the same line as the
closing brace from the earlier body.

```php
<?php
if ($expr1) {
    // if body
} elseif ($expr2) {
    // elseif body
} else {
    // else body;
}
```

The keyword `elseif` SHOULD be used instead of `else if` so that all control
keywords look like single words.


### 5.2. `switch`, `case`

A `switch` structure looks like the following. Note the placement of
parentheses, spaces, and braces. The `case` statement MUST be indented once
from `switch`, and the `break` keyword (or other terminating keyword) MUST be
indented at the same level as the `case` body. There MUST be a comment such as
`// no break` when fall-through is intentional in a non-empty `case` body.

```php
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
```


### 5.3. `while`, `do while`

A `while` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

```php
<?php
while ($expr) {
    // structure body
}
```

Similarly, a `do while` statement looks like the following. Note the placement
of parentheses, spaces, and braces.

```php
<?php
do {
    // structure body;
} while ($expr);
```

### 5.4. `for`

A `for` statement looks like the following. Note the placement of parentheses,
spaces, and braces.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### 5.5. `foreach`
    
A `foreach` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

```php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### 5.6. `try`, `catch`

A `try catch` block looks like the following. Note the placement of
parentheses, spaces, and braces.

```php
<?php
try {
    // try body
} catch (FirstExceptionType $e) {
    // catch body
} catch (OtherExceptionType $e) {
    // catch body
}
```

6. Closures
-----------

Closures MUST be declared with a space after the `function` keyword, and a
space before and after the `use` keyword.

The opening brace MUST go on the same line, and the closing brace MUST go on
the next line following the body.

There MUST NOT be a space after the opening parenthesis of the argument list
or variable list, and there MUST NOT be a space before the closing parenthesis
of the argument list or variable list.

In the argument list and variable list, there MUST NOT be a space before each
comma, and there MUST be one space after each comma.

Closure arguments with default values MUST go at the end of the argument
list.

A closure declaration looks like the following. Note the placement of
parentheses, commas, spaces, and braces:

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

Argument lists and variable lists MAY be split across multiple lines, where
each subsequent line is indented once. When doing so, the first item in the
list MUST be on the next line, and there MUST be only one argument or variable
per line.

When the ending list (whether or arguments or variables) is split across
multiple lines, the closing parenthesis and opening brace MUST be placed
together on their own line with one space between them.

The following are examples of closures with and without argument lists and
variable lists split across multiple lines.

```php
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
```

Note that the formatting rules also apply when the closure is used directly
in a function or method call as an argument.

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


7. Conclusion
--------------

There are many elements of style and practice intentionally omitted by this
guide. These include but are not limited to:

- Declaration of global variables and global constants

- Declaration of functions

- Operators and assignment

- Inter-line alignment

- Comments and documentation blocks

- Class name prefixes and suffixes

- Best practices

Future recommendations MAY revise and extend this guide to address those or
other elements of style and practice.


Appendix A. Survey
------------------

In writing this style guide, the group took a survey of member projects to
determine common practices.  The survey is retained herein for posterity.

### A.1. Survey Data

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

### A.2. Survey Legend

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
