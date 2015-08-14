Autoloading 规范
====================

> **已废弃** - 在 2014-10-21 psr-0已被标记为废弃的。 [PSR-4] 现在被推荐为替代规范。

[PSR-4]: http://www.php-fig.org/psr/psr-4/

下面介绍了实现autoloader 互操作性必须遵守的强制性要求

强制性要求
---------

* 一个完整的命名空间和类必须有以下结构`\<厂商名>\(<命名空间>\)*<类名>`
* 每个命名空间必须有一个顶级命名空间 ("厂商名").
* 每个命名空间可以有任意多个子命名空间.
* 当从文件系统加载时，命名空间的分隔符会被转换为` 目录分隔符 `。
* 每个在类名中的 `_` 字符会被转换为 `目录分隔符`。
`_`字符在命名空间中没有特殊含义。
* 当从文件系统加载时，完整的命名空间和类会被加上`.php`后缀。
* 厂商名，命名空间，和类名可以由任意的大小写字母组成。

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

我们在这里设置的标准应该是实现autoloader互操作性的最低共同标准。
你可以通过利用这个能够加载PHP 5.3类的SplClassLoader实现来测试你是否遵循这些标准。


实现示例
----------------------

下面是一个示例函数来简单地演示上述建议的标准是如何自动加载。


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

SplClassLoader 的实现
-----------------------------

下面的要点是一个 SplClassLoader 样本实现，可以加载您的类，如果你遵循以上所建议的autoloader的互操作性标准。
它是当前所推荐加载遵循这些标准的 PHP5.3 类的方法。

* [http://gist.github.com/221634](http://gist.github.com/221634)

