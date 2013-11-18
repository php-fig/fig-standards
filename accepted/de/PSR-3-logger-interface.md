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

- Jede Methode MUSS einen String als Nachricht akzeptieren oder ein Objekt, dass
  die `__toString()` Methode implementiert. `Implementierer` k&ouml;nnen eine
  gesonderte Behandlung f&uuml;r &uuml;bergebene Objekte implementieren. WENN NICHT
  MUSS das Objekt als String gecastet werden.

- Die Nachricht KANN Platzhalter beinhalten welche der `Implementierer` mit den 
  Werten aus dem Contaxt Array ersetzen KANN.

  Platzhalter M&Uuml;SSEN den Schl&uuml;sseln des Kontext Array entsprechen.

  Platzhalter-Namen M&Uuml;SSEN begrenzt sein durch eine am Beginn stehende 
  &ouml;ffnende geschungene Klammer `{` und am Ende durch eine schlie&szlig;ende 
  geschungene Klammer `}`. Es D&Uuml;RFEN KEINE Leerzeichen zwischen den Begrenzern
  und des Platzhalter-Namens stehen.

  Platzhalter-Namen SOLLTEN nur aus den Zeichen `A-Z`, `a-z`, `0-9`, Unterstrich
  `_` und Punkt `.` bestehen. Das Verwenden von anderen Zeichen is reserviert f&uuml;
  zuk&uuml;nftige &Auml;nderungen an der Spezifikation f&uuml;r Platzhalter.

  `Implementierer` K&Ouml;NNEN Platzhalter nutzen um verschiedenen Escaping 
  Strategien oder das &Uuml;bersetzen von Log-Eintr&auml;gen zur Anzeige zu 
  implementieren. `Anwender` SOLLTEN KEINE bereits escapte Platzhalter-Werte 
  verwenden, da der `Anwender` nicht im Vorfeld wissen kann wie die COntext Daten 
  angezeigt oder verarbeitet werden.

  Im Folgenden ist ein Beispiel f&uuml;r das Interpolieren des Kontext Arrays 
  als Referenz Beispiel zu sehen. Dieser Code stammt aus dem englischen Original
  und wird daher nicht &Uuml;bersetzt:

  ```php
  /**s
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

### 1.3 Kontext

- Jede Methode akzeptiert ein Array als Kontext Daten. Dies ist dazu gedacht,
  externe Daten zu halten die nicht direkt in die Nachricht passen bzw. formattiert 
  werden k&ouml;nnen. Das Array kann alles beinhalten.
  `Implementierer` M&Uuml;SSEN sicherstellen, dass die Kontext-Daten so 
  Nachsichtig wie m&ouml;glich behandelt werden. Ein Wert aus dem Kontext 
  DARF KEINE `Exception` werfen oder eine(n) `PHP Fehler, Warnung oder Notiz` 
  ausl&ouml;sen.

  - WENN ein `Exception` Objekt in Kontext-daten enthalten ist MUSS dieses mit 
  dem Schl&uuml;ssel `exception` &uuml;bergeben werden.
  Das Loggen von Exceptions ist ein g&auml;ngiges Vorgehen und erlaubt dem 
  `Implementierer` das Extrahieren des Stacktraces aus der `Exception` WENN 
  das Backend dies unterst&uuml;tzt. `Implementierer` M&Uuml;SSEN trotzdem pr&uuml;fen
  ob die Daten aus `'exception'` eine g&uuml;ltige `Exception` bevore sie den 
  Inhalt wie eine `Exception` behandeln, da in `'exception'` `alles` stehen KANN.

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
