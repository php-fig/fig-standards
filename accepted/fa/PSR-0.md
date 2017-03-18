<div dir="rtl">
<h2>
استاندارد بارگذاری خودکار
</h2>

در زیر شرایط لازم برای قابلیت همکاری با(استفاده از) بارگذار خودکار را شرح می دهیم

<h2>
دستور کار
</h2>


* یک namespace کامل و کلاس باید دارای ساختار زیر باشند
</div>
  `\<Vendor Name>\(<Namespace>\)*<Class Name>`
<div dir="rtl">
* هر namespace باید یک namespace سطح بالاتر داشته باشد ("Vendor Name"). <br />
* هر namespace می تواند هر تعداد زیرnamespace که می خواهد داشته باشد<br />
* هر جداساز namespace (\) هنگام بارگذاری از فایل سیستم تبدیل به یک `DIRECTORY_SEPARATOR` می شود<br />
* هر کاراکتر `_` در نام کلاس، تبدیل به یک `DIRECTORY_SEPARATOR` می شود. کاراکتر `_` در namespace هیچ معنای خاصی ندارد<br />
* یک namespace کامل و کلاس هنگام بارگذاری از فایل سیستم با `.php` پسوندی می شوند (شامل پسوند `.php` می شوند).<br />
* کاراکترهای الفبایی در نام vendor و namespace و نام کلاس، ممکن است شامل هر ترکیبی از حروف کوچک و بزرگ باشند.<br />



<h2>
مثال ها
</h2>
</div>

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

<div dir="rtl">
<h2>
خط زیرین (_) در نام namespace و نام کلاس ها 
</h2>
</div>

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

<div dir="rtl">

</div>

The standards we set here should be the lowest common denominator for
painless autoloader interoperability. You can test that you are
following these standards by utilizing this sample SplClassLoader
implementation which is able to load PHP 5.3 classes.

Example Implementation
----------------------

Below is an example function to simply demonstrate how the above
proposed standards are autoloaded.

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

SplClassLoader Implementation
-----------------------------

The following gist is a sample SplClassLoader implementation that can
load your classes if you follow the autoloader interoperability
standards proposed above. It is the current recommended way to load PHP
5.3 classes that follow these standards.

* [http://gist.github.com/221634](http://gist.github.com/221634)

