# Autoloader

这篇文档中的关键词 "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", 和 "OPTIONAL" 在 [RFC 2119](http://tools.ietf.org/html/rfc2119) 这里有详细的解释。


## 1. 概述

这篇PSR文档是对 [autoloading][] class，也就是从文件路径自动加载类的标准实现的说明。它是一个能和包括 [PSR-0][] 在内的其他自动加载类的标准兼容的标准。这篇PSR文档同时也说明了如何安排具体的文件结构。


## 2. 详细说明

1. 下面用到的术语 "class" 指代 class, interface, trait 以及其他相似的代码结构。

2. 一个标准的class名应该遵循如下格式:

		\<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>
		
		\<顶级命名空间名>(\<子命名空间名>)*\<具体类名> // 这是上面一行的中文版

    1. 一个标准的class名必须(MUST)有一个顶级命名空间名，也就是这个类所属代码库的命名空间名。

    2. 一个标准的class名可以(MAY)有一个或多个子命名空间。

    3. 一个标准的class名必须(MUST)以一个具体类名为结尾。

    4. 在标准的class名中的任何一个部分下划线都不应具备特殊的含义。

    5. 一个标准的class名可以(MAY)是大小写字母的任意组合。

    6. 所有的class名必须(MUST)是严格区分大小写的。

3. 当加载一个遵循本标准的class文件时 ...

    1. class名开头从左往右的一系列命名空间名中，不包括开头的反斜线'\'，必须关联上至少一个存放类文件的 “基础目录”，开头的这一段命名空间名在以下称之为命名空间前缀部分(namespace prefix)。

    2. class名前缀部分之后的部分应该对应“基础目录”下的子目录结构，命名空间的分隔符'\'对应子目录的层级，并且子目录的名字必须(MUST)在大小写上也严格匹配子命名空间名。

    3. 结尾的具体类名应该对应一个后缀为 '.php' 的文件，该文件的文件名必须(MUST)在大小写上也严格的匹配具体类名。

4. 自动加载方法的实现绝对不能(MUST NOT)抛出任何等级的异常、错误，也不应该(SHOULD NOT)返回一个值。


## 3. 范例

下面的表格展示了文件路径和标准的class名、命名空间前缀部分、前缀对应的基础目录之间的关联

| 标准的class名 | 命名空间前缀部分 | 前缀对应的基础目录 | 最终文件路径
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

遵循本标准的自动加载器的一个实现可以参看这里 [examples file][] 。该实现是一个仅供参考的范例，绝对不应该(MUST NOT)被当做是本标准的一部分并且它随时可能(MAY)改变。

[autoloading]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[examples file]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
