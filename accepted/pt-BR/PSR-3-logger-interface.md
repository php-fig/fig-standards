Interface de Log
================

Este documento descreve uma interface comum para bibliotecas de logging.

O principal objetivo deste documento é permitir que bibliotecas recebam um objeto
`Psr\Log\LoggerInterface` e escrevam logs para ele de uma maneira simples
e universal. Frameworks e CMSs que têm necessidades específicas PODEM
estender a interface para os seus próprios fins, mas DEVEM permanecer
compatíveis com este documento. Isto garante que as bibliotecas de terceiros
utilizadas possam escrever logs na aplicação principal.

As palavras chave "DEVE", "NÃO DEVE", "OBRIGATÓRIO", "TEM QUE", "NÃO TEM QUE", "DEVERIA", "NÃO DEVERIA", "RECOMENDADO", "PODE" e "OPCIONAL" existentes neste documento devem ser interpretadas como são descritas na [RFC 2119][].

A palavra `implementador` deste documento DEVE ser interpretada como
uma implementação de `LoggerInterface` numa biblioteca ou framework.
Usuários de loggers serão referidos como `usuários`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Especificação
----------------

### 1.1 Básico

- O `LoggerInterface` possui oito métodos para gravar logs para os 8
  níveis da [RFC 5424][] (debug, info, notice, warning, error, critical, alert,
  emergency).

- Um nono método, `log`, aceita um nível de log como primeiro argumento. Chamar
  este método com uma das constantes de nível de log DEVE ter o mesmo resultado
  que chamar um método especifico do nível. Chamar este método com um nível
  não definido por esta especificação DEVE lançar uma `Psr\Log\InvalidArgumentException`
  se a implementação não conhecer o nível. Usuários NÃO DEVEM usar um
  nível personalizado sem ter certeza de que a implementação atual o suporta.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Mensagem

- Todo método aceita uma string como mensagem, ou um objeto com um método
  `__toString()`. Implementadores PODEM ter um tratamento especial para os objetos
  passados. Se não for o caso, devem convertê-los pra uma string.

- A mensagem PODE conter placeholders dos quais os implementadores PODEM substituir
  por valores de um array de contexto.

  Os nomes dos placeholders DEVEM corresponder com os das chaves do array de contexto.

  Nomes de placeholders DEVEM ser delimitados com uma única chave de abertura
  `{` e uma única chave de fechamento `}`. NÃO DEVERÃO existir quaisquer espaços em branco
  entre os delimitadores e o nome do placeholder.

  Nomes de placeholders DEVEM ser compostos apenas dos caracteres `A-Z`, `a-z`,
  `0-9`, sublinhado `_`, e ponto `.`. O uso de outros caracteres é reservado
  para futuras modificações da especificação dos placeholders.

  Implementadores PODEM utilizar placeholders para implementar várias estratégias de saída de dados
  e traduzir os logs para exibição. Os usuários NÃO DEVEM fazer a saída dos valores dos placeholders antes,
  uma vez que não é possível saber em qual contexto os dados serão exibidos.

  O que se segue é uma implementação de exemplo para interpolação de placeholders fornecidos
  para fins apenas de referência:

  ```php
  /**
   * Realiza a interpolação dos valores do array de contexto nos placeholders da mensagem.
   */
  function interpolate($message, array $context = array())
  {
      // constrói um array substitutivo com chaves(`{}`) em torno da chave de contexto do array
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // interpola os valores do array de substituição na mensagem e retorna
      return strtr($message, $replace);
  }

  // uma mensagem com placeholder delimitado por chaves
  $message = "User {username} created";

  // um array de contexto de nome do placeholder => valor de substituição
  $context = array('username' => 'bolivar');

  // exibe "User bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Contexto

- Todo método aceita um array como dados do contexto. Isso serve para segurar
  qualquer informação estranha que não se ajuste bem a uma string. O array pode
  conter qualquer tipo de valor. Implementadores devem garantir o tratamento dos dados do contexto
  com a maior leniência possível. Um valor no contexto NÃO DEVE lançar
  uma exceção nem lançar qualquer erro, aviso ou notificação.

- Se um objeto`Exception` é passado no contexto de dados, este DEVE estar na chave
  `'exception'`. Realizar logging de exceções é um padrão comum e isso permite aos implementadores
  extrair uma pilha de rastreamento da exceção quando o backend do log suportar isto.
  Implementadores DEVEM ainda verificar se a chave `'exception'` é de fato uma `Exception`
  antes de usá-la como tal, pois esta PODE conter qualquer coisa.

### 1.4 Classes e Interfaces Auxiliares

- A classe `Psr\Log\AbstractLogger` permite você implementar a `LoggerInterface`
  facilmente herdando-a e implementando o método `log` genérico.
  Os outros oito métodos encaminharão a mensagem e o contexto a ele.

- Da mesma forma, o uso do `Psr\Log\LoggerTrait` requer apenas que você implemente
  o método genérico `log`. Atente-se que traits não podem implementar interfaces, neste
  caso você ainda terá de implementar `LoggerInterface`.

- A `Psr\Log\NullLogger` é fornecida juntamente com a interface. Ela PODE ser
  usada por usuários da interface para fornecer uma implementação de "buraco negro"
  de fall-back se nenhum logger é dado para eles. No entanto, logging condicional
  pode ser uma melhor abordagem se a criação de dados de contexto for muito custosa.

- A `Psr\Log\LoggerAwareInterface` contém apenas um método
  `setLogger(LoggerInterface $logger)` que pode ser usado por frameworks para
  interligar automaticamente instâncias arbitrárias com um logger.

- A trait `Psr\Log\LoggerAwareTrait` pode ser usada para implementar a interface
  equivalente facilmente em qualquer classe. Isso dá a você o acesso à `$this->logger`.

- A classe `Psr\Log\LogLevel` contém as constantes para os oito níveis de logs.

2. Pacote
----------

As interfaces e classes descritas assim como classes de exceção relevantes e uma suite de testes para verificar sua implementação é fornecida como parte do pacote [psr/log](https://packagist.org/packages/psr/log).

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Descreve uma instância de logger
 *
 * A mensagem DEVE ser uma string ou objeto implementando __toString().
 *
 * A mensagem PODE conter placeholders no seguinte formato: {foo}, sendo que foo
 * será substituído pelo dado de contexto presente na chave "foo".
 *
 * O array de contexto pode conter dados arbitrários, sendo que a única hipótese que pode ser
 * feita pelos implementadores é que se uma instância de Exception for dada
 * para produzir um rastreamento de pilha, esta DEVE estar na chave nomeada "exception".
 *
 * Ver https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * para a especificação completa da interface.
 */
interface LoggerInterface
{
    /**
     * Sistema está inutilizado.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * Ação deve ser tomada imediatamente.
     *
     * Exemplo: Todo o website fora do ar, base de dados indisponível, etc.
     * Isto deve disparar o gatilho para lhe alertar por SMS e te acordar.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Condições críticas.
     *
     * Exemplo: Componente da aplicação indisponível, exception não esperada.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Erros em tempo de execução que não requerem ação imediata mas que devem
     * tipicamente ser registrados no log e monitorados.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Ocorrências excepcionais que não sejam erros.
     *
     * Exemplo: Uso de APIs depreciadas, mal uso de API, coisas indesejáveis
     * mas que não estejam erradas.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Eventos normais, porém significantes
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Eventos interessantes.
     *
     * Exemplo: Logins de usuários, logs de SQL.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Informação detalhada para debug.
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
 * Descreve uma instância de logger-aware.
 */
interface LoggerAwareInterface
{
    /**
     * Coloca uma instância de logger no objeto.
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
 * Descreve os níveis de log.
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
