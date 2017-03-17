Стандарт автозавантаження
=========================

> **Deprecated** - Станом на 21 жовтня 2014 року стандарт PSR-0 був позначений як застарілий.
Натомість, на даний момент рекомендується використовувати [PSR-4].

[PSR-4]: http://www.php-fig.org/psr/psr-4/

Нижче вказано вимоги, обов'язкові до виконання з метою забезпечення сумісності механізмів автозавантаження.

Обов'язкові вимоги
------------------

* Повністю визначений простір назв та назва класу повинні мати наступну структуру: \<Vendor Name>\(<Namespace>\)*<Class Name>.
* Кожен простір назв повинен починатися з простору назв вищого рівня, що вказує на розробника коду («ім'я виробника»).
* Кожен простір назв може містити необмежену кількість вкладених підпросторів назв.
* При зверненні до файлової системи кожен роздільник у просторі назв перетворюється в DIRECTORY_SEPARATOR.
* Кожен символ _ («нижнє підкреслення») у НАЗВІ КЛАСУ перетворюється в DIRECTORY_SEPARATOR. При цьому символ _ не має жодного особливого значення в назві простору назв (і не зазнає перетворень).
* При зверненні до файлової системи повністю визначений простір назв та ім'я класу доповнюються суфіксом .php.
* В імені виробника, назві простору назв та назві класу допускається використання буквених символів у будь-яких комбінаціях нижнього і верхнього регістрів.

Приклади
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Нижнє підкреслення у назвах просторів назв та класів
----------------------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Представлені тут стандарти повинні сприйматися як мінімально необхідний набір правил для забезпечення сумісності автозавантажувачів. Ви можете перевірити, наскільки дотримуєтесь вказаних правил, скориставшись наступним прикладом реалізації SplClassLoader (орієнтований на завантаження класів у PHP 5.3).

Приклад реалізації
------------------

Нижче наведено приклад функції, що ілюструє, як описані вище вимоги впливають на процес автозавантаження:

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
spl_autoload_register('autoload');
```

Реалізація SplClassLoader
-------------------------

Нижче подано приклад реалізації SplClassLoader, здатного виконувати автозавантаження ваших класів за умови, що ви дотримуєтесь описаних вище стандартів. На даний момент такий підхід є рекомендованим для завантаження класів у PHP 5.3 за умови дотримання даного стандарту:

* [http://gist.github.com/221634](http://gist.github.com/221634)
