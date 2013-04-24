Este documento describe los requisitos que han de seguirse para 
la autocarga interoperativa.

Obligatorio
---------

* Un paquete correctamente organizado y clases descriptivas deben tener la
  estructura `\<Nombre del proveedor>\(<Paquete>\)*<Nombre de clase>`
* Cada paquete debe tener un paquete raíz ("Nombre del proveedor").
* Cada paquete puede tener tantos subpaquetes como sea necesario.
* Cada separador de paquete se convierte en un `DIRECTORY_SEPARATOR` cuando se
  cargue del sistema de archivos.
* Cada carácter `_`  en el nombre de la clase se convertirá en un `DIRECTORY_SEPARATOR`. 
  El caracter `_` no tiene un significado especial en el paquete.
* Al paquete y la clase se le agregará el sufijo `.php` cuando se cargue del sistema de
  archivos.
* El nombre del proveedor, paquetes y clases, serán una combinación de caracteres alfabéticos
  combinados de cualquier forma en mayúsculas y minúsculas.

Ejemplos
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Guiones bajos en Paquetes y Clases
----------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

El estandar que describimos debe ser el minimo comun denominador para
reducir los problemas de autocarga interoperativa. Puedes comprobar que 
estás siguiendo estos estandares utilizando este ejemplo de la implementación
del SplClassLoader que debe cargar las clases en PHP 5.3.

Ejemplo de implementación
-------------------------

Debajo hay un método de ejemplo, que demuestra como las propuestas anteriores
son autocargadas.
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
--------------------------------

El siguiente gist es un ejemplo de la implementación de SplClassLoader
que puede cargar tus clases siguiendo los estándares de autocarga interoperativa
propuestos anteriormente. Es el método recomendado para carga de clases en PHP 5.3
que sigue estos estándares.

* [http://gist.github.com/221634](http://gist.github.com/221634)

