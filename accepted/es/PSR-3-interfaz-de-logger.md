Interfaz de Logger
==================

Este documento describe una interfaz común para todas las librerías de `logging`.

En el documento original se usa el RFC 2119 para el uso de las palabras MUST, MUST NOT, SHOULD, SOULD NOT y MAY. Para que la traducción sea lo más fiel posible, se traducira siempre MUST como el verbo deber en presente (DEBE, DEBEN), SHOULD como el verbo deber en condicional (DEBERÍA, DEBERÍAN) y el verbo MAY como el verbo PODER.

El objetivo principal es permitir a todas las librerías usar un objecto `Psr\Log\LoggerInterface` y escribir logs con él de manera simple y universal. Frameworks y CMSs que tengan necesidades específicas PUEDEN extender la interfaz para su propio uso, pero DEBERÍA mantenerse la compatibilidad con este documento. Eso asegura que las librerías de terceros usadas en la aplicación pueden escribir en los logs centralizados de la aplicación.

La palabra `implementación` en este documento tiene que ser interpretada como un objeto que implementa la interfaz `LoggerInterface`
en una librería de logs relacionada o un framework. Los usuarios de los logs son referidos como `usuario`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Especificación
-----------------

### 1.1 Fundamental

- El `LoggerInterface` expone ocho métodos para la escritura de logs en los ocho niveles definidos en el [RFC 5424][] (debug, info, notice, warning, error, critical, alert, emergency). [^1]

- Un noveno método, `log`, acepta un nivel de log como primer parámetro. La llamada a este método con alguna de las constantes de nivel de log, DEBE tener el mismo resultado que la llamada al método específico de dicho nivel. Las llamadas a este método con un nivel no definido por esta norma DEBEN lanzar una excepción de tipo `Psr\Log\InvalidArgumentException` si la implementación no conoce el nivel. Los usuarios NO DEBERÍAN usar niveles específicos sin conocer de manera precisa que la implementación en uso lo soporta.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Mensaje

- Cada método debería aceptar una cadena de texto como mensaje, o un objeto con el método `__toString()`. Las implementaciones PUEDEN tener un tratamiento especial para el objeto en uso. En este caso, la implementación DEBE convertirlo a una cadena de texto.

- El mensaje PUEDE contener marcadores que las implementaciones PUEDEN remplazar con los valores de una array contextual.

  Los nombres de los marcadores DEBEN corresponder con las claves del array contextual.

  Los nombres de los marcadores DEBEN estar delimitados con una llave de apertura `{` y una llave de cierre `}`. NO DEBE
  haber ningún espacio en blanco entre los delimitadores (llaves) y el nombre del marcador.

  Los nombres de los marcadores DEBERÍAN estar compuestos sólo por los caracteres `A-Z`, `a-z`, `0-9`, guión bajo `_`, y punto `.`. El uso de otros caracteres está reservado para futuras modificaciones en los nombres de los marcadores en la norma.

  Las implementaciones PUEDEN usar marcadores para implementar varias estrategias de escape y traducción para mostrar en los logs.
  Los usuarios NO DEBERÍAN pre-escapar valores de marcadores, dado que no podrían conocer en qué contexto serían mostrados los datos.

  El siguiente código es un ejemplo de implementación de interpolación de marcadores únicamente a modo de referencia:

  ```php
  /**
   * Reemplaza los valores de contexto por los marcadores del mensaje.
   */
  function interpolacion($mensaje, array $contexto = array())
  {
      // crea un array de sustituciones con llaves alrededor de cada clave
      // del array contextual
      $reemplazo = array();
      foreach ($contexto as $clave => $valor) {
          $reemplazo['{' . $clave . '}'] = $valor;
      }

      // Reemplaza los valores dentro del mensaje y lo devuelve
      return strtr($mensaje, $reemplazo);
  }

  // un mensaje con un marcador delimitado por llaves
  $mensaje = "Usuario {nombre_de_usuario} creado";

  // un array contextual con los marcadores => valores de sustitución
  $contexto = array('nombre_de_usuario' => 'Pedro');

  // imprime "Usuario Pedro creado"
  echo interpolacion($mensaje, $contexto);
  ```

### 1.3 Contexto

- Cada método acepta un array de datos contextual. Esto se usa para contener cualquier información extraña que no encaje bien en una cadena de texto. El array puede contener cualquier cosa. Las implementaciones DEBEN asegurar que el tratamiento de los datos de contexto se hace con la mayor claridad posible. Un valor dado en el contexto NO DEBE lanzar ninguna excepción, error, warning o notice de PHP.

- Si un objecto `Exception` es pasado en el array contextual, DEBE ir en la clave `'exception'`. Mostrar excepciones en el log es un patrón común y permite a las implementaciones extraer la traza de la pila del error cuando la aplicación de log lo soporte. Las implementaciones DEBEN verificar que la clave `'exception'` contiene una `Exception` antes de usarla como tal, dado que PUEDE contener cualquier cosa.

### 1.4 Clases de ayuda e Interfaces

- La clase `Psr\Log\AbstractLogger` permite implementar la interfaz `LoggerInterface` de manera sencilla extendiéndola e implementado el método genérico `log`. Los otros ocho métodos realizan una llamada con el mensaje y el array contextual a este método.

- De manera similar, usando `Psr\Log\LoggerTrait` sólo necesita implementar el método genérico `log`. Tenga en cuenta que los traits no permiten implementar interfaces, en ese caso tendrá que hacer un `implement LoggerInterface`.

- La clase `Psr\Log\NullLogger` está incluida junto con la interfaz. PUEDE ser usada por los usuarios de la interfaz para proveer un escape seguro en la implementación si no se ha provisto de un logger. De todas formas, imprimir logs de manera condicional puede ser una mejor práctica si el coste de la creación de los datos de contexto es alta.

- La clase `Psr\Log\LoggerAwareInterface` sólo contiene el método `setLogger(LoggerInterface $logger)` y puede ser usada por frameworks para auto enlazar de manera arbitraria instancias de un logger.

- El trait `Psr\Log\LoggerAwareTrait` puede ser usado para implementar el equivalente a la interfaz de manera sencilla en cualquier clase. Le provee acceso a `$this->logger`.

- La clase `Psr\Log\LogLevel` contiene las constantes para los ocho niveles de log.

2. Paquete
----------

Las interfaces y clases descritas, las clases de excepción relevantes y una serie de test para comprobar el funcionamiento de su
implementación se proveen como parte del paquete [psr/log](https://packagist.org/packages/psr/log)


3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Describes a logger instance.
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data. The only assumption that
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
     * @param array  $context
     *
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
     * @param array  $context
     *
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
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
     * @param array  $context
     *
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
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
 * Describes a logger-aware instance.
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
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
 * Describes log levels.
 */
class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';
}
```

Notas
--------

[^1]: Los términos expresados en inglés debug, info, notice, warning, error, critical, alert y emergency se traducen literalmente al español como depuración, información, nota, advertencia, error, crítico, alerta y emergencia respectivamente, e implican un nivel de gravedad de menor a mayor.

