Logger Interfaz
================

Este documento describe una interfaz común para todas las librerías de logging.

El objetivo principal es permitir a todas las librerías usar un
`Psr\Log\LoggerInterface` objecto y escribir logs con él de manera simple y
universal. Frameworks y CMSs que tengan necesidades específicas
PUEDEN extender la interfaz para su propio uso, pero DEBERÍA mantenerse
la compatibilidad con este documento. Eso asegura que las librerías de
terceros usadas en la aplicación puedan escribir en la aplicación
centralizada de logs.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The word `implementor` in this document is to be interpreted as someone
implementing the `LoggerInterface` in a log-related library or framework.
Users of loggers are refered to as `user`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Especificación
-----------------

### 1.1 Fundamental

- El `LoggerInterface` expone ocho métodos para la escritura de logs en los
ocho [RFC 5424][] niveles (debug, info, notice, warning, error, critical, alert,
emergency).

- Un noveno método, `log`, acepta un nivel de log como primer parámetro. La
llamada a este método con alguno de las constantes de nivel de log TIENE
QUE tener el mismo resultado que la llamada al método específico de dicho
nivel. Las llamadas a este método con un nivel no definido por esta
especificación TIENEN QUE lanzar una `Psr\Log\InvalidArgumentException`
si la implementación no conoce el nivel. Los usuarios NO DEBERÍAN usar
niveles específicos sin conocer de manera precisa que la implementación en
uso lo soporta.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Mensaje

- Cada método debería aceptar un string como mensaje, or un objeto con el
método `__toString()`. Las implementaciones PUEDEN tener un tratamiento
especial para el objecto uso. En tal caso, la implementación TIENE QUE
convertirlo en un string.

- El mensaje PUEDE contener marcadores que las implementaciones PUEDEN remplazar con valores del array de contexto.

Los nombres de los marcadores TIENEN QUE corresponder con las claves
del array de contexto.

Los nombres de los marcadores TIENEN QUE estar delimitadas con una
apertura de llave `{` y un cierre de llave `}`. NO TIENE QUE haber ningún
espacio en blanco entre los delimitadores (llaves) y el nombre del marcador.

Los nombres de los marcadores DEBERÍAN estar compuestos sólo por los
caracteres `A-Z`, `a-z`, `0-9`, barra baja `_`, and punto `.`. El uso de otros
caracteres está reservado para futuras modificaciones en los nombres de los marcadores en la espeficiación.

Las implementaciones PUEDEN usar marcadores para implementar varias
estrategias y traduciones para mostrar en los logs. Los usuarios NO DEBERÍAN pre-escapar valores de marcadores dado que ellos no pueden conocer en que contexto serán mostrados los datos.

El siguiente código es un ejemplo de implementación de interpolación de marcadores a modo de referencia únicamente:

  ```php
  /**
   * Interpolates context values into the message placeholders.
   */
  function interpolate($message, array $context = array())
  {
      // build a replacement array with braces around the context keys
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // interpolate replacement values into the message and return
      return strtr($message, $replace);
  }

  // a message with brace-delimited placeholder names
  $message = "User {username} created";

  // a context array of placeholder names => replacement values
  $context = array('username' => 'bolivar');

  // echoes "Username bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Contexto

- Every method accepts an array as context data. This is meant to hold any
  extraneous information that does not fit well in a string. The array can
  contain anything. Implementors MUST ensure they treat context data with
  as much lenience as possible. A given value in the context MUST NOT throw
  an exception nor raise any php error, warning or notice.

- If an `Exception` object is passed in the context data, it MUST be in the
  `'exception'` key. Logging exceptions is a common pattern and this allows
  implementors to extract a stack trace from the exception when the log
  backend supports it. Implementors MUST still verify that the `'exception'`
  key is actually an `Exception` before using it as such, as it MAY contain
  anything.

### 1.4 Helper classes and interfaces

- The `Psr\Log\AbstractLogger` class lets you implement the `LoggerInterface`
  very easily by extending it and implementing the generic `log` method.
  The other eight methods are forwarding the message and context to it.

- Similarly, using the `Psr\Log\LoggerTrait` only requires you to
  implement the generic `log` method. Note that since traits can not implement
  interfaces, in this case you still have to `implement LoggerInterface`.

- The `Psr\Log\NullLogger` is provided together with the interface. It MAY be
  used by users of the interface to provide a fall-back "black hole"
  implementation if no logger is given to them. However conditional logging
  may be a better approach if context data creation is expensive.

- The `Psr\Log\LoggerAwareInterface` only contains a
  `setLogger(LoggerInterface $logger)` method and can be used by frameworks to
  auto-wire arbitrary instances with a logger.

- The `Psr\Log\LoggerAwareTrait` trait can be used to implement the equivalent
  interface easily in any class. It gives you access to `$this->logger`.

- The `Psr\Log\LogLevel` class holds constants for the eight log levels.

2. Package
----------

The interfaces and classes described as well as relevant exception classes
and a test suite to verify your implementation is provided as part of the
[psr/log](https://packagist.org/packages/psr/log) package.

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Describes a logger instance
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data, the only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * for the full interface specification.
 */
interface LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * Logs with an arbitrary level.
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
 * Describes a logger-aware instance
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object
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
 * Describes log levels
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
