Interfaz de Logger
==================

Este documento describe una interfaz común para todas las librerías de `logging`.

El objetivo principal es permitir a todas las librerías usar un objecto `Psr\Log\LoggerInterface` y escribir logs con él de manera simple y universal. Frameworks y CMSs que tengan necesidades específicas PUEDEN extender la interfaz para su propio uso, pero DEBERÍA mantenerse la compatibilidad con este documento. Eso asegura que las librerías de terceros usadas en la aplicación pueden escribir en los logs centralizados de la aplicación.

Las palabras claves "TIENE QUE" ("MUST"/"SHALL"), "NO TIENE QUE" ("MUST NOT"/"SHALL NOT"), "NECESARIO" ("REQUIRED"), "DEBERÍA" ("SHOULD"), "NO DEBERÍA" ("SHOULD NOT"), "RECOMENDADO" ("RECOMMENDED"), "PUEDE" ("MAY") y "OPCIONAL" ("OPTIONAL") de este documento son una traducción de las palabras inglesas descritas en [RFC 2119][] y deben ser interpretadas de la siguiente manera: 
- TIENE QUE o REQUERIDO implica que es un requisito absoluto de la especificación.
- NO TIENE QUE conlleva la completa prohibición de la especificación.
- DEBERÍA o RECOMENDADO implica que pueden existen razones válidas para ignorar dicho elemento, pero las implicaciones que ello conlleva deben ser entendidas y sopesadas antes de elegir una opción diferente.
- NO DEBERÍA implica que pueden existir razones bajo ciertas circunstancias cuando el comportamiento es aceptable o incluso útil, pero todas las implicaciones deben ser entendidas cuidadosamente y sopesadas antes de implementar algún comportamiento descrito por esta etiqueta para ignorar dicho comportamiento.
- PUEDE u OPCIONAL implica que el elemento es puramente opcional. Cualquier proveedor puede elegir incluir dicho elemento porque crea que conlleva mejoras en su producto mientras otro puede elegir obviarlas. Una implementación que no incluya un opción particular TIENE QUE estar preparada para operar con otra implementación que incluya dicha opción, aunque implique limitar la funcionalidad. De la misma manera, una implementación que incluya una opción particular TIENE QUE estar preparada para otra que no la incluya (excepto, por supuesto, para la característica que la opción provea).

La palabra `implementación` en este documento tiene que ser interpretada como un objeto que implementa la interfaz `LoggerInterface`
en una librería de logs relacionada o un framework. Los usuarios de los logs son referidos como `usuario`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Especificación
-----------------

### 1.1 Fundamental

- El `LoggerInterface` expone ocho métodos para la escritura de logs en los ocho niveles definidos en el [RFC 5424][] (debug, info, notice, warning, error, critical, alert, emergency). [^1]

- Un noveno método, `log`, acepta un nivel de log como primer parámetro. La llamada a este método con alguna de las constantes de nivel de log, TIENE QUE tener el mismo resultado que la llamada al método específico de dicho nivel. Las llamadas a este método con un nivel no definido por esta norma TIENEN QUE lanzar una excepción de tipo `Psr\Log\InvalidArgumentException` si la implementación no conoce el nivel. Los usuarios NO DEBERÍAN usar niveles específicos sin conocer de manera precisa que la implementación en uso lo soporta.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Mensaje

- Cada método debería aceptar una cadena de texto como mensaje, o un objeto con el método `__toString()`. Las implementaciones PUEDEN tener un tratamiento especial para el objeto en uso. En este caso, la implementación TIENE QUE convertirlo a una cadena de texto.

- El mensaje PUEDE contener marcadores que las implementaciones PUEDEN remplazar con los valores de una array contextual.

  Los nombres de los marcadores TIENEN QUE corresponder con las claves del array contextual.

  Los nombres de los marcadores TIENEN QUE estar delimitados con una llave de apertura `{` y una llave de cierre `}`. NO TIENE QUE
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

- Cada método acepta un array de datos contextual. Esto se usa para contener cualquier información extraña que no encaje bien en una cadena de texto. El array puede contener cualquier cosa. Las implementaciones TIENEN QUE asegurar que el tratamiento de los datos de contexto se hace con la mayor claridad posible. Un valor dado en el contexto NO TIENE QUE lanzar ninguna excepción, error, warning o notice de PHP.

- Si un objecto `Exception` es pasado en el array contextual, TIENE QUE ir en la clave `'exception'`. Mostrar excepciones en el log es un patrón común y permite a las implementaciones extraer la traza de la pila del error cuando la aplicación de log lo soporte. Las implementaciones TIENEN QUE verificar que la clave `'exception'` contiene una `Exception` antes de usarla como tal, dado que PUEDE contener cualquier cosa.

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
 * Describe una instancia de logger
 * 
 * El mensaje TIENE QUE ser una cadena o un objecto que implemente __toString().
 *
 * El mensaje PUEDE contener marcadores con el formato: {foo} donde foo
 * será reemplazado por el valor de la clave "foo" en el array de contexto.
 *
 * El array de contexto puede contener cualquier dato arbitrario de datos, la
 * única suposición que pueden hacer las implementaciones es si se provee
 * una instancia de `Exception` para producir una pila de trazas, ésta TIENE QUE
 * estar en la clave "exception".
 *
 * Revise https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * para obtener la especificación completa de esta interfaz.
 */
interface LoggerInterface
{
    /**
     * El sistema no está disponible.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * Debe actuarse de manera inmediata.
     *
     * Ejemplo: Sitio web caído, base de datos no disponible, etc. Esto debería
     * mandar un SMS de alerta y despertarle.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Condiciones críticas.
     *
     * Ejemplo: Componente de la aplicación no disponible, excepción inesperada.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Errores en tiempo de ejecución que no requieren de una acción inmediata
     * pero que deberían ser imprimidas en el log y monitoreadas.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Evento excepcional pero que no implica error sino adevertencia.
     *
     * Ejemplo: Uso de APIs obsoletas, mal uso de un API, cosas indeseables que
     * no son necesariamente un error.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Eventos normales pero significantes.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Evento interesante.
     *
     * Ejemplo: Acceso de usuarios, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Información detallada de debug.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * Imprime un log con nivel arbitrario.
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
 * Describe un objecto que soporta loggers.
 */
interface LoggerAwareInterface
{
    /**
     * Define una instancia de log en el objeto
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
 * Describe los niveles de log
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
Notas
--------

[^1]: Los términos expresados en inglés debug, info, notice, warning, error, critical, alert y emergency se traducen literalmente al español como depuración, información, nota, advertencia, error, crítico, alerta y emergencia respectivamente, e implican un nivel de gravedad de menor a mayor.

