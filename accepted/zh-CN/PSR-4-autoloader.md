# 自动加载器

文档中出现的 "MUST"、"MUST NOT"、"REQUIRED"、"SHALL"、"SHALL NOT"、"SHOULD"、
"SHOULD NOT"、"RECOMMENDED"、"MAY" 和 "OPTIONAL" 关键字参考
[RFC 2119](http://tools.ietf.org/html/rfc2119) 的描述。


## 1. 概述

该 PSR 描述了从文件路径中 [自动加载][]类的规范。它是完全的可互用，
并且可以添加到其它任意的自动加载规范中，包括 [PSR-0][]。
该 PSR 还描述了使用该规范自动加载的文件如何存放。


## 2. 规范

1. 术语 "类(class)" 指代的是类(classes)、接口(interfaces)、
   特性(traits)和其他相似的结构。

2. 完整的类名格式如下：

        \<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>

    1. 完整的类名必须(MUST)要有一个顶层的命名空间名，称之为厂商命名空间("Vendor Name")。

    2. 完整的类名可以(MAY)有一个或者多个子命名空间。

    3. 完整的类名必须(MUST)要有一个终结类名。

    4. 在完整类名的任何部分，下划线没有特殊含义。

    5. 完整类名中的字母字符可以(MAY)是大小写任意组合。

    6. 所有的类名必须(MUST)是区分大小写的。

3. 要加载的文件何时与完整类名对应...

    1. 在完整的类名中不包含前置命名空间分隔符的一系列连续的前置命名空间和
       子命名空间名（称为“命名空间前缀”）至少要对应一个“基本目录”。

    2. “命名空间前缀”后的连续子命名空间名要对应“基本目录”内的子目录，在其内部
       命名空间分隔符表示目录分隔符。子目录名必须(MUST)与子命名空间名匹配。

    3. 最终的类名与以 `.php` 结尾的文件名对应。文件名必须(MUST)与最终的类名相匹配。

4. 自动加载器实现在任何层级中都必须不能(MUST NOT)抛出异常，
   必须不能(MUST NOT)抛出错误，而且不应该(SHOULD NOT)有返回值。


## 3. 例子

下面的表格展示了对于给定的完整类名、命名空间前缀和基本目录所对应的文件路径。

|  完整类名                     |  命名空间前缀      |  基本目录                |  文件路径
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

与该规范一致的自动加载器实现的例子，请参考[实例文件][]。
实例实现不能(MUST NOT)认为是规范的一部分，并且可能(MAY)随时更改。

[自动加载]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/zh-CN/PSR-0.md
[实例文件]: https://github.com/php-fig/fig-standards/blob/master/accepted/zh-CN/PSR-4-autoloader-examples.md
