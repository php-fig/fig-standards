コーディングガイド [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#coding-style-guide)
==================

このガイドは[PSR-1][]に準拠し、標準的なコーディング規約のためのスタイルガイドです。

このガイドの目的は、複数メンバーがコードを読む際の認識のずれを抑えることです。
これはPHPコードをどのような書式にするかについて、ルールや期待値を共有することで実現します。

スタイルルールは、様々なプロジェクトの共通内容から生み出されています。
様々な作者が複数プロジェクトを横断して協力しあうことで、全てのプロジェクトで有用なガイドライン策定の助けとなります。
従って、このガイド本来の利点はルール自体にはなく、ルールを共有することにあります。

原文書内で記載されている "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY" 及び "OPTIONAL" は、[RFC 2119][]で説明される趣旨で解釈してください。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. 概要 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#1-overview)
-----------

- [PSR-1][]に準拠しなければなりません。

- インデントには４つのスペースを使用し、タブは使用してはいけません。

- 行の長さに対してハードリミットがあってはいけません。ソフトリミットは１２０文字を上限とし、実際は８０文字以内に抑えるべきです。

- 名前空間定義のあとには空行を挟まなければいけません。またuse定義ブロックのあとにも同様に空行を挟まなければなりません。

- クラスの開き括弧は次の行に記述しなければなりません。また閉じ括弧は本文最後の次の行に記述しなければなりません。

- メソッドの開き括弧は次の行に記述しなければなりません。また閉じ括弧は本文最後の次の行に記述しなければなりません。

- アクセス修飾子は、全てのプロパティ、メソッドに定義しなければなりません。またabstractとfinalはアクセス修飾子の前に定義し、staticはアクセス修飾子の後に定義しなければなりません。

- 制御構造の開始時は、その後に１スペースを開けなければなりません。メソッドや関数の呼び出しはスペースを開けてはいけません。

- 制御構造の開き括弧は同じ行に記述しなければなりません。また閉じ括弧は本文最後の次の行に記述しなければなりません。

- 制御構造の開始前にスペースがあってはいけません。また閉じる際もその前にスペースがあってはいけません。

### 1.1. 例 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#11-example)

以下は、概要内容を適用した例です。

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
        // メソッド本文
    }
}
```

2. 一般 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#2-general)
----------

### 2.1 標準的なコーディング規約 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#21-basic-coding-standard)

[PSR-1][]に準拠しなければなりません。

### 2.2 ファイル [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#22-files)

全てのPHPファイルの改行コードは、LFでなければなりません。

全てのPHPファイルは、最後に空行を入れなければなりません。

PHPだけが書かれたファイルについては、終了タグ「?>」を省略しなければなりません。

### 2.3. 行 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#23-lines)

行の長さに対してハードリミットがあってはいけません。

行の長さのソフトリミットは１２０文字を上限とします。
自動スタイルチェッカーはソフトリミットで警告しなければなりませんが、エラーを出してはいけません。

１行あたりの文字数が８０文字を超えるべきではありません。超えてしまう場合は８０文字以内の複数の行に分割するべきです。

行末に空白文字列を含んではいけません。

空行は読みやすさや関連するコードのまとまりを示すために適切に加えて構いません。

１行に複数のステートメントがあってはいけません。

### 2.4. インデント [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#24-indenting)

インデントは４つのスペースとしなければなりません。タブによるインデントは用いてはなりません。

> 注意：スペースとタブを混在せず、スペースのみとすることにより差分表示やパッチ、履歴や注釈がずれる問題を回避できます。
> スペースのみを使うことで、微妙なサブインデントの位置合わせを容易とすることができます。

### 2.5. 予約語とTrue/False/Null [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#25-keywords-and-truefalsenull)

PHPの[予約語][]は小文字で使用しなければなりません。

PHP定数であるtrue、falseそしてnullは小文字でなければなりません。

[予約語]: http://www.php.net/manual/ja/reserved.keywords.php



3. 名前空間とuse演算子による定義 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#3-namespace-and-use-declarations)
---------------------------------

名前空間の定義の後に空行が必要です。

use定義は、名前空間宣言の後でなければなりません。

定義ごとにuse演算子が必要です。

use定義のブロックの後には空行が必要です。

例:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... additional PHP code ...

```


4. クラス、プロパティ及びメソッド [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#4-classes-properties-and-methods)
-----------------------------------

ここでのクラスは、全ての一般クラス、インターフェイス、トレイトを含みます。

### 4.1. ExtendsとImplements [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#41-extends-and-implements)

extendsとimplementsは、クラス名と同じ行で定義されなけれなりません。

クラスの開き括弧は次の行に記述しなければなりません。また閉じ括弧は本文最後の次の行に記述しなければなりません。

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

implements定義は、インデントにより揃えることで、複数行に分割しても構いません。
その際、最初の定義も次の行からはじめるものとし、１行に１つのインターフェイスを定義しなければなりません。

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

### 4.2. プロパティ [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#42-properties)

アクセス修飾子は、全てのプロパティに定義しなければなりません。

プロパティ定義に、varは使用してはいけません。

ステートメントあたりに複数のプロパティ定義があってはなりません。

プロパティ名に、protectedまたはprivateを示すためにシングルアンダースコアを使用すべきではありません。

具体的なプロパティ定義は下記のようになります。

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. メソッド [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#43-methods)

アクセス修飾子は、全てのメソッドに定義しなければなりません。

メソッド名に、protectedまたはprivateを示すためにシングルアンダースコアを使用すべきではありません。

メソッド名の後ろにスペースを使用してはいけません。
開き括弧は次の行に記述しなければなりません。また閉じ括弧は本文最後の次の行に記述しなければなりません。
開き括弧の後ろや、閉じ括弧の前にスペースがあってはいけません。

メソッド定義は下記のようになります。
括弧、カンマ、スペースの位置に注意してください。

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

### 4.4. メソッドの引数 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#44-method-arguments)

引数リストでは、それぞれのカンマの前にスペースがあってはいけません。
また各カンマの後ろには１スペースおかなければなりません。

デフォルト値を持つ引数は、引数リストの最後に配置しなければなりません。

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

引数リストは、インデントにより揃えることで、複数行に分割しても構いません。
その際、最初の定義も次の行からはじめるものとし、１行に１つの引数を定義しなければなりません。

引数リストを複数行の分割配置としている場合、閉じ括弧と開き中括弧の間にはスペースを含め同じ行に配置する必要があります。

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

### 4.5. `abstract`, `final`, and `static` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#45-abstract-final-and-static)

abstractとfinalはアクセス修飾子の前に定義しなければなりません。

staticはアクセス修飾子の後に定義しなければなりません。

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

### 4.6. メソッド及び関数の呼び出し [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#46-method-and-function-calls)

メソッドや関数の呼び出し時は、メソッドや関数名と開き括弧の間にスペースがあってはなりません。
また開き括弧の後や、閉じ括弧の前にスペースがあってもいけません。
引数リストの前にスペースがあってはなりませんが、各カンマの後に１スペースが必要です。

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

引数リストは、インデントにより揃えることで、複数行に分割しても構いません。
その際、最初の定義も次の行からはじめるものとし、１行に１つの引数を定義しなければなりません。

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. 制御構造 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#5-control-structures)
---------------------

制御構造の一般的なスタイルルールや下記の通りです。

- 制御構造キーワードの後には１スペースを設けなければなりません。
- 開き括弧の後にスペースを配置してはなりません。
- 閉じ括弧の前にスペースを配置すべきではありません。
- 開き括弧と閉じ中括弧の間にはスペースを挟まなければなりません。
- 構造本文は１インデント下げなければなりません。
- 閉じ括弧は構造本文の後に改行して配置しなければなりません。

各構造本文は、中括弧で囲わなければなりません。
これは構造の見え方を標準化し、追加実装等が発生した際のエラーを抑えます。


### 5.1. `if`, `elseif`, `else` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#51-if-elseif-else)

if制御について例えば下記のようになります。
括弧、スペースの位置に注意してください。elseやelseifの前後括弧は同じ行に配置されます。

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

全てのキーワードが１単語に見えるように、else ifではなくelseifを使うべきです。


### 5.2. `switch`, `case` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#52-switch-case)

switch制御は下記のようになります。
括弧、スペースの位置に注意してください。
case文はswitchからインデントし、break（またはその他の終端キーワード）は、case内本文と同じレベルのインデントで揃えなければなりません。
また意図的に処理スルーさせる場合は「// no break」等、コメントしなければなりません。


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


### 5.3. `while`, `do while` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#53-while-do-while)

while文は下記のようになります。
括弧、スペースの位置に注意してください。

```php
<?php
while ($expr) {
    // structure body
}
```

同様に、do while文は下記のようになります。
括弧、スペースの位置に注意してください。

```php
<?php
do {
    // structure body;
} while ($expr);
```

### 5.4. `for` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#54-for)

for文は下記のようになります。
括弧、スペースの位置に注意してください。

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### 5.5. `foreach` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#55-foreach)

foreach文は下記のようになります。
括弧、スペースの位置に注意してください。

```php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### 5.6. `try`, `catch` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#56-try-catch)

try catch文は下記のようになります。
括弧、スペースの位置に注意してください。

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

6. `Closure` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#6-closures)
-----------

クロージャは、functionキーワードの後にスペースを、useキーワードの前後にスペースが必要です。

開き括弧は同じ行に記述しなければなりません。また閉じ括弧は本文最後の次の行に記述しなければなりません。

引数または変数リストの開き括弧の後にスペースがあってはなりません。
また閉じ括弧の前にスペースがあってもなりません。

引数または変数リストの前にスペースがあってはなりませんが、各カンマの後に１スペースが必要です。

デフォルト値を持つ引数は、引数リストの最後に配置しなければなりません。

クロージャ定義は下記のようになります。
括弧、スペースの位置に注意してください。

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

引数または変数リストは、インデントにより揃えることで、複数行に分割しても構いません。
その際、最初の定義も次の行からはじめるものとし、１行に１つの引数または変数を定義しなければなりません。

引数または変数リストを複数行の分割配置としている場合、閉じ括弧と開き中括弧の間にはスペースを含め同じ行に配置する必要があります。

引数リストが無く、及び変数リストが複数行に渡る場合のクロージャ実装は下記のようになります。


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

引数として、クロージャが関数またはメソッドに直接使用される場合もまた、ルールが適用されることに注意してください。

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


7. その他 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#7-conclusion)
--------------

本スタイルガイドでは、意図的に省略しているスタイルやプラクティスが多くあります。
例えば下記のような幾つかについては、ここでは明記していません。

- グローバル変数と定数について

- 関数群の定義について

- 演算と代入について

- 行間の配置

- コメントとドキュメントブロックについて

- クラス名の接頭辞と接尾辞について

- ベストプラクティスについて

なお、本スタイルガイドは将来的に様々なスタイルやプラクティスの登場に応じて改定・拡張をできるものとします。


付録A 調査（未翻訳） [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#appendix-a-survey)
------------------

本スタイルガイドを書くにあたって、参加プロジェクトに対する実体調査を実施することで、共通プラクティスを導き出しました。
調査結果をここに残します。

### A.1. Survey Data [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#a1-survey-data)

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

### A.2. Survey Legend [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#a2-survey-legend)

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

### A.3. Survey Results [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md#a3-survey-results)

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
