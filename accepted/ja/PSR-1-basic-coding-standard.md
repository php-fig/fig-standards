基本コーディング規約 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#basic-coding-standard)
=====================

この規約セクションでは、共有されるPHPコードにおいて高い技術レベルでの連携を確保するために必要とされる標準的なコーディング要素を考慮したうえで構成されています。

原文書内で記載されている "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY" 及び "OPTIONAL" は、[RFC 2119][]で説明される趣旨で解釈してください。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md


1. 概要 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#1-overview)
-----------

- PHPコードは「<?php」及び 「<?=」タグを使用しなければなりません。

- 文字コードはUTF-8（BOM無し）を使用しなければなりません。

- シンボル（クラス、関数、定数など）を宣言するためのファイルと、副作用のある処理（出力の生成、ini設定の変更など）を行うためのファイルは、分けるべきです。

- 名前空間、クラスについては[PSR-0][]に準拠しなければなりません。

- クラス名は、StudlyCaps（単語の先頭文字を大文字で表記する記法）記法で定義しなければなりません。

- クラス定数は全て大文字とし、区切り文字にはアンダースコアを用いて定義しなければなりません。

- メソッド名はcamelCase記法で定義しなければなりません。


2. ファイル [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#2-files)
--------

### 2.1. PHPタグ [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#21-php-tags)

PHPコードは「<?php ?>」または短縮記述の「<?= ?>」を使用しなければなりません。それ以外のタグを使用してはいけません。

### 2.2. 文字コード [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#22-character-encoding)

文字コードは、UTF-8（BOM無し）でなければなりません。

### 2.3. 副作用 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#23-side-effects)

新たなシンボル（クラス、関数、定数など）を宣言するためのファイルと、副作用のある処理を行うためのファイルは、分けるべきです。一つのファイルの中で両方を行うべきではありません。

ここでの「副作用」とは、クラス、関数、定数を宣言するのに直接関係しない処理が、単にファイルを取り込んだだけで実行されてしまうことを指します。

副作用のある処理には、次のようなものが含まれます。ただしここで挙げるものが全てではありません：
出力の生成、明示的な「require」や「include」の使用、外部サービスへの接続、ini設定の修正、エラーや例外の発行、グローバル変数や静的変数の修正、ファイルからの読み込み・書き込みなど。

次の例には宣言と副作用とが両方含まれています。このような書き方は推奨されません。

```php
<?php
// 副作用: iniの設定を変更しています
ini_set('error_reporting', E_ALL);

// 副作用: ファイルを読み込んでいます
include "file.php";

// 副作用: 出力を生成しています
echo "<html>\n";

// 宣言
function foo()
{
    // 関数本体
}
```

次の例には宣言だけが含まれ、副作用は含まれません。このような書き方が推奨されます。


```php
<?php
// 宣言
function foo()
{
    // 関数本体
}

// 条件付きの宣言は副作用では*ありません*
if (! function_exists('bar')) {
    function bar()
    {
        // 関数本体
    }
}
```


3. 名前空間とクラス名 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#3-namespace-and-class-names)
----------------------------

[PSR-0][]に準拠しなければなりません。

各クラスが、（トップレベルのベンダー名のように）少なくとも１レベルの名前空間となります。

クラス名は、StudlyCaps記法で定義しなければなりません。

PHP 5.3以降では、正しい名前空間を使用しなければなりません。

例:

```php
<?php
// PHP 5.3 以降:
namespace Vendor\Model;

class Foo
{
}
```

PHP 5.2以前では、クラス名に「Vendor_」接頭辞を使用し、擬似名前空間とする必要があります。

```php
<?php
// PHP 5.2.x 以前:
class Vendor_Model_Foo
{
}
```

4. クラス定数、プロパティ及びメソッドについて [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#4-class-constants-properties-and-methods)
-------------------------------------------

ここでのクラスは、全ての一般クラス、インターフェイス、トレイトを含みます。

### 4.1. 定数 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#41-constants)

クラス定数は総じてアンダースコア文字を区切り文字として大文字で定義しなければなりません。
例:

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. プロパティ [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#42-properties)

このガイドでは、プロパティの命名規則として特定のスタイル（$StudlyCaps、$camelCase、$under_scoreなど）を推奨することはしません。

どのような命名規則を使用するにせよ、適切なスコープ内において一貫性を持たせるべきです。
ここでのスコープは、ベンダーレベル、パッケージレベル、クラスレベルまたはメソッドレベルを指します。

### 4.3. Methods [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#43-methods)

メソッド名はcamelCase記法で定義しなければなりません。
