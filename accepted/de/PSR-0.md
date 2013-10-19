Autoloading Standard
====================

Das folgende Dokument beschreibt die Voraussetzungen, welche eingehalten werden müssen,
um die Kompatibilität mit dem Autoloader zu gewährleisten.

Verpflichtend
---------

* Ein komplett ausgeschriebener Namespace mit Klasse muss die folgende
  Struktur einhalten `\<Anbieter Name>\(<Namespace>\)*<Name der Klasse>`
* Jeder Namespace muss den übergeordneten Namespace ("Anbieter Namen") besitzen.
* Jeder Namespace kann beliebig viele Unter-Namespaces besitzen.
* Jeder Trenner für Namespaces wird beim Laden vom Dateisystem zu einem `DIRECTORY_SEPARATOR` konvertiert.
* Jedes `_` Zeichen im KLASSENNAMEN wird zu einem
  `DIRECTORY_SEPARATOR` konvertiert. Das Zeichen `_` hat keine besondere Bedeutung in einem
  Namespace.
* Der komplette Namespace wird mit dem Namen der Klasse und dem Suffix `.php` kombiniert,
  wenn dieser vom Dateisystem geladen wird.
* Alphabetische Zeichen in Anbieternamen, Namespaces und Klassennamen können
  in beliebiger Kombination aus Groß- und Kleinschreibung bestehen.

Beispiele
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Unterstriche in Namespaces und Klassennamen
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Der Standard, welcher hier durch uns gesetzt wird, repräsentiert die minimale Anforderung,
um eine Kompatibilität hinsichtlich Autoloader zu gewährleisten.
Mit der Nutzung der Beispiel-Implementation des SplClassLoaders (verfügbar ab PHP 5.3)
kann getestet werden, ob die Standards eingehalten werden.

Beispiel-Implementation
----------------------

Das Beispiel zeigt eine Beispielfunktion, welche auf einfache Weise demonstriert, wie die
oben erklärten Standards in einem Autoloader implementiert werden können:


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

SplClassLoader Implementation
-----------------------------

Das folgende Gist beinhaltet eine Beispiel-Implementation des SplClassLoaders.
Die Implementation kann genutzt werden, um Klassen zu laden, sofern diese die
oben erklärten Standards einhalten. Derzeit wird PSR-0
empfohlen um PHP Klassen (ab PHP 5.3) zu laden, sofern diese die oben erklärten Standards einhalten.

* [http://gist.github.com/221634](http://gist.github.com/221634)

