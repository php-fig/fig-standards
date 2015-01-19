类自动加载标准
====================

> **已弃用** - 自 2014-10-21 起 PSR-0 已标记为弃用。推荐使用 [PSR-4] 代替。

[PSR-4]: http://www.php-fig.org/psr/psr-4/

以下描述了互用性自动加载器必须遵守的强制依赖。

强制性
---------

* 完整的命名空间和类名必须如下结构 `\<Vendor Name>\(<Namespace>\)*<Class Name>`
* 每个命名空间必须要有一个顶层命名空间 （"Vendor Name"）。
* 每个命名空间可以有任意多个子命名空间。
* 当从文件系统加载时，每个命名空间的分隔符将会被转换为 `目录分隔符`。
* 在类名（CLASS NAME） 中的每个 `_` 字符将会被转换为 `目录分隔符`。
  `_` 字符中命名空间中没有特殊意义。
* 当从文件系统加载时，完整的命名空间和类名后缀为 `.php`。
* 在厂商名（Vendor Name）、命名空间和类名中的字母字符可以是大小写的任意组合。

例子
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

命名空间和类名中的下划线
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

这里我们制定的标准应该是互用性自动加载器的最低的共同标准。
你可以使用这个 SplClassLoader 加载器事例测试一下这些标准，它能够加载 PHP 5.3 的类。

实现例子
----------------------

下面是一个例子函数简单地展示了如何自动加载上面的标准。

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

SplClassLoader 加载器实现
-----------------------------

下面的 gist 是一个 SplClassLoader 加载器实现的事例，
如果你遵守上面互用性加载器的标准，那么你就可以使用它来加载你的类。
对于 PHP 5.3 中遵循这些标准的类推荐使用这种方式加载。

* [http://gist.github.com/221634](http://gist.github.com/221634)

