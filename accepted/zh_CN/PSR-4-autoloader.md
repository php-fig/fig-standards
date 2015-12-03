# 自动加载器

关键词“MUST”，“MUST NOT”，“REQUIRED”，“SHALL”，“SHALL NOT”，“SHOULD”，
“SHOULD NOT”，“RECOMMENDED”，“MAY”以及“OPTIONAL”的详细说明见 [RFC 2119](http://tools.ietf.org/html/rfc2119) 。


## 1. 概述

本 PSR 描述的是从文件路径来 [自动加载][] 类的一个规范。它是完全可互操作的，可以
额外使用其它的自动加载规范，包括 [PSR-0][] 。 这个 PSR 还描述了如何规范存放文件
来自动加载。


## 2. 规范

1. “类”是一个泛称，它包含类（classes），接口（interfaces），traits，以及其他类
   似的结构。

2. 一个完整的类名应该符合如下格式：

        \<NamespaceName>（\<SubNamespaceNames>)*\<ClassName>

    1. 完整的类名 MUST 有一个顶级命名空间名称，也就是众所周知的 “组织命名空间（vendor namespace）”。

    2. 完整的类名 MAY 有一个或多个子命名空间名称。

    3. 完整的类名 MUST 有一个终止类名。

    4. 下划线在完整的类名的任何部分中都没有特殊意义。

    5. 完整的类名 MAY 由大小字母任意组合。

    6. 所有的类名 MUST 以区分大小写的方式引用。

3. 当加载一个文件对应一个完整的类名 ...

    1. 一个连续的一个或多个主命名空间和子命名空间名称，不包括主命名空间分隔符，
       在完整的类名（一个“命名空间前缀”）必须对应于至少一个“基础目录”。

    2. 在“命名空间前缀”后的连续子命名空间名称对应于“基础目录”下的子目录，
       其中的命名空间分隔符表示目录分隔符，子目录名称必须匹配子命名空间名称。

    3. 最终的类名应该对应于以 `.php` 结尾的文件名。文件名 MUST 匹配最终类名。

4. 自动加载器的实现 MUST NOT 抛出任何异常，MUST NOT 唤起任何级别的错误，以及
   SHOULD NOT 返回一个值。


## 3. 示例

下表展示的是完整的类名对应的文件路径，命名空间前缀，以及基础目录。

| 完整的类名                    | 命名空间前缀       | 基础目录                 | 文件路径
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

实现符当前合规范的自动加载器示例，请查阅 [示例文件][] 。
示例代码 MUST NOT 被视为本规范的一部分且 MAY 随时更改。

[自动加载]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[示例文件]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
