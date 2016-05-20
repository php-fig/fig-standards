Стандарт Автозагрузки
=====================

> **Deprecated** - По состоянию на 21 октября 2014 года PSR-0 был помечен как устаревший.
В настоящее время рекомендуется использовать [PSR-4] в качестве замены.

[PSR-4]: http://www.php-fig.org/psr/psr-4/

Ниже описаны обязательные требования, которые должны быть выполнены для совместимости с автозагрузчиком классов.

Обязательно
-----------

* Полное пространство имён вместе с классом должны иметь следующую структуру `\<Имя производителя>\(<Пространство имён>\)*<Имя класса>`
* У каждого пространства имён должен быть корневой уровень («Имя Производителя»).
* У каждого пространства имён при необходимости может быть неограниченное количество подуровней.
* Все разделители пространства имён преобразуются в `DIRECTORY_SEPARATOR` при загрузке из файловой системы.
* Каждый символ `_` в ИМЕНИ КЛАССА преобразуется в `DIRECTORY_SEPARATOR`. Символ `_` не имеет специального значения в пространстве имён.
* Полное пространство имён вместе с классом дополняются суффиксом `.php` при загрузке из файловой системы.
* Названия производителей, пространства имен и имена классов могут содержать любую комбинацию строчных и заглавных букв.

Примеры
-------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Подчеркивания в пространстве имён и именах классов
--------------------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Указанные выше стандарты являются минимальным требованием для совместимости с автозагрузчиками. Вы можете убедиться, что следуете стандартам, используя пример реализации SplClassLoader, который способен загружать классы PHP 5.3.

Пример реализации
-----------------

Ниже приведён пример функции, показывающей, как стандарты, предлагаемые выше, могут использоваться автозагрузчиком.

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

Реализация SplClassLoader
-------------------------

В gist по ссылке ниже приводится пример реализации SplClassLoader, который может загружать ваши классы, если они соответствуют стандарту, описанному выше, и является рекомендуемым способом загрузки классов в PHP 5.3.

* [http://gist.github.com/221634](http://gist.github.com/221634)
