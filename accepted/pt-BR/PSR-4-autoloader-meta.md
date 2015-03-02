PSR-4 Documento Meta
====================

1. Sumário
----------

O objetivo consiste em especificar as regras para um carregador automático PHP interoperável que
mapeia namespaces para caminhos de arquivo do sistema, e que pode coexistir com qualquer outro
carregador automático SPL registrado. Seria um complemento e não um substituto para o
PSR-0.

2. Por que se preocupar?
------------------------

### História do PSR-0

O padrão PSR-0 para nomenclatura de classes e para carregamento automático surgiu da ampla
aceitação da convenção Horde/PEAR sob as limitações do PHP 5.2 e
anteriores. Com essa convenção, a tendência foi colocar todas as classes fonte PHP
em um único diretório principal, usando sublinhados (underscores) no nome da classe para indicar
pseudo-namespaces, dessa forma:

    /path/to/src/
        VendorFoo/
            Bar/
                Baz.php     # VendorFoo_Bar_Baz
        VendorDib/
            Zim/
                Gir.php     # Vendor_Dib_Zim_Gir

Com o lançamento do PHP 5.3 e a disponibilidade de namespaces adequados, o PSR-0
foi introduzido para permitir tanto o modo sublinhado antigo Horde/PEAR *e* o uso
da nova notação namespace. Sublinhados ainda eram permitidos no nome da classe
para facilitar a transição da antiga nomenclatura de namespace para a mais recente,
e, assim, incentivar a adoção mais ampla.

    /path/to/src/
        VendorFoo/
            Bar/
                Baz.php     # VendorFoo_Bar_Baz
        VendorDib/
            Zim/
                Gir.php     # VendorDib_Zim_Gir
        Irk_Operation/
            Impending_Doom/
                V1.php
                V2.php      # Irk_Operation\Impending_Doom\V2

Essa estrutura é formada muito pelo fato de que o instalador PEAR movia
os arquivos fonte de pacotes PEAR para um único diretório central.

### Junto vem o Composer

Com o Composer, pacotes fonte não são mais copiados para uma única localização
global. Eles são utilizados a partir de sua localização instalada e não são
movidos. Isso significa que com o Composer não há um "único diretório principal" para
os fontes PHP como acontecia com o PEAR. Em vez disso, existem vários diretórios; cada
pacote está em um diretório separado para cada projeto.

Para satisfazer as exigências do PSR-0, isso faz com que os pacotes do Composer
pareçam com:

    vendor/
        vendor_name/
            package_name/
                src/
                    Vendor_Name/
                        Package_Name/
                            ClassName.php       # Vendor_Name\Package_Name\ClassName
                tests/
                    Vendor_Name/
                        Package_Name/
                            ClassNameTest.php   # Vendor_Name\Package_Name\ClassNameTest

Os diretórios "src" e "tests" têm que incluir nomes de diretórios vendor e
package. Esse é um artefato de cumprimento do PSR-0.

Muitos acham essa estrutura mais profunda e repetitiva do que o necessário. Essa
proposta sugere que um PSR adicional ou substituindo seria útil para
termos pacotes que parecem mais com o seguinte:

    vendedor /
        vendor_name /
            nome_do_pacote /
                src /
                    ClassName.php # Vendor_Name \ PACKAGE_NAME \ ClassName
                testes /
                    ClassNameTest.php # Vendor_Name \ PACKAGE_NAME \ ClassNameTest

Isso exigiria uma implementação do que foi chamado inicialmente
"Carregamento automático orientado a pacote" (vs o tradicional "carregamento automático direto
classe-para-arquivo").

### Carregamento Automático Orientado a Pacote

É difícil implementar o carregamento automático orientado a pacote através de uma extensão ou
alteração no PSR-0, porque o PSR-0 não permite um caminho de intercessão
entre quaisquer partes do nome da classe. Isso significa que a implementação de um
carregador automático orientado a pacote será mais complicado do que o PSR-0. Entretanto, ele
permitiria pacotes mais limpos.

Inicialmente, foram sugeridas as seguintes regras:

1. Os implementadores DEVEM usar pelo menos dois níveis de namespace: nome do vendor, e
nome do pacote dentro desse vendor. (Esta combinação de dois nomes de nível superior é
a seguir designada como o nome do vendor-package ou o namespace do
vendor-package.)

2. Os implementadores DEVEM permitir um caminho infixo entre o namespace vendor-package
e o restante do nome de classe totalmente qualificado.

3. O namespace vendor-package PODE mapear para qualquer diretório. A parte
restante do nome de classe totalmente qualificado DEVE mapear os nomes de namespaces
para diretórios identicamente nomeados e DEVE mapear o nome da classe para um
arquivo identicamente nomeado que termina em .php.

Note que isso significa o fim do sublinhado-como-separador-de-diretório no nome da
classe. Pode-se pensar que os sublinhados devem ser honrados pois eles estão sob
o PSR-0, mas vendo que a sua presença no documento é em referência a
transição do PHP 5.2 e de pseudo-namespacing anteriores, é
aceitável removê-los aqui.


3. Escopo
---------

### 3.1 Metas

- Manter a regra PSR-0 que implementadores DEVEM usar pelo menos dois níveis de
  namespaces: nome do vendor e nome do pacote dentro desse vendor.

- Permitir um caminho infixo entre o namespace vendor-package e o restante do
  nome de classe totalmente qualificado.

- Permitir que o namespace vendor-package POSSA mapear para qualquer diretório, talvez
  vários diretórios.

- Acabar com os sublinhados em nomes de classe como separadores de diretório

### 3.2 Não-Metas

- Proporcionar um algoritmo de transformação geral para recursos não-classe


4. Abordagens
-------------

### 4.1 Abordagem Escolhida

Esta abordagem mantém características-chave do PSR-0, eliminando as
estruturas de diretórios mais profundas que ele exige. Além disso, ela especifica determinadas
regras adicionais que fazem as implementações explicitamente mais interoperáveis.

Apesar de não ser relacionada ao mapeamento de diretório, a versão final também especifica como
os carregadores automáticos deve lidar com erros. Especificamente, ela proíbe lançar exceções
ou gerar erros. A razão é dupla.

1. Carregadores automáticos em PHP são explicitamente projetados para serem empilháveis ​​de modo que, se um
carregador automático não consegue carregar uma classe, outro tem a chance de fazê-lo. Ter um carregador automático
que dispara uma quebra de erro viola a compatibilidade.

2. `class_exists()` e `interface_exists()` permitem "não encontrado, mesmo depois de tentar
o carregamento automático" como um caso de uso normal e legítimo. Um carregador automático que gera exceções
torna `class_exists()` inutilizável, o que é totalmente inaceitável no ponto de vista da
interoperabilidade. Os carregadores automáticos que desejam fornecer informações de depuração adicional
em um caso classe-não-encontrada devem fazê-lo através de log, seja para um logger compatível com o PSR-3
ou outro.

Prós:

- Estruturas de diretórios rasas

- Localizações de arquivos mais flexíveis

- Parar de usar o sublinhado no nome da classe como separador de diretório

- Tornar as implementações mais explicitamente interoperáveis

Contras:

- Não é mais possível, como sob o PSR-0, examinar apenas um nome de classe para
  determinar onde ele está no sistema de arquivos (a convenção "classe-para-arquivo"
  herdada do Horde/PEAR).


### 4.2 Alternativa: Ficar Somente Com o PSR-0

Ficar apenas com o PSR-0, embora razoável, nos deixa com uma estrutura de
diretórios relativamente mais profunda.

Prós:

- Não há necessidade de mudar os hábitos ou implementações de ninguém

Contras:

- Deixa-nos com estruturas de diretórios mais profundas

- Deixa-nos com sublinhados no nome da classe usados como separadores de
  diretório


### 4.3 Alternativa: Dividir Carregamento Automático e Transformação

Beau Simensen e outros sugeriram que o algoritmo de transformação pode ser
dividido da proposta de carregamento automático, de modo que as regras de transformação
poderiam ser referenciadas por outras propostas. Depois de fazer o trabalho para separá-las,
seguido por uma votação e alguma discussão, a versão combinada (ou seja,
regras de transformação incorporadas na proposta de carregamento automático) foi revelada como a
preferência.

Prós:

- Regras de transformação poderiam ser referenciadas separadamente por outra proposta

Contras:

- Não está de acordo com os desejos dos que responderam a pesquisa e alguns colaboradores

### 4.4 Alternativa: Usar uma Linguagem mais Imperativa e Narrativa

Após a segunda votação ser puxada por um patrocinador após ouvir um múltiplo +1
dos eleitores que apoiaram a idéia, mas não concordavam (ou entenderam) com a
formulação da proposta, houve um período em que a proposta votada
foi ampliada com mais narrativa e uma linguagem um pouco mais imperativa. Essa
abordagem foi criticada por uma minoria dos participantes. Depois de algum tempo, Beau
Simensen iniciou uma revisão experimental com um olho no PSR-0; o Editor e
os Patrocinadores favoreceram essa abordagem mais concisa e conduziu a versão agora sob
consideração, escrita por Paul M. Jones e com a contribuição de muitos.

### Nota de Compatibilidade com o PHP 5.3.2 e anteriores

Versões do PHP anteriores a 5.3.3 não tiram o separador de namespace principal, assim
a responsabilidade de verificar por essa diferença cai sobre a implementação. A não
retirada do separator de namespace principal pode levar a um comportamento inesperado.


5. Pessoas
----------

### 5.1 Editor

- Paul M. Jones, Solar/Aura

### 5.2 Patrocinadores

- Phil Sturgeon, PyroCMS (Coordinator)
- Larry Garfield, Drupal

### 5.3 Contribuintes

- Andreas Hennings
- Bernhard Schussek
- Beau Simensen
- Donald Gilbert
- Mike van Riel
- Paul Dragoonis
- Muitos outros para nomear e contar


6. Votos
--------

- **Voto de Entrada:** <https://groups.google.com/d/msg/php-fig/_LYBgfcEoFE/ZwFTvVTIl4AJ>

- **Voto de aceitação:**

    - 1ª tentativa: <https://groups.google.com/forum/#!topic/php-fig/Ua46E344_Ls>,
      apresentada antes do novo fluxo de trabalho; abortada devido à modificação acidental da proposta
      
    - 2ª tentativa: <https://groups.google.com/forum/#!topic/php-fig/NWfyAeF7Psk>,
      cancelada a critério do patrocinador <https://groups.google.com/forum/#!topic/php-fig/t4mW2TQF7iE>
    
    - 3ª tentativa: TBD


7. Links relevantes
-------------------

- [Carregador Automático, round 4](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/lpmJcmkNYjM)
- [ENQUETE: Carregador Automático: Dividir ou Combinado?](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/fGwA6XHlYhI)
- [PSR-X Especificação do Carregador Automático: Lacunas, ambigüidades](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/kUbzJAbHxmg)
- [Carregador Automático: Combinar Propostas?](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/422dFBGs1Yc)
- [Carregador Automático Orientado a Pacote, Round 2](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/Y4xc71Q3YEQ)
- [Carregador Automático: Olhando novamente para o namespace](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/bnoiTxE8L28)
- [DISCUSSÃO: Carregador Automático Orientado a Pacote - voto contra](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/SJTL1ec46II)
- [VOTO: Carregador Automático Orientado a Pacote](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/Ua46E344_Ls)
- [Proposta: Carregador Automático Orientado a Pacote](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/qT7mEy0RIuI)
- [Towards a Package Oriented Carregador automático](https://groups.google.com/forum/#!searchin/php-fig/package$20oriented$20autoloader/php-fig/JdR-g8ZxKa8/jJr80ard-ekJ)
- [Lista de Propostas Alternativas ao PSR-4](https://groups.google.com/forum/#!topic/php-fig/oXr-2TU1lQY)
- [Sumário das [post-Acceptance Vote pull] discussões do PSR-4](https://groups.google.com/forum/#!searchin/php-fig/psr-4$20summary/php-fig/bSTwUX58NhE/YPcFgBjwvpEJ)
