Autoloading Štandard
====================

> **Zastarané** - Od 2014-10-21 PSR-0 bolo označené ako zastaralé. [PSR-4] je teraz odporúčané
ako alternatíva.

[PSR-4]: http://www.php-fig.org/psr/psr-4/

Nasleduje opis povinných požiadaviek, ktoré sa musia dodržať aby bol autoloader schopný operovať.

Povinné
---------

* Plne definovaný menný priestor a trieda musia byť v tvare
 `\<Meno balíka>\(<Menný priestor>\)*<Meno triedy>`
* Každý menný priestor musí mať na najvyššej úrovni menný priestor ("Meno balíka").
* Každý menný priestor môže mať hocikoľko menných priestorov v pod-úrovniach.
* Každý oddelovač menného priestoru sa zmení na `DIRECTORY_SEPARATOR` keď sa
  nahráva zo súborového systému.
* Každý znak `_` v Mene Triedy sa zmení na `DIRECTORY_SEPARATOR`. Znak `_` nemá špeciálny význam v mennom priestore.
* Plne definovaný menný priestor a trieda majú príponu `.php` keď sa načítavajú zo súborového systému.
* Abecedné znaky v menách balíkov, menných priestoroch a menách tried môžu byť v hociakej kombinácii
  malých a veľkých písmen.

Príklady
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/cesta/ku/projektu/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/cesta/ku/projektu/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/cesta/ku/projektu/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/cesta/ku/projektu/lib/vendor/Zend/Mail/Message.php`

Podtržítka v menných priestoroch a menách tried
-----------------------------------------------

* `\namespace\package\Meno_triedy` => `/cesta/ku/projektu/lib/vendor/namespace/package/Meno/Triedy.php`
* `\menny_priestor\meno_balika\Meno_triedy` => `/cesta/ku/projektu/lib/vendor/menny_priestor/meno_balika/Meno/Triedy.php`

Štandardy ktoré sme tu nastavili, by mali byť najmenším spoločným menovateľom 
pre bezproblémový chod autoloadera. Môžete otestovať, že nasledujete 
tieto štandardy, využitím tohto jednoduchého SplClassLoadera,
jeho implementáciou budete schopný nahrávať PHP 5.3 triedy.

Príklad Implementácie
---------------------

Nižšie je príklad funkcie, ktorá jednoducho demonštruje ako sa vyššie uvedený štandard samo-načítava.

```php
<?php

function autoload($menoTriedy)
{
    $menoTriedy = ltrim($menoTriedy, '\\');
    $menoSuboru  = '';
    $mennyPriestor = '';
    if ($poziciaPoslPriestoru = strrpos($menoTriedy, '\\')) {
        $mennyPriestor = substr($menoTriedy, 0, $poziciaPoslPriestoru);
        $menoTriedy = substr($menoTriedy, $poziciaPoslPriestoru + 1);
        $menoSuboru  = str_replace('\\', DIRECTORY_SEPARATOR, $mennyPriestor) . DIRECTORY_SEPARATOR;
    }
    $menoSuboru .= str_replace('_', DIRECTORY_SEPARATOR, $menoTriedy) . '.php';

    require $menoSuboru;
}
spl_autoload_register('autoload');
```

SplClassLoader Implementácia
----------------------------

Nasledujúci návrh je jednoduchá implementácia SplClassLoader-a, ktorá vie načítať vaše triedy
použitím autoloadera ak nasledujete kroky uvedené vyššie. Je to momentálne odporúčaný spôsob
načítavania PHP 5.3 tried, ktoré spĺňajú tento štandard.

* [http://gist.github.com/221634](http://gist.github.com/221634)

