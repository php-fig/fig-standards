下面描述了关于自动加载器特性强制性要求：

强制性
---------

* 一个完全标准的命名空间必须要有以下的格式结构`\<Vendor Name>\(<Namespace>\)*<Class Name>`
* 命名空间必须有一个顶级的组织名称 ("Vendor Name").
* 命名空间中可以根据情况使用任意数量的子空间
* 从文件系统中加载源文件的时，命名空间中的分隔符将被映射为 `DIRECTORY_SEPARATOR`
* 命名空间中的类名中的`_`没有特殊含义，也将被作为`DIRECTORY_SEPARATOR`对待.
* 标准的命名空间和类从文件系统加载源文件时只需要加上`.php`后缀即可
* 组织名，空间名，类名都可以随意使用大小写英文字符的组合

示例
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

命名空间和类名中的下划线
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

以上是我们为轻松实现自动加载特性设定的最低标准。你可以利用下面这个可以自动加载 PHP 5.3 类的SplClassLoader来测试你的代码是否符合以上这些标准。

实例
----------------------

下面是一个函数实例简单展示如何使用上面建议的标准进行自动加载
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

SplClassLoader实现
-----------------------------

下面的gist是一个可以按照上面建议的自动加载特性来加载类的SplClassLoader实例。这也是我们当前在PHP5.3中依据以上标准加载类时推荐的方。

* [http://gist.github.com/221634](http://gist.github.com/221634)
