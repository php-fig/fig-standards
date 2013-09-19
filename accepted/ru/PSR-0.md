Ниже описаны обязательные требования, которые должны быть выполнены для взаимодействия с автозагрузчиком.

Обязательные
------------

* Полное пространство имен и имя класса должны иметь данную структуру
   `\<Vendor Name>\(<Namespace>\)*<Class Name>`
* Каждое пространство имен должно иметь корневой уровень ("Vendor Name").
* Каждое пространство имен может иметь под пространство сколько необходимо.
* Все разделители пространства имен преобразуется в `DIRECTORY_SEPARATOR` при загрузке из файловой системы.
* Каждый `_` символ в ИМЕНИ КЛАССА преобразуется в `DIRECTORY_SEPARATOR`. Символ `_` не имеет специального значения в пространстве имен.
* Полное пространство имен и клас добовляет cуффикc `.php` при загрузке из файловой ситемы.
* Символы в имени корневого уровня, пространства имен и имена классов могут иметь любую комбинацию из строчных и заглавных букв.

Примеры
-------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Подчеркивания в пространстве имен и именах класов
-------------------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Установленные здесь стандарты это минимум которым не стоит пренебрегать для совместимости с автозагрузчиком. Вы можете убедиться, что соответствуете этим стандартам используя пример реализации SplClassLoader который способен загружать PHP 5.3 классы.

Пример Реализации.
------------------

Ниже приведен пример функции, чтобы показать, как стандарты предлагаемые выше могут использоваться автозагрузчиком.

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

SplClassLoader Реализация
-------------------------

Ниже приводится реализация *gist* примера SplClassLoader который загружает классы, если вы следовали стандартам, предложенных выше. Это рекомендуемый способ загрузки классов в PHP 5.3, которые соответствуют этим стандартам.

* [http://gist.github.com/221634](http://gist.github.com/221634)
