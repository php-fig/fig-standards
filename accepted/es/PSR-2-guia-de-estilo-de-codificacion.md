Guía de estilo de condificación
=======================

Esta guía amplía y extiende el [PSR-1][], el estándar de codificación básica.

La intención de esta guía es la de reducir la dificultad cuando se revisa un código de autores diferentes.
Lo realiza mediante la enumeración de un conjunto común de normas y expectativas sobre cómo dar formato al código PHP.

Las reglas de estilo de este documento se derivan de los puntos comunes de los diferentes miembros del proyecto. 
Cuando varios autores colaboran en varios proyectos, ayuda tener un conjunto de directrices que se utilizarán en todos los proyectos.
Por tanto, el beneficio de esta guía no está en las normas en sí, sino en el hecho de compartir de esas reglas.

Las palabras claves "TIENE QUE" ("MUST"/"SHALL"), "NO TIENE QUE" ("MUST NOT"/"SHALL NOT"), "NECESARIO" ("REQUIRED"), "DEBERÍA" ("SHOULD"), "NO DEBERÍA" ("SHOULD NOT"), "RECOMENDADO" ("RECOMMENDED"), "PUEDE" ("MAY") y "OPCIONAL" ("OPTIONAL") de este documento son una traducción de las palabras inglesas descritas en [RFC 2119][] y deben ser interpretadas de la siguiente manera: 
- TIENE QUE o REQUERIDO implica que es un requisito absoluto de la especificación.
- NO TIENE QUE conlleva la completa prohibición de la especificación.
- DEBERÍA o RECOMENDADO implica que pueden existen razones válidas para ignorar dicho elemento, pero las implicaciones que ello conlleva deben ser entendidas y sopesadas antes de elegir una opción diferente.
- NO DEBERÍA implica que pueden existir razones bajo ciertas circunstancias cuando el comportamiento es aceptable o incluso útil, pero todas las implicaciones deben ser entendidas cuidadosamente y sopesadas antes de implementar algún comportamiento descrito por esta etiqueta para ignorar dicho comportamiento.
- PUEDE u OPCIONAL implica que el elemento es puramente opcional. Cualquier proveedor puede elegir incluir dicho elemento porque crea que conlleva mejoras en su producto mientras otro puede elegir obviarlas. Una implementación que no incluya un opción particular TIENE QUE estar preparada para operar con otra implementación que incluya dicha opción, aunque implique limitar la funcionalidad. De la misma manera, una implementación que incluya una opción particular TIENE QUE estar preparada para otra que no la incluya (excepto, por supuesto, para la característica que la opción provea).

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. Visión general
-------------------

- El código TIENE QUE seguir [PSR-1][].

- El código TIENE QUE usar 4 espacios como indentación y no tabulación.

- NO TIENE QUE existir un límite fijo a la longitud de línea, y el límite dinámico TIENE QUE ser 120 caracteres; las líneas DEBERÍAN tener 80 caracteres o menos.

- TIENE QUE haber una línea en blanco después de las declaraciones de `namespace`, y TIENE QUE haber  una línea en blanco después del bloque de declaraciones `use`.

- Las llaves de apertura de las clases TIENEN QUE ir en la siguiente línea, y las llaves de cierre TIENEN QUE ir en la línea siguiente posterior al cuerpo de la clase.

- Las llaves de apertura de los métodos TIENEN QUE ir en la siguiente línea, y las llaves de cierre TIENEN QUE ir en la línea posterior al cuerpo del método.

- La visibilidad TIENE QUE estar declarada en todas las propiedades y métodos; `abstract` y `final` TIENEN QUE estar declaradas antes de la visibilidad; `static` se TIENE QUE declarar después de la visibilidad.

- Las palabras clave de control de estructuras TIENEN QUE tener un espacio después de ellos, método y llamadas a funciones NO TIENEN QUE tenerlo.

- Las llaves de apertura en las estructuras de control TIENEN QUE ir en la misma línea, y las de cierre TIENEN QUE ir en la siguiente línea posterior al cuerpo.

- Los paréntesis de apertura en las estructuras de control NO TIENEN QUE tener un espacio después de ellos, y los paréntesis de cierre NO TIENEN QUE tener un espacio antes de ellos.

### 1.1. Ejemplo

Este ejemplo incluye algunas de las siguientes reglas a modo de visión general rápida:

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
        // Cuerpo del método
    }
}
```

2. General
------------

### 2.1 Estándar de codificación básica

El código TIENE QUE seguir las normas expuestas en el [PSR-1][].

### 2.2 Ficheros

Todos los ficheros PHP TIENEN QUE usar el final de linea Unix LF.

Todos los ficheros PHP TIENEN QUE finalizar con un línea en blanco.

La etiqueta de cierre `?>` TIENE QUE ser omitida en todos aquellos ficheros que únicamente incluyan código PHP.

### 2.3. Líneas

NO TIENE QUE haber un límite fijo a la longitud de línea.

El límite dinámico de longitud de línea TIENE QUE ser de 120 caracteres; los chequeadores de estilos automatizados TIENEN QUE adevertir de ésto, pero NO TIENE que dar error.

Las líneas NO DEBERÍAN ser más largas de 80 caracteres; las líneas más largas de estos 80 caracteres DEBERÍAN dividirse en múltiples líneas de no más de 80 caracteres cada una.

NO TIENE QUE haber espacios en blanco al final de las líneas que no estén vacías.

PUEDEN ser añadidas líneas en blanco para mejorar la lectura del código y para indicar bloques que estén relacionados.

NO TIENE QUE haber más de una sentencia por línea.

### 2.4. indentación

El código TIENE QUE  usar una indentación de 4 espacios, y NO TIENE QUE usar tabulación para la indentación.

> Nota: Utilizando sólo los espacios y no mezclar espacios con tabuladores,
> ayuda a evitar problemas con diffs, parches, históricos y anotaciones.
> El uso de los espacios también facilita afinar la alineación interlineada.

### 2.5. Palabras clave y True/False/Null

Las [Palabras clave][] de PHP TIENEN QUE estar en minúsculas.

Las constantes de PHP `true`, `false` y `null` TIENEN QUE estar en minúsculas.

[Palabras clave]: http://php.net/manual/en/reserved.keywords.php



3. Namespace y declaraciones `use`
----------------------------------------

Cuando esté presente, TIENE QUE haber una línea en blanco después de la declación del `namespace`.

Cuando estén presentes, todas las declaraciones `use` TIENEN QUE ir después de la declaración del `namespace`.

TIENE QUE haber un `use` por declaración.

TIENE QUE haber una línea en blaco después del bloque de declaraciones `use`.

Por ejemplo:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... código PHP adicional ...

```


4.Clases, propiedades y métodos
-------------------------------------

El término "clase" hace referencia a todas las clases, interfaces o traits.

### 4.1. Extensiones e implementaciones

Las palabras clave `extends` e `implements` TIENEN QUE declararse en la misma línea del nombre de la clase.

Las llaves de apertura de la clase TIENE QUE ir en la siguiente línea; las llaves de cierre TIENEN QUE ir en la siguiente línea al cuerpo de la clase.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constantes, propiedades, métodos
}
```

Las listas de `implements` PUEDEN ser divididas en múltiples líneas, donde las líneas subsiguientes serán indentadas una vez. Al hacerlo, el primer elemento de la lista TIENE QUE estar en la línea siguiente, y TIENE QUE haber una sola interfaz por línea.

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
    // constantes, propiedades, métodos
}
```

### 4.2. Propiedades

La visibilidad TIENE QUE ser declarada en todas las propiedades.

La palabra clave `var` NO TIENE QUE ser usada al declarar una propiedad.

NO TIENE QUE declararse más de una propiedad por sentencia.

Los nombres de las propiedades NO DEBERIAN tener prefijos con un guión bajo para indicar si es privada o protegida.

Una declaración de propiedas a modo de ejemplo.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. Métodos

La visibilidad TIENE QUE declararse en todos los métodos.

Los nombres de los métodos NO DEBERÍAN llevar ningún prefijo con guión bajo indicando la visibilidad.

Los nombres de métodos TIENEN QUE declararse con un espacio después del nombre del método. La llave apertura TIENE QUE situarse en su propia línea, y la llave de cierre TIENE QUE ir en la siguiente línea al cuerpo del método. NO TIENE QUE haber ningún espacio después de la apertura de los paréntesis, y NO TIENE QUE haber ningún espacio antes del paréntesis de cierre.

Una declaración de método se parece al siguiente ejempo. Fíjese en la situación de los paréntesis, comas, espacios y llaves:

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // Cuerpo del método
    }
}
```    

### 4.4. Argumentos de los métodos

En la lista de argumentos, NO TIENE QUE haber un espacio antes de cada coma, y TIENE QUE haber un espacio después de cada coma.

Los argumentos con valores por defecto de un método TIENE QUE ir al final de la lista.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // Cuerpo del método
    }
}
```

La lista de argumentos PUEDE dividirse en múltiples líneas, donde cada línea será indentada un vez.. Cuando se dividan de esta forma, el primer argumento TIENE QUE estar en la siguiente línea, y TIENE QUE haber únicamente un argumento por línea.

Cuando la lista de argumentos se divide en varias líneas, el paréntesis de cierre y la llave de apertura TIENEN QUE ir juntos en su propia línea, separados por un espacio.

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
        // Cierpo del método
    }
}
```

### 4.5. `abstract`, `final`, y `static`

Cuando estén presentes, las declaraciones `abstract` y `final` TIENEN QUE preceder a la declaración de visibilidad.

Cuando esté presente, la declaración `static` TIENE QUE ir después de la declaración de visibilidad.

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // Cuerpo del método
    }
}
```

### 4.6. Llamadas a métodos y funciones

Cuando se haga una llamada a un método o función, NO TIENE QUE haber espacio entre el nombre del método o función y el paréntesis de apertura, NO TIENE QUE haber espacio después de la apartura del paréntesis, y NO TIENE QUE haber espacio antes del paréntesis de cierre. In la lista de argumentos, NO TIENE QUE haber espacio antes de cada coma, y TIENE QUE haber un espacio después de cada coma.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

La lista de argumentos PUEDE dividirse en múltiples líneas, donde cada una se indenta una vez. Cuando esto suceda, el primer argumento TIENE QUE estar en la siguiente línea, y TIENE QUE haber sólo un argumento por línea.

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. Estructuras de control
----------------------------

Las reglas de estilo para las estructuras de control son las siguientes:

- TIENE QUE haber un espacio después de una palabra clave de estructura de control.
- NO TIENE QUE haber espacios después de la apertura de los paréntesis.
- NO TIENE QUE haber espacios antes del cierre de paréntesis.
- TIENE QUE haber un espacio entre paréntesis de cierre y la llave de apertura.
- El cuerpo de la estructura de control TIENE QUE estar indentada una vez.
- La llave de cierre TIENE QUE estar en la linea siguiente al final del cuerpo de la estructura.

El cuerpo de cada estructura TIENE QUE encerrarse entre llaves. Esto estandariza el aspecto de las estructuras y reduce la probabilidad de añadir errores como nuevas líneas que se añaden al cuerpo de la estructura.


### 5.1. `if`, `elseif`, `else`

Una estructura `if` se ve como sigue. Fíjese en el lugar de paréntesis, espacios y llaves; 
y que `else` y `elseif` están en la misma línea que las llaves de cierre anteriores.

```php
<?php
if ($expr1) {
    // if cuerpo
} elseif ($expr2) {
    // elseif cuerpo
} else {
    // else cuerpo;
}
```

La palabra reservada `elseif` DEBERÍA ser empleada en lugar de `else if`
tal que toda la estructura esté compuesta por palabras reservadas en un solo término.


### 5.2. `switch`, `case`

Una estructura `switch` se ve como se muestra a continuación. Fíjese
en el lugar donde están paréntesis, espacios y llaves. La palabra reservada `case`
TIENE QUE estar indentada una vez respecto al `switch` y la parabra reservada `break`
o cualquier otro término de finalización TIENEN QUE estar indentadas al mismo nivel
que el cuerpo del `case`. TIENE QUE ser comentado como `// no break`
cuando hay `case` en cascada no vacíos.

```php
<?php
switch ($expr) {
    case 0:
        echo 'Primer case con break';
        break;
    case 1:
        echo 'Segundo case sin break en cascada';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Tercer case; el return implica break';
        return;
    default:
        echo 'case por defecto';
        break;
}
```


### 5.3. `while`, `do while`

Una instrucción `while` se expresa a continuación.
Fíjese en el lugar donde están paréntesis, espacios y llaves.

```php
<?php
while ($expr) {
    // Cuerpo de la estructura
}
```

Igualmente, una sentencia `do while` se muestra a continuación.
Fíjese en el lugar donde están paréntesis, espacios y llaves.

```php
<?php
do {
    // Cuerpo de la estructura;
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
