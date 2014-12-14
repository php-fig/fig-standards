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

范例
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

命名空间和类名中的下划线
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

这里我们设置的标准应该是可互用的自动加载器的最低标准。你可以利用这个能够加载PHP 5.3的类的简单的SplClassLoader实现测试代码是否遵循了这些标准。

范例实现
----------------------

下面是一个能够简要实现上面提议标准的自动加载器的范例函数。

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

SplClassLoader 实现
-----------------------------

下面的gist是一个SplClassLoader实现的例子，如果你遵循上述的自动加载器互用性标准，那它就能够加载你的类。
这也是遵循上述标准，用于加载PHP 5.3中的类的推荐做法。

* [http://gist.github.com/221634](http://gist.github.com/221634)

