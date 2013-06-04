A seguir, o manual com os requisitos obrigatórios que devem ser seguidos para a interoperabilidade de _autoloading_.

Obrigatório
---------

* A namespace totalmente qualificada e as classes devem seguir a seguinte estrutura `\<Nome do Fornecedor>\(<Namespace>\)*<Nome da Classe>`
* Cada namespace deve ter uma namespace de nível superior ("Nome do Fornecedor").
* Cada namespace pode ter quantos sub-namespaces que quiser.
* Cada separador de namespace é convertido para `DIRECTORY_SEPARATOR` durante o carregamento do sistema de arquivos.
* Cada caractere `_` no nome da classe é convertido para
  `DIRECTORY_SEPARATOR`. O caractere `_`não tem nenhum significado especial na namespace.
* A namespace totalmente qualificada e a classe é sufixada com `.php` durante o carregamento do sistema de arquivos.
* Caracteres alfabéticos em nomes de fornecedor, namespaces e nomes de classe podem ser de qualquer combinação de letras maiúsculas (upper case) e minúsculas (lower case).

Exemplos
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/lib/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/lib/Symfony/Core/Request.php`
* `\Zend\Acl` => `/lib/Zend/Acl.php`
* `\Zend\Mail\Message` => `/lib/Zend/Mail/Message.php`

Underscores em Namespaces e em Nomes de Classe
-----------------------------------------

* `\namespace\package\Class_Name` => `/lib/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/lib/namespace/package_name/Class/Name.php`

Os padrões que definimos aqui devem ser o menor denominador comum para uma indolor interoperabilidade de _autoloading_. Você pode testar se você está seguindo estes padrões utilizando esta amostra da implementação da SplClassLoader que é capaz de carregar classes no PHP 5.3.

Exemplo de Implementação
----------------------

Abaixo está uma função de exemplo para simplesmente demonstrar como os padrões propostos acima são automaticamente carregados.
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

Implementação da SplClassLoader
-----------------------------

O seguinte gist é um exemplo de implementação da SplClassLoader que pode carregar suas classes caso você siga os padrões de interoperabilidade de _autoloading_ propostos acima. Atualmente, esta é a forma recomendada para carregar classes no PHP 5.3 que seguem estes padrões.

* [http://gist.github.com/221634](http://gist.github.com/221634)

