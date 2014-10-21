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

    1. 在全称的类名（含有“命名空间前缀”）中的一系列连续的一个或者多个
       前缀命名空间和子命名空间，不包含前缀的命名空间分隔符，对应了至少一个“基础目录”。

    2. 在“命名空间前缀”之后的连续的子命名空间名称对应了“基础目录”中的一个子目录，
       命名空间分隔符代表了目录的分隔符。子目录名称必须完全匹配子命名空间名称。

    3. 最后的类名对应了以`.php`为后缀的文件名。文件名必须与类名的大小写匹配。

4. 自动加载器的实现不允许抛出异常，不允许产生任何级别的错误，不应该有返回值。


## 3. 示例

下面的表格显示了文件路径和跟定的全称类名，命名空间前缀，基础目录。

| 全称的类名                    | 命名空间前缀       | 基础目录                 | 产生的文件路径
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

按照本规范实现的自动加载器的示例，请查看 [示例文件][]。示例实现不是本规范的一部分，它随时都有可能会发生更改。

[自动加载]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[示例文件]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
