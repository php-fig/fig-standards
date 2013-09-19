Ниже описаны обязательные требования, которые должны быть выполнены для взаимодействия с автозагрузчиком.

Обязательно
-----------

* Полное пространство имён и имя класса должны иметь следующую структуру
   `\<Vendor Name>\(<Namespace>\)*<Class Name>`
* Каждое пространство имён должно иметь корневой уровень ("Имя Производителя").
* Каждое пространство имён может иметь сколько угодно подпространств. Вложенность не ограничена.
* Все разделители пространства имён преобразуется в `DIRECTORY_SEPARATOR` при загрузке из файловой системы.
* Каждый символ `_` в ИМЕНИ КЛАССА преобразуется в `DIRECTORY_SEPARATOR`. Символ `_` не имеет специального значения в пространстве имён.
* Полное пространство имён и класс дополняются cуффикcом .php при загрузке из файловой ситемы.
* Символы в имени корневого уровня, пространства имён и имена классов могут иметь любую комбинацию из строчных и заглавных букв.

Примеры
-------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Подчеркивания в пространстве имён и именах класов
-------------------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Как минимум указанные выше стандарты обязаны выполняться для безболезненной взаимозаменяемости автозагрузчиков. Вы можете убедиться, что следуете стандартам используя пример реализации SplClassLoader, который способен загружать классы PHP 5.3.

Пример Реализации
-----------------

Ниже приведен пример функции, показывающей как стандарты, предлагаемые выше, могут использоваться автозагрузчиком.

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

Реализация SplClassLoader
-------------------------

В gist по ссылке ниже приводится пример реализации SplClassLoader, который может загружать ваши классы, если они подчиняются стандарту, описанному выше и является рекомендуемым способом загрузки классов в PHP 5.3.

* [http://gist.github.com/221634](http://gist.github.com/221634)
