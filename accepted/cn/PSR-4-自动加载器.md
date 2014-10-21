# 自动加载器

本文档中的关键字“必须”， “不允许”，“必需”，“将会”，“将不会”，“应该”，“不应该”，
“推荐”，“可以”和“可选”遵循[RFC 2119](http://tools.ietf.org/html/rfc2119)中的描述。


## 1. 概述

这个PSR是描述了从文件路径 [自动加载][] 类的规范 。它与任何其它自动加载规范，
包括[PSR-0][]是可互用的。这个PSR也描述了根据规范，文件放在哪里可以被自动加载。


## 2. 规范

1. 术语“类”值得是类，接口，Trait和其它类似的结构。

2. 一个全称的类名为下列形式：

        \<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>

    1. 全称类名必须有一个顶级命名空间，也称为“厂商命名空间”。

    2. 全称的类名可以有一个或多个子命名空间名。

    3. 全称的类名必须以一个类名结束。

    4. 在全称命名空间的任意部分，下划线都没有任何特殊含义。

    5. 在全称的类名中字母字符可以是大写字母和小写字母的任意组合。

    6. 所有的类名必须使用大小写敏感的方式引用。

3. 当加载一个对应全称的类名的文件的时候 ……

    1. A contiguous series of one or more leading namespace and sub-namespace
       names, not including the leading namespace separator, in the fully
       qualified class name (a "namespace prefix") corresponds to at least one
       "base directory".

    2. The contiguous sub-namespace names after the "namespace prefix"
       correspond to a subdirectory within a "base directory", in which the
       namespace separators represent directory separators. The subdirectory
       name MUST match the case of the sub-namespace names.

    3. The terminating class name corresponds to a file name ending in `.php`.
       The file name MUST match the case of the terminating class name.

4. Autoloader implementations MUST NOT throw exceptions, MUST NOT raise errors
   of any level, and SHOULD NOT return a value.


## 3. 示例

下面的表格显示了文件路径和跟定的全称类名，命名空间前缀，基础目录。

| 全称的类名                    | 命名空间前缀       | 基础目录                 | 产生的文件路径
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

For example implementations of autoloaders conforming to the specification,
please see the [examples file][]. Example implementations MUST NOT be regarded
as part of the specification and MAY change at any time.

[autoloading]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[examples file]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
