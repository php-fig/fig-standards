Padrão básico de codificação

Essa sessão do padrão compreende o que devem ser considerados os elementos padrões de codificação que são necessários para assegurar um elevado nível de interoperabilidade técnica entre o código PHP compartilhado.
Padrão Autoloading (vide psr-0)
A seguir descreve os requisitos obrigatórios que devem ser respeitados para a interoperabilidade do autoloader.

Mandatório
Um namespace e uma classe totalmente qualificadas devem obedecer a seguinte estrutura: \<Vendor Name>\(<Namespace>\)*<Class Name>
Cada namespace deve conter um namespace de nível superior ("Vendor Name").
Cada namespace pode conter quantos sub-namespaces forem necessários.
Cada separador de namespace é convertido para um DIRECTORY_SEPARATOR quando carregado do sistema de arquivo.
Cada caracter “_” (underline) no nome da classe é convertido para um DIRECTORY_SEPARATOR. O caracter “_” não tem nenhum significado especial no namespace.
Um namespace e uma classe totalmente qualificada tem é sufixada com php quando carregada do sistema de arquivos.
Caracteres alfabéticos em nomes de fornecedores, namespaces e nomes de classe podem ser de qualquer combinação de letras minúsculas e maiúsculas.

Exemplos
\Doctrine\Common\IsolatedClassLoader => /path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php
\Symfony\Core\Request => /path/to/project/lib/vendor/Symfony/Core/Request.php
\Zend\Acl => /path/to/project/lib/vendor/Zend/Acl.php
\Zend\Mail\Message => /path/to/project/lib/vendor/Zend/Mail/Message.php

Underlines em Namespaces e Nome de Classes
\namespace\package\Class_Name => /path/to/project/lib/vendor/namespace/package/Class/Name.php
\namespace\package_name\Class_Name => /path/to/project/lib/vendor/namespace/package_name/Class/Name.php
O padrão que estabelecemos aqui deve ser o menor denominador comum para um autoloader de uma interoperabilidade indolor. Você pode testar esses padrões utilizando o exemplo da implementação da SplClassLoader que é capaz de carregar classes a partir do PHP 5.3.

Exemplo de Implementação
Below is an example function to simply demonstrate how the above proposed standards are autoloaded.
Abaixo é um exemplo de uma função para demonstrar como os padrões propostos acima são carregados automaticamente.
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

Implementação da SplClassLoader
O exemplo a seguir é uma implementação da SplClassLoader que pode carregar suas classes se você usar os padrões de interoperabilidade proposto acima. É a maneira mais atual para carregar classes de versão PHP 5.3+ que seguem esses padrões. http://gist.github.com/221634
Referências
https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
http://www.ietf.org/rfc/rfc2119.txt

