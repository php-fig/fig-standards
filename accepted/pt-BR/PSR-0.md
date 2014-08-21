A seguir são descritos os requisitos obrigatórios que devem ser respeitados para o funcionamento correto da classe autoloader
.

Obrigatório
---------

* O espaço de nomes deve ter a seguinte estrutura `\<Nome do fornecedor>\(<Namespace>\)*<Nome da classe>`
* Cada namespace deve ter um namespace de nível superior ("Nome do fornecedor").
* Cada namespace pode ter quantos sub-namespaces forem necessários.
* Cada separador de namespace é convertido para `DIRECTORY_SEPARATOR` quando o sistema de arquivos é carregado.
* Cada caractere `_` no nome da classe é convertido para 
  `DIRECTORY_SEPARATOR`. O caractere `_` não tem um significado importante no namespace.
* O espaço de nomes é sufixado com a extensão `.php` quando o sistema de arquivos é carregado.
* Podem ser utilizados qualquer combinação das letras do alfabeto minúculas e maiúsculas em vendor names, namespaces e nome de classes.


Exemplos
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Underscores nos Namespaces e Nome de classes
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Os padrões que estabelecemos aqui são o menor denominador comum para o funcionamento mínimo da classe autoloader. Você pode fazer testes seguindo estes padrões utilizando a implementação da classe SplClassLoader que é capaz de carregar as classes do PHP 5.3.

Exemplo de implementação
----------------------

Abaixo está um exemplo de uma função para demonstrar como os padrões acima
padrões propostos são carregados automaticamente

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

Implementação da classe SplClassLoader
-----------------------------

O gist a seguir, é um exemplo de implementação da classe SplClassLoader, que pode carregar suas classes seguindo os padrões expostos acima.
Este é o método recomendado para o carregamento de classes do PHP 5.3.

* [http://gist.github.com/221634](http://gist.github.com/221634)

