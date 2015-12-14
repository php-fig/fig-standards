# Documento Meta Mensagem HTTP

## 1. Resumo

O objetivo desta proposta é fornecer um conjunto de interfaces comuns para mensagens HTTP
, tal como descrito em [RFC 7230] (http://tools.ietf.org/html/rfc7230) e
[RFC 7231] (http://tools.ietf.org/html/rfc7231), e URIs como descrito em 
[RFC 3986] (http://tools.ietf.org/html/rfc3986) (no contexto de mensagens HTTP).

- RFC 7230: http://www.ietf.org/rfc/rfc7230.txt
- RFC 7231: http://www.ietf.org/rfc/rfc7231.txt
- RFC 3986: http://www.ietf.org/rfc/rfc3986.txt

Todas as mensagens HTTP consistem da versão do protocolo HTTP sendo usado, cabeçalhos, e
um corpo da mensagem. Uma _Requisição_ baseia-se na mensagem para incluir o método HTTP
usado para fazer a requisição, e o URI para o qual a requisição é feita. Uma
_Resposta_ inclui o código de status HTTP e uma frase de justificativa.

No PHP, as mensagens HTTP são usadas ​​em dois contextos:

- Para enviar uma requisição HTTP, através da extensão `ext/curl`, camada de stream nativa do PHP
  , etc, e processar a resposta HTTP recebida. Em outras palavras, as mensagens HTTP
  são utilizadas ao usar o PHP como um _cliente HTTP_.
- Para processar a chegada de uma requisição HTTP no servidor, e retornar uma resposta HTTP
  para o cliente que fez a requisição. O PHP pode utilizar mensagens HTTP quando usado como uma
  _aplicação do lado do servidor_ para atender às requisições HTTP.

Essa proposta apresenta uma API para descrever plenamente todas as partes das várias
mensagens HTTP no PHP.

## 2. Mensagens HTTP no PHP

O PHP não tem suporte embutido para mensagens HTTP.

### Suporte HTTP do lado do cliente

PHP suporta o envio de requisições HTTP através de vários mecanismos:

- [Streams PHP] (http://php.net/streams)
- A [extensão cURL] (http://php.net/curl)
- [ext/http] (http://php.net/http) (v2 também tenta abordar suporte do lado do servidor)

Streams PHP são a maneira mais conveniente e onipresente para enviar requisições HTTP,
mas apresentam uma série de limitações no que diz respeito à configurar corretamente suporte SSL
e fornece uma interface complicada para configurar coisas como
cabeçalhos. cURL fornece um conjunto de funcionalidades completo e expandido, mas, uma vez que não é uma
extensão padrão, muitas vezes não está presente. A extensão http sofre
o mesmo problema que cURL, bem como o fato de que ela tem, tradicionalmente, muito
menos exemplos de uso.

A maioria das bibliotecas de cliente HTTP modernas tendem a abstrair a implementação, para
garantir que elas possam trabalhar em qualquer ambiente onde são executadas, e em
quaisquer das camadas acima.

### Suporte HTTP do lado do servidor

PHP usa APIs do Servidor (SAPI) para interpretar a chegada das requisições HTTP, organizar a entrada,
e passar o manuseio para os scripts. O projeto original SAPI espelhado [Commom
Gateway Interface] (http://www.w3.org/CGI/), que iria organizar os dados da requisição
e colocá-los em variáveis ​​de ambiente antes de passar a delegação para um script;
o script, então, busca das variáveis ​​de ambiente a fim de processar
a requisição e retornar uma resposta.

O projeto SAPI do PHP abstrai fontes de entrada comuns, tais como cookies, argumentos de query string
e conteúdos POST codificado na url através de superglobais (`$_COOKIE`, `$_GET`,
e `$ _POST`, respectivamente), proporcionando uma camada de conveniência para os desenvolvedores web.

No lado da resposta da equação, o PHP foi originalmente desenvolvido como uma
linguagem de templates, e permite misturar HTML e PHP; quaisquer partes HTML
de um arquivo são imediatamente liberadas para o buffer de saída. As aplicações modernas e
frameworks evitam essa prática, pois ela pode levar a problemas no
que diz respeito à emissão de uma linha de status e/ou cabeçalhos de resposta; eles tendem a
agregar todos os cabeçalhos e conteúdo, e emiti-los de uma só vez, quando todos os outros
processamentos da aplicação estão completos. Um cuidado especial deve ser prestado para garantir
que os erros reportados e outras ações que enviam conteúdo para o buffer de saída
não liberem o buffer de saída.

## 3. Porque se Importar?

Mensagens HTTP são usadas ​​em uma ampla série de projetos PHP - em ambos clientes e
servidores. Em cada caso, observamos um ou mais dos seguintes padrões ou
situações:

1. Projetos usam superglobais do PHP diretamente.
2. Projetos irão criar implementações a partir do zero.
3. Projetos podem exigir uma biblioteca cliente/servidor HTTP específica que forneça
   implementações de mensagens HTTP.
4. Projetos podem criar adaptadores para implementações de mensagens HTTP comuns.

Como exemplos:

1. Apenas sobre qualquer aplicação que iniciou o desenvolvimento antes do surgimento dos
   frameworks, que inclui uma série de CMS muito populares, fórum, e sistemas de compras
   , têm historicamente utilizado superglobais.
2. Frameworks, como Symfony e Zend Framework, definem componentes HTTP
   que formam a base de suas camadas MVC; mesmo bibliotecas pequenas, de finalidade única
   como oauth2-server-php fornecem e exigem suas próprias implementações de requisição/resposta
   HTTP. Guzzle, Buzz, e outras implementações de cliente HTTP
   cada um cria suas próprias implementações de mensagem HTTP também.
3. Projetos como Silex, Stack e Drupal 8 têm dependências rígidas no
   kernel HTTP do Symfony. Qualquer SDK construído sobre Guzzle tem uma exigência rígida nas
   implementações de mensagem HTTP do Guzzle.
4. Projetos como Geocoder criam [adaptadores redundantes para bibliotecas comuns
   ](https://github.com/geocoder-php/Geocoder/tree/6a729c6869f55ad55ae641c74ac9ce7731635e6e/src/Geocoder/HttpAdapter).

O uso direto de superglobais tem uma série de preocupações. Em primeiro lugar, essas são
mutáveis, o que faz com que seja possível para bibliotecas e código alterar os valores,
e, assim, alterar o estado para a aplicação. Além disso, superglobais tornam os testes de unidade
e de integração difíceis e frágeis, levando a degradação da qualidade do
código.

No ecossistema atual dos frameworks que implementam abstrações de mensagem HTTP,
o resultado final é que os projetos não são capazes de interoperabilidade ou
polinização cruzada. A fim de consumir código visando um framework de
outro, a primeira ordem de negócio é a construção de uma camada ponte entre as
implementações de mensagem HTTP. No lado do cliente, se uma determinada biblioteca
não tem um adaptador que você pode utilizar, você precisa cobrir os pares requisição/resposta
, se quiser usar um adaptador de outra biblioteca.

Finalmente, quando se trata de respostas do lado do servidor, o PHP fica em seu próprio jeito: qualquer
conteúdo emitido antes de uma chamada para `header()` irá resultar em aquela chamada tornando-se um
no-op; dependendo das configurações dos erros reportados, muitas vezes isso pode significar que cabeçalhos
e/ou status de resposta não são corretamente enviados. Uma forma de contornar esse problema é
usar os recursos de saída de buffer do PHP, mas o aninhamento dos buffers de saída pode
tornar-se problemático e difícil de depurar. Frameworks e aplicações, portanto,
tendem a criar abstrações de resposta para a agregação de cabeçalhos e conteúdo que
podem ser emitidos de uma só vez - e essas abstrações são muitas vezes incompatíveis.

Portanto, o objetivo dessa proposta é abstrair as interfaces de requisição e resposta
tanto do lado do cliente quanto do servidor, a fim de promover a interoperabilidade entre
projetos. Se os projetos implementam essas interfaces, um nível razoável de
compatibilidade pode ser assumido ao adotar o código de diferentes bibliotecas.

Deve-se notar que o objetivo dessa proposta não é tornar obsoletas as
interfaces atuais utilizadas por bibliotecas PHP existentes. Essa proposta destina-se
a interoperabilidade entre os pacotes PHP com a finalidade de descrever mensagens
HTTP.

## 4. Escopo

### 4.1 Objetivos

* Fornecer as interfaces necessárias para descrever mensagens HTTP.
* Foco em aplicações práticas e usabilidade.
* Definir as interfaces para modelar todos os elementos da mensagem HTTP e
  especificações URI.
* Garantir que a API não impõe limites arbitrários sobre mensagens HTTP. Por
  exemplo, alguns corpos de mensagens HTTP podem ser muito grandes para armazenar na memória, por isso,
  devemos levar isso em consideração.
* Fornecer abstrações úteis tanto para lidar com solicitações de entrada para
  aplicações do lado do servidor quanto para o envio de requisições de saída em clientes HTTP.

### 4.2 Não-Objetivos

* A presente proposta não espera que todas as bibliotecas cliente HTTP ou frameworks do lado do servidor
  mudem suas interfaces para se conformar. É estritamente destinada para
  interoperabilidade.
* Embora a percepção de todos sobre o que é e o que não é um detalhe de implementação
  varia, essa proposta não deve impor detalhes de implementação. Como as
  RFCs 7230, 7231 e 3986 não forçam qualquer implementação particular,
  haverá uma certa quantidade de invenção necessária para descrever interfaces de mensagem HTTP
  no PHP.

## 5. Decisões de Projeto

### Projeto de Mensagem

A `MessageInterface` fornece assessores para os elementos comuns a todas as mensagens HTTP
, sendo eles de requisições ou respostas. Esses elementos incluem:

- Versão do protocolo HTTP (por exemplo, "1.0", "1.1")
- Cabeçalhos HTTP
- Corpo da mensagem HTTP

Interfaces mais específicas são usadas para descrever as requisições e respostas, e mais
especificamente no contexto de cada (lado do cliente vs. lado do servidor). Essas divisões são
inspiradas parcialmente pelo uso PHP existente, mas também por outras linguagens como
[Rack](https://rack.github.io) do Ruby,
[WSGI](https://www.python.org/dev/peps/pep-0333/) do Python,
[http package](http://golang.org/pkg/net/http/) do Go,
[http module](http://nodejs.org/api/http.html) do Node, etc.
