Logger Interface
================

Diese Dokument beschreibt eine generische Schnittestelle f&uuml;r Bibliotheken die
Logging anbieten möchten.

Das Hauptziel ist, Bibliotheken zu erm&ouml;glichen eine Instanz von 
`Psr\Log\LoggerInterface` zu erhalten und auf einem einfachen und universellen 
Weg aufdieses Interface Log-Eintr&auml;ge zu schreiben. Frameworks und Content 
Management Systeme, welche ihre eigenen Anforderungen haben K&Ouml;NNEN das Interface
erweitern, SOLLTEN jedoch die Kompatibilit&auml;t zu diesem Dokument erhalten. Dies 
sichert den Anwendungen die drittanbieter Bibliotheken verwenden zu, dass in die 
anwendungszentralen Logs geschrieben werden kann.

Die Schl&uuml;sselw&ouml;rter "M&Uuml;SSEN", "D&Uuml;RFEN NICHT", "ERFORDERT", "SOLLEN", 
"SOLLEN NICHT", ", WENN", "WENN NICHT", "EMPFOHLEN", "K&Ouml;NNEN" und "OPTIONAL", die
in diesem Dokument verwendet werden, sind zu verstehen wie in RFC 2119 [RFC 2119][] 
beschrieben.

Was Wort `Implementierer` in diesem Dokument ist zu verstehen als: eine 
Person/Organisation welche das hier erw&auml;hnte `Psr\Log\LoggerInterface` in einem 
Framework oder einer Bibliothek, welche mit Logging in Zusammenhang steht 
implemenitert.

Anwender eines Loggers werden als `Anwender` bezeichnet.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Spezifikation
-----------------

### 1.1 Grundlegendes

- Das `Psr\Log\LoggerInterface` ver&ouml;ffentlicht acht Methoden um in Logs zu 
  schreibeneight methods. Diese acht Methoden bilden die acht Log-Levels aus
  [RFC 5424][] (debug, info, notice, warning, error, critical, alert, emergency) 
  ab.

- Es wir deine neunte Methode `log` ver&ouml;ffentlicht welche als erstes 
  Argument eines der acht Log-Levels akzeptiert. Der Aufruf dieser Methode mit 
  einem der acht Log-Levels MUSS das selbe Ergebnis haben als wenn man die 
  dem Log-Level entsprechende Methode aufruft. Wird diese Methode mit einem 
  Log-Level aufgerufen, dass nicht einem der acht Log-Levels aus [RFC 5424][] 
  entspricht und von der Implementierung nicht verstanden wird MUSS eine 
  `Psr\Log\InvalidArgumentException` geworfen werden.
  `Anwender` SOLLTEN KEINE benutzerspezifischen Log-Levels verwenden ohne zu wissen
  ob diese von der aktuellen Implementierung unterst&uuml; werden.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Nachricht

- Every method accepts a string as the message, or an object with a
  `__toString()` method. Implementors MAY have special handling for the passed
  objects. If that is not the case, implementors MUST cast it to a string.

- The message MAY contain placeholders which implementors MAY replace with
  values from the context array.

  Placeholder names MUST correspond to keys in the context array.

  Placeholder names MUST be delimited with a single opening brace `{` and
  a single closing brace `}`. There MUST NOT be any whitespace between the
  delimiters and the placeholder name.

  Placeholder names SHOULD be composed only of the characters `A-Z`, `a-z`,
  `0-9`, underscore `_`, and period `.`. The use of other characters is
  reserved for future modifications of the placeholders specification.

  Implementors MAY use placeholders to implement various escaping strategies
  and translate logs for display. Users SHOULD NOT pre-escape placeholder
  values since they can not know in which context the data will be displayed.

  The following is an example implementation of placeholder interpolation
  provided for reference purposes only:

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

  // echoes "User bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Context

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
