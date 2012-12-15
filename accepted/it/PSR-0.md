Quanto segue descrive i requisiti necessari ai quali ci si deve uniformare
per garantire l''interoperabilità degli autoloader.

Obblighi
---------

* Il fully-qualified namespace e la classe deve avere la seguente
  struttura `\<Nome Vendor>\(<Namespace>\)*<Nome Classe>`
* Ogni namespace deve avere un namespace di primo livello ("Nome Vendor").
* Ogni namespace può avere una quantità arbitraria di sotto-namespace.
* Ogni separatore di namespace deve essere convertito in un `DIRECTORY_SEPARATOR` al
  caricamento dal file system.
* Ogni carattere `_` nel NOME DELLA CLASSE deve essere convertito in un
  `DIRECTORY_SEPARATOR`. Il carattere `_` non ha nessun significato particolare nel
  namespace.
* Al fully-qualified namespace e alla classe viene apposto il suffisso `.php` al
  caricamento da file system.
* I caratteri alfabetici nei nomi dei vendor, nei namespace, e nei nomi delle classi possono
  formare una qualsiasi combinazione di caratteri minuscoli e caratteri maiuscoli.

Esempi
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Underscores in Namespaces and Class Names
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

The standards we set here should be the lowest common denominator for
painless autoloader interoperability. You can test that you are
following these standards by utilizing this sample SplClassLoader
implementation which is able to load PHP 5.3 classes.

Example Implementation
----------------------

Below is an example function to simply demonstrate how the above
proposed standards are autoloaded.
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

The following gist is a sample SplClassLoader implementation that can
load your classes if you follow the autoloader interoperability
standards proposed above. It is the current recommended way to load PHP
5.3 classes that follow these standards.

* [http://gist.github.com/221634](http://gist.github.com/221634)

