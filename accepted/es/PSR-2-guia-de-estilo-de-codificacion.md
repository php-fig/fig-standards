Guía de estilo de condificación
=======================

Esta guía amplía y extiende el estándar de codificación básica [PSR-1][].

La intención de esta guía es la de reducir la dificultad cuando se revisa un
código de autores diferentes. Lo realiza mediante la enumeración de un
conjunto común de normas y expresiones sobre cómo dar formato al código PHP.

Las reglas de estilo de este documento se derivan de los puntos comunes
de los diferentes miembros del proyecto. Cuando varios autores colaboran
en varios proyectos, ayuda tener un conjunto de directrices que se utilizarán
en todos los proyectos. Por tanto, el beneficio de esta guía no está en las normas
en sí, sino en el hecho de compartir de esas reglas.

Las palabras claves "TIENE QUE" ("MUST"/"SHALL"), "NO TIENE QUE"
("MUST NOT"/"SHALL NOT"), "NECESARIO" ("REQUIRED"), "DEBERÍA"
("SHOULD"), "NO DEBERÍA" ("SHOULD NOT"), "RECOMENDADO"
("RECOMMENDED"), "PUEDE" ("MAY") y "OPCIONAL" ("OPTIONAL")
de este documento son una traducción de las palabras inglesas descritas
en [RFC 2119][] y deben ser interpretadas de la siguiente manera: 
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

- El código TIENE QUE usar 4 espacios como indentación y no tabuladores.

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
    public function funcionDeEjemplo($a, $b = null)
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

La etiqueta de cierre `?>` TIENE QUE ser omitida en todos aquellos ficheros
que únicamente incluyan código PHP.

### 2.3. Líneas

NO TIENE QUE haber un límite fijo a la longitud de línea.

El límite dinámico de longitud de línea TIENE QUE ser de 120 caracteres;
los chequeadores de estilos automatizados TIENEN QUE advertir de ésto,
pero NO TIENE que dar error.

Las líneas NO DEBERÍAN ser más largas de 80 caracteres; las líneas más largas
de estos 80 caracteres DEBERÍAN dividirse en múltiples líneas de no más de 80
caracteres cada una.

NO TIENE QUE haber espacios en blanco al final de las líneas que no estén vacías.

PUEDEN añadirse líneas en blanco para mejorar la lectura del código y
para indicar bloques que estén relacionados.

NO TIENE QUE haber más de una sentencia por línea.

### 2.4. Indentación

El código TIENE QUE  usar una indentación de 4 espacios,
y NO TIENE QUE usar tabuladores para la indentación.

> Nota: Utilizar sólo los espacios y no mezclar espacios con tabuladores,
> ayuda a evitar problemas con diffs, parches, históricos y anotaciones.
> El uso de los espacios también facilita afinar la alineación entre líneas.

### 2.5. Palabras clave y `true`/`false`/`null`.

Las [Palabras clave][] de PHP TIENEN QUE estar en minúsculas.

Las constantes de PHP `true`, `false` y `null` TIENEN QUE estar en minúsculas.

[Palabras clave]: http://php.net/manual/en/reserved.keywords.php


3. Namespace y declaraciones `use`
----------------------------------------

Cuando esté presente, TIENE QUE haber una línea en blanco después de
la declación del `namespace`.

Cuando estén presentes, todas las declaraciones `use` TIENEN QUE ir
después de la declaración del `namespace`.

TIENE QUE haber un `use` por declaración.

TIENE QUE haber una línea en blanco después del bloque de declaraciones `use`.

Por ejemplo:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtroProveedor\OtroPaquete\BazClass;

// ... código PHP adicional ...

```


4.Clases, propiedades y métodos.
-------------------------------------

El término "clase" hace referencia a todas las clases, interfaces o traits.

### 4.1. Extensiones e implementaciones

Las palabras clave `extends` e `implements` TIENEN QUE declararse en la
misma línea del nombre de la clase.

Las llaves de apertura de la clase TIENE QUE ir en la siguiente línea; las llaves
de cierre TIENEN QUE ir en la línea siguiente al cuerpo de la clase.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtroProveedor\OtroPaquete\BazClass;

class NombreDeClase extends ClasePadre implements \ArrayAccess, \Countable
{
    // constantes, propiedades, métodos
}
```

Las listas de `implements` PUEDEN ser divididas en múltiples líneas, donde las
líneas subsiguientes serán indentadas una vez. Al hacerlo, el primer elemento
de la lista TIENE QUE estar en la línea siguiente, y TIENE QUE haber una sola interfaz por línea.

```php
<?php
namespace Proveedor\Paquete;

use FooClass;
use BarClass as Bar;
use OtroProveedor\OtroPaquete\BazClass;

class NombreDeClase extends ClasePadre implements
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

Los nombres de las propiedades NO DEBERIAN tener prefijos con un guión
bajo para indicar si es privada o protegida.

Una declaración de propiedas a modo de ejemplo.

```php
<?php
namespace Proveedor\Paquete;

class NombreDeClase
{
    public $foo = null;
}
```

### 4.3. Métodos

La visibilidad TIENE QUE declararse en todos los métodos.

Los nombres de los métodos NO DEBERÍAN llevar ningún prefijo
con guión bajo indicando la visibilidad.

Los nombres de métodos TIENEN QUE declararse con un espacio
después del nombre del método. La llave apertura TIENE QUE
situarse en su propia línea, y la llave de cierre TIENE QUE ir en
la siguiente línea al cuerpo del método. NO TIENE QUE haber
ningún espacio después de la apertura de los paréntesis, y
NO TIENE QUE haber ningún espacio antes del paréntesis de cierre.

Una declaración de método se parece al siguiente ejemplo.
Fíjese en la situación de los paréntesis, comas, espacios y llaves:

```php
<?php
namespace Proveedor\Paquete;

class NombreDeClase
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // Cuerpo del método
    }
}
```    

### 4.4. Argumentos de los métodos

En la lista de argumentos NO TIENE QUE haber un espacio antes
de cada coma y TIENE QUE haber un espacio después de cada coma.

Los argumentos con valores por defecto de un método TIENE QUE ir
al final de la lista.

```php
<?php
namespace Proveedor\Paquete;

class NombreDeClase
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // Cuerpo del método
    }
}
```

La lista de argumentos PUEDE dividirse en múltiples líneas, donde
cada línea será indentada un vez. Cuando se dividan de esta forma,
el primer argumento TIENE QUE estar en la siguiente línea, y
TIENE QUE haber únicamente un argumento por línea.

Cuando la lista de argumentos se divide en varias líneas, el paréntesis
de cierre y la llave de apertura TIENEN QUE ir juntos en su propia línea,
separados por un espacio.

```php
<?php
namespace Proveedor\Paquete;

class NombreDeClase
{
    public function metodoConNombreLargo(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // Cuerpo del método
    }
}
```

### 4.5. `abstract`, `final`, y `static`

Cuando estén presentes, las declaraciones `abstract` y `final` TIENEN QUE
preceder a la declaración de visibilidad.

Cuando esté presente, la declaración `static` TIENE QUE ir después
de la declaración de visibilidad.

```php
<?php
namespace Proveedor\Paquete;

abstract class NombreDeClase
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

Cuando se haga una llamada a un método o función, NO TIENE QUE
haber espacio entre el nombre del método o función y el paréntesis
de apertura, NO TIENE QUE haber espacio después de la apertura
del paréntesis y NO TIENE QUE haber espacio antes del paréntesis
de cierre. En la lista de argumentos, NO TIENE QUE haber espacio
antes de cada coma y TIENE QUE haber un espacio después de cada coma.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

La lista de argumentos PUEDE dividirse en múltiples líneas, donde
cada una se indenta una vez. Cuando esto suceda, el primer argumento
TIENE QUE estar en la siguiente línea, y TIENE QUE haber sólo un
argumento por línea.

```php
<?php
$foo->bar(
    $argumentoLargo,
    $argumentoMaslargo,
    $argumentoTodaviaMasLargo
);
```

5. Estructuras de control
----------------------------

Las reglas de estilo para las estructuras de control son las siguientes:

- TIENE QUE haber un espacio después de una palabra clave de la estructura de control.
- NO TIENE QUE haber espacios después de la apertura de los paréntesis.
- NO TIENE QUE haber espacios antes del cierre de paréntesis.
- TIENE QUE haber un espacio entre paréntesis de cierre y la llave de apertura.
- El cuerpo de la estructura de control TIENE QUE estar indentada una vez.
- La llave de cierre TIENE QUE estar en la linea siguiente al final del cuerpo de la estructura.

El cuerpo de cada estructura TIENE QUE encerrarse entre llaves.
Esto estandariza el aspecto de las estructuras y reduce la probabilidad
de añadir errores como nuevas líneas que se añaden al cuerpo de la estructura.


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
TIENE QUE estar indentada una vez respecto al `switch` y la palabra reservada `break`
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

Una sentencia `for` se ve como sigue.
Fíjese en el lugar donde aparecen paréntesis, espacios y llaves.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // Cuerpo del for
}
```

### 5.5. `foreach`
    
Un sentencia `foreach` se ve a continuación.
Fíjese en el lugar donde aparecen paréntesis, espacios y llaves.

```php
<?php
foreach ($iterable as $key => $value) {
    // Cuerpo foreach
}
```

### 5.6. `try`, `catch`

Un bloque `try catch` se ve a continuación.
Fíjese en el lugar donde aparecen paréntesis, espacios y llaves.


```php
<?php
try {
    // Cuerpo del try
} catch (PrimerTipoDeExcepcion $e) {
    // Cuerpo catch
} catch (OtroTipoDeExcepcion $e) {
    // Cuerpo catch
}
```

6. Closures
-------------

Las closures TIENEN QUE declararse con un espacio después de la
palabra clave `function` y un espacio antes de la parabra clave `use`.

La llave de apertura TIENE QUE ir en la misma línea y
la llave de cierre TIENE QUE ir en la siguiente línea al final del cuerpo.

NO TIENE QUE haber espacios después del paréntesis de apertura
de la lista de argumentos o lista de variables y NO TIENE QUE haber
espacios antes del paréntesis de cierre de la lista de argumentos
o lista de variables.

En las listas de argumentos y variables NO TIENE QUE haber espacios
antes de cada coma y TIENE QUE haber un espacio después de cada coma.

Los argumentos de las closures con valores por defecto TIENEN QUE ir
al final de la lista de argumentos.

Una declaración de una closure tendrá el siguiente aspecto.
Fíjese en el lugar donde aparecen paréntesis, comas, espacios y llaves.

```php
<?php
$closureConArgumentos = function ($arg1, $arg2) {
    // Cuerpo
};

$closureConArgumentosYVariables = function ($arg1, $arg2) use ($var1, $var2) {
    // Cuerpo
};
```

La lista de argumetos y la de variables PUEDEN ser divididas
en múltiples líneas, donde cada nueva línea se indentará una vez.
Cuando esto suceda, el primer elemento de la lista TIENE QUE
ir en una nueva línea y TIENE QUE haber sólo un argumento o
variable por línea.

Al final de una lista en múltiples líneas (de argumentos o variables)
el paréntesis de cierre y la llave de apertura TIENEN QUE estar en
la misma línea separados por un espacio.

A continuación se muestran ejemplos de closures con y sin lista de
argumentos y variables, así como con listas de argumentos y variables
en múltiples líneas.

```php
<?php
$listaLargaDeArgumentos_sinVariables = function (
    $argumentLargo,
    $argumentMasLargo,
    $argumentoMuchoMasLargo
) {
   // Cuerpo
};

$sinArgumentos_listaLargaDeVariables = function () use (
    $variableLarga1,
    $variableMasLarga2,
    $variableMuchoMasLarga3
) {
   // Cuerpo
};

$listaLargaDeArgumentos_listaLargaDeVariables = function (
    $argumentLargo,
    $argumentMasLargo,
    $argumentoMuchoMasLargo
) use (
    $variableLarga1,
    $variableMasLarga2,
    $variableMuchoMasLarga3
) {
   // Cuerpo
};

$listaLargaDeArgumentos_listaDeVars = function (
    $argumentLargo,
    $argumentMasLargo,
    $argumentoMuchoMasLargo
) use ($var1) {
   // Cuerpo
};

$listaDeArgumentos_listaLargaDeVariables = function ($arg) use (
    $variableLarga1,
    $variableMasLarga2,
    $variableMuchoMasLarga3
) {
   // Cuerpo
};
```

Fíjese que las reglas de formateo se aplican cuando una closure se usar
directamente en una función o llamada a método como argumento.

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // Cuerpo
    },
    $arg3
);
```


7. Conclusión
----------------

Hay muchos elementos de estilo y prácticas omitidas intencionadamente
en esta guía. 
Estos incluyen pero no limitan a:

- Declaraciones de variables y constantes globales.

- Declaración de funciones.

- Operadores y asignaciones.

- Alineación entre líneas.

- Comentarios y bloques de documentación.

- Prefijos y sufijos en nombres de clases.

- Buenas prácticas.

Futuras recomendaciones PUEDEN revisar y extender para hacer frente a estos u
otros elementos en la práctica.


Apéndice A. Encuesta.
--------------------------

Al escribir esta guía a los miembros del grupo se les hizo una
encuesta con el fin de determinar las prácticas comunes.
Esta encuesta se conserva en el documento para su uso posterior.

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
The type of indenting. `tab` = "Usar tabuladores", `2` or `4` = "número de espacios"

`line_length_limit_soft`:
El límite de la línea dinámica ("soft"), en caracteres. `?` = no sabe o no contesta, `no` significa sin límite.

`line_length_limit_hard`:
El límite de la línea dura ("hard"), en caracteres. `?` = no sabe o no contesta, `no` significa sin límite.

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
