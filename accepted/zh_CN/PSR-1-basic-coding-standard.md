基本代码规范
============

这部分的标准包括哪些应该被认为是标准的编码元素，以此来确保共享的 PHP 代码之间的技术互操作性的高水平。

关键词“MUST”，“MUST NOT”，“REQUIRED”，“SHALL”，“SHALL NOT”，“SHOULD”，
“SHOULD NOT”，“RECOMMENDED”，“MAY”以及“OPTIONAL”的详细说明见 [RFC 2119] 。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


1. 概述
-------

- 文件 MUST 只使用 `<?php` 和 `<?=` 标签。

- 文件 MUST 只使用 UTF-8 无 BOM 编码格式。

- 文件 SHOULD 只用来做声明（class，function，constant，等）或者只用来做一些引起
  副作用的逻辑（如：产生输出，修改 .ini 配置，等），但 SHOULD NOT 同时做。

- 命名空间和类 MUST 遵循一个“自动加载”PSR：[ [PSR-0] ， [PSR-4] ]。

- 类名 MUST 使用 `StudlyCaps 大驼峰` 。

- 类中的常量 MUST 只由大写字母和下划线组成。

- 方法名 MUST 使用 `camelCase 小驼峰` 。


2. 文件
-------

### 2.1. PHP 标签

PHP代码 MUST 只使用长标签 `<?php ?>` 或者短输出式标签 `<?= ?>`；MUST NOT 使用其
它标签。

### 2.2. 字符编码

PHP 代码 MUST 只使用 UTF-8 无 BOM 头编码格式。

### 2.3. 副作用

一个文件 SHOULD 只用来做声明（class，function，constant，等）且不引起其它的副作
用，或它 SHOULD 只执行副作用逻辑，但是 SHOULD NOT 同时做。

“副作用”意味着执行的逻辑不直接与 classes，functions，constants，等，相关
*merely from including the file* 。

“副作用”包含但不局限于：产生输出，显式地使用 `require` 或 `include`，连接外部
服务，修改 ini 配置，触发错误或异常，修改全局或静态变量，读取表单或修改文件，等等。

下面是一个既包含声明又有副作用的一个示例；即，应避免的示例：

```php
<?php
// 副作用：更改 ini 设置
ini_set('error_reporting', E_ALL);

// 副作用：加载一个文件
include "file.php";

// 副作用：产生输出
echo "<html>\n";

// 声明
function foo()
{
    // function body
}
```

下面这个示例是一个仅包含声明的示例文件；即，应提倡的示例：

```php
<?php
// 声明
function foo()
{
    // 函数体
}

// 有条件的声明 *不是* 一个副作用
if (! function_exists('bar')) {
    function bar()
    {
        // 函数体
    }
}
```


3. 命名空间和类名
-----------------

命名空间和类 MUST 遵循一个“自动加载”PSR：[ [PSR-0] ， [PSR-4] ]。

这意味着一个文件中只能有一个类，并且每个类至少要有一级命名空间：一个顶级的组织名
称。

类名 MUST 使用 `StudlyCaps 大驼峰` 。

PHP 5.3 及之后的代码 MUST 使用正规的命名空间。

示例：

```php
<?php
// PHP 5.3 及之后:
namespace Vendor\Model;

class Foo
{
}
```

PHP 5.2.x 及之前的代码 SHOULD 使用伪命名空间约定 `Vendor_` 作为类的前缀。

```php
<?php
// PHP 5.2.x and earlier:
class Vendor_Model_Foo
{
}
```

4. 类的常量，属性，和方法
-------------------------

术语“类”指代所有 classes，interfaces，以及 traits。

### 4.1. 常量

类中的常量 MUST 只由大写字母和下划线组成。
示例：

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

本规范中故意不对 `$StudlyCaps`，`$camelCase` 或者 `$under_score` 中的某一种风格
作特别推荐，完全由你依据个人喜好决定属性名的命名风格。

但是不管你如何定义属性名， SHOULD 在一个合理的范围内保持一致。这个范围可能是组织级别
的，包级别的，类级别的，或者方法级别的。

### 4.3. 方法

方法名 MUST 使用 `camelCase() 小驼峰` 。
