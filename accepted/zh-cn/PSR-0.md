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
* 厂商名，命名空间，和类名中的字母可以是任意的大小写组合。

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

我们设定的标准应该是最低的共同标准
无痛自动装弹机的互操作性。你可以测试你是
以下这些标准，利用这样splclassloader
实现能够加载PHP 5.3类

实现示例
----------------------

下面是示例函数来简单地演示如何以上
拟议的标准是自动加载。


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

The following gist is a sample SplClassLoader implementation that can
load your classes if you follow the autoloader interoperability
standards proposed above. It is the current recommended way to load PHP
5.3 classes that follow these standards.

* [http://gist.github.com/221634](http://gist.github.com/221634)

