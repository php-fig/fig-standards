A seguir serão descritos os requisitos obrigatórios que devem ser respeitados
para interoperabilidade no autoloader.

Obrigatório
---------

* Um namespace e classe totalmente qualificados devem ter a seguinte estrutura
`\<Vendor Name>\(<Namespace>\)*<Class Name>`
* Cada namespace deve ter um namespace de nível superior ("Vendor Name").
* Cada namespace pode conter quantos sub-namespaces forem necessários.
* Cada separador de namespace é convertido para um `DIRECTORY_SEPARATOR` quando
carregado no sistema de arquivos.
* Cada caracter `_`  no nome da classe é convertido em um 
`DIRECTORY_SEPARATOR`. O caracter `_` não tem nenhum significado especial 
no namespace.
* Para um namespace e/ou classe é adicionado o sufixo `.php`  quando
carregado no sistema de arquivos.
* Caracteres alfabéticos em __vendor names__, namespaces e classes pode 
conter qualquer combinação entre caixas alta e baixa.


Exemplos
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Underscores em namespaces e nomes de Classes
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

O padrão aqui definido devem ser o mínimo denominador para uma 
interoperabilidade do autoloader sem maiores esforços.
Você pode testar este padrão utilizando o exemplo 
da implementação da classe SplClassLoader que é capaz de 
carregar classes do PHP 5.3.

Exemplo de Implementação
----------------------

A seguir uma função exemplo que simplesmente demonstra 
como o padrão proposto é carregado automaticamente.

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

O gist abaixo é um exemplo da implementação da SplClassLoaderque pode
carregar suas classes automaticamente se você seguir o padrão de interoperabilidade
do autoloader proposto acima. Esta é a maneira recomendada para carregar
classes do PHP 5.3 seguindo esses padrões.

* [http://gist.github.com/221634](http://gist.github.com/221634)

