A continuación se describen los requisitos obligatorios que deben cumplirse para la interoperabilidad del autoloader.

Obligatorio
-----------

* Un namespace fully-qualified y clase debe tener la estructura siguiente `\ <Nombre del proveedor> \ (<Namespace> \) * <Nombre de clase>`. [^1]
* Cada namespace debe tener un namespace de nivel superior ("Nombre del proveedor").
* Cada namespace puede tener tantos sub-namespaces como quiera.
* Cada separador de namespace se convierte en un `DIRECTORY_SEPARATOR` cuando se carga desde el sistema de archivos. [^2]
* Cada carácter `_` en el nombre de la clase se convierte en un `DIRECTORY_SEPARATOR`. El carácter `_` no tiene ningún significado especial en el namespace.
* Al namespace fully-qualified y clase se le añade el sufijo `.php` cuando se cargue desde el sistema de archivos.
* Los caracteres alfabéticos en los nombres de proveedor, namespaces y nombres de clase pueden contener cualquier combinación de mayúsculas y minúsculas.

Ejemplos
----------

* `\Doctrine\Common\IsolatedClassLoader` => `/directorio/del/proyecto/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/directorio/del/proyecto/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/directorio/del/proyecto/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/directorio/del/proyecto/lib/vendor/Zend/Mail/Message.php`

El guión bajo en Namespaces y nombres de Clase
--------------------------------------------------------

* `\namespace\paquete\Nombre_De_Clase` => `/directorio/del/proyecto/lib/vendor/namespace/paquete/Nombre/De/Clase.php`
* `\namespace\nombre_de_paquete\Nombre_De_Clase` => `/directorio/del/proyecto/lib/vendor/namespace/nombre_de_paquete/Nombre/De/Clase.php`

Los estándares que aquí establecidos deben ser el mínimo común denominador para la interoperabilidad del autoloader. Puede comprobar que sigue estas normas mediante la utilización del la implementación del ejemplo de autoloader SplClassLoader, capaz de cargar clases de PHP 5.3.

Ejemplo de implementación
----------------------------

A continuación se muestra una función de ejemplo para demostrar de forma sencilla cómo se cargan de automáticamente las clases con la propuesta anterior.
```php
<?php

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
```

Implementación de SplClassLoader
------------------------------------

El siguiente gist es un ejemplo de implementación de SplClassLoader, que puede cargar sus clases si ha seguido el estándar anteriormente expuesto. Esta es la forma actual recomendada para la carga de clases de PHP 5.3 que sigan estas normas.

* [http://gist.github.com/221634](http://gist.github.com/221634)

Notas
------

[^1]: Namespace fully-qualified se trata de un namespace completamente cualificado.

[^2]: El nombre del proveedor se traduce en inglés como `vendor`. La constante `DIRECTORY_SEPARATOR` contiene el carácter de separación de directorios, diferente en cada sistema operativo. Por ejemplo en *nix u OS X este caracter es la barra `/` mientras en Windows se trata de la barra invertida `\`.