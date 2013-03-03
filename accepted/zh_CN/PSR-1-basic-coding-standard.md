基本代码规范
=====================

本节标准包含了成为标准代码所需要的基本元素，以确保高级技术特性可以在PHP代码中共享。

在 [RFC 2119][]中的特性关键词"必须"(MUST)，“不可”(MUST NOT)，“必要”(REQUIRED)，“应当”(SHALL)，“不应”(SHALL NOT)，“可以”(SHOULD)，“不可”(SHOULD NOT)，“推荐”(RECOMMENDED)，“或许”(MAY)和“可选”(OPTIONAL)在这文档中将被用来描述。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md


1. 大纲 
-----------

- 文件`必须`使用 `<?php` 和 `<?=` 标签。

- 文件`必须`使用不带BOM的UTF-8代码文件。

- 文件`可以`声明符号（类，函数，常量等...）或者引起边缘效应（例如：生成输出，修改.ini配置等）,但不能同时存在。

- 命名空间和类名`必须`遵守 [PSR-0][]。

- 类名`必须`使用骆驼式`StudlyCaps`写法 (译者注：驼峰式的一种变种，后文将直接用`StudlyCaps`表示)。

- 类名常量`必须`使用全大写和下划线分隔符。

- 方法名`必须`使用驼峰式`cameCase`写法(译者注：后文将直接用`camelCase`表示)。


2. 文件
--------

### 2.1. PHP标签

PHP代码`必须`使用长标签`<?php ?>`或者短输出式`<?= ?>`标签；它`不可`使用其他的标签变种。

### 2.2. 字符编码

PHP代码`必须`只使用不带BOM的UTF-8。

### 2.3. 边缘效应

一个文件`可以`声明新符号 (类名，函数名，常量等)并且不产生边缘效应，或者`可以`执行有边缘影响的逻辑，但不能同时使用。

短语"边缘效应"意思是不直接执行逻辑的类，函数，常量等 *仅包括文件*

“边缘效应”包含但不局限于：生成输出，明确使用`require`和`include`，连接外部服务，修改ini配置，触发错误和异常，修改全局或者静态变量，读取或修改文件等等

下面是一个例子文件同时包含声明和边缘效应
即避免的例子：

```php
<?php	
// side effect: change ini settings
ini_set('error_reporting', E_ALL);

// side effect: loads a file
include "file.php";

// side effect: generates output
echo "<html>\n";

// declaration
function foo()
{
    // function body
}
```

下面这个例子仅仅包含声明并且没有边缘效应；
即需要提倡的例子：

```php
<?php
// declaration
function foo()
{
    // function body
}

// conditional declaration is *not* a side effect
if (! function_exists('bar')) {
    function bar()
    {
        // function body
    }
}
```


3. 命名空间和类名
----------------------------

命名空间和类名必须遵守 [PSR-0][].

这意味着每个类只能是一个文件本身，并且至少有一个层级的命名空间：顶级的组织名称。

类名必须使用骆驼式`StudlyCaps`写法

代码`必须`使用PHP5.3及以后编写正式的命名空间
例子：

```php
<?php
// PHP 5.3 and later:
namespace Vendor\Model;

class Foo
{
}
```

代码使用5.2.x及之前编写可以使用`Vendor_`作为前缀的伪命名空间作为类名

```php
<?php
// PHP 5.2.x and earlier:
class Vendor_Model_Foo
{
}
```

4. 类常量，属性和方法
-------------------------------------------

术语“类”指所有的类，接口和特性(traits)

### 4.1. 常量

类常量`必须`使用全大写，分隔符使用下划线作为声明。
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

本手册有意避免推荐使用`$StulyCaps`，`$cameCase`或者`unser_score`作为属性名字

不管名称约定是不是在一个`可以`接受的合理范围。这个范围可能是组织，包，类，方法。

### 4.3. 方法

方法名必须用`cameCase()`写法