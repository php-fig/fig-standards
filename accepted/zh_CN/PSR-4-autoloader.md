## 1. 概况

这个 PSR 描述的是通过文件路径[自动载入][]类的指南；它作为对 [PSR-0][] 的补充；根据这个
指导如何规范存放文件来自动载入；

## 2. 说明（Specification）

1. 术语「类」是一个泛称；它包含类，接口，traits 以及其他类似的结构；

2. 完全限定类名应该类似如下范例：

    \<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>

    1. 完全限定类名必须有一个顶级命名空间（Vendor Name）；
    2. 完全限定类名可以有多个子命名空间；
    3. 完全限定类名应该有一个终止类名；
    4. 下划线在完全限定类名中是没有特殊含义的；
    5. 字母在完全限定类名中可以是任何大小写的组合；
    6. 所有类名必须以大小写敏感的方式引用；

3. 当从完全限定类名载入文件时：

    1. 在完全限定类名中，连续的一个或几个子命名空间构成的命名空间前缀（不包括顶级命名空间的分隔符），至少对应着至少一个基础目录。
    2. 在「命名空间前缀」后的连续子命名空间名称对应一个「基础目录」下的子目录，其中的命名
空间分隔符表示目录分隔符。子目录名称必须和子命名空间名大小写匹配；
    3. 终止类名对应一个以 `.php` 结尾的文件。文件名必须和终止类名大小写匹配；

4. 自动载入器的实现不可抛出任何异常，不可引发任何等级的错误；也不应返回值；

## 3. 范例

如下表格展示的是与完全限定类名、命名空间前缀和基础目录相对应的文件路径：

| 完全限定类名                    | 命名空间前缀       | 基础目录                 | 实际的文件路径
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

例子中的自动载入器非常适应这个指南，请参照 [示例文件][]。由于可能随时变更，实例不能作为指南的一部分。

[自动载入]: http://php.net/autoload
[PSR-0]: https://github.com/hfcorriez/fig-standards/tree/master/accepted/zh_CN/PSR-0.md
[示例文件]: http://www.php-fig.org/psr/psr-4/PSR-4-autoloader-examples.md
