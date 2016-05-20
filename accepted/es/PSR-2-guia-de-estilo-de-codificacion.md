Guía de estilo de codificación
===============================

Esta guía amplía y extiende el estándar de codificación básica [PSR-1][].

El objetivo de esta guía es el de reducir la dificultad cuando se lee código de diferentes autores. Para ello, se enumeran una serie de reglas común y expresiones sobre cómo dar formato al código PHP.

En el documento original se usa el RFC 2119 para el uso de las palabras MUST, MUST NOT, SHOULD, SOULD NOT y MAY. Para que la traducción sea lo más fiel posible, se traducira siempre MUST como el verbo deber en presente (DEBE, DEBEN), SHOULD como el verbo deber en condicional (DEBERÍA, DEBERÍAN) y el verbo MAY como el verbo PODER.

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/es/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/es/PSR-1-codificacion-estandar-basica.md


1. Visión general
-----------------

- El código DEBE seguir el estándar [PSR-1][].

- El código DEBE usar 4 espacios como indentación, no tabuladores.

- NO DEBE haber un límite estricto en la longitud de la línea; el límite DEBE estar en 120 caracteres; las líneas DEBERÍAN tener 80 caracteres o menos.

- DEBE haber una línea en blanco después de la declaración del `namespace`, y DEBE haber una línea en blanco después del bloque de declaraciones `use`.

- Las llaves de apertura de las clases DEBEN ir en la línea siguiente, y las llaves de cierre DEBEN ir en la línea siguiente al cuerpo de la clase.

- Las llaves de apertura de los métodos DEBEN ir en la línea siguiente, y las llaves de cierre DEBEN ir en la línea siguiente al cuerpo del método.

- La visibilidad DEBE declararse en todas las propiedades y métodos; `abstract` y `final` DEBEN declararse antes de la visibilidad; `static` DEBE declararse después de la visibilidad.

- Las palabras clave de las estructuras de control DEBEN tener un espacio después de ellas, las llamadas a los métodos y las funciones NO DEBEN tenerlo.

- Las llaves de apertura de las estructuras de control DEBEN estar en la misma línea, y las de cierre DEBEN ir en la línea siguiente al cuerpo.

- Los paréntesis de apertura en las estructuras de control NO DEBEN tener un espacio después de ellos, y los paréntesis de cierre NO DEBEN tener un espacio antes de ellos.

### 1.1. Ejemplo

Este ejemplo incluye algunas de las siguientes reglas a modo de visión general rápida:

~~~php
<?php
namespace Proveedor\Paquete;

use FooInterfaz;
use BarClase as Bar;
use OtroProveedor\OtroPaquete\BazClase;

class Foo extends Bar implements FooInterfaz
{
    public function funcionDeEjemplo($a, $b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClase::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // cuerpo del método
    }
}
~~~

2. General
----------

### 2.1 Codificación estándar básica

El código DEBE seguir las normas expuestas en el estándar [PSR-1][].

### 2.2 Ficheros

Todos los ficheros PHP DEBEN usar el final de línea Unix LF.

Todos los ficheros PHP DEBEN terminar con una línea en blanco.

La etiqueta de cierre `?>` DEBE ser omitida en los ficheros que sólo
contengan código PHP.

### 2.3. Líneas

NO DEBE haber un límite estricto en la longitud de la línea.

El límite flexible de la línea DEBE estar en 120 caracteres; los correctores de estilo automáticos DEBEN advertir de ésto, pero NO DEBEN producir errores.

Las líneas NO DEBERÍAN ser más largas de 80 caracteres; las líneas más largas de estos 80 caracteres DEBERÍAN dividirse en múltiples líneas de no más de 80 caracteres cada una.

NO DEBE haber espacios en blanco al final de las líneas que no estén vacías.

PUEDEN añadirse líneas en blanco para mejorar la lectura del código y para indicar bloques de código que estén relacionados.

NO DEBE haber más de una sentencia por línea.

### 2.4. Indentación

El código DEBE usar una indentación de 4 espacios, y NO DEBE usar tabuladores para la indentación.

> Nota: Utilizar sólo los espacios, y no mezclar espacios con tabuladores, ayuda a evitar problemas con diffs, parches, historiales y anotaciones. El uso de los espacios también facilita a ajustar la alineación entre líneas.

### 2.5. Palabras clave y `true`/`false`/`null`.

Las [Palabras clave][] de PHP DEBEN estar en minúsculas.

Las constantes de PHP `true`, `false` y `null` DEBEN estar en minúsculas.

[Palabras clave]: http://php.net/manual/es/reserved.keywords.php


3. Espacio de nombre y declaraciones `use`
------------------------------------------

Cuando esté presente, DEBE haber una línea en blanco después de la declación del `namespace`.

Cuando estén presentes, todas las declaraciones `use` DEBEN ir después de la declaración del `namespace`.

DEBE haber un `use` por declaración.

DEBE haber una línea en blanco después del bloque de declaraciones `use`.

Por ejemplo:

~~~php
<?php
namespace Proveedor\Paquete;

use FooClass;
use BarClase as Bar;
use OtroProveedor\OtroPaquete\BazClase;

// ... código PHP adicional ...

~~~


4. Clases, propiedades y métodos
--------------------------------

El término "clase" hace referencia a todas las clases, interfaces o traits.

### 4.1. Extensiones e implementaciones

Las palabras clave `extends` e `implements` DEBEN declararse en la misma línea del nombre de la clase.

La llave de apertura de la clase DEBE ir en la línea siguiente; la llave de cierre DEBE ir en la línea siguiente al cuerpo de la clase.

~~~php
<?php
namespace Proveedor\Paquete;

use FooClase;
use BarClase as Bar;
use OtroProveedor\OtroPaquete\BazClase;

class NombreDeClase extends ClasePadre implements \ArrayAccess, \Countable
{
    // constantes, propiedades, métodos
}
~~~

La lista de `implements` PUEDE ser dividida en múltiples líneas, donde las líneas subsiguientes serán indentadas una vez. Al hacerlo, el primer elemento de la lista DEBE estar en la línea siguiente, y DEBE haber una sola interfaz por línea.

~~~php
<?php
namespace Proveedor\Paquete;

use FooClase;
use BarClase as Bar;
use OtroProveedor\OtroPaquete\BazClase;

class NombreDeClase extends ClasePadre implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // constantes, propiedades, métodos
}
~~~

### 4.2. Propiedades

La visibilidad DEBE ser declarada en todas las propiedades.

La palabra clave `var` NO DEBE ser usada para declarar una propiedad.

NO DEBE declararse más de una propiedad por sentencia.

Los nombres de las propiedades NO DEBERÍAN usar un guión bajo como prefijo para indicar si son privadas o protegidas.

Una declaración de propiedas tendrá el siguiente aspecto.

~~~php
<?php
namespace Proveedor\Paquete;

class NombreDeClase
{
    public $foo = null;
}
~~~

### 4.3. Métodos

La visibilidad DEBE ser declarada en todos los métodos.

Los nombres de los métodos NO DEBERÍAN usar un guión bajo como prefijo para indicar si son privados o protegidos.

Los nombres de métodos NO DEBEN estar declarados con un espacio después del nombre del método. La llave de apertura DEBE situarse en su propia línea, y la llave de cierre DEBE ir en la línea siguiente al cuerpo del método. NO DEBE haber ningún espacio después del paréntesis de apertura, y NO DEBE haber ningún espacio antes del paréntesis de cierre.

La declaración de un método tendrá el siguiente aspecto. Fíjese en la situación de los paréntesis, las comas, los espacios y las llaves:

~~~php
<?php
namespace Proveedor\Paquete;

class NombreDeClase
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // cuerpo del método
    }
}
~~~

### 4.4. Argumentos de los métodos

En la lista de argumentos NO DEBE haber un espacio antes de cada coma y DEBE haber un espacio después de cada coma.

Los argumentos con valores por defecto del método DEBEN ir al final de la lista de argumentos.

~~~php
<?php
namespace Proveedor\Paquete;

class NombreDeClase
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // cuerpo del método
    }
}
~~~

La lista de argumentos PUEDE dividirse en múltiples líneas, donde cada línea será indentada una vez. Cuando se dividan de esta forma, el primer argumento DEBE estar en la línea siguiente, y DEBE haber sólo un argumento por línea.

Cuando la lista de argumentos se divide en varias líneas, el paréntesis de cierre y la llave de apertura DEBEN estar juntos en su propia línea separados por un espacio.

~~~php
<?php
namespace Proveedor\Paquete;

class NombreDeClase
{
    public function metodoConNombreLargo(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // cuerpo del método
    }
}
~~~

### 4.5. `abstract`, `final`, y `static`

Cuando estén presentes las declaraciones `abstract` y `final`, DEBEN preceder a la declaración de visibilidad.

Cuando esté presente la declaración `static`, DEBE ir después de la declaración de visibilidad.

~~~php
<?php
namespace Proveedor\Paquete;

abstract class NombreDeClase
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // cuerpo del método
    }
}
~~~

### 4.6. Llamadas a métodos y funciones

Cuando se realize una llamada a un método o a una función, NO DEBE haber un espacio entre el nombre del método o la función y el paréntesis de apertura, NO DEBE haber un espacio después del paréntesis de apertura, y NO DEBE haber un espacio antes del paréntesis de cierre. En la lista de argumentos, NO DEBE haber espacio antes de cada coma y DEBE haber un espacio después de cada coma.

~~~php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
~~~

La lista de argumentos PUEDE dividirse en múltiples líneas, donde cada una se indenta una vez. Cuando esto suceda, el primer argumento DEBE estar en la línea siguiente, y DEBE haber sólo un argumento por línea.

~~~php
<?php
$foo->bar(
    $argumentoLargo,
    $argumentoMaslargo,
    $argumentoTodaviaMasLargo
);
~~~

5. Estructuras de control
-------------------------

Las reglas de estilo para las estructuras de control son las siguientes:

- DEBE haber un espacio después de una palabra clave de estructura de control.
- NO DEBE haber espacios después del paréntesis de apertura.
- NO DEBE haber espacios antes del paréntesis de cierre.
- DEBE haber un espacio entre paréntesis de cierre y la llave de apertura.
- El cuerpo de la estructura de control DEBE estar indentado una vez.
- La llave de cierre DEBE estar en la línea siguiente al final del cuerpo.

El cuerpo de cada estructura DEBE estar encerrado entre llaves. Esto estandariza el aspecto de las estructuras y reduce la probabilidad de añadir errores como nuevas líneas que se añaden al cuerpo de la estructura.


### 5.1. `if`, `elseif`, `else`

Una estructura `if` tendrá el siguiente aspecto. Fíjese en el lugar de los paréntesis, los espacios y las llaves; y que `else` y `elseif` están en la misma línea que las llaves de cierre del cuerpo anterior.

~~~php
<?php
if ($expr1) {
    // if cuerpo
} elseif ($expr2) {
    // elseif cuerpo
} else {
    // else cuerpo;
}
~~~

La palabra clave `elseif` DEBERÍA ser usada en lugar de `else if` de forma que todas las palabras clave de la estructura estén compuestas por palabras de un solo término.


### 5.2. `switch`, `case`

Una estructura `switch` tendrá el siguiente aspecto. Fíjese en el lugar donde están los paréntesis, los espacios y las llaves. La palabra clave `case` DEBE estar indentada una vez respecto al `switch` y la palabra clave `break` o cualquier otra palabra clave de finalización DEBE estar indentada al mismo nivel que el cuerpo del `case`. DEBE haber un comentario como `// no break` cuando hay `case` en cascada no vacío.

~~~php
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
        echo 'Tercer case; con return en vez de break';
        return;
    default:
        echo 'Case por defecto';
        break;
}
~~~


### 5.3. `while`, `do while`

Una instrucción `while` tendrá el siguiente aspecto. Fíjese en el lugar donde están los paréntesis, los espacios y las llaves.

~~~php
<?php
while ($expr) {
    // cuerpo de la estructura
}
~~~

Igualmente, una sentencia `do while` tendrá el siguiente aspecto. Fíjese en el lugar donde están los paréntesis, los espacios y las llaves.

~~~php
<?php
do {
    // cuerpo de la estructura;
} while ($expr);
~~~

### 5.4. `for`

Una sentencia `for` tendrá el siguiente aspecto. Fíjese en el lugar donde aparecen los paréntesis, los espacios y las llaves.

~~~php
<?php
for ($i = 0; $i < 10; $i++) {
    // cuerpo del for
}
~~~

### 5.5. `foreach`

Un sentencia `foreach` tendrá el siguiente aspecto. Fíjese en el lugar donde aparecen los paréntesis, los espacios y las llaves.

~~~php
<?php
foreach ($iterable as $key => $value) {
    // cuerpo foreach
}
~~~

### 5.6. `try`, `catch`

Un bloque `try catch` tendrá el siguiente aspecto. Fíjese en el lugar donde aparecen los paréntesis, los espacios y los llaves.


~~~php
<?php
try {
    // cuerpo del try
} catch (PrimerTipoDeExcepcion $e) {
    // cuerpo catch
} catch (OtroTipoDeExcepcion $e) {
    // cuerpo catch
}
~~~

6. Closures
-----------

Las closures DEBEN declararse con un espacio después de la palabra clave `function`, y un espacio antes y después de la parabra clave `use`.

La llave de apertura DEBE ir en la misma línea, y la llave de cierre DEBE ir en la línea siguiente al final del cuerpo.

NO DEBE haber un espacio después del paréntesis de apertura de la lista de argumentos o la lista de variables, y NO DEBE haber un espacio antes del paréntesis de cierre de la lista de argumentos o la lista de variables.

En la lista de argumentos y la lista de variables, NO DEBE haber un espacio antes de cada coma, y DEBE haber un espacio después de cada coma.

Los argumentos de las closures con valores por defecto, DEBEN ir al final de la lista de argumentos.

Una declaración de una closure tendrá el siguiente aspecto. Fíjese en el lugar donde aparecen los paréntesis, las comas, los espacios y las llaves.

~~~php
<?php
$closureConArgumentos = function ($arg1, $arg2) {
    // cuerpo
};

$closureConArgumentosYVariables = function ($arg1, $arg2) use ($var1, $var2) {
    // cuerpo
};
~~~

La lista de argumetos y la lista de variables PUEDEN ser divididas en múltiples líneas, donde cada nueva línea se indentará una vez. Cuando esto suceda, el primer elemento de la lista DEBE ir en una nueva línea y DEBE haber sólo un argumento o variable por línea.

Cuando la lista de argumentos o variables se divide en varias líneas, el paréntesis de cierre y la llave de apertura DEBEN estar juntos en su propia línea separados por un espacio.

A continuación se muestran ejemplos de closures con y sin lista de argumentos y variables, así como con listas de argumentos y variables en múltiples líneas.

~~~php
<?php
$listaLargaDeArgumentos_sinVariables = function (
    $argumentoLargo,
    $argumentoMasLargo,
    $argumentoMuchoMasLargo
) {
    // cuerpo
};

$sinArgumentos_listaLargaDeVariables = function () use (
    $variableLarga1,
    $variableMasLarga2,
    $variableMuchoMasLarga3
) {
    // cuerpo
};

$listaLargaDeArgumentos_listaLargaDeVariables = function (
    $argumentoLargo,
    $argumentoMasLargo,
    $argumentoMuchoMasLargo
) use (
    $variableLarga1,
    $variableMasLarga2,
    $variableMuchoMasLarga3
) {
    // cuerpo
};

$listaLargaDeArgumentos_listaDeVars = function (
    $argumentoLargo,
    $argumentoMasLargo,
    $argumentoMuchoMasLargo
) use ($var1) {
    // cuerpo
};

$listaDeArgumentos_listaLargaDeVariables = function ($arg) use (
    $variableLarga1,
    $variableMasLarga2,
    $variableMuchoMasLarga3
) {
    // cuerpo
};
~~~

Fíjese que las reglas de formateo se aplican también cuando una closure se usa directamente en una función o llamada a método como argumento.

~~~php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // cuerpo
    },
    $arg3
);
~~~


7. Conclusión
-------------

Hay muchos elementos de estilo y prácticas omitidas intencionadamente en esta guía. Estos incluyen pero no se limitan a:

- Declaraciones de variables y constantes globales.

- Declaración de funciones.

- Operadores y asignaciones.

- Alineación entre líneas.

- Comentarios y bloques de documentación.

- Prefijos y sufijos en nombres de clases.

- Buenas prácticas.

Futuras recomendaciones PUEDEN revisar y extender esta guía para hacer frente a estos u otros elementos de estilo y práctica.


Apéndice A. Encuesta.
--------------------------

Al escribir esta guía a los miembros del grupo se les hizo una encuesta con el fin de determinar las prácticas comunes. Esta encuesta se conserva en el documento para su uso posterior.

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

### A.2. Leyenda de la encuesta.

`indent_type`:
Tipo de indentación. `tab` = "Usar tabuladores", `2` or `4` = "número de espacios"

`line_length_limit_soft`:
El límite de la línea dinámica ("soft"), en caracteres. `?` = no sabe o no contesta, `no` significa sin límite.

`line_length_limit_hard`:
El límite de la línea estricto ("hard"), en caracteres. `?` = no sabe o no contesta, `no` significa sin límite.

`class_names`:
¿Cómo nombrar las clases?. `lower` = solo minúsculas, `lower_under` = minúsculas con guiones bajos como separador, `studly` = StudlyCase.

`class_brace_line`:
¿La llave de apertura para clases puede ir en la misma línea (`same`) que la palabra clave class, o en la siguiente (`next`) línea?

`constant_names`:
¿Cómo nombrar las constantes en las clases? `upper` = Mayúsculas con guiones bajos como separador.

`true_false_null`:
¿Las palabras clave `true`, `false`, y `null` se expresan todo en minúsculas (`lower`) o todo en mayúsculas (`upper`)?

`method_names`:
¿Cómo se nombran los métodos? `camel` = `camelCase`, `lower_under` = minúsculas con guiones bajos como separador.

`method_brace_line`:
¿La llave de apertura para los métodos se ponen en la misma (`same`) línea del nombre del método o en la siguiente (`next`) línea?

`control_brace_line`:
¿La llave de apertura para las estructuras de control se ponen en la misma (`same`) línea o en la siguiente `next` línea?

`control_space_after`:
¿Hay un espacio después de la palabra clave en una estructura de control?

`always_use_control_braces`:
¿Las estructuras de control siempre usan llaves?

`else_elseif_line`:
Cuando se usa `else` o `elseif`, ¿se ponen en la misma (`same`) línea que la llave de cierre previa o en la siguiente (`next`) línea?

`case_break_indent_from_switch`:
¿Cuántas veces hay que indentar el `case` y el `break` respecto a la apertura del `switch`?

`function_space_after`:
¿Tienen las llamadas a funciones un espacio después del nombre de la función y antes de la apertura del paréntesis?

`closing_php_tag_required`:
En los ficheros que solamente contengan código PHP, ¿Se requiere la etiqueta de cierre `?>`?

`line_endings`:
¿Qué tipo de final de línea se usa?

`static_or_visibility_first`:
Cuando se declara un método, ¿se pone primero `static` o la visibilidad?

`control_space_parens`:
En una expresión de estructura de control, ¿hay un espacio después del paréntesis de apertura y un espacio antes del paréntesis de cierre? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
¿Hay una línea en blanco después de la etiqueta de inicio de PHP?

`class_method_control_brace`:
Un resumen de en qué línea se abren las llaves para clases, métodos y estructuras de control. (Clases/Métodos/Estructuras)

### A.3. Resultados de la encuesta.

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
