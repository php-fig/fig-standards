以下、オートローダー連携のための要件を説明します。

必須要件 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#mandatory)
---------

* 完全な名前空間とクラス名のために下記構造としてください。`\<ベンダー名>\(<名前空間>\)*<クラス名>`
* いずれの名前空間もトップレベルの名前空間である「ベンダー名」を持つ必要があります。
* 名前空間は複数の自由な名前空間を持つことができます。
* 名前空間の区切りは読み込まれる際に、`DIRECTORY_SEPARATOR`に変換されます。
* クラス名に含まれるアンダースコア`_`は、`DIRECTORY_SEPARATOR`に変換されます。アンダースコアは特別な意味を持ちません。
* 名前空間とクラス名に`.php`をつけて完全なファイルとなり読み込まれます。
* ベンダー名、名前空間及びクラス名のアルファベット文字列における小文字・大文字の組み合わせは自由です。

例 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#examples)
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

名前空間及びクラス名におけるアンダースコアの扱い [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#underscores-in-namespaces-and-class-names)
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

ここでは余計な混乱を防ぐための最低限のオートローダー連携基準を示します。
下記のPHP 5.3上におけるSplClassLoader実装例により、これらの基準を確認することができます。

実装例 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#example-implementation)
----------------------

以下は、上記基準に従ったクラスのオートロードにおける振る舞いを確認するための例です。

```php
<?php

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
```

SplClassLoader実装について [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#splclassloader-implementation)
-----------------------------

以下のgistで、上記のオートローダー互換運用を満たした上で実装クラスがロードされるというSplClassLoaderの簡単な実装例を示します。
基準を満たしたうえで、PHP 5.3クラスをロードするための推奨方法となります。

* [http://gist.github.com/221634](http://gist.github.com/221634)

