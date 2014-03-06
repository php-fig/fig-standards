### 1. 概况

这个 PSR 描述的是通过文件路径自动载入类的指南；它作为对 PSR-0 的补充；根据这个
指导如何规范存放文件来自动载入；

### 2. 说明（Specification）

#### 1. 类是一个泛称；它包含类，结构，traits 以及其他类似的结构；

#### 2. 完整的类名应该类似如下范例：

    \<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>

+ 每一个命名空间必须有一个顶级命名空间（Vendor Name）；
+ 每一个命名空间都可以有多个子命名空间；
+ 每一个命名空间的分隔符都必须转化成 `DIRECTORY_SEPARATOR`，当它被文件系统载入时；
+ 每一个在类名中的 `_` 符号都会被转化成 `DIRECTORY_SEPARATOR`。而 `_` 符号在
命名空间中没有任何意义；
+ 完整的的命名空间和类当从文件系统中被加载的时候，都会以 `.php` 为后缀；
+ Vendor 名，命名空间和类名中的英文字符可以是任意大小写的组合；

#### 3. 当从完整的类名载入文件时：

1. 一个连续的一个或多个主命名空间和子命名空间名称，不包括主命名空间分隔符，
在完全限定类名（一个“命名空间前缀”）必须对应于至少一个“基本目录”。
2. 在“命名空间前缀”后的连续子命名空间名称对应的子目录中的“基本目录”，其中的命名
空间分隔符表示目录分隔符；子目录名称必须匹配的子命名空间名称；
3. 最后的类名应该和 php 文件名匹配；文件名的大小写必须匹配；

#### 4. 自动载入器的实现不能抛出任何异常，不能抛出任何等级的错误；也不能返回值；

### 3. 范例

如下表格展示的是完整的类名与其中相关文件路径的关系：

| 完整的类名                    | 命名空间前缀       | 基础目录                 | 实际的类文件路径
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

例子中的自动载入器非常适应这个指南，请查阅 [examples file](http://www.php-fig.org/psr/psr-4/PSR-4-autoloader-examples.md)
；但是他不能作为指南的一部分；可能随时被改变；
