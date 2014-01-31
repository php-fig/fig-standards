Padrão de Autoload
==================

A seguir são descritos os requisitos obrigatórios que devem ser aderidos para permitir a interoperabilidade do autoloader.

Obrigatório
-----------

* Os nomes completos de um namespace e sua classe devem ter a seguinte estrutura `\<Vendor Name>\(<Namespace>\)*<Class Name>`
* Cada namespace deve ter um namespace no nivel raíz ("Vendor Name").
* Cada namespace pode ter quantos sub-namespaces forem necessários.
* Cada separador de namespace é convertido para um `DIRECTORY_SEPARATOR` quando carregando do sistema de arquivos.
* Cada caractér `_` no CLASS NAME é convertido para um `DIRECTORY_SEPARATOR`. O Caractér `_` não tem nenhum significado especial no namespace.
* Os nomes completos de um namespace e sua classe recebem o sufixo `.php` quando carregando do sistema de arquivos.
* Caracteres nos nomes do vendor, dos namespaces e das classes podem ser qualquer combinação de maiúsculas e minúsculas.

Exemplos
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Underscores em nomes de Namespaces e Classes
--------------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

As padronizações que definimos aqui deveriam ser o mais baixo denominador comum para a interoperabilidade indolor do autoloader. Você pode testar se você está seguindo estes padrões utilizando esse exemplo de implementação de SplClassLoader que é capáz de carregar classes do PHP 5.3.

Implementação de Exemplo
------------------------

Abaixo está um exemplo de função para simplesmente demonstrar como os padrões propostos acima funcionam para o carregamento automático.

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

Implementação do SplClassLoader
-------------------------------

O gist a seguir é uma amostra de implementação do SplClassLoader que pode carregar suas classes se você segue os padrões de interoperabilidade de autoloader propostos acima. Atualmente é a maneira recomendada para carregar classes do PHP 5.3 que seguem estes padrões.

* [http://gist.github.com/221634](http://gist.github.com/221634)

