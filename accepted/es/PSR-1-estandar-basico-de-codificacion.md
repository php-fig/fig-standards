Estándar básico de codificación
===============================

Esta sección del estándar define lo que debe considerarse elementos de código
estándar que son obligatorios para asegurar un alto nivel técnico de 
interoperatividad entre código PHP compartido.

Las palabras clave "DEBE/MUST", "NO DEBE/MUST NOT", "REQUERIDO/REQUIRED", 
"SE DEBE/SHALL", "NO SE DEBE/SHALL NOT", "SE DEBERÍA/SHOULD", "NO SE DEBERÍA/SHOULD NOT", "RECOMENDADO/RECOMMENDED", "PUEDE/MAY", y "OPCIONAL/OPTIONAL" de este documento
se deben interpretar como se describe en el [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md


1. Resúmen
----------

- Los archivos DEBEN usar sólo las etiquetas `<?php` y `<?=`.

- Los archivos DEBEN usar sólo UTF-8 sin BOM para código PHP.

- Los archivos DEBERÍAN *o* declarar símbolos (clases, funciones, constantes, etc.)
  *o* tener otros efectos (ej. generar salída de información, cambiar configuraciones, etc.)
  pero NO DEBERÍAN realizar ambas acciones.

- Los Paquetes y las Clases DEBEN seguir el estandar [PSR-0][].

- Los nombres de las Clases DEBEN declararse en `StudlyCaps`.

- Las constantes de una Clase, DEBEN declararse en mayúsculas con guiones bajos como separadores.

- Los nombres de los métodos DEBEN declararse en `camelCase`.


2. Archivos
-----------

### 2.1. Etiquetas PHP

El código PHP DEBE usar las etiquetas con formato largo `<?php ?>` o el formato de salida corto short-echo `<?= ?>`; el código NO DEBE utilizar ningún otro tipo de etiqueta.

### 2.2. Codificación de caracteres

El código PHP DEBE usar sólo UTF-8 sin BOM.

### 2.3. Otros efectos

Un archivo DEBERÍA declarar nuevos símbolos (clases, funciones, constantes,
etc.) y no debería causar otros efectos, o el archivo DEBERÍA ejecutar lógica 
con con otros efectos, pero NO DEBERÍA realizar ambas acciones.

La frase "otros efectos" significa, ejecución de lógica que no está relacionada
directamente con la declaración de clases, funciones, constantes, etc., *excluyendo
la inclusión o requerimiento de archivos*.

"Otros efectos" incluye pero no limita a: generación de salida de información,
uso explícito de `require` o `include`, conectando con servicios externos, modificar configuraciones del ini, emitir errores o excepciones, modificar variables globales o estáticas, leer en o escribir a un archivo, etc.

A continuación se muestra un ejemplo de de un archivo con una declaración y otros efectos;
p.e, un ejemplo de lo que se debe evitar:

```php
<?php
// otro efectos: cambio de configuración del ini
ini_set('error_reporting', E_ALL);

// otro efectos: carga de un archivo
include "file.php";

// otro efecto: generación de salida
echo "<html>\n";

// declaración
function foo()
{
    // cuerpo de la función
}
```

El siguiente ejemplo muestra un archivo con una declaración sin otros efectos; 
p.e., un ejemplo de lo que se debe emular:

```php
<?php
// declaración
function foo()
{
    // cuerpo de la función
}

// la declaración condiciona *no* es considerado otros efectos
if (! function_exists('bar')) {
    function bar()
    {
        // cuerpo de la función
    }
}
```


3. Paquetes y nombres de Clases
-------------------------------

Los Paquetes y las Clases DEBEN seguir el estándar [PSR-0][].

Esto significa que cada estará declarada en un único archivo, y estará en un 
paquete que tendrá al menos un nivel: un nivel raíz correspondiente al nombre
del proveedor.

El nombre de las Clases DEBE estar declarado en `StudlyCaps`.

El código escrito para PHP 5.3 y superior DEBE utilizar paquetes adecuados.

Por ejemplo:

```php
<?php
// PHP 5.3 y superior:
namespace Proveedor\Model;

class Foo
{
}
```

El código escrito para PHP 5.2.x y anteriores DEBERÍA utilizar la conveción de 
pseudo-paquetes agregando el prefijo `Proveedor_` al nombre de las clases.

```php
<?php
// PHP 5.2.x y anteriores:
class Proveedor_Model_Foo
{
}
```

4. Constantes, Propiedades y Métodos de Clases
----------------------------------------------

El término "clase" hace referencia a todas las clases, interfaces y traits.

### 4.1. Constantes

Las constantes de Clases, DEBEN estar declaradas en mayúsculas haciendo uso del 
guión bajo como separador.

Por ejemplo:

```php
<?php
namespace Proveedor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. Propiedades

Esta guía de forma intencionada evita cualquier recomendación en relación la uso
de `$StudlyCaps`, `$camelCase`, o `$under_score` en los nombres de las propiedades.

Cualquiern conveción de nombres que se utilice DEBERÍA estar aplicada de forma 
consistente en un ámbito razonable. El ámbito puede ser a nivel del proveedor, 
a nivel de paquete, a nivel de clase o a nivel de método.

### 4.3. Métodos

Los nombres de los métodos DEBEN estar declarados en `camelCase()`.
