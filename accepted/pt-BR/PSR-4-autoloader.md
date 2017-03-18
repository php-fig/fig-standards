# Carregador automático (autoloader)

As palavras-chave "DEVE", "NÃO DEVE", "OBRIGATÓRIO", "DEVERÁ", "NÃO DEVERÁ",
"DEVERIA", "NÃO DEVERIA", "RECOMENDADO", "PODE", e "OPCIONAL" neste documento devem ser
interpretadas como descrito na [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Visão geral

Este PSR descreve uma especificação para o [carregamento automático (autoloading)][] de classes a partir de
caminhos de arquivo. É totalmente interoperável, e pode ser utilizado em adição a qualquer outra
especificação de carregamento automático (autoloading), incluindo o [PSR-0][]. Esse PSR também descreve onde
colocar os arquivos que serão carregados automaticamente de acordo com a especificação.


## 2. Especificação

1. O termo "classe" refere-se a classes, interfaces, traits e outras estruturas
   semelhantes.

2. Um nome de classe totalmente qualificado tem a seguinte forma:

        \<NomeDoNamespace>(\<NomesDosSubNamespaces>)*\<NomeDaClasse>

    1. O nome de classe totalmente qualificado DEVE ter um nome de namespace de nível superior,
       também conhecido como "vendor namespace".

    2. O nome de classe totalmente qualificado PODE ter um ou mais nomes de
       sub-namespaces.

    3. O nome de classe totalmente qualificado DEVE ter como terminação um nome de classe.

    4. Sublinhados (underscores) não têm significado especial em qualquer parte do nome
       de classe totalmente qualificado.

    5. Caracteres alfabéticos no nome de classe totalmente qualificado PODEM ser qualquer
       combinação de letras minúsculas e maiúsculas.

    6. Todos os nomes de classe DEVEM ser referenciados na forma caso-sensitiva.

3. Ao carregar um arquivo que corresponde a um nome de classe totalmente qualificado ...

    1. Uma série contígua de um ou mais nomes de namespaces e
       sub-namespaces, não incluindo o separador de namespace, no nome de classe
       totalmente qualificado (um "prefixo de namespace") corresponde a pelo menos um
       "diretório base".

    2. Os nomes de sub-namespaces contíguos após o "prefixo de namespace"
       correspondem a um subdiretório dentro de um "diretório base", no qual os
       separadores de namespace representam separadores de diretório. O nome de subdiretório
       DEVE coincidir com o caso dos nomes dos sub-namespaces.

    3. O nome da classe de terminação corresponde a um nome de arquivo que termina com `.php`.
       O nome do arquivo DEVE corresponder ao caso do nome da classe de terminação.

4. Implementações de carregador automático NÃO DEVEM lançar exceções, NÃO DEVEM gerar erros
   de qualquer nível, e NÃO DEVERIAM retornar um valor.


## 3. Exemplos

A tabela abaixo mostra o caminho do arquivo correspondente para um determinado nome de classe totalmente 
qualificado, prefixo de namespace, e o diretório base.

| Nome de Classe Totalmente Qualificado | Prefixo de Namespace | Diretório Base           | Caminho de Arquivo Resultante
| ------------------------------------- |----------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer          | Acme\Log\Writer      | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status             | Aura\Web             | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request                 | Symfony\Core         | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                             | Zend                 | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

Para implementações exemplo de carregadores automáticos em conformidade com a especificação,
por favor consulte [arquivos exemplo][]. Implementações exemplo, NÃO DEVEM ser consideradas
como parte da especificação e PODEM mudar a qualquer momento.

[carregamento automático (autoloading)]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[arquivos exemplo]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
