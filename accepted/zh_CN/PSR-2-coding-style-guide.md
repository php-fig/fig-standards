代码风格指南
============

本指南在基本编码标准 [PSR-1] 之上进行延伸和扩展。

本指南的意图是提升我们阅读不同开发者代码的效率。下面列举了一些有关如何格式化 PHP
 代码的常用规范。

这组代码风格规范由各个项目之间的共性组成。当开发者们在多个项目中合作时，这有助于
有一套规范在所有这些项目中使用。因此，本指南的益处不在于这些规则本身，而在于在所
有项目中共用这些规则。

关键词“MUST”，“MUST NOT”，“REQUIRED”，“SHALL”，“SHALL NOT”，“SHOULD”，
“SHOULD NOT”，“RECOMMENDED”，“MAY”以及“OPTIONAL”的详细说明见 [RFC 2119] 。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. 概述
-------

- 代码 MUST 遵循 “基本代码规范” PSR [[PSR-1]] 。

- 代码 MUST 使用4个空格来缩进，而不是用制表符。

- 行长度 MUST NOT 有硬限制；软限制 MUST 为120字符；SHOULD 每行代码80个字符或更少。

- `namespace` 的声明下面 MUST 有一行空行，并且在 `use` 的声明下面也 MUST 有一行
  空行。

- 类的左花括号 MUST 放到其声明下面自成一行，右花括号则 MUST 放到类主体下面自成一
  行。

- 方法的左花括号 MUST 放到其声明下面自成一行，右花括号则 MUST 放到方法主体下面自
  成一行。

- 所有的属性和方法 MUST 有可见性声明；`abstract` 和 `final` 声明 MUST 在可见性声
  明之前；`static` 声明 MUST 在可见性声明之后。

- 控制结构关键字的后面 MUST 有一个空格；方法和函数的声明后面 MUST NOT 有空格。

- 控制结构的左花括号 MUST 跟其放在同一行，右花括号 MUST 放在该控制结构代码主体的
  下一行。

- 控制结构的左圆括号之后 MUST NOT 有空格，右圆括号之前也 MUST NOT 有空格。

### 1.1. 示例

这个示例作为本指南规则的一个快速浏览：

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
        // method body
    }
}
```

2. 常规
-------

### 2.1 基本代码规范

代码 MUST 遵循 [PSR-1] 中所有规则。

### 2.2 文件

所有的 PHP 文件 MUST 使用 Unix LF (换行符) 作为行结束符。

所有 PHP 文件 MUST 以一个空行结束。

纯 PHP 代码文件的关闭标签 `?>` MUST 省略。

### 2.3. 行

行长度 MUST NOT 有硬限制。

行长度的软限制 MUST 是120字符；对于软限制代码风格检查器 MUST 警告但 MUST NOT 报
错。

一行代码的长度 SHOULD NOT 超过80个字符；较长的行 SHOULD 拆分成多个不超过80个字符
的子行。

在非空行结尾处 MUST NOT 尾随空格。

空行 MAY 用来增强可读性和区分相关代码块。

每行 MUST NOT 多于一个语句。

### 2.4. 缩进

代码 MUST 使用4个空格，且 MUST NOT 使用制表符来作为缩进。

> 注意：代码中只使用空格，且不和制表符混合使用，将会对避免代码差异，补丁，历史以
> 及注解中的一些问题有帮助。空格的使用还可以使通过调整细微的缩进来改进行间对齐变
> 得更加的简单。

### 2.5. 关键词以及 True/False/Null

PHP [关键词] MUST be in lower case.

PHP 常量 `true`，`false`，以及 `null` MUST 使用小写字符。

[关键词]: http://php.net/manual/en/reserved.keywords.php



3. Namespace 和 Use 声明
------------------------

如果存在， `namespace` 的声明后面 MUST 有一空行。

如果存在，所有 `use` 声明 MUST 放在 `namespace` 声明之后。

每个声明 MUST 有一个 `use` 关键字。

`use` 代码块后面 MUST 有一空行。

示例：

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... 额外 PHP 代码 ...

```


4. 类，属性，及方法
-------------------

术语“类”指代所有 classes，interfaces，以及 traits。

### 4.1. Extends 和 Implements

`extends` 和 `implements` 关键词 MUST 和类名在同一行。

类的左花括号 MUST 放在下面自成一行；右花括号 MUST 放在类主体的后面自成一行。

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constants, properties, methods
}
```

`implements` 列表 MAY 被拆分为多行，每个子行都要缩进一次。当这样做时，列表的第一
项 MUST 要放在下一行，且每行 MUST 只有一个接口。

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
    // constants, properties, methods
}
```

### 4.2. 属性

所有属性都 MUST 声明其可见性。

`var` 关键字 MUST NOT 用来声明属性。

每个声明 MUST NOT 超过一个属性。

属性名  SHOULD NOT 用单个下划线作为前缀来表明其保护或私有的可见性。

属性声明看起来应该像下面这样。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. 方法

所有的方法都 MUST 声明其可见性。

方法名 SHOULD NOT 用单个下划线作为前缀来表明其保护或私有的可见性。

方法名在其声明后面 MUST NOT 有空格跟随。其左花括号 MUST 放在下面自成一行，且右花
括号 MUST 放在方法主体的下面自成一行。左括号后面 MUST NOT 有空格，且右括号前面也
 MUST NOT 有空格。

方法声明看来应该像下面这样。 注意括号，逗号，空格及花括号的位置：

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // 方法体
    }
}
```    

### 4.4. 方法参数

在参数列表中，逗号之前 MUST NOT 有空格，而逗号之后则 MUST 要有一个空格。

方法参数默认值 MUST 放在参数列表的最后面。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // 方法体
    }
}
```

参数列表 MAY 被拆分为多行，每个子行缩进一次。当这样做，参数列表的第一项 MUST 放
在下一行，且每行代码 MUST 只有一个参数。

当参数立标被拆分为多行，右括号和左花括号之间 MUST 有一个空格并且自成一行。

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
        // 方法体
    }
}
```

### 4.5. `abstract`，`final`，和`static`

如果存在，`abstract` 和 `final` 声明 MUST 放在可见性声明的前面。

如果存在，`static` 声明 MUST 放在可见性声明的后面。

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // 方法体
    }
}
```

### 4.6. 方法和函数的调用

调用一个方法或函数时，在方法名或者函数名和左括号之间 MUST NOT 有空格，左括号之后
 MUST NOT 有空格，右括号之前也 MUST NOT 有空格。参数列表中，逗号之前 MUST NOT 有
空格，逗号之后则 MUST 有一个空格。

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

参数列表 MAY 被拆分成多行，每个子行被缩进一次。当这样做时，列表中的第一项 MUST 放
在下一行，且每一行 MUST 只能有一个参数。

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. 控制结构
-----------

下面是对于控制结构代码风格的概括：

- 控制结构的关键词之后 MUST 有一个空格。
- 控制结构的左括号之后 MUST NOT 有空格。
- 控制结构的右括号之前 MUST NOT 有空格。
- 控制结构的右括号和左花括号之间 MUST 有一个空格。
- 控制结构的代码主体 MUST 进行一次缩进。
- 控制结构的右花括号 MUST 在主体的下一行。

每个控制结构的代码主体 MUST 被括在花括号里。这样可是使代码看上去更加标准化，并且
加入新代码的时候还可以因此而减少引入错误的可能性。


### 5.1. `if`，`elseif`，`else`


下面是一个 `if` 条件控制结构的示例，注意其中括号，空格和花括号的位置。同时注意
 `else` 和 `elseif` 要和前一个条件控制结构的右花括号在同一行。

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

SHOULD 用 `elseif` 来替代 `else if` ，以保持所有的条件控制关键字看起来像是一个单
词。


### 5.2. `switch`，`case`

下面是一个 `switch` 条件控制结构的示例，注意其中括号，空格和花括号的位置。
`case` 语句 MUST 要缩进一级，而 `break` 关键字（或其它中止关键字） MUST 和
 `case` 结构的代码主体在同一个缩进层级。如果一个有主体代码的 `case` 结构故意的继
续向下执行，则 MUST 要有一个类似于 `// no break` 的注释。

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


### 5.3. `while`, `do while`

下面是一个 `while` 循环控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
while ($expr) {
    // structure body
}
```

下面是一个 `do while` 循环控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
do {
    // structure body;
} while ($expr);
```

### 5.4. `for`

下面是一个 `for` 循环控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### 5.5. `foreach`
    
下面是一个 `foreach` 循环控制结构的示例，注意其中括号，空格和花括号的位置。

```php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### 5.6. `try`, `catch`

下面是一个 `try catch` 异常处理控制结构的示例，注意其中括号，空格和花括号的位置。

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
-------

声明闭包时所用的 `function` 关键字之后 MUST 要有一个空格，而 `use` 关键字的前后
都要有一个空格。

闭包的左花括号 MUST 跟其在同一行，而右花括号 MUST 在闭包主体的下一行。

闭包的参数列表和变量列表的左括号后面 MUST NOT 有空格，右括号的前面也 MUST NOT 有
空格。

闭包的参数列表和变量列表中逗号前面 MUST NOT 有空格，而逗号后面则 MUST 有空格。

闭包的参数列表中带默认值的参数 MUST 放在参数列表的结尾部分。

下面是一个闭包的示例。注意括号，空格和花括号的位置：

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

参数列表和变量列表可以被拆分成多行，每个子行缩进一级。当这样做时，列表中的第一项
 MUST 放在下一行，且每一行 MUST 只放一个参数或变量。

当列表（不管是参数还是变量）最终被拆分成多个子行，右括号和左花括号之间 MUST 要有
一个空格并且自成一行。

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

把闭包作为一个参数在函数或者方法中调用时，依然要遵循上述规则。

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


7. 总结
-------

本指南有意的省略了许多元素的代码风格及实践。包括但不限于：

- 全局变量和全局常量的声明

- 函数声明

- Operators and assignment

- 行间对齐

- 注释和文档块

- 类名的前缀和后缀

- 最佳实践

以后的代码规范中 MAY 会修正或扩展本指南中规定的代码风格。


附录 A. 调查
------------

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
缩进类型。`tab` = “使用制表符”，`2` 或 `4` = "空格数量"

`line_length_limit_soft`:
行长度的“软”限制，用字符。 `?` = 不表示或者数字，`no` 意为不限制。

`line_length_limit_hard`:
行长度的“硬”限制，用字符。 `?` = 不表示或者数字，`no` 意为不限制。

`class_names`:
类名如何命名。 `lower` = 只是小写，`lower_under` = 小写加下划线，`studly` = 骆驼型。

`class_brace_line`:
类的左花括号是放在 `同一` 行还是在 `下一` 行？

`constant_names`:
类常量如何命名？`upper` = 大写加下划线分隔符。

`true_false_null`:
`true`，`false`，和 `null` 关键字全 `lower` 或者 全 `upper` ？

`method_names`:
方法名如何命名？ `camel` = `驼峰式`，`lower_under` = 小写加下划线分隔符。

`method_brace_line`:
方法的左花括号在 `同一` 行还是在 `下一` 行？

`control_brace_line`:
控制结构的左花括号在 `同一` 行还是在 `下一` 行？

`control_space_after`:
控制结构关键词后是否有空格？

`always_use_control_braces`:
控制结构总是使用花括号？

`else_elseif_line`:
当使用 `else` 或 `elseif` ，是否放在 `同一` 行还是在 `下一` 行？

`case_break_indent_from_switch`:
`case` 和 `break` 分别从 `swith` 语句处缩进多少次？

`function_space_after`:
函数调用的函数名和左括号是否有空格？

`closing_php_tag_required`:
在纯 PHP 文件，关闭标签 `?>` 是否需要？

`line_endings`:
使用何种的行结束符？

`static_or_visibility_first`:
在定义方法的时候 `static` 和可见性谁在前面？

`control_space_parens`:
在控制结构表达式中，左括号后面和右括号前面是否要有一个空格？ `yes` = `if ( $expr )` ， `no` = `if ($expr)` 。

`blank_line_after_php`:
PHP 的开始标签后面是否需要一个空行？

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
