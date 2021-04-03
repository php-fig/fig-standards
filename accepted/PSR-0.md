Autoloading Standard
====================

> **Deprecated** - As of 2014-10-21 PSR-0 has been marked as deprecated. [PSR-4] is now recommended
as an alternative.

[PSR-4]: https://www.php-fig.org/psr/psr-4/

下面將描述一個具備互用性的「自動載入 (autoloader)」所需要遵守的條件。

必要條件
---------

* 一個完全合格的 namespace (命名空間) 與 class (類別) 需要符合這樣的結構`\<Vendor Name>\(<Namespace>\)*<Class Name> `。
* 每個 namespace 需要有一個頂層的命名空間 (“提供者名稱(Vendor Name”)。
* 如果需要的話，每個 namespace 皆可有多個子命名空間。
* 當 namespace 若是從檔案系統載入時，其使用的分隔符號皆要轉換成 `DIRECTORY_SEPARATOR`。
* 類別名稱 (class name) 中，每個底線符號 (`_`) 皆要轉換成 `DIRECTORY_SEPARATOR`。因為底線(`_`)在 namespace 中是沒有意義的。
* 從檔案系統所載入的合格 namespace 與 class 一定是 `.php` 結尾。
* Vendors name、namespace 以及 class name 所使用的字母可以由大小寫組成。

範例
--------
* `\Doctrine\Common\IsolatedClassLoader => /path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request => /path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl => /path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message => /path/to/project/lib/vendor/Zend/Mail/Message.php`

命名空間與類別名稱中的底線
-----------------------------------------
* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

我們制定這個標準，來使自動載入有個基本的共通性。你可以嘗試遵循這個標準實作 `SplClassLoader` 來載入 PHP 5.3 的類別。[譯註：RFC: SplClassLoader]


範例實作
----------------------

下面示範如何實踐上述標準建議的自動載入範例。
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

SplClassLoader 實作
-----------------------------

下面這個 gist 連結中是實作 SplClassLoader 的範例，若是你有遵循這個標準建議來命名類別的話，你可以使用它來載入自己的類別。這也是目前 PHP 5.3 所推薦的類別命名標準。

* [http://gist.github.com/221634](http://gist.github.com/221634)


譯者註
--------------
這個建議標準([PSR-0][])主要是提供在撰寫 autoload 時，其檔案、類別 (class) 以及命名空間 (namespace) 在程式碼中的公約，講白話些，就是規範了檔案怎麼放(命名空間)，類別的名稱以及其檔案如何定義，讓大家使用有跟隨這個規則的框架或系統時，也能夠有個最低限度的共用標準。

本篇是直接從原文所翻譯過來，有些地方會為了讓句子通順就沒有完全就每個字去翻譯，倘若有覺得與原意不符的地方，麻煩[提出指正][]，感謝。原文連結在：[PSR-0][]

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[提出指正]: http://blog.mosil.biz/2012/08/psr-0-autoloading-standard/
