Logger Interface
================

Este documento descreve uma interface comum para bibliotecas de log.

O objetivo principal é permitir que bibliotecas recebam um objeto `Psr\Log\LoggerInterface` e escrevam logs de uma forma simples e universal. Frameworks e CMSs que têm necessidades próprias PODEM estender a interface para sua própria proposta, mas DEVERIAM manter a compatibilidade com este documento. Isso assegura que bibliotecas de terceiros possam escrever nos logs da aplicação de forma centralizada.

As palavras-chave "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", e "OPTIONAL" nesse documento devem ser interpretadas como descrito na [RFC 2119][].

A palavra `implementor` neste documento deve ser interpretada como alguém
implementando a `LoggerInterface` numa bilbioteca ou framework relacionado.
Usuários dos sistemas de logs (loggers) são referidos como `user`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Especificação
----------------

### 1.1 Básico

- A `LoggerInterface` expões oito métodos para escrever logs para os oito
  [RFC 5424][] níveis (debug, info, notice, warning, error, critical, alert,
  emergency).

- Um nono método, `log`, aceita um nível de log como primeiro argumento.
  Chamar este método com uma das constantes dos níveis de log DEVE ter o
  mesmo resultado ao chamar o método específico do nível. Chamar este método
  com um nível não definido na sua especificação DEVE lançar a exceção
  `Psr\Log\InvalidArgumentException` se a implementação não conhece o nível.
  Usuários NÃO DEVERIAM usar um´nível personalizado sem saber ao certo se a
  implementação atual irá suportá-lo.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

- Um nono método, `log`, aceita um nível de log como primeiro argumento. Ao chamar este método com uma das constantes dos níveis de log DEVE ter o mesmo resultado ao chamar o método específico do nível. Ao chamar este método com um nível não definido por esta especificação DEVE lançar uma `Psr\Log\InvalidArgumentException` se a implementação não conhece o nível. Usuários NÃO DEVERIAM usar um nível customizado sem saber ao certo se a implementação atual irá suportá-lo.

### 1.2 Mensagem

- Todo método aceita uma string como a mensagem ou um objeto com um método `__toString()`. Os implementadores PODEM ter um tratamento especial para os objetos passados. Se esse não for o caso, os implementadores DEVEM fazer a
conversão para string.

- A mensagem PODE conter placeholders que implementadores PODEM substituir com valores do array de contexto.

- Os nomes de placeholders DEVEM corresponder com as chaves no array de contexto.

- Os nomes de placeholders DEVEM ser delimitados com uma única chave de abertura `{` e uma única chave de fechamento `}`. NÃO DEVE haver nenhum espaço em branco entre o delimitador e o nome do placeholder.

- Os nomes de placeholders DEVERIAM ser compostos apenas de caracteres `A-Z`, `a-z`, `0-9`, underscore `_` e ponto `.`. O uso de outros caracteres é reservado para futuras modificações da especificação dos placeholders.

- Implementadores PODEM usar placeholders para implementar várias estratégias de escapamento ("escaping") e traduzir logs para exibir. Usuários NÃO DEVEM pré-escapar ("pre-escape") valores de placeholder, já que eles não podem saber em qual contexto os dados serão exibidos.

- O código abaixo é um exemplo de uma implementação de interpolação de placeholder fornecida apenas como referência:

  ```php
  /**
   * Interpola valores de contexto nos placeholders de mensagem
   */
  function interpolate($message, array $context = array())
  {
      // cria um array de substituição com chaves em torno das chaves de contexto
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // interpola valores de substituição na mensagem e retorna
      return strtr($message, $replace);
  }

  // uma mensagem com nomes de placeholders com delimitação de chaves ("brace-delimited")
  $message = "User {username} created";

  // um array de contexto de nomes de placeholders => valores de substituição
  $context = array('username' => 'bolivar');

  // exibe "User bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Contexto

- Cada método aceita um array como dado de contexto. Isto se destina a manter qualquer informação irrelevante que não se encaixa bem em uma string. O array pode conter qualquer coisa. Implementadores DEVEM garantir que eles tratem dados de contexto com a maior leniência quanto possível. Um dado valor no contexto NÃO DEVE lançar uma exception nem qualquer php error, warning ou notice.

- Se um objeto `Exception` é passado no contexto de dados, ele DEVE estar na chave `'exception'`. Exceções de log são um padrão comum e isso permite que implementadores extraiam um stack trace da exceção quando for suportado pelo
log backend. Implementadores DEVEM ainda verificar que a chave `'exception'` é realmente uma `Exception` antes de usá-la como tal, pois ela PODE conter
qualquer coisa.

### 1.4 Classes helper e interfaces

- A classe `Psr\Log\AbstractLogger` permite que você implemente a interface
`LoggerInterface` muito facilmente estendendo e implementando o método genérico `log`.
Os outros oito métodos são o encaminhamento da mensagem e contexto para isso.

- Similarmente, usando a `Psr\Log\LoggerTrait` apenas requer que você implemente o método genérico `log`. Note que uma vez que traits não podem
implementar interfaces, neste caso você ainda tem que implementar a
`LoggerInterface`.

- A `Psr\Log\NullLogger` é fornecida junto com a interface. Ela PODE ser usada por usuários da interface para fornecer uma implementação fall-back "black hole" caso nenhum logger lhe for dado. No entanto, a utização de log
condicional pode ser uma melhor abordagem se a criação de dados de contexto é custosa.

- A `Psr\Log\LoggerAwareInterface` apenas contém um método `setLogger(LoggerInterface $logger)` e pode ser usada por frameworks para criar
instâncias arbitrárias com um logger.

- A trait `Psr\Log\LoggerAwareTrait` pode ser usada para implementar a
interface equivalente facilmente em qualquer classe. Ela fornece acesso para
`$this->logger`.

- A classe `Psr\Log\LogLevel` mantém constantes para os oito níveis de log.

2. Pacote
----------

As interfaces e classes descritas, bem como as classes de exceptions relevantes e uma suíte de testes para verificar sua implementação, é fornecida como parte do pacote [psr/log](https://packagist.org/packages/psr/log).

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Descreve um exemplo de logger
 *
 * A mensagem DEVE ser uma string ou um objeto implementando __toString().
 *
 * A mensagem PODE conter placeholders na forma: {foo} onde foo será substituido
 * pelo dado de contexto na chave "foo".
 *
 * O array de contexto pode conter dados arbitrários, a única suposição que pode
 * ser feita pelos implementadores é que se uma instância de Exception é dada
 * para produzir um stack trace, ele DEVE estar em uma chave chamada "exception".
 *
 * Veja https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * para a especificação completa da interface.
 */
interface LoggerInterface
{
    /**
     * O sistema está inutilizável.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     *
     * Uma ação deve ser tomada imediatamente.
     *
     * Exemplo: Website inteiro fora do ar, banco de dados inacessível, etc.
     * Isso deveria disparar os alertas de SMS e acordar você.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Condições críticas.
     *
     * Exemplo: Componente da aplicação não disponível, exception inesperada.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Erros em tempo de execução que não requerem ação imediata, mas, normalmente,
     * deveriam ser gravados em log e monitorados.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Ocorrências excepcionais que não são erros.
     *
     * Exemplo: Uso de APIs obsoletas, mau uso de uma API, coisas indesejáveis
     * que não são necessariamente erradas.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Eventos normais porém significantes.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Eventos interessantes.
     *
     * Exemplo: Usuário faz login, SQL cria log.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Informação de debug detalhada.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * Logs com um nível arbitrário.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array());
}
```

4. `Psr\Log\LoggerAwareInterface`
---------------------------------

```php
<?php

namespace Psr\Log;

/**
 * Descreve uma instância da logger-aware
 */
interface LoggerAwareInterface
{
    /**
     * Define uma instância do logger no objeto
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger);
}
```

5. `Psr\Log\LogLevel`
---------------------

```php
<?php

namespace Psr\Log;

/**
 * Descreve níveis de log
 */
class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
}
```
