Standard avtomatskega nalagalnika
=================================

> **Opuščen** - Od 2014-10-21 je bil PSR-0 označen za opuščenega. [PSR-4] je sedaj priporočen
kot alternativa.

[PSR-4]: http://www.php-fig.org/psr/psr-4/

Sledeče opisuje obvezne zahteve, ki se jih morate držati
za interoperabilnost avtomatskega nalagalnika.

Obveznosti
----------

* Polno kvalificirani imenski prostor in razred morata imeti sledečo
  strukturo `\<Vendor Name>\(<Namespace>\)*<Class Name>`
* Vsak imenski prostor mora imeti imenski prostor najvišje ravni ("t.i. Vendor Name oz. ime izdelovalca").
* Vsak imenski prostor ima lahko kolikor želite pod-imenskih prostorov.
* Vsak ločevalec imenskega prostora je pretvorjen v t.i. `DIRECTORY_SEPARATOR`, ko
  nalaga iz datotečnega sistema.
* Vsak znak `_` v imenu razreda je pretvorjen v
  `DIRECTORY_SEPARATOR`. Znak `_` nima kakšnega posebnega pomena v
  imenskem prostoru.
* Polno kvalificirani imenski prostor in razred ima pripono `.php` ko
  se nalaga iz datotečnega sistema.
* Znaki abecede v imenih izdelovalcev, imenskih prostorih in imenih razredov so lahko
  katerakoli kombinacija malih in velikih črk.

Primeri
-------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Podčrtaji v imenskih prostorih in imena razredov
------------------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Standarde, ki jih tu nastavimo, bi morali biti najnižji skupni imenovalec za
nebolečo interoperabilnost avtomatskega nalagalnika. Preverite lahko, da
sledite tem standardom z uporabo tega primera SplClassLoader
izvedbe, ki je zmožen naložiti PHP 5.3 razrede.

Primer izvedbe
--------------

Spodaj je primer funkcije, ki enostavno ponazarja, kako so zgoraj
predlagani standardi avtomatsko naloženi.

~~~php
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
spl_autoload_register('autoload');
~~~

SplClassLoader izvedba
----------------------

Sledeči gist je primer izvedbe SplClassLoader, ki lahko
naloži vaše razrede, če sledite zgoraj predlaganim standardom interoperabilnosti
avtomatskega nalagalnika. Gre za trenutno priporočljivi način nalaganja PHP
5.3 razredov, ki sledijo tem standardom.

* [http://gist.github.com/221634](http://gist.github.com/221634)
