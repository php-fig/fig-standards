Guia de Estilo de Codificação
=============================

Este guia estende e expande o [PSR-1][], o padrão básico de codificação.

A intenção deste guia é reduzir a fricção cognitiva quando lendo códigos de diferentes autores. Isto é feito enumerando um conjunto de regras compartilhadas e expectativas de como formatar códigos em PHP.

As regras de estilo incluídas aqui são derivadas de semelhanças entre vários projetos membros. Quando vários autores colaboram entre múltiplos projetos, ajuda ter um conjunto de princípios básicos à ser utilizado em todos os projetos. Assim, o benefício deste guia não está nas regras em sí, mas no compartilhamento destas regras.

As palavras chave "DEVE", "NÃO DEVE", "OBRIGATÓRIO", "TEM QUE", "NÃO TEM QUE", "DEVERIA", "NÃO DEVERIA", "RECOMENDADO", "PODE" e "OPCIONAL" existentes neste documento devem ser interpretadas como são descritas no [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. Visão Geral
--------------

- Códigos DEVEM seguir [PSR-1][].

- Códigos DEVEM utilizar 4 espaços para indentação, não tabs.

- NÃO DEVE existir um limite absoluto no comprimento da linha; O limite relativo DEVE ser de 120 caracteres; As linhas DEVERIAM ter 80 caracteres ou menos.

- DEVE existir uma linha em branco após da declaração do `namespace` e DEVE existir uma linha em branco após o bloco de declarações de `use`.

- Chaves de abertura para classes DEVEM ser colocadas na linha seguinte e chaves de fechamento DEVEM ser colocadas na linha após o corpo da classe.

- Chaves de abertura para métodos DEVEM ser colocadas na linha seguinte e chaves de fechamento DEVEM ser colocadas na linha após o corpo do método.

- Visibilidade DEVE ser declarada em todas as propriedades e métodos; `abstract` e `final` DEVEM ser declarados antes da visibilidade; `static` DEVE ser declarado depois da visibilidade.

- Palavras-chave de estruturas de controle DEVEM ter um espaço depois delas; chamadas de métodos e funções NÃO DEVEM.
  
- Chaves de abertura para estruturas de controle DEVEM ser colocadas na mesma linha e chaves de fechamento DEVEM ser colocadas na linha após o corpo da estrutura de controle.

- Parenteses de abertura para estruturas de controle NÃO DEVEM ter um espaço depois delas e parenteses de fechamento para estruturas de controle NÃO DEVEM ter um espaço antes.


### 1.1. Exemplo

Este exemplo engloba algumas das regras abaixo como uma rápida visão geral:

```php
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class Foo extends Bar implements FooInterface
{
    public function sampleFunction($a, $b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // corpo do método
    }
}
```

2. Geral
--------

### 2.1 Padrão Básico de Codificação

Códigos DEVEM seguir todas as regras traçadas na [PSR-1][].

### 2.2 Arquivos

Todos os arquivos PHP DEVEM utilizar o padrão Unix LF (linefeed) de terminação de linhas.

Todos os arquivos PHP DEVEM terminar com uma única linha em branco.

A tag de fechamento `?>` DEVE ser omitida em arquivos contendo somente PHP.

### 2.3. Linhas

NÃO DEVE haver um limite absoluto no comprimento da linha.

O limite relativo no comprimento das linhas DEVE ser de 120 caracteres; Analizadores automáticos de estilo DEVEM advertir, mas NÃO DEVEM exibir um erro ao ultrapassar o limite relativo.

Linhas NÃO DEVERIAM ser mais longas que 80 caracteres; Linhas mais longas que isso DEVERIAM ser divididas em múltiplas linhas subsequentes de não mais que 80 caracteres cada.

NÃO DEVE haver espaços em branco ao final de linhas que não estão em branco.

Linhas em branco PODEM ser adicionadas para melhorar a legibilidade e para indicar blocos relacionados de código.

NÃO DEVE haver mais do que uma declaração por linha.

### 2.4. Indentação

Códigos DEVEM utilizar uma indentação de 4 espaços e NÃO DEVEM utilizar tabs para indentação.

> Utilizando somente espaços e não misturando com tabs, ajuda a evitar
> problemas com diffs, patches, history e annotations em sistemas de 
> versionamento. A utilização de espaços também torna fácil incluir 
> sub-indentações granuladas para alinhamento inter-linhas.

### 2.5. Palavras-chave e True/False/Null

[Palavras-chave][] PHP DEVEM ser em letras minúsculas.

As constantes PHP `true`, `false` e `null` DEVEM ser em letras minúsculas.

[Palavras-chave]: http://php.net/manual/en/reserved.keywords.php


3. Declarações Namespace e Use
------------------------------

Quando presente, DEVE haver uma linha em branco após a declaração do `namespace`.

Quando presente, todas as declarações `use` DEVEM ser colocadas após a declaração do `namespace`.

DEVE haver somente uma palavra-chave `use` por declaração.

DEVE haver uma linha em branco depois do bloco de `use`.

Por exemplo:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... mais código PHP ...

```


4. Classes, Propriedades e Métodos
----------------------------------

O termo classe se refere a todas as classes, interfaces e traits.

### 4.1. Extends e Implements

As palavras-chave `extends` e `implements` DEVEM ser declaradas na mesma linha que o nome da classe.

A chave de abertura para a classe DEVE ser colocada em sua própria linha; a chave de fechamento DEVE ser coloca na linha após o corpo da classe.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constantes, propriedades, métodos
}
```

Listas de `implements` PODEM ser divididas em múltiplas linhas, onde cada linha subsequente é indentada uma vez. Quando fazendo isto, o primeiro item da lista DEVE ser colocado na linha seguinte e DEVE haver somente uma interface por linha.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // constantes, propriedades, métodos
}
```

### 4.2. Propriedades

Visibilidades DEVEM ser declaradas para todas as propriedades.

A palavra-chave `var` NÃO DEVE ser utilizada pra declarar uma propriedade.

NÃO DEVE haver mais de uma propriedade declarada por linha.

Nomes de propriedades NÃO DEVERIAM ser prefixadas com `_` para indicar visibilidades `protected` ou `private`.

Uma declaração de propriedade se parece com o seguinte:

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. Métodos

Visibilidades DEVEM ser declaradas em todos os métodos.

Nomes de métodos NÃO DEVERIAM ser prefixadas com `_` para indicar visibilidades `protected` ou `private`.

Assinaturas de métodos NÃO DEVEM ser declaradas com um espaço após o nome do método. A chave de abertura DEVE ser colocada em sua própria linha e a chave de fechamento DEVE ser colocada na linha após o corpo do método. NÃO DEVE haver um espaço depois do parenteses de abertura e NÃO DEVE haver um espaço antes do parenteses de fechamento.

Uma declaração de método se parece com o seguinte. Note o posicionamento dos parenteses, virgulas, espaços e chaves:

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // corpo do método
    }
}
```    

### 4.4. Argumentos de Métodos

Na lista de argumentos, NÃO DEVE haver um espaço antes de cada vírgula e DEVE haver um espaço após cada vírgula.

Argumentos de métodos com valores default DEVEM ser colocados ao fim da lista de argumentos.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // corpo do método
    }
}
```

Lista de argumentos PODEM ser divididas entre múltiplas linhas, onde cada linha subsequente é indentada uma vez. Quando fazendo isto, o primeiro item da lista DEVE estar na linha seguinte e DEVE haver somente um argumento por linha.

Quando a lista de argumento é dividida em multiplas linhas, o parenteses de fechamento e a chave abertura DEVEM ser colocadas juntas em sua própria linha com um espaço entre elas.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // corpo do método
    }
}
```

### 4.5. `abstract`, `final` e `static`

Quando presente, as declarações `abstract` e `final` DEVEM preceder as declarações de visibilidade.

Quando presente, a declaração `static` DEVEM vir depois da declaração de visibilidade.

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // corpo do método
    }
}
```

### 4.6. Method and Function Calls

Quando fazendo uma chamada de métodos ou funções, NÃO DEVE haver um espaço entre o nome do método e o parenteses de abertura, NÃO DEVE haver um espaço após o parenteses de abertura e NÃO DEVE haver um espaço antes do parenteses de fechamento. Na lista de argumentos, NÃO DEVE haver um espaço antes de cada vírgula e DEVE haver um espaço após cada vírgula.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

Listas de argumentos PODEM ser divididas em múltiplas linhas, onde cada linha subsequente é indentada uma vez. Quando fazendo isto, o primeiro item da lista DEVE estar na linha seguinte e DEVE haver somente um argumento por linha.

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. Estruturas de Controle
-------------------------

As regras gerais de estilo para estruturas de controle são as seguintes:

- DEVE haver um espaço após a palavra-chave da estrutura de controle
- NÃO DEVE haver um espaço depois do parenteses de abertura
- NÃO DEVE haver um espaço antes do parenteses de fechamento
- DEVE haver um espaço entre o parenteses de fechamento e a chave de abertura.
- O corpo da estrutura DEVE ser indentada uma vez
- A chave de fechamento DEVE ser colocada na linha após o corpo da estrutura

O corpo de cada estrutura DEVE ser envolta por chaves. Isso padroniza como as estruturas se parecem e reduz a possibilidade de introduzir erros à medida que novas linhas são adicionadas ao corpo da estrutura.


### 5.1. `if`, `elseif`, `else`

Uma estrutura `if` se parece com o seguinte. Note o posicionamento dos parenteses, espaços e chaves; e que `else` e `elseif` estão na mesma linha que a chave de fechamento do corpo da estrutura anterior.

```php
<?php
if ($expr1) {
    // corpo do if
} elseif ($expr2) {
    // corpo do elseif
} else {
    // corpo do else
}
```

A palavra-chave `elseif` DEVERIA ser utilizada ao invés de `else if` para que todas as palavras-chave de controle se pareçam com uma só palavra.


### 5.2. `switch`, `case`

Uma estrutura `switch` se parece com o seguinte. Note o posicionamento dos parenteses, espaços e chaves. A declaração `case` DEVE ser identada uma vez do `switch` e a palavra-chave `case` (ou qualquer outra palavra-chave de terminação) DEVE ser indentada no mesmo nível que o corpo do `case`. DEVE haver um comentário como `//sem break` quando a passagem próximo case é intencional em um corpo de `case` que não está vazio.

```php
<?php
switch ($expr) {
    case 0:
        echo 'Primeiro case, com um break';
        break;
    case 1:
        echo 'Segundo case, passando para o próximo case';
        // sem break
    case 2:
    case 3:
    case 4:
        echo 'Terceiro case, return ao invés de break';
        return;
    default:
        echo 'Default case';
        break;
}
```


### 5.3. `while`, `do while`

Uma estrutura `while` se parece com o seguinte. Note o posicionamento dos  parenteses, espaços e chaves.

```php
<?php
while ($expr) {
    // corpo da estrutura
}
```

Similarmente, uma estrutura `do while` se parece com o seguinte. Note o posicionamento dos  parenteses, espaços e chaves.

```php
<?php
do {
    // corpo da estrutura
} while ($expr);
```

### 5.4. `for`

Uma estrutura `for` se parece com o seguinte. Note o posicionamento dos  parenteses, espaços e chaves.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // corpo do for
}
```

### 5.5. `foreach`

Uma estrutura `foreach` se parece com o seguinte. Note o posicionamento dos  parenteses, espaços e chaves.

```php
<?php
foreach ($iterable as $key => $value) {
    // corpo do foreach
}
```

### 5.6. `try`, `catch`

Uma estrutura `try catch` se parece com o seguinte. Note o posicionamento dos  parenteses, espaços e chaves.

```php
<?php
try {
    // corpo do try
} catch (FirstExceptionType $e) {
    // corpo do catch
} catch (OtherExceptionType $e) {
    // corpo do catch
}
```

6. Closures
-----------

Closures DEVEM ser declaradas com um espaço após a palavra-chave `function`, e um espaço antes e depois da palavra-chave `use`.

A chave de abertura DEVE ser colocada na mesma linha e a chave de fechamento DEVE ser colocada na linha seguinte ao fim do corpo da closure.

NÃO DEVE haver um espaço após o parentese de abertura da lista de argumentos ou variáveis e NÃO DEVE haver um espaço antes do parentese de fechamento da lista de argumentos ou variáveis.

Na lista de argumentos e lista de variáveis, NÃO DEVE haver um espaço antes de cada vírgula e DEVE haver um espaço após cada vírgula.

Argumentos de closures com valores default DEVEM ser colocados ao fim da lista de argumentos.

Uma declaração de closure se parece com o seguinte. Note o posicionamento dos parenteses, vírgulas, espaços e chaves:

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // corpo
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // corpo
};
```

Listas de argumentos e variáveis PODEM ser dividas em múltiplas linhas, onde cada linha subsequente  é indentada uma vez. Quando fazendo isto, o primeiro item da lista DEVE estar na próxima linha e DEVE haver somente um argumento ou variável por linha.

Quando uma lista finalizando (sendo argumentos ou variáveis) é divida em múltiplas linhas, o parentese de fechamento e a chave abertura  DEVEM ser colocados em sua própria linha com um espaço entre eles.

A seguir estão exemplos de closures com e sem listas de argumentos e variáveis que se dividem por múltiplas linhas.

```php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // corpo
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // corpo
};

$longArgs_longVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // corpo
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // corpo
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // corpo
};
```

Note que as regras de formatação também se aplicam em closures que são utilizadas diretamente numa chamada de função ou método como um argumento.

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // corpo
    },
    $arg3
);
```


7. Conclusão
------------

Há vários elementos de estilos e práticas que foram intencionalmente omitidos neste guia. Incluindo, mas não limitado à:

- Declaração de variáveis globais e constantes globais

- Declaração de funções

- Operadores e atribuição

- Alinhamento inter-linhas

- Comentários e blocos de documentação

- Prefixos e sufixos de nomes de classes

- Melhores práticas

Recomendações futuras PODEM revisar e extender este guia para cobrir esses ou outros elementos de estilo e prática.


Apêndice A. Votação
-------------------

Ao escrever este guia de estilo, o grupo fez uma votação entre os projetos membros para determinar práticas comuns. Esta votação esta incluida aqui para posteridade.

### A.1. Dados da votação (Mantido como o original)

    url,http://www.horde.org/apps/horde/docs/CODING_STANDARDS,http://pear.php.net/manual/en/standards.php,http://solarphp.com/manual/appendix-standards.style,http://framework.zend.com/manual/en/coding-standard.html,http://symfony.com/doc/2.0/contributing/code/standards.html,http://www.ppi.io/docs/coding-standards.html,https://github.com/ezsystems/ezp-next/wiki/codingstandards,http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html,https://github.com/UnionOfRAD/lithium/wiki/Spec%3A-Coding,http://drupal.org/coding-standards,http://code.google.com/p/sabredav/,http://area51.phpbb.com/docs/31x/coding-guidelines.html,https://docs.google.com/a/zikula.org/document/edit?authkey=CPCU0Us&hgd=1&id=1fcqb93Sn-hR9c0mkN6m_tyWnmEvoswKBtSc0tKkZmJA,http://www.chisimba.com,n/a,https://github.com/Respect/project-info/blob/master/coding-standards-sample.php,n/a,Object Calisthenics for PHP,http://doc.nette.org/en/coding-standard,http://flow3.typo3.org,https://github.com/propelorm/Propel2/wiki/Coding-Standards,http://developer.joomla.org/coding-standards.html
    voting,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,no,no,no,?,yes,no,yes
    indent_type,4,4,4,4,4,tab,4,tab,tab,2,4,tab,4,4,4,4,4,4,tab,tab,4,tab
    line_length_limit_soft,75,75,75,75,no,85,120,120,80,80,80,no,100,80,80,?,?,120,80,120,no,150
    line_length_limit_hard,85,85,85,85,no,no,no,no,100,?,no,no,no,100,100,?,120,120,no,no,no,no
    class_names,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,lower_under,studly,lower,studly,studly,studly,studly,?,studly,studly,studly
    class_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,next,next,next,next,next,next,same,next,next
    constant_names,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper
    true_false_null,lower,lower,lower,lower,lower,lower,lower,lower,lower,upper,lower,lower,lower,upper,lower,lower,lower,lower,lower,upper,lower,lower
    method_names,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,lower_under,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel
    method_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,same,next,next,next,next,next,same,next,next
    control_brace_line,same,same,same,same,same,same,next,same,same,same,same,next,same,same,next,same,same,same,same,same,same,next
    control_space_after,yes,yes,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes
    always_use_control_braces,yes,yes,yes,yes,yes,yes,no,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes
    else_elseif_line,same,same,same,same,same,same,next,same,same,next,same,next,same,next,next,same,same,same,same,same,same,next
    case_break_indent_from_switch,0/1,0/1,0/1,1/2,1/2,1/2,1/2,1/1,1/1,1/2,1/2,1/1,1/2,1/2,1/2,1/2,1/2,1/2,0/1,1/1,1/2,1/2
    function_space_after,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no
    closing_php_tag_required,no,no,no,no,no,no,no,no,yes,no,no,no,no,yes,no,no,no,no,no,yes,no,no
    line_endings,LF,LF,LF,LF,LF,LF,LF,LF,?,LF,?,LF,LF,LF,LF,?,,LF,?,LF,LF,LF
    static_or_visibility_first,static,?,static,either,either,either,visibility,visibility,visibility,either,static,either,?,visibility,?,?,either,either,visibility,visibility,static,?
    control_space_parens,no,no,no,no,no,no,yes,no,no,no,no,no,no,yes,?,no,no,no,no,no,no,no
    blank_line_after_php,no,no,no,no,yes,no,no,no,no,yes,yes,no,no,yes,?,yes,yes,no,yes,no,yes,no
    class_method_control_brace,next/next/same,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/next,same/same/same,same/same/same,same/same/same,same/same/same,next/next/next,next/next/same,next/same/same,next/next/next,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/same,next/next/next

### A.2. Legenda da votação

`indent_type`:
Tipo de indentação. `tab` = "Usar o tab", `2` or `4` = "Número de espaços".

`line_length_limit_soft`:
O limite "relativo" do comprimento da linha, em caracteres. `?` = não optou or não respondeu, `no` significa sem limite.

`line_length_limit_hard`:
O limite "absoluto" do comprimento da linha, em caracteres. `?` = não optou or não respondeu, `no` significa sem limite.

`class_names`:
Como classes são nomeadas. `lower` = somente minúsculas, `lower_under` = minúsculas com separadores `_`, `studly` = StudlyCase.

`class_brace_line`:
A chave de abertura da clase vai na mesma (`same`) linha que a palavra-chave da classe, ou na linha seguinte (`next`) à ela?

`constant_names`:
Como as constantes de classes são nomeadas? `upper` = Somente maiúsculas com separadores `_`.

`true_false_null`:
As palavras chaves `true`, `false`, and `null` são escritas todas em caracteres minúsculos (`lower`), ou em maiúsculos (`upper`)?

`method_names`:
Como os métodos são nomeados? `camel` = `camelCase`, `lower_under` = caracteres minúsculos com separadores `_`.

`method_brace_line`:
A chave de abertura para um método é colocada na mesma (`same`) linha que o nome do método, ou na linha seguinte (`next`)?

`control_brace_line`:
A chave de abertura para uma estrutura de controle é colocada na mesma (`same`) linha que a palavra-chave da estrutura, ou na linha seguinte (`next`)?

`control_space_after`:
Existe um espaço após a palavra-chave da estrutura de controle?

`always_use_control_braces`:
As estruturas de controle sempre têm chaves?

`else_elseif_line`:
Quando usando `else` ou `elseif`, estes são colocados na mesma (`same`) linha que a última chave de fechamento, ou na linha seguinte (`next`)?

`case_break_indent_from_switch`:
Quantas vezes `case` e `break` são indentados dentro de uma declaração `switch`?

`function_space_after`:
Chamadas de funções têm um espaço entre o nome da função e o parentese de abertura?

`closing_php_tag_required`:
Em arquivos que possuam somente código PHP, a tag de fechamento `?>` é requerida?

`line_endings`:
Que tipo de terminação de linha é utilizado?

`static_or_visibility_first`:
Quando declarando um método, `static` é colocado antes ou a visibilidade é colocado antes?

`control_space_parens`:
Numa expressão de estruturas de controle, há um espaço após o parentese de abertura e um espaço antes do parentese de fechamento? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
Ha um linha em branco após a tag de abertura do PHP?

`class_method_control_brace`:
Um resumo de onde é colocado a chave de abertura para classes, métodos e estruturas de controle.

### A.3. Resultado da votação

    indent_type:
        tab: 7
        2: 1
        4: 14
    line_length_limit_soft:
        ?: 2
        no: 3
        75: 4
        80: 6
        85: 1
        100: 1
        120: 4
        150: 1
    line_length_limit_hard:
        ?: 2
        no: 11
        85: 4
        100: 3
        120: 2
    class_names:
        ?: 1
        lower: 1
        lower_under: 1
        studly: 19
    class_brace_line:
        next: 16
        same: 6
    constant_names:
        upper: 22
    true_false_null:
        lower: 19
        upper: 3
    method_names:
        camel: 21
        lower_under: 1
    method_brace_line:
        next: 15
        same: 7
    control_brace_line:
        next: 4
        same: 18
    control_space_after:
        no: 2
        yes: 20
    always_use_control_braces:
        no: 3
        yes: 19
    else_elseif_line:
        next: 6
        same: 16
    case_break_indent_from_switch:
        0/1: 4
        1/1: 4
        1/2: 14
    function_space_after:
        no: 22
    closing_php_tag_required:
        no: 19
        yes: 3
    line_endings:
        ?: 5
        LF: 17
    static_or_visibility_first:
        ?: 5
        either: 7
        static: 4
        visibility: 6
    control_space_parens:
        ?: 1
        no: 19
        yes: 2
    blank_line_after_php:
        ?: 1
        no: 13
        yes: 8
    class_method_control_brace:
        next/next/next: 4
        next/next/same: 11
        next/same/same: 1
        same/same/same: 6
