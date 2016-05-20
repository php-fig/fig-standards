Standardy autoloadingu
==================================

> **Porzucony** - W dniu 2014-10-21 standard PSR-0 został porzucony na rzecz [PSR-4].

[PSR-4]: http://www.php-fig.org/psr/psr-4/

Poniższy dokument opisuje obowiązkowe wymagania, do których należy się zastosować
w celu ustandaryzowania procesu autoloadingu klas.

Wymagania
---------

* W pełni poprawna przestrzeń nazw (namespace) i nazwa klasy muszą posiadać następującą strukturę
`\<Nazwa Vendora>\(<Przestrzeń Nazw>\)*<Nazwa klasy>`
* Każda przestrzeń nazw musi posiadać przestrzeń bazową ("Nazwa Vendora").
* Każda przestrzeń nazw może posiadać dowolną ilość podprzestrzeni.
* Każdy separator między przestrzeniami nazw jest zamieniany na stałą `DIRECTORY_SEPARATOR`
podczas ładowania z systemu plików.
* Każdy znak `_` w nazwie klasy jest konwertowany do wartości stałej `DIRECTORY_SEPARATOR`.
Znak `_` nie ma żadnego specjalnego znaczenia w przestrzeni nazw.
* W pełni poprawny plik przestrzeni nazw i klasy musi kończyć się rozszerzeniem `.php` podczas ładowania z systemu plików.
* Wielkość znaków (duże/małe litery) w przestrzeni bazowej ("Nazwa Vendora"), przestrzeniach nazw czy klasach nie ma żadnego znaczenia.

Przykłady
---------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Znaki podkreślenia w przestrzeniach nazw i nazwach klas
-------------------------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Opisane powyżej reguły powinny być najbardziej uniwersalnym rozwiązaniem problemu autoloadingu klas w PHP.
W każdym momencie można wypróbować działanie powyższych standardów poprzez implementację klasy SplClassLoader –
będzie ona działać poprawnie już dla projektów opartych o wersję PHP 5.3.

Przykładowa implementacja
-------------------------

Poniższy przykład demonstruje jak powinna wyglądać implementacja autloadingu na podstawie powyższych standardów.

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

Implementacja SplClassLoader
----------------------------

Poniższy link zawiera kod klasy SplClassLoader implementującej przedstawione powyżej standardy.
Jest to aktualnie zalecane podejście do procesu autoloadingu klas napisanych dla wersji PHP 5.3 które
przestrzegają powyższych standardów.

* [http://gist.github.com/221634](http://gist.github.com/221634)

