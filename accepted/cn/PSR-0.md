自动加载标准
====================

下面描述了实现自动加载器互用性必须遵循的强制性要求。

强制性
---------

* 一个全称的命名空间和类必须遵循下列结构  `\<Vendor Name>\(<Namespace>\)*<Class Name>`。
* 每一个命名空间必须有一个顶级的命名空间（“厂商名称”）。
* 每个命名空间可以按照它的需要来创建多个子命名空间。
* 在从文件系统载入文件的时候，每一个命名空间分隔符被转换为 `DIRECTORY_SEPARATOR`。
* 在类名中的每一个 `_` 字符将被转换为一个 `DIRECTORY_SEPARATOR`。 字符 `_` 在命名空间中没有特殊的含义。
* 当从文件系统加载文件的时候，全称的命名空间和类以 `.php` 结尾。
* 厂商名称，命名空间和类名可以是任何小写和大写字母的组合。

举例
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

命名空间和类名中的下划线
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

