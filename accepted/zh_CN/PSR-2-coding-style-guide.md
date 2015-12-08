代码风格指南
==================

本手册是基础代码规范([PSR-1][])的继承和扩展。

为了尽可能的提升阅读其他人代码时的效率，下面例举了一系列的通用规则，特别是有关于PHP代码风格的。

各个成员项目间的共性组成了这组代码规范。当开发者们在多个项目中合作时，本指南将会成为所有这些项目中共用的一组代码规范。 因此，本指南的益处不在于这些规则本身，而在于在所有项目中共用这些规则。

[RFC 2119][]中的`必须(MUST)`，`不可(MUST NOT)`，`建议(SHOULD)`，`不建议(SHOULD NOT)`，`可以/可能(MAY)`等关键词将在本节用来做一些解释性的描述。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/zh_CN/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/zh_CN/PSR-1-basic-coding-standard.md


1. 概述
-----------

- 代码`必须`遵守 [PSR-1][]。

- 代码`必须`使用4个空格来进行缩进，而不是用制表符。

- 一行代码的长度`不建议`有硬限制；软限制`必须`为120个字符，`建议`每行代码80个字符或者更少。

- 在`命名空间(namespace)`的声明下面`必须`有一行空行，并且在`导入(use)`的声明下面也`必须`有一行空行。

- `类(class)`的左花括号`必须`放到其声明下面自成一行，右花括号则`必须`放到类主体下面自成一行。

- `方法(method)`的左花括号`必须`放到其声明下面自成一行，右花括号则`必须`放到方法主体的下一行。

- 所有的`属性(property)`和`方法(method)` `必须`有可见性声明；`抽象(abstract)`和`终结(final)`声明`必须`在可见性声明之前；而`静态(static)`声明`必须`在可见性声明之后。

- 在控制结构关键字的后面`必须`有一个空格；而`方法(method)`和`函数(function)`的关键字的后面`不可`有空格。

- 控制结构的左花括号`必须`跟其放在同一行，右花括号`必须`放在该控制结构代码主体的下一行。

- 控制结构的左括号之后`不可`有空格，右括号之前也`不可`有空格。

### 1.1. 示例

这个示例中简单展示了上文中提到的一些规则：

```php
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class Foo extends Bar implements FooInterface
{
    public function sampleFunction($a, $b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // 方法主体
    }
}
```

2. 通则
----------

### 2.1 基础代码规范

代码`必须`遵守 [PSR-1][] 中的所有规则。

### 2.2 源文件

所有的PHP源文件`必须`使用Unix LF(换行)作为行结束符。

所有PHP源文件`必须`以一个空行结束。

纯PHP代码源文件的关闭标签`?>` `必须`省略。

### 2.3. 行

行长度`不可`有硬限制。

行长度的软限制`必须`是120个字符；对于软限制，代码风格检查器`必须`警告但`不可`报错。

一行代码的长度`不建议`超过80个字符；较长的行`建议`拆分成多个不超过80个字符的子行。

在非空行后面`不可`有空格。

空行`可以`用来增强可读性和区分相关代码块。

一行`不可`多于一个语句。

### 2.4. 缩进

代码`必须`使用4个空格，且`不可`使用制表符来作为缩进。

> 注意：代码中只使用空格，且不和制表符混合使用，将会对避免代码差异，补丁，历史和注解中的一些问题有帮助。空格的使用还可以使通过调整细微的缩进来改进行间对齐变得更加的简单。

### 2.5. 关键字和 True/False/Null

PHP关键字([keywords][])`必须`使用小写字母。

PHP常量`true`, `false`和`null` `必须`使用小写字母。

[keywords]: http://php.net/manual/en/reserved.keywords.php


3. `命名空间(Namespace)`和`导入(Use)`声明
---------------------------------

`命名空间(namespace)`的声明后面`必须`有一行空行。

所有的`导入(use)`声明`必须`放在`命名空间(namespace)`声明的下面。

一句声明中，`必须`只有一个`导入(use)`关键字。

在`导入(use)`声明代码块后面`必须`有一行空行。

示例：

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... 其它PHP代码 ...

```


4. `类(class)`，`属性(property)`和`方法(method)`
-----------------------------------

术语“类”指所有的`类(class)`，`接口(interface)`和`特性(trait)`。

### 4.1. `扩展(extend)`和`实现(implement)`

一个类的`扩展(extend)`和`实现(implement)`关键词`必须`和`类名(class name)`在同一行。

`类(class)`的左花括号`必须`放在下面自成一行；右花括号必须放在`类(class)`主体的后面自成一行。


```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // 常量、属性、方法
}
```

`实现(implement)`列表`可以`被拆分为多个缩进了一次的子行。如果要拆成多个子行，列表的第一项`必须`要放在下一行，并且每行`必须`只有一个`接口(interface)`。

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // 常量、属性、方法
}
```

### 4.2. `属性(property)`

所有的`属性(property)`都`必须`声明其可见性。

`变量(var)`关键字`不可`用来声明一个`属性(property)`。

一条语句`不可`声明多个`属性(property)`。

`属性名(property name)` `不推荐`用单个下划线作为前缀来表明其`保护(protected)`或`私有(private)`的可见性。

一个`属性(property)`声明看起来应该像下面这样。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. `方法(method)`

所有的`方法(method)`都`必须`声明其可见性。

`方法名(method name)` `不推荐`用单个下划线作为前缀来表明其`保护(protected)`或`私有(private)`的可见性。

`方法名(method name)`在其声明后面`不可`有空格跟随。其左花括号`必须`放在下面自成一行，且右花括号`必须`放在方法主体的下面自成一行。左括号后面`不可`有空格，且右括号前面也`不可`有空格。

一个`方法(method)`声明看来应该像下面这样。 注意括号，逗号，空格和花括号的位置：

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // 方法主体部分
    }
}
```

### 4.4. `方法(method)`的参数

在参数列表中，逗号之前`不可`有空格，而逗号之后则`必须`要有一个空格。

`方法(method)`中有默认值的参数必须放在参数列表的最后面。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // 方法主体部分
    }
}
```

参数列表`可以`被拆分为多个缩进了一次的子行。如果要拆分成多个子行，参数列表的第一项`必须`放在下一行，并且每行`必须`只有一个参数。

当参数列表被拆分成多个子行，右括号和左花括号之间`必须`又一个空格并且自成一行。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // 方法主体部分
    }
}
```

### 4.5. `抽象(abstract)`，`终结(final)`和 `静态(static)`

当用到`抽象(abstract)`和`终结(final)`来做类声明时，它们`必须`放在可见性声明的前面。

而当用到`静态(static)`来做类声明时，则`必须`放在可见性声明的后面。

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // 方法主体部分
    }
}
```

### 4.6. 调用方法和函数

调用一个方法或函数时，在方法名或者函数名和左括号之间`不可`有空格，左括号之后`不可`有空格，右括号之前也`不可`有空格。参数列表中，逗号之前`不可`有空格，逗号之后则`必须`有一个空格。

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

参数列表`可以`被拆分成多个缩进了一次的子行。如果拆分成子行，列表中的第一项`必须`放在下一行，并且每一行`必须`只能有一个参数。

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. 控制结构
---------------------

下面是对于控制结构代码风格的概括：

- 控制结构的关键词之后`必须`有一个空格。
- 控制结构的左括号之后`不可`有空格。
- 控制结构的右括号之前`不可`有空格。
- 控制结构的右括号和左花括号之间`必须`有一个空格。
- 控制结构的代码主体`必须`进行一次缩进。
- 控制结构的右花括号`必须`主体的下一行。

每个控制结构的代码主体`必须`被括在花括号里。这样可是使代码看上去更加标准化，并且加入新代码的时候还可以因此而减少引入错误的可能性。

### 5.1. `if`，`elseif`，`else`

下面是一个`if`条件控制结构的示例，注意其中括号，空格和花括号的位置。同时注意`else`和`elseif`要和前一个条件控制结构的右花括号在同一行。

```php
<?php
if ($expr1) {
    // if body
} elseif ($expr2) {
    // elseif body
} else {
    // else body;
}
```

`推荐`用`elseif`来替代`else if`，以保持所有的条件控制关键字看起来像是一个单词。


### 5.2. `switch`，`case`

下面是一个`switch`条件控制结构的示例，注意其中括号，空格和花括号的位置。`case`语句`必须`要缩进一级，而`break`关键字（或其他中止关键字）`必须`和`case`结构的代码主体在同一个缩进层级。如果一个有主体代码的`case`结构故意的继续向下执行则`必须`要有一个类似于`// no break`的注释。

```php
<?php
switch ($expr) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Third case, return instead of break';
        return;
    default:
        echo 'Default case';
        break;
}
```


### 5.3. `while`，`do while`

下面是一个`while`循环控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
while ($expr) {
    // structure body
}
```

下面是一个`do while`循环控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
do {
    // structure body;
} while ($expr);
```

### 5.4. `for`

下面是一个`for`循环控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### 5.5. `foreach`

下面是一个`for`循环控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### 5.6. `try`, `catch`

下面是一个`try catch`异常处理控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
try {
    // try body
} catch (FirstExceptionType $e) {
    // catch body
} catch (OtherExceptionType $e) {
    // catch body
}
```

6. 闭包
-----------

声明闭包时所用的`function`关键字之后`必须`要有一个空格，而`use`关键字的前后都要有一个空格。

闭包的左花括号`必须`跟其在同一行，而右花括号`必须`在闭包主体的下一行。

闭包的参数列表和变量列表的左括号后面`不可`有空格，右括号的前面也`不可`有空格。

闭包的参数列表和变量列表中逗号前面`不可`有空格，而逗号后面则`必须`有空格。

闭包的参数列表中带默认值的参数`必须`放在参数列表的结尾部分。

下面是一个闭包的示例。注意括号，空格和花括号的位置。

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

参数列表和变量列表`可以`被拆分成多个缩进了一级的子行。如果要拆分成多个子行，列表中的第一项`必须`放在下一行，并且每一行`必须`只放一个参数或变量。

当列表（不管是参数还是变量）最终被拆分成多个子行，右括号和左花括号之间`必须`要有一个空格并且自成一行。

下面是一个参数列表和变量列表被拆分成多个子行的示例。

```php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // body
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};

$longArgs_longVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // body
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};
```

把闭包作为一个参数在函数或者方法中调用时，依然要遵守上述规则。

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // body
    },
    $arg3
);
```


7. 结论
--------------

本指南有意的省略了许多元素的代码风格。主要包括：

- 全局变量和全局常量的声明

- 函数声明

- 操作符和赋值

- 行间对齐

- 注释和文档块

- 类名的前缀和后缀

- 最佳实践

以后的代码规范中`可能`会修正或扩展本指南中规定的代码风格。

附录A 调查
------------------

为了写这个风格指南，我们调查了各个项目以最终确定通用的代码风格。并把这次调查在这里公布出来。

### A.1. 调查数据

    url,http://www.horde.org/apps/horde/docs/CODING_STANDARDS,http://pear.php.net/manual/en/standards.php,http://solarphp.com/manual/appendix-standards.style,http://framework.zend.com/manual/en/coding-standard.html,http://symfony.com/doc/2.0/contributing/code/standards.html,http://www.ppi.io/docs/coding-standards.html,https://github.com/ezsystems/ezp-next/wiki/codingstandards,http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html,https://github.com/UnionOfRAD/lithium/wiki/Spec%3A-Coding,http://drupal.org/coding-standards,http://code.google.com/p/sabredav/,http://area51.phpbb.com/docs/31x/coding-guidelines.html,https://docs.google.com/a/zikula.org/document/edit?authkey=CPCU0Us&hgd=1&id=1fcqb93Sn-hR9c0mkN6m_tyWnmEvoswKBtSc0tKkZmJA,http://www.chisimba.com,n/a,https://github.com/Respect/project-info/blob/master/coding-standards-sample.php,n/a,Object Calisthenics for PHP,http://doc.nette.org/en/coding-standard,http://flow3.typo3.org,https://github.com/propelorm/Propel2/wiki/Coding-Standards,http://developer.joomla.org/coding-standards.html
    voting,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,no,no,no,?,yes,no,yes
    indent_type,4,4,4,4,4,tab,4,tab,tab,2,4,tab,4,4,4,4,4,4,tab,tab,4,tab
    line_length_limit_soft,75,75,75,75,no,85,120,120,80,80,80,no,100,80,80,?,?,120,80,120,no,150
    line_length_limit_hard,85,85,85,85,no,no,no,no,100,?,no,no,no,100,100,?,120,120,no,no,no,no
    class_names,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,lower_under,studly,lower,studly,studly,studly,studly,?,studly,studly,studly
    class_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,next,next,next,next,next,next,same,next,next
    constant_names,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper
    true_false_null,lower,lower,lower,lower,lower,lower,lower,lower,lower,upper,lower,lower,lower,upper,lower,lower,lower,lower,lower,upper,lower,lower
    method_names,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,lower_under,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel
    method_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,same,next,next,next,next,next,same,next,next
    control_brace_line,same,same,same,same,same,same,next,same,same,same,same,next,same,same,next,same,same,same,same,same,same,next
    control_space_after,yes,yes,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes
    always_use_control_braces,yes,yes,yes,yes,yes,yes,no,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes
    else_elseif_line,same,same,same,same,same,same,next,same,same,next,same,next,same,next,next,same,same,same,same,same,same,next
    case_break_indent_from_switch,0/1,0/1,0/1,1/2,1/2,1/2,1/2,1/1,1/1,1/2,1/2,1/1,1/2,1/2,1/2,1/2,1/2,1/2,0/1,1/1,1/2,1/2
    function_space_after,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no
    closing_php_tag_required,no,no,no,no,no,no,no,no,yes,no,no,no,no,yes,no,no,no,no,no,yes,no,no
    line_endings,LF,LF,LF,LF,LF,LF,LF,LF,?,LF,?,LF,LF,LF,LF,?,,LF,?,LF,LF,LF
    static_or_visibility_first,static,?,static,either,either,either,visibility,visibility,visibility,either,static,either,?,visibility,?,?,either,either,visibility,visibility,static,?
    control_space_parens,no,no,no,no,no,no,yes,no,no,no,no,no,no,yes,?,no,no,no,no,no,no,no
    blank_line_after_php,no,no,no,no,yes,no,no,no,no,yes,yes,no,no,yes,?,yes,yes,no,yes,no,yes,no
    class_method_control_brace,next/next/same,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/next,same/same/same,same/same/same,same/same/same,same/same/same,next/next/next,next/next/same,next/same/same,next/next/next,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/same,next/next/next

### A.2. 调查说明

`indent_type`:
缩进类型。 `tab` = "使用制表符"，`2` or `4` = "空格数量"

`line_length_limit_soft`:
行长度的“软”限制，用字符。 `?` = 不表示或者数字 `no` 意为不限制.

`line_length_limit_hard`:
行长度的"硬"限制，用字符。 `?` = 不表示或者数字, `no` 意为不限制.

`class_names`:
类名如何命名 `lower` = 只是小写, `lower_under` = 小写加下划线, `studly` = 骆驼型.

`class_brace_line`:
类的左花括号是放在同(`same`)一行还是在下(`next`)一行？

`constant_names`:
类常量如何命名？`upper` = 大写加下划线分隔符。

`true_false_null`:
全小写或者全大写？

`method_names`:
方法名如何命名？`camel` = `驼峰式`, `lower_under` = 小写加下划线分隔符。

`method_brace_line`:
方法的左花括号在同(`same`)一行还是在下(`next`)一行？

`control_brace_line`:
控制结构的左花括号在同(`same`)一行还是在下(`next`)一行？

`control_space_after`:
控制结构关键词后是否有空格？

`always_use_control_braces`:
控制结构总是使用花括号？

`else_elseif_line`:
当使用`else`和`elseif`，是否放在同(`same`)一行还是在下(`next`)一行？

`case_break_indent_from_switch`:
`case`和`break`分别从`swith`语句处缩进多少次？

`function_space_after`:
函数调用的函数名和左括号是否有空格？

`closing_php_tag_required`:
如过是纯PHP文件，关闭标签`?>`是否需要？

`line_endings`:
使用何种的行结束符？

`static_or_visibility_first`:
在定义方法的时候`static`和可见性谁在前面？

`control_space_parens`:
在控制结构表达式中，左括号后面和右括号前面是否要有一个空格？`yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
PHP的开始标签后面是否需要一个空行？

`class_method_control_brace`:
左花括号在类，方法和控制结构中的位置。

### A.3. 调查结果

    indent_type:
        tab: 7
        2: 1
        4: 14
    line_length_limit_soft:
        ?: 2
        no: 3
        75: 4
        80: 6
        85: 1
        100: 1
        120: 4
        150: 1
    line_length_limit_hard:
        ?: 2
        no: 11
        85: 4
        100: 3
        120: 2
    class_names:
        ?: 1
        lower: 1
        lower_under: 1
        studly: 19
    class_brace_line:
        next: 16
        same: 6
    constant_names:
        upper: 22
    true_false_null:
        lower: 19
        upper: 3
    method_names:
        camel: 21
        lower_under: 1
    method_brace_line:
        next: 15
        same: 7
    control_brace_line:
        next: 4
        same: 18
    control_space_after:
        no: 2
        yes: 20
    always_use_control_braces:
        no: 3
        yes: 19
    else_elseif_line:
        next: 6
        same: 16
    case_break_indent_from_switch:
        0/1: 4
        1/1: 4
        1/2: 14
    function_space_after:
        no: 22
    closing_php_tag_required:
        no: 19
        yes: 3
    line_endings:
        ?: 5
        LF: 17
    static_or_visibility_first:
        ?: 5
        either: 7
        static: 4
        visibility: 6
    control_space_parens:
        ?: 1
        no: 19
        yes: 2
    blank_line_after_php:
        ?: 1
        no: 13
        yes: 8
    class_method_control_brace:
        next/next/next: 4
        next/next/same: 11
        next/same/same: 1
        same/same/same: 6
