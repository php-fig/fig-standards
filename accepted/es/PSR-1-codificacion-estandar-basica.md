Codificación estándar básica
============================

Esta sección de la norma comprende lo que debe considerarse la norma
de codificación de los elementos que se requieren para garantizar un alto
nivel técnico de interoperabilidad entre el código PHP.

Las palabras claves "TIENE QUE" ("MUST"/"SHALL"), "NO TIENE QUE"
("MUST NOT"/"SHALL NOT"), "NECESARIO" ("REQUIRED"), "DEBERÍA"
("SHOULD"), "NO DEBERÍA" ("SHOULD NOT"), "RECOMENDADO"
("RECOMMENDED"), "PUEDE" ("MAY") y "OPCIONAL" ("OPTIONAL")
de este documento son una traducción de las palabras inglesas descritas
en el [RFC 2119][] y deben ser interpretadas de la siguiente manera: 
- TIENE QUE o REQUERIDO, implica que es un requisito absoluto de la especificación.
- NO TIENE QUE, conlleva la completa prohibición de la especificación.
- DEBERÍA o RECOMENDADO, implica que pueden existir razones válidas para ignorar dicho elemento, pero las implicaciones que ello conlleva deben ser entendidas y sopesadas antes de elegir una opción diferente.
- NO DEBERÍA, implica que pueden existir razones bajo ciertas circunstancias cuando el comportamiento es aceptable o incluso útil, pero todas las implicaciones deben ser entendidas cuidadosamente y sopesadas antes de implementar algún comportamiento descrito por esta etiqueta para ignorar dicho comportamiento.
- PUEDE u OPCIONAL, implica que el elemento es puramente opcional. Cualquier proveedor puede elegir incluir dicho elemento porque crea que conlleva mejoras en su producto, mientras otro puede elegir obviarlas. Una implementación que no incluya un opción particular, TIENE QUE estar preparada para operar con otra implementación que incluya dicha opción, aunque implique limitar la funcionalidad. De la misma manera, una implementación que incluya una opción particular TIENE QUE estar preparada para otra que no la incluya (excepto, por supuesto, para la característica que la opción provea).

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md

1. Visión general
----------------------

- Los archivos TIENEN QUE utilizar solamente las etiquetas `<?php` y `<?=`.

- Los archivos TIENEN QUE emplear solamente la codificación UTF-8 sin BOM para el código en PHP.

- Los archivos DEBERÍAN declarar *cualquier* estructura (clases, funciones, constantes, etc,...) *o* realizar partes de la lógica de negocio (por ejemplo, generar una salida, cambio de configuración ini, etc,...) pero NO DEBERÍAN hacer las dos cosas.

- Los espcios de nombres y las clases TIENEN QUE cumplir [PSR-0][].

- Los nombres de las clases se TIENEN QUE declarar en notación `StudlyCaps`. [^1]

- Las constantes de las clases se TIENEN QUE declarar en mayúsculas con guiones bajos como separaradores  `CONSTANTE_DE_CLASE`.

- Los nombres de los métodos se TIENEN QUE declarar en notación `camelCase`. [^2]

2. Archivos
--------------

### 2.1. Etiquetas PHP

El código en PHP TIENE QUE utilizar las etiquetas largas `<?php ?>`
o las etiquetas cortas para imprimir salida de información `<?= ?>`; NO TIENE QUE emplear
otras variantes.

### 2.2. Codificación de caracteres

El código PHP sólo debe utilizar codificación UTF-8 sin BOM.

### 2.3. Efectos secundarios

Un archivo DEBERÍA declarar estructuras (clases, funciones,
constantes, etc,...) y no causar efectos secundarios o DEBERÍA
ejecutar partes de la lógica de negocio, pero NO DEBERÍA hacer
las dos cosas.

La frase "efectos secundarios" significa la ejecución de la lógica
de negocio que no está directamente relacionado con declarar
clases, funciones, constantes, etc, *simplemente la de incluir el archivo*.

"Efectos secundarios" incluyen, pero no se limitan a: generar
salidas, uso explícito de `requiere` o `include`, conexiones a
servicios externos, modificación de configuraciones iniciales,
enviar errores o excepciones, modificar variables globales o
estáticas, leer o escribir un archivo, etc...

El siguiente ejemplo muestra un archivo que incluye las dos,
declaraciones y efectos secundarios;
Un ejemplo de lo que debe evitar:

```php
<?php
// efecto secundario: cambiar configuracion ini
ini_set('error_reporting', E_ALL);

// efecto secundario: cargar ficheros
include "file.php";

// efecto secundario: generar salida
echo "<html>\n";

// declaración
function foo()
{
    // cuerpo de la función
}
```

El siguiente ejemplo es el de un archivo que contiene declaraciones
sin efectos secundarios;
Un ejemplo que puede seguir:

```php
<?php
// declaración
function foo()
{
    // cuerpo de la función
}

// una declaración condicional *no* es un
// efecto secundario
if (! function_exists('bar')) {
    function bar()
    {
        // cuerpo de la función
    }
}
```

3. Espacio de nombre y nombres de las Clases
----------------------------------------------

Los espacios de nombres y las clases TIENEN QUE seguir el [PSR-0][].

Esto significa que cada clase estará en un fichero independiente y
está dentro de un espcio de nombre en al menos un nivel: un nombre
de proveedor de nivel superior.

Los nombres de las clases TIENEN QUE declararse con notación
`StudlyCaps`. [^1]

El código escrito para PHP 5.3 o superior TIENE QUE hacer
un uso formal de los espcios de nombres.

Por ejemplo:

```php
<?php
// PHP 5.3 o superior:
namespace Proveedor\Model;

class Foo
{
}
```

El código escrito para PHP 5.2.x o inferior DEBERÍA emplear
una convención de pseudo-espacios de nombres con prefijos en los
nombres de las clases con el formato `Proveedor_`.

```php
<?php
// PHP 5.2.x o inferior:
class Proveedor_Model_Foo
{
}
```

4. Constantes de Clases, Propiedades y Métodos
---------------------------------------------------------------

El término "clases" hace referencia a todas las clases, interfaces y traits.

### 4.1. Constantes

Las constantes de las clases TIENEN QUE declararse siempre en
mayúsculas y separadas con guiones bajos.
Por ejemplo:

```php
<?php
namespace Proveedor\Model;

class Foo
{
    const VERSION = '1.0';
    const FECHA_DE_APROBACION = '2012-06-01';
}
```

### 4.2. Propiedades

Esta guía evita intencionadamente cualquier recomendación respecto al uso de las
notaciones `$StudlyCaps`, `$camelCase`, o `$guion_bajo` en los nombres de las
propiedades. [^1] [^2]

Cualquiera que sea la convención en nomenclatura, DEBERÍA ser
utilizada de forma coherente con un alcance razonable. Ese alcance
PUEDE ser a nivel de proveedor, a nivel de paquete, a nivel de clase
o a nivel de método.

### 4.3. Métodos

Los nombres de los métodos TIENE QUE declararse en notación
`camelCase()`. [^2]

Notas
------

[^1] `StudlyCaps`, nombres en minúsculas sin espacios y con la primera letra de cada palabra en mayúsculas.

[^2] `camelCase`, nombres en minúculas sin espacios y con la primera letra de cada palabra en mayúsculas exceptuando la primera palabra.