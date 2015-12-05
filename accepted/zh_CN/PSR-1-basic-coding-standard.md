基本代码规范
=====================

本节我们将会讨论一些基本的代码规范问题，以此作为将来讨论更高级别的代码分享和技术互用的基础。

[RFC 2119][]中的`必须(MUST)`，`不可(MUST NOT)`，`建议(SHOULD)`，`不建议(SHOULD NOT)`，`可以/可能(MAY)`等关键词将在本节用来做一些解释性的描述。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/zh_CN/PSR-0.md


1. 概述
-----------

- 源文件`必须`只使用 `<?php` 和 `<?=` 这两种标签。

- 源文件中php代码的编码格式`必须`只使用不带`字节顺序标记(BOM)`的`UTF-8`。

- 一个源文件`建议`只用来做声明（`类(class)`，`函数(function)`，`常量(constant)`等）或者只用来做一些引起副作用的操作（例如：输出信息，修改`.ini`配置等）,但`不建议`同时做这两件事。

- `命名空间(namespace)`和`类(class)` `必须`遵守[PSR-0][]标准。

- `类名(class name)` `必须`使用`骆驼式(StudlyCaps)`写法 (译者注：`驼峰式(cameCase)`的一种变种，后文将直接用`StudlyCaps`表示)。

- `类(class)`中的常量`必须`只由大写字母和`下划线(_)`组成。

- `方法名(method name)` `必须`使用`驼峰式(cameCase)`写法(译者注：后文将直接用`camelCase`表示)。


2. 文件
--------

### 2.1. PHP标签

PHP代码`必须`只使用`长标签(<?php ?>)`或者`短输出式标签(<?= ?>)`；而`不可`使用其他标签。

### 2.2. 字符编码

PHP代码的编码格式`必须`只使用不带`字节顺序标记(BOM)`的`UTF-8`。

### 2.3. 副作用

一个源文件`建议`只用来做声明（`类(class)`，`函数(function)`，`常量(constant)`等）或者只用来做一些引起副作用的操作（例如：输出信息，修改`.ini`配置等）,但`不建议`同时做这两件事。

短语`副作用(side effects)`的意思是 *在包含文件时* 所执行的逻辑与所声明的`类(class)`，`函数(function)`，`常量(constant)`等没有直接的关系。

`副作用(side effects)`包含但不局限于：产生输出，显式地使用`require`或`include`，连接外部服务，修改ini配置，触发错误或异常，修改全局或者静态变量，读取或修改文件等等

下面是一个既包含声明又有副作用的示例文件；即应避免的例子：

```php
<?php
// 副作用：修改了ini配置
ini_set('error_reporting', E_ALL);

// 副作用：载入了文件
include "file.php";

// 副作用：产生了输出
echo "<html>\n";

// 声明
function foo()
{
    // 函数体
}
```

下面是一个仅包含声明的示例文件；即应提倡的例子：

```php
<?php
// 声明
function foo()
{
    // 函数体
}

// 条件式声明不算做是副作用
if (! function_exists('bar')) {
    function bar()
    {
        // 函数体
    }
}
```


3. `空间名(namespace)`和`类名(class name)`
----------------------------

`命名空间(namespace)`和`类(class)`必须遵守 [PSR-0][].

这意味着一个源文件中只能有一个`类(class)`，并且每个`类(class)`至少要有一级`空间名（namespace）`：即一个顶级的`组织名(vendor name)`。

`类名(class name)` `必须`使用`StudlyCaps`写法。

`PHP5.3`之后的代码`必须`使用正式的`命名空间(namespace)`
例子：

```php
<?php
// PHP 5.3 及之后:
namespace Vendor\Model;

class Foo
{
}
```

`PHP5.2.x`之前的代码`建议`用伪命名空间`Vendor_`作为`类名(class name)`的前缀

```php
<?php
// PHP 5.2.x 及之前:
class Vendor_Model_Foo
{
}
```

4. 类的常量、属性和方法
-------------------------------------------

术语`类(class)`指所有的`类(class)`，`接口(interface)`和`特性(trait)`

### 4.1. 常量

类常量`必须`只由大写字母和`下划线(_)`组成。
例子：

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

本指南中故意不对`$StulyCaps`，`$camelCase`或者`$unser_score`中的某一种风格作特别推荐，完全由读者依据个人喜好决定属性名的命名风格。

但是不管你如何定义属性名，`建议`在一个合理的范围内保持一致。这个范围可能是`组织(vendor)`级别的，`包(package)`级别的，`类(class)`级别的，或者`方法(method)`级别的。

### 4.3. 方法

方法名则`必须`使用`camelCase()`风格来声明。
