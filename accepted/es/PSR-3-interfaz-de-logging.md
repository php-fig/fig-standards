Interfaz de logging
===================

Este documento describe una interfaz común para el logging de librerías.

El objetivo principal es permitir a las librerias recibir un objeto de 
tipo `Psr\Log\LoggerInterface` y escribir logs en el de forma simple y 
universal. Los Frameworks y CMSs con otras necesidades PUEDEN extender la
interfaz para su propio propósito, pero DEBE seguir siendo compatible con
este documento. Para asegurar que las librerias de terceros que se utilicen
en la aplicación puedan seguir escribiendo en los logs centralizados de la 
aplicación.

Las palabras clave "DEBE/MUST", "NO DEBE/MUST NOT", "REQUERIDO/REQUIRED", 
"SE DEBE/SHALL", "NO SE DEBE/SHALL NOT", "SE DEBERÍA/SHOULD", "NO SE DEBERÍA
/SHOULD NOT", "RECOMENDADO/RECOMMENDED", "PUEDE/MAY", y "OPCIONAL/OPTIONAL" 
de este documento se deben interpretar como se describe en el [RFC 2119][].

La palabra `implementor` en este documento se debe interpretar como alguien que 
implementa la interfaz `LoggerInterface` en una librería relacionada con el 
logging o framework. `user` se refiere al usuario de loggers.

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt

1. Especificación
-----------------

### 1.1 Conceptos básicos

- La interfaz `LoggerInterface` expone ocho métodos para escribir ocho niveles
  de registros [RFC 5424][] (debug, info, notice, warning, error, critical, 
  alert, emergency).

- Un noveno método, `log`, acepta el nivel de registro como primer argumento.
  Llamando a este método con una constante de nivel de registro DEBE resultar
  en el mismo resultado que llamar al método específico del nivel. Llamando 
  a este método con un nivel no definido por esta especificación DEBE lanzar
  una excepción `Psr\Log\InvalidArgumentException`, si la implenetación no
  conoce el nivel. NO SE DEBE utilizar un nivel propio por el usuario, sin 
  tener la certeza de que la implementación lo soporta.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Mensaje

- Todos los métodos aceptan una cadena de texto como el mensaje, o un objeto 
  con el método `__toString()`. Los implementors PUEDEN hacer un manejo especial 
  para el objeto. Si este no es el caso, el implementor DEBE realizar un cast
  del objeto a cadena de texto.

- El mensaje PUEDE contener marcadores que el implementor PUEDE reemplazar con 
  valores del array del contexto.

  Los nombres de los marcadores DEBEN corresponder con claves del array del 
  contexto.

  Los nombres de los marcadores DEBEN estar delimitados por una llave de apertura
  `{` y una llave de cierre `}`. NO DEBE haber ningún espacio en blanco entra los
  delimitadores del nombre del marcador.

  Los nombres del marcador DEBERÍAN estar compuestos solo por caracteres `A-Z`, 
  `a-z`, `0-9`, guiones bajos `_`, y punto `.`. El uso de otros caracteres está 
  reservado para futuras modificaciones de la especificación de los marcadores.

  Los implementors PUEDEN utilizar los marcadores para implementar varias
  estrategias de escape y traducción de registros para mostrarlos. Los usuarios
  NO DEBERÍAN pre-escapar valores de marcadores hasta que no conozcan el contexto
  en el que van a ser mostrados los datos.

  A continuación se muestra un ejemplo de la implementación de un marcador 
  interpolado proveido, sólo con propósitos de referencia.

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

- Cada método acepta un array como datos de contexto. Esto quiere decir que 
  contendrá cualquier información exraña que no pueda ser representada como 
  una cadena de texto. El array puede contener cualquier cosa. Los implementors
  DEBEN asegurarse que tratan la información de contexto con tanta exigencia
  como sea posible. Un valor proporcionado en el contexto NO DEBE lanzar una
  excepción, ni producir ningún error, aviso o notificación de PHP.

- Si en los datos de contexto se pasa un objeto de tipo `Exception`, este DEBE
  estar en la clave `'exception'`. El registro de excepciones es un patron
  común y este, debe permitir a los implementors extraer la traza de la pila
  de la excepción cuando el log backend lo soporte. Los implementors DEBEN
  verificar que la clave `'exception'` es una `Exception` antes de utilizarla
  como tal, ya que esta PUEDE contener cualquier cosa.

### 1.4 Clases e interfaces Helper

- La clase `Psr\Log\AbstractLogger` te permite implementar la interfaz
  `LoggerInterface` de forma muy sencilla extendiendo e implementando el
  método generico `log`. Los otros ocho métodos reenvian el mensaje y el contexto
  a la misma.

- De forma similar, utilizando el `Psr\Log\LoggerTrait` solo requiere que se
  implemente el método generico `log`. Se debe tener en cuenta que, mientras
  que los tratis no puedan implementar interfaces, en ese caso se debe seguir
  implementando `LoggerInterface`.

- El `Psr\Log\NullLoger` se proporciona junto con la interfaz. Esta PUEDE ser 
  usada por los usuarios de la interfaz para proporcionar la implementación de
  un método de fallo "black hole" si no se proporciona un logger. Sin embargo
  el registro condicional puede ser una aproximación mejor si la creación
  de los datos de contexto es muy costosa.

- El `Psr\Log\LoggerAwareInterface` solo contiene un método
  `setLogger(LoggerInterface $logger)` que puede ser utilizado por los frameworks
  para autodirigir de forma arbitraria las instancias con el logger.

- El trait `Psr\Log\LoggerAwareTrait` puede ser utilizado para implementar la
  interfaz equivalente en cualquier clase. Este proporciona acceso a 
  `$this->logger`.

- La clase `Psr\Log\LogLevel` contiene las constantes de los ocho niveles de
  registro.

2. Paquete
----------

Las interfaces y las clases descritas, así como las clases excepciones y un
conjunto de pruebas para verificar su implementación son proporcionadas como
parte del paquete [psr/log](https://packagist.org/packages/psr/log).

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
