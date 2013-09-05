下文描述了若要使用一个通用的`自动加载器(autoloader)`，你所需要遵守的规范：

规范
---------

* 一个完全标准的`命名空间(namespace)`和`类(class)`的结构是这样的：`\<Vendor Name>\(<Namespace>\)*<Class Name>`
* 每个`命名空间(namespace)`都必须有一个顶级的`空间名(namespace)`("`组织名(Vendor Name)`")。
* 每个`命名空间(namespace)`中可以根据需要使用任意数量的`子命名空间(sub-namespace)`。
* 从文件系统中加载源文件时，`空间名(namespace)`中的分隔符将被转换为 `DIRECTORY_SEPARATOR`。
* `类名(class name)`中的每个下划线`_`都将被转换为一个`DIRECTORY_SEPARATOR`。下划线`_`在`空间名(namespace)`中没有什么特殊的意义。
* 完全标准的`命名空间(namespace)`和`类(class)`从文件系统加载源文件时将会加上`.php`后缀。
* `组织名(vendor name)`，`空间名(namespace)`，`类名(class name)`都由大小写字母组合而成。

示例
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

`空间名(namespace)`和`类名(class name)`中的下划线
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

以上是我们为实现通用的自动加载而制定的最低标准。你可以利用能够自动加载`PHP 5.3`类的`SplClassLoader`来测试你的代码是否符合这些标准。

实例
----------------------

下面是一个怎样利用上述标准来实现自动加载的示例函数。

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

`SplClassLoader`实现
-----------------------------

下面的gist是一个按照上面建议的标准来自动加载类的`SplClassLoader`实例。这是依据这些标准来加载`PHP 5.3`类的推荐方案。

* [http://gist.github.com/221634](http://gist.github.com/221634)
