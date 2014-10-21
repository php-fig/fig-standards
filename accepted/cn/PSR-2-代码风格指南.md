代码风格指南
==================

本文档扩展了[PSR-1] 基本编程标准。

本文档的目的是通过枚举共享一系列的用于格式化PHP代码的规则和期望值， 减少在阅读不同作者的代码时的认知摩擦。 这里所列举出的样式规则来源于各种成员项目中的共性。在多个作者跨多个项目的合作中， 它可以在所有的这些项目中协助提供一系列的指导方针。因此，本文档的价值不是规则本身， 而是这些规则的共享。

本文档中的关键字“必须”， “不允许”，“必需”，“将会”，“将不会”，“应该”，“不应该”，“推荐”，“可以”和“可选”遵循[RFC 2119]中的描述。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. 概述
-----------

- 代码必须遵循“编码风格指南” PSR [[PSR-1]]。

- 代码必须使用4个空格缩进，而不是tab。

- 对一行的长度不能是一个硬性的限制；但对每一行必须限制在120字符之内，每一行应该80个字符或者更少。

- 在`namespace`声明之后必须有一个空行，在`use`声明之后也必须有一个空行。

- 类的打开的大括号必须在下一行，关闭大括号必须类的主体之后的下一行。

- 方法的打开的大括号必须在方法的下一行，关闭大括号必须在主体之后的下一行。

- 所有的属性和方法必须声明可见性；`abstract`和`final`必须在可见性声明之前；`static`必须在可见性声明之后。
  
- 控制结构的关键字之后必须有一个空格；方法和函数调用关键字后面不允许有空格。

- 控制结构的开始大括号必须在控制结构同一行，关闭大括号必须在主体的下一行。

- 控制结构的开始括号之前必须有一个空格，关闭括号之前不允许有空格。

### 1.1. 范例

下面的例子包含了一些规则，以做一个快速的概述：

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

2. 基础
----------

### 2.1 基本编码标准

代码必须遵循标准 [PSR-1]。

### 2.2 文件

所有的PHP文件必须使用Unix LF行结尾符。

所有的PHP文件必须以单个空行结尾。

在只包含PHP代码的文件中，结尾的`?>`标记必须省略。

### 2.3. 行

对一行的长度不允许做硬性的限制。

对一行的长度的软限制是必须120个字符之内；自动语法检查器必须在超过这个软限制时发出警告，但是不允许产生错误提示。

一行不应该超过80个字符长度；超过该长度的行应该被分割成每一行都少于80字符的多行。

在非空行结尾不允许有空格。

可以添加空行以提高代码的可读性和区分相关的代码块。

每一行不能多于一个声明。

### 2.4. 缩进

代码必须使用4个空格缩进，不允许使用tabs进行缩进。

> 注意： 只使用空格，而不要空格和tabs混合可以协助
> 避免使用diff, patches, history和annotations时出现的问题。 
> 空格的使用也可以使国际线插入子缩进的对齐更加简单。


### 2.5. 关键字和True/False/Null

PHP [关键字] 必须是小写的.

PHP常量 `true`, `false`, 和 `null` 必须是小写的.

[关键字]: http://php.net/manual/en/reserved.keywords.php



3. 命名空间和Use声明
---------------------------------

当`命名空间`出现的时候，在之后必须有一个空行

当使用`use`声明的时候，所有的`use`声明必须跟在`namespace`声明之后。

每一个声明必须使用一个`use`关键字。

在`use`块之后必须有一个空行。

例如:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... 额外的PHP代码 ...

```


4. 类, 属性和方法
-----------------------------------

术语`类`值得是所有的类、接口和Traits。

### 4.1. 继承和实现

关键字 `extends` 和 `implements` 必须在类名的同一行进行声明。

类的开始大括号必须在新的独立的一行；关闭大括号必须在主体的下一行。

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // 常量, 属性, 方法
}
```

关键字`implements`的列表可以分割为多行，每一个子行缩进一次。在这种情况下，列表中的第一个项必须在下一行，并且，每个接口必须独占一行。

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

所有属性必须声明其可见性。

关键字 `var` 不允许用来声明属性.

每一个声明只能有一个属性。

属性名不应该以单个下划线开头来暗示其为protected或者private的可见性。

一个属性的声明看起来是下面这样的：

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. 方法

所有方法都必须声明其可见性.

方法名不应该以单个下划线开头来暗示其为protected或者private的可见性。

不允许在方法名之后添加空格。开始大括号必须另起一行，关闭括号必须在方法体的下一行。
在参数的开始括号之后和结束括号之前不允许有空格。

方法的声明是下面这样的。注意括号，逗号，空格和大括号的位置：

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```    

### 4.4. 方法参数

在参数列表中，在每个逗号之前不允许有空格，每个逗号之后有一个空格。

含有默认值的方法参数必须在参数列表的最后。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```

参数列表可能会被分割成多行，每一行都要缩进一次。列表中第一项必须另起一行，每一行只能有一个参数。

当参数列表被分割成多行的时候，结束括号和主体的开始大括号必须占用同一行，并且之间有一个空格。

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
        // method body
    }
}
```

### 4.5. `abstract`, `final`, 和 `static`

当出现 `abstract` and `final` 声明的时候，它们必须在可见性声明之前。

当出现 `static` 声明的时候，它必须在可见性声明之后。

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // method body
    }
}
```

### 4.6. 方法和函数调用

当进行方法或者函数调用的时候，在方法或者函数名和参数括号之间不允许有空格，
在参数开始括号之后和参数结束括号之前不允许有空格。在参数列表中，
每一个逗号之前不允许有空格，逗号之后必须有一个空格。

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

参数列表可能会被分割成多行，每一行都需要缩进一次。
列表中的第一项必须另起一行，每个参数必须独占一行。

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

对于控制结构，通用的样式规则如下：

- 控制结构关键字之后必须有一个空格
- 开始括号之后不允许有空格
- 关闭括号之前不允许有空格
- 关闭括号和开始大括号之间必须有一个空格
- 结构的主体必须缩进一次
- 关闭的大括号必须在主体的另起一行

每一个结构的主体必须使用大括号包围。这样标准化了结构的样式，
减少了将错误作为主体的新行引入的可能性。


### 5.1. `if`, `elseif`, `else`

结构 `if` 看起来像下面这样。注意括号、空格和大括号的位置；
`else`和`elseif`在第一个主体结束括号的同一行。

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

应该将关键字 `else if` 替换为关键字 `elseif` ，这样所有的else if看起来都是一体的。 


### 5.2. `switch`, `case`

`switch` 结构看起来是下面这样。注意括号、空格和大括号的位置。`case`块必须在`switch`
块中缩进一次，并且`break`关键字（或者其它结束关键字）必须与`case`保持同样的缩进级别。
如果`case`的主体会一直执行下去，在第一个非空的`case`主体后必须添加一个`// no break`
这样的注释说明。

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

`while` 结构看起来是下面这样的。注意括号、空格和大括号的位置。

```php
<?php
while ($expr) {
    // structure body
}
```

类似的，`do while`语句看起来是下面这样的。注意括号、空格和大括号的位置。

```php
<?php
do {
    // structure body;
} while ($expr);
```

### 5.4. `for`

`for` 语句看起来是下面这样的。注意括号、空格和大括号的位置。

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### 5.5. `foreach`
    
`foreach` 语句看起来是下面这样的。注意括号，空格和大括号的位置。

```php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### 5.6. `try`, `catch`

`try catch` 块看起来是下面这样的。注意括号，空格和大括号的位置。

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

在闭包的声明中，`function`关键字后面必须跟着一个空格，后面的`use`关键字的前后都要有一个空格。

开始大括号必须和声明在同一行，关闭大括号必须主体的下一行。

开始括号和参数列表或者变量列表之间不允许有空格，参数或者变量列表和关闭括号之间不允许有空格。

在参数和变量列表中，每一个逗号之前不允许与空格，之后必须有一个空格。

闭包参数的默认值必须在参数列表的结尾。

闭包声明看起来是下面这样的。注意括号，逗号，空格和大括号的位置：

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

参数和变量列表可能会被分割为多行，每一行都要缩进一次。
第一个项必须另起一行，每一个参数或者变量独占一行。

当分割长多行的时候，结束括号和开始大括号必须另起一行，
并且放在同一行，之间必须有一个空格。

下面是含有参数和变量列表分割为多行和非多行的例子。

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

注意当闭包函数直接用在函数或者方法参数的时候，以上规则同样适用。

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
--------------

还有很多元素的样式和实践在本指南中故意忽略了。包含但并不局限于下面这些：

- 全局变量和全局常量的声明

- 函数的声明

- 操作符和赋值

- 国际线对齐

- 注释和文档块

- 类名前缀和后缀Class name prefixes and suffixes

- 最佳实践

未来可能会通过修改和扩展本指南来添加其它元素的样式和实践规范。


附录 A. Survey
------------------

In writing this style guide, the group took a survey of member projects to
determine common practices.  The survey is retained herein for posterity.

### A.1. Survey Data

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

### A.2. Survey Legend

`indent_type`:
The type of indenting. `tab` = "Use a tab", `2` or `4` = "number of spaces"

`line_length_limit_soft`:
The "soft" line length limit, in characters. `?` = not discernible or no response, `no` means no limit.

`line_length_limit_hard`:
The "hard" line length limit, in characters. `?` = not discernible or no response, `no` means no limit.

`class_names`:
How classes are named. `lower` = lowercase only, `lower_under` = lowercase with underscore separators, `studly` = StudlyCase.

`class_brace_line`:
Does the opening brace for a class go on the `same` line as the class keyword, or on the `next` line after it?

`constant_names`:
How are class constants named? `upper` = Uppercase with underscore separators.

`true_false_null`:
Are the `true`, `false`, and `null` keywords spelled as all `lower` case, or all `upper` case?

`method_names`:
How are methods named? `camel` = `camelCase`, `lower_under` = lowercase with underscore separators.

`method_brace_line`:
Does the opening brace for a method go on the `same` line as the method name, or on the `next` line?

`control_brace_line`:
Does the opening brace for a control structure go on the `same` line, or on the `next` line?

`control_space_after`:
Is there a space after the control structure keyword?

`always_use_control_braces`:
Do control structures always use braces?

`else_elseif_line`:
When using `else` or `elseif`, does it go on the `same` line as the previous closing brace, or does it go on the `next` line?

`case_break_indent_from_switch`:
How many times are `case` and `break` indented from an opening `switch` statement?

`function_space_after`:
Do function calls have a space after the function name and before the opening parenthesis?

`closing_php_tag_required`:
In files containing only PHP, is the closing `?>` tag required?

`line_endings`:
What type of line ending is used?

`static_or_visibility_first`:
When declaring a method, does `static` come first, or does the visibility come first?

`control_space_parens`:
In a control structure expression, is there a space after the opening parenthesis and a space before the closing parenthesis? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
Is there a blank line after the opening PHP tag?

`class_method_control_brace`:
A summary of what line the opening braces go on for classes, methods, and control structures.

### A.3. Survey Results

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
