下面描述了关于自动加载器特性强制性要求：

强制性
---------

* 一个完全标准的命名空间必须要有一下的格式结构`\<Vendor Name>\(<Namespace>\)*<Class Name>`
* 命名空间必须有一个顶级的组织名称 ("Vendor Name").
* 命名空间中可以根据情况决定使用多少个子空间
* 命名空间中的分隔符当从文件系统加载的时候将被映射为 `DIRECTORY_SEPARATOR` 
* 命名空间中的类名中的`_`没有特殊含义，也将被作为`DIRECTORY_SEPARATOR`对待. 
* 命名空间中的类名在从文件系统加载时文件名都需要以`.php`结尾
* 组织名，空间名，类名都可以随意选择使用大小写英文字符

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

以上是我们为实现无痛的自动加载特性设定的最低标准。你可以按照此标准实现一个SplClassLoader在PHP 5.3中去加载类。

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

下面的gist是一个SplClassLoader实例可以按照上面建议的自动加载特性来加载类。这也是我们当前推荐在PHP5.3中按照上述标准加载类的方式

* [http://gist.github.com/221634](http://gist.github.com/221634)