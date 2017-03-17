# Autoloader

文書内記載されている "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY" 及び "OPTIONAL" は、[RFC 2119](http://tools.ietf.org/html/rfc2119)で説明される趣旨で解釈してください。

## 1. 概要

このPSRは、ファイルパスから、クラスをオートローディングするための仕様について記述します。
これは、[PSR-0][]を含む、その他のオートロードの仕様と完全に相互運用可能であり、その他の仕様に追加して使用することができます。
また、このPSRは、この仕様に従ってオートロードされるファイルの配置についても記述しています。

## 2. 仕様


1. 「クラス」という用語は、クラス、インターフェース, トレイト、および他の類似の構造を指します。


2. 完全修飾クラス名は次のような形式になります。

        \<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>

    1. 完全修飾クラス名は、「ベンダーの名前空間」として知られているトップレベルの名前空間名を持っている必要があります。(MUST)

    2. 完全修飾クラス名は、1つ以上のサブ名前空間名を持つことがあります。(MAY)

    3. 完全修飾クラス名は終端クラス名を持つ必要があります。(MUST)

    4. アンダースコアは、完全修飾クラス名のいずれの部分にも特別な意味を持ちません。

    5. 英字は小文字と大文字の任意の組み合わせでかまいません。(MUST)

    6. すべてのクラス名は大文字と小文字を区別して参照する必要があります。(MUST)

3. 完全修飾クラス名に対応するファイルをロードする場合、

    1. 完全修飾クラス名の名前空間の先頭とそれに続くサブ名前空間名の連続(名前空間プレフィックス)が「ベースディレクトリ」に対応します。
  
    2. 「名前空間プレフィックス」の後の連続したサブ名前空間名は、ベースディレクトリ内のサブディレクトリに対応します。名前空間のセパレータは、ディレクトリのセパレータを表し、サブディレクトリ名は、サブ名前空間名の大文字小文字と一致しなければなりません。

    3. 終端クラス名は .php で終わるファイル名に対応します。ファイル名は、終端クラス名の大文字小文字と一致しなければなりません。


4. オートローダの実装では、任意のレベルのエラーを発生させてはならなず、例外もスローしてはなりません。またその値も返すべきではありません。


## 3. 例

以下の表は、与えられた完全修飾クラス名、名前空間プレフィックス、およびベースディレクトリに対応するファイルパスを示しています。

| 完全修飾クラス名    | 名前空間プレフィックス   | ベースディレクトリ           | ファイルパス
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

仕様に準拠したオートローダーの実装例については、[examples file][] を参照してください。
実装例は仕様の一部とはみなされず、任意のタイミングで変更されることがあります。

[autoloading]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[examples file]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
