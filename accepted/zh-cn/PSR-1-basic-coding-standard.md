基础编码标准
=====================

这一节的标准包含那些确保共享PHP代码之间的高水平技术互操作性，被认为所必须的标准编码元素。

关键词 "必须", "不能", "REQUIRED", "SHALL", "SHALL NOT", "应该",
"不应该", "RECOMMENDED", "MAY", 和 "OPTIONAL" 在此文档中的意义按照 [RFC 2119] 
文档中的描述理解。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


1. 概述
-----------

- 文件 **必须** 只使用 `<?php` 和 `<?=` 标签。

- PHP源文件 #必须# 只使用没有 BOM 的 UTF-8 编码。

- 一个文件 **应该** *只做* 符号声明(类，函数，常量等)
  *或者* 引起副作用的操作 (例如：输出信息，修改.ini配置文件等)
  但**不应该**同时做这两件事。

- 命名空间和类 **必须** 遵守 "autoloading" PSR: [[PSR-0], [PSR-4]].

- 类名 **必须** 被声明为`StudlyCaps`的格式。

- 类常量声明 **必须** 只用大写字母，由下划线分词。

- 类方法 **必须** 被声明为`camelCase`的格式。


2. 文件
--------

### 2.1. PHP 标签

PHP 代码 **必须** 使用长的 `<? php? >` 标签或短回显 `<? =? >` 标签;
**不能** 使用其它的变体标签。

### 2.2. 字符编码

PHP代码#必须#只使用没有 BOM 的 UTF-8 编码。

### 2.3. 副作用

一个文件 **应该** 在声明新的符号(类，函数，常量等)时，
不引起其它的副作用，或者 **应该** 执行带有副作用的逻辑操作，
但**不应该**同时做这两件事。

习语 "副作用" 是指与声明类，函数，常量等，不直接相关的逻辑操作
*仅仅指当前的文件*。

"副作用" 包括但不是限于： 产生输出，显式的使用 `require` 或 `include`，
连接到外部的服务器，修改 ini 设置，发出错误或异常，修改全局或静态变量，
读取或写入的文件，等等。

下面是一个文件包含了声明和副作用的例子；
即，一个应该被避免的例子：

```php
<?php
// 副作用: 改变了 ini 设置
ini_set('error_reporting', E_ALL);

// 副作用: 加载了一个文件
include "file.php";

// 副作用: 产生了输出
echo "<html>\n";

// 声明
function foo()
{
    // function body
}
```

下面的例子是一个不含副作用的声明文件；
即，一个应该被模仿的例子：

```php
<?php
// 声明
function foo()
{
    // function body
}

// 条件句的声明 *不是* 副作用
if (! function_exists('bar')) {
    function bar()
    {
        // function body
    }
}
```


3. 命名空间和类名
----------------------------

命名空间和类 **必须** 遵守 "autoloading" PSR: [[PSR-0], [PSR-4]].

这意味着每个类本身就是在一个文件中, 并且在至少一个级别的命名空间中
： 一个顶级的 厂商名.

类名 **必须** 被声明为`StudlyCaps`的格式。

PHP 5.3 及更新版本的代码 **必须** 使用命名空间.

例如：

```php
<?php
// PHP 5.3 及更新版本:
namespace Vendor\Model;

class Foo
{
}
```

5.2.x 及之前的代码 **应该** 使用伪命名空间约定
即把`Vendor_` 缀在类名前面。

```php
<?php
// PHP 5.2.x 及更旧版本:
class Vendor_Model_Foo
{
}
```

4. 类常量，属性，和方法
-------------------------------------------

习语 "类" 指的是所有的classes，interfaces，和traits。

### 4.1. 常量

类常量声明 **必须** 只用大写字母，由下划线分词。
例如：

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. 属性

本指南有意地回避了使用如下属性名的任何建议
`$StudlyCaps`， `$camelCase`， 或 `$under_score`。

不管使用什么样的命名约定，**应该** 在一个合理范围内保持一致的方式应用。
那个范围可以是厂商级别，包级别，类级别或方法级别。

### 4.3. 方法

类方法 **必须** 被声明为`camelCase`的格式。
