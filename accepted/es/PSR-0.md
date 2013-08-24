A continuación se describen los requisitos obligatorios que deben cumplirse para la interoperabilidad del autoloader.

Obligatorio
-----------

* Un espacio de nombres y clase completamente cualificada debe tener la siguiente estructura `\<Nombre del proveedor>\(<Paquete>\)<Nombre de clase>`.
* Cada espacio de nombres debe tener un espacio de nombres de nivel superior ("Nombre del proveedor").
* Cada espacio de nombres puede tener tantos sub-espacios de nombres como sea necesario.
* Cada separador de espacio de nombres se convierte en la constante `DIRECTORY_SEPARATOR` cuando se carga desde el sistema de archivos. [^1]
* Cada carácter `_` en el nombre de la clase se convierte en la constante `DIRECTORY_SEPARATOR`. El carácter `_` no tiene ningún significado especial en el espacio de nombres.
* Al espacio de nombres y la clase completamente cualificada se le añade el sufijo `.php` cuando se cargue desde el sistema de archivos.
* Los caracteres alfabéticos en los nombres de proveedor, espacios de nombres y nombres de clase pueden contener cualquier combinación de mayúsculas y minúsculas.

Ejemplos
----------

* `\Doctrine\Common\IsolatedClassLoader` => `/directorio/del/proyecto/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/directorio/del/proyecto/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/directorio/del/proyecto/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/directorio/del/proyecto/lib/vendor/Zend/Mail/Message.php`

Guiones bajos en Espacios de nombres y nombres de Clase
--------------------------------------------------------

* `\espacio_de_nombres\paquete\Nombre_De_Clase` => `/directorio/del/proyecto/lib/proveedor/espacio_de_nombres/paquete/Nombre/De/Clase.php`
* `\espacio_de_nombres\nombre_de_paquete\Nombre_De_Clase` => `/directorio/del/proyecto/lib/proveedor/espacio_de_nombres/nombre_de_paquete/Nombre/De/Clase.php`

El estándar aquí descrito, debe ser el mínimo común denominador para la interoperabilidad del autoloader. Puede comprobar que sigue estas normas mediante la utilización del la implementación de ejemplo de autoloader SplClassLoader, capaz de cargar clases de PHP 5.3.

Ejemplo de implementación
----------------------------

A continuación, se muestra una función de ejemplo para demostrar de forma sencilla cómo se cargan de automáticamente las clases con la propuesta anterior.

```php
<?php

function autoload($nombreDeClase)
{
    $nombreDeClase = ltrim($nombreDeClase, '\\');
    $nombreDeFichero  = '';
    $nombreDeEspacio = '';
    if ($ultimaPos = strrpos($nombreDeClase, '\\')) {
        $nombreDeEspacio = substr($nombreDeClase, 0, $ultimaPos);
        $nombreDeClase = substr($nombreDeClase, $ultimaPos + 1);
        $nombreDeFichero  = str_replace('\\', DIRECTORY_SEPARATOR, $nombreDeEspacio) . DIRECTORY_SEPARATOR;
    }
    $nombreDeFichero .= str_replace('_', DIRECTORY_SEPARATOR, $nombreDeClase) . '.php';

    require $nombreDeFichero;
}
```

Implementación de SplClassLoader
------------------------------------

El siguiente *gist*, es un ejemplo de implementación de SplClassLoader, que puede cargar sus clases si ha seguido el estándar anteriormente expuesto. Este es el método recomendado para la carga de clases de PHP 5.3 que siga estas normas.

* [http://gist.github.com/221634](http://gist.github.com/221634)

Notas
------

[^1]: El nombre del proveedor se traduce en inglés como `vendor`. La constante `DIRECTORY_SEPARATOR` contiene el carácter de separación de directorios, diferente en cada sistema operativo. Por ejemplo en *Unix u OS X este carácter es la barra `/`
mientras que en Windows se trata de la barra invertida `\`.
