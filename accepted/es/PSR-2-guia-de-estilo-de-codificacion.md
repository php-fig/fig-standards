Guía de estilo de codificación
==============================

Esta guía extiende y expande lo descrito en [PSR-1][], estándar básico de 
codificación.

El objetivo de esta guía es reducir el rozamiento cognitivo al revisar código
de fierentes autores. Lo hace mediante la enumeración de una serie de reglas
y expectativas sobre como se debe dar formato al código PHP.

Las reglas de estilo de este documento derivan de los puntos en común de los
proyectos miembros. Cuando varios autores colaboran a lo largo de múltiples 
proyectos, ayuda el hecho de tener una serie de guías para utilizarlas a lo
largo de estos proyectos. Así, le beneficio de esta guía no está en las reglas
en sí mismo, sino en compartir estar reglas.

Las palabras clave "DEBE/MUST", "NO DEBE/MUST NOT", "REQUERIDO/REQUIRED", 
"SE DEBE/SHALL", "NO SE DEBE/SHALL NOT", "SE DEBERÍA/SHOULD", "NO SE DEBERÍA/SHOULD NOT", "RECOMENDADO/RECOMMENDED", "PUEDE/MAY", y "OPCIONAL/OPTIONAL" de este documento
se deben interpretar como se describe en el [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/es/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/es/PSR-1-estandar-basico-de-codificacion.md


1. Resúmen
-----------

- El código DEBE seguir el estandar [PSR-1][].

- El código DEBE utilizar 4 espacios para la indentación, no tabluación.

- NO DEBE haber un límite extricto en la longitud de la línea; el límite DEBE 
  estar en 120 caracteres; las líneas DEBERIAN tener 80 caracteres o menos.

- DEBE haber una línea en blanco después de la declaración del `namespace`, y 
  DEBE haber una línea en blanco después del bloque de declaraciones `use`.

- Las llaves de apertura de las clases DEBEN estar en la línea siguiente, y las 
  llaves de cierre de las clases DEBEN estar en la línea siguiente al cuerpo de la 
  clase.

- Las llaves de apertura de los métodos DEBEN estar en la siguiente línea, y las 
  llaves de cierre de los métodos DEBEN estar en la línea siguiente al cuerpo del
  método.

- La visibilidad DEBE estar declarada en todas las propiedades y métodos; `abstract`
  y `final` DEBEN estar declaradas antes de la visibilidad; `static` DEBEN estar
  declaradas después de la visibilidad.
  
- Las palabras clave de las estructuras de control DEBEN tener un espacio después;
  las llamadas a los métodos y las funciones NO DEBEN tenerlo..

- Las llaves de apertura de las estructuras de control DEBEN estar en la misma línea, y
  las llaves de cierre DEBEN estar en la línea siguiente al cuerpo..

- Los paréntesis de las estructuras de control NO DEBEN tener un espacio después, y los
  paréntesis de cierre de estructuras de control NO DEBEN tener un espacio antes.

### 1.1. Ejemplo

Este ejemplo abarca algunas de las reglas descritas arriba a modo de resúmen rápido:

```php
<?php
namespace Proveedor\Package;

use FooInterface;
use BarClass as Bar;
use OtroProveedor\OtroPaquete\BazClass;

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
        // cuerpo del método
    }
}
```

2. General
----------

### 2.1 Estándar básico de codificación

El código DEBE seguir las relgas descritas en [PSR-1][].

### 2.2 Archivos

Todos los archivos PHP DEBEN utilizar el final de línea Unix LF.

Todos los archivos PHP DENEN terminar con una línea en blanco.

La etiqueta de cierre `?>` DEBE omitirse en los archivos que sólo contengan 
código PHP.

### 2.3. Líneas

NO DEBEN haber un límite estricto en la longitud de la línea.

El límite flexible de la línea DEBE ser de 120 caracteres; los correctores de estilo 
automáticos DEBEN advertir pero NO DEBEN producir errores de límite flexible.

Las líneas NO DEBERÍAN ser de longitud mayor a 80 caracteres; las líneas más largas
DEBERÍAN separarse en subsecuencias múltiples de líneas de no más de 80 caracteres
cada una.

NO DEBE haber espacios en blanco al final de las líneas que no estén en blanco.

SE PUEDE añadir líneas en blanco para mejorar la lectura del código y para indicar 
relación de bloques de código.

NO DEBE haber más de una declaración por línea.

### 2.4. Indentación

El código DEBE utilizar una indentación de 4 espacios, y NO DEBE utilizar tabulacionas
para la indentación.

> N.b.: Utilizando sólo espacios, y evitando la mezcla de espacios y tabuladores, ayuda
> a evitar problemas al aplicar diffs, patches, historiales y anotaciones. El uso de 
> espacios también facilita la inserción de subindentación granular para la alineación
> entre líneas.  

### 2.5. Palabras clave True/False/Null

Las [Palabras clave][]] en PHP siempre DEBEN estar en minúsculas.

Las constantes de PHP `true`, `false`, y `null` DEBEN estar siempre en minúsuclas.

[Palabras clave]: http://php.net/manual/es/reserved.keywords.php



3. Paquete y declaraciones Use
------------------------------

Cuando están presentes, DEBE haber una línea en blanco después de la declaración 
de `namespace`.

Cuando están presentes, todas las declaraciones `use` DEBEN estar después de la
declaración `namespace`.

DEBE haber una palabra clave `use` por cada declaración.

DEBE haber una línea en blanco después del bloque de declaraciones `use`.

Por ejemplo:

```php
<?php
namespace Proveedor\Paquete;

use FooClass;
use BarClass as Bar;
use OtroProveedor\OtroPaquete\BazClass;

// ... código PHP adicional ...

```


4. Clases, Propiedades y métodos
--------------------------------

El término "clase" se refiere a todas las clases, interfaces y traits.

### 4.1. Extends e Implements

Las palabras reservadas `extends` e `implements` DEBEN estar declaradas en la 
misma línea que el nombre de la clase.

La llave de apertura de clase DEBE ir en su propia línea; la llave de cierre 
de clase  DEBE ir en la línea siguiente después del cuerpo.

```php
<?php
namespace Proveedor\Paquete;

use FooClass;
use BarClass as Bar;
use OtroProveedor\OtroPaquete\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constantes, propiedades, métodos
}
```

Listas de `implements` PODRÍAN separarse en varias líneas, donde cada línea se
indentaría una una vez. Cuando se hace esto, el primer elemento de la lista DEBE
estar en la línea siguiente, y DEBE haber sólo una interface por línea.

```php
<?php
namespace Proveedor\Paquete;

use FooClass;
use BarClass as Bar;
use OtroProveedor\OtroPaquete\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // constantes, propiedades, métodos
}
```

### 4.2. Propiedades

La visibilidad DEBE estar declarada en todas las propiedades.

La palabra clave `var` NO DEBE utilizarse para declarar una propiedad.

NO DEBE haber más de una propiedad declarada por línea.

Los nombres de las propiedades NO DEBERÍAN utilizar un guión bajo como 
prefijo para indicar la visibilidad `protected` o `private`.

La declaración de una propiedad se parece al siguiente ejemplo.

```php
<?php
namespace Proveedor\Paquete;

class ClassName
{
    public $foo = null;
}
```

### 4.3. Métodos

La visibilidad DEBE estar declarada en todos los métodos.

Los nombres de los métodos NO DEBERÍAN utilizar un guión bajo como
prefijo para indicar la visibilidad `protected` o `private`.

Los nombres de los métodos NO DEBEN estar declarados con un espacio después 
del nombre. La llave de apertura DEBE estar en su propia línea, y la llave de 
cierre DEBE estar en la siguiente línea del cuerpo. NO DEBE existir un espacio
después del paréntesis de apertura, y NO DEBE existir un espacio antes del 
paréntesis de cierre.

La declaración de un método se parece al siguiente ejemplo. Hay que tener 
en cuenta la colocación de los paréntesis, comas, espacios y llaves:

```php
<?php
namespace Proveedor\Paquete;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // cuerpo del método
    }
}
```    

### 4.4. Argumentos del método

En la lista de argumentos, NO DEBE haber un espacio delante de cada coma, 
y DEBE haber un espacio después de cada coma.

Los argumentos del método con valores por defecto DEBEN ir al final de la
lista de argumentos.

```php
<?php
namespace Proveedor\Paquete;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // cuerpo del método
    }
}
```

La lista de argumentos PUEDE separarse en varias líneas, donde cada línea se
indenta sólo una vez. Cuando se hace esto, el primer elemento de la lista DEBE
estar en la siguiente línea, y sólo DEBE haber un argumento por línea.

Cuando la lista de argumentos se separa en varias líneas, el paréntesis de cierre
y la llave de apertura se DEBEN colocar juntos en su propia línea con un espacio
entre ellos.

```php
<?php
namespace Proveedor\Paquete;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // cuerpo del método
    }
}
```

### 4.5. `abstract`, `final`, y `static`

Cuando hay presentes declaraciones de `abstract` y `final`, SE DEBE preceder 
con la declaración de la visibilidad.

Cuando hay presente una declaración `static`, SE DEBE colocar después de la 
declaración de visibilidad.

```php
<?php
namespace Proveedor\Paquete;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // cuerpo del método
    }
}
```

### 4.6. Llamadas a métodos y funciones

Al realizar una llamada a un método o a una función, NO DEBE haber un espcio
entre el nombre del método o de la función y la apertura de paréntesis, NO DEBE
haber un espacio después del paréntesis de apertura, y NO DEBE haber un espacio 
antes del paréntesis de cierre. En la lista de argumentos, NO DEBE haber
espacio antes de cada coma, y DEBE haber un espacio después de cada coma.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

El listado de argumentos PUEDE separarse en varias líneas, donde cada línea es
indentada una vez. Al hacer esto, el primer elemento de la lista DEBE estar en 
la línea siguiente, y DEBE existir un sólo argumento por línea.

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. Estructuras de control
-------------------------

Las reglas de estilo generales de las esctructuras de control son las
siguientes:

- DEBE haber un espacio después de la palabra clave de la estructura de control.
- NO DEBE haber un espacio después del paréntesis de apertura.
- NO DEBE haber un espacio antes del paréntesis de cierre.
- DEBE haber un espacio entre el paréntesis de cierre y la llave de apertura.
- El cuerpo de la estructura DEBE estar indentado una vez.
- La llave de cierre DEBE estar en la línea siguiente del cuerpo.

El cuerpo de cada estructura DEBE estar encerrado entre llaves. Esto estandariza
cómo se ven las estructuras, y redice la probabilidad de errores como que se 
añadan nuevas líneas al cuerpo.


### 5.1. `if`, `elseif`, `else`

Una estructura `if` se parece al siguiente ejemplo. Se debe tener en cuenta
la posición de los paréntesis, espacios y llaves; y que `else` y `elseif` se
encuentran en la misma línea que las llaves de cierre del cuerpo anterior.

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

La palabra clave `elseif` PODRÍA ser usada en lugar de `else if` de forma que 
todas las palabras clave de la estructura serían palabras simples.


### 5.2. `switch`, `case`

Una estructura `switch` se parece al siguiente ejemplo. Se debe tener en cuenta
la posición de los paréntesis, espacios y llaves. La declaración `case` DEBE estar 
indentada una vez respecto a `switch`, y la palabra clave `break` (o cualquier otra
palabra de terminación) DEBE estar indentada al mismo nivel del cuerpo del `case`. 
En el caso de un `case` que debe pasar a través del cuerpo de otro `case` intencionalmente, 
se debe poner el comentario `// no break`.

```php
<?php
switch ($expr) {
    case 0:
        echo 'Primer case, con break';
        break;
    case 1:
        echo 'Segundo case, que debe pasar a través';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Tercer case, return en lugar de break';
        return;
    default:
        echo 'case por defecto';
        break;
}
```


### 5.3. `while`, `do while`

Una estructura `while` se parece al siguiente ejemplo. Se debe tener en cuenta
la posición de los paréntesis, espacios y llaves.

```php
<?php
while ($expr) {
    // cuerpo de la estructura
}
```

De forma similar, una estructura `do while` se parece al siguiente ejemplo. Se debe
tener en cuenta los paréntesis, espacios y llaves.

```php
<?php
do {
    // cuepo de la estructura;
} while ($expr);
```

### 5.4. `for`

Una estructura `for` se parece al siguiente ejemplo. Se debe tener en cuenta
la posición de los paréntesis, espacios y llaves.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // cuerpo de la estructura for
}
```

### 5.5. `foreach`
    
Una estructura `foreach` se parece al siguiente ejemplo. Se debe tener en cuenta
la posición de los paréntesis, espacios y llaves.

```php
<?php
foreach ($iterable as $key => $value) {
    // cuerpo de la estructura foreach
}
```

### 5.6. `try`, `catch`

Un bloque `try catch` se parece al siguiente ejemplo. Se debe tener en cuenta
la posición de los paréntesis, espacios y llaves.

```php
<?php
try {
    // cuerpo de try
} catch (FirstExceptionType $e) {
    // cuerpo de catch
} catch (OtherExceptionType $e) {
    // cuerpo de catch
}
```

6. Closures
-----------

Closures DEBEN estar declarados con un espacio después de la palabra clave 
`function`, y un espacio antes y después de la palabra clave `use`.

La llave de apertura DEBE estar en la misma línea, y lallave de cierre DEBE
estar en la línea siguiente del cuerpo.

NO DEBE haber un espacio después del paréntesis de la lista de argumentos o
lista de variables, y NO DEBE haber un espacio antes del paréntesis de cierre
de la lista de argumentos o lista de variables.

En la lista de argumentos y la lista de variables, NO DEBE haber espacio
antes de cada coma, y DEBE haber un espacio después de cada coma.

Los argumentos de un Closure con valor por defecto DEBEN estar al final de 
la lista de argumentos.

La declaración de un Closure se parece al siguiente ejemplo. Se debe tener en
cuenta la posición de los paréntesis, comas, espacios y llaves:

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // cuerpo
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // cuerpo
};
```

La lista de argumentos y la lista de variables PUEDE separarse en varias líneas, 
donde cada línea es indentada una vez. Al hacer esto, el primer elemento de la
lista DEBE estar en la línea siguiente, y DEBE haber un solo argumento o variable
por línea.

Cuando finaliza el listado de argumentos o de variables y este está separado 
en varias líneas, el paréntesis de cierre y la llave de apertura DEBEN colocarse
juntos en su propia línea con un espacio entre ambos.

Los siguientes ejemplos muestran la declaración de clousures con y sin listado
de argumentos ys listado de variables separados en varias líneas.

```php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // cuerpo
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // cuerpo
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
   // cuerpo
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // cuerpo
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // cuerpo
};
```

Se debe tener en cuenta que las reglas de formato también se aplican cuando
se utiliza un clousure de forma directa en una llamada función o un método 
como argumento. 

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // cuerpo
    },
    $arg3
);
```


7. Conclusión
--------------

Hay muchos elementos de estulo y práctias intencionalmente omitidas por esta
guía. Estos incluyen, pero no están limitados a:

- Declaración de variables globales y constantes globales.

- Declaración de funciones.

- Operadores y asignaciones.

- Alineación entre líneas.

- Comentarios y bloques de documentación.

- Prefijos y sufijos de nombres de clase.

- Mejores prácticas.

PUEDE revisar y ampliar esta guía para hacer frente a estos u otros elementos 
de estilo y práctica.


Apéndice A. Encuesta
--------------------

Al escribir esta guía, el grupo realizó una encuesta a los miembros de los
proyectos para determinar prácticas comunes. La encuesta se conserva en 
este documento para usos posteriores.

### A.1. Datos de la encuesta

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

### A.2. Leyenda de la encuesta

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

### A.3. Resultados de la encuesta

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
