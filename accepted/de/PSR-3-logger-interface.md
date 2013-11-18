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

### 1.4 Helfer Klassen und Interfaces

- Die Klasse `Psr\Log\AbstractLogger` erm&ouml;glicht ein sehr einfaches 
  Implementieren der Schnittstelle `Psr\Log\LoggerInterface`. Dazu muss die 
  Implementierung nur von `Psr\Log\AbstractLogger` ableiten und die generische 
  Methode `log` implementieren.
  Die anderen acht Methoden leiten die Nachricht und den Kontext via des entspechenden
  Log-Levels and diese Methode weiter.

- &Auml;hnlich dazu kann `Psr\Log\LoggerTrait` verwendet werden. Auch hier muss
  nur die generische `log` Methode implementiert werden und in diesem Fall explizit
  die Schnittstelle `Psr\Log\LoggerInterface` implementiert werden.

- Die Klasse `Psr\Log\NullLogger` wird ebenfalls mitgeliefert. Diese KANN vom 
  `Anwender` verwendet werden um z.B. als Fallback zu dienen, WENN KEIN Logger
  bereit steht. Normalerweise KANN bedingtes Loggen einen besseren Ansatz sein, 
  da das Erstellen der Kontext daten teuer, zeitaufw&auml;ndig sein KANN.

- Die Schnittstelle `Psr\Log\LoggerAwareInterface` beinhaltet nur eine Methode
  `setLogger(LoggerInterface $logger)` und kann dazu genutzt werden, dass Frameworks 
  automatisch einen Logger an beliebige Instanzen binden k&ouml;nnen.

- Das Trait `Psr\Log\LoggerAwareTrait` kann gleich wie `Psr\Log\LoggerAwareInterface`
  dazu verwendet werden &uuml;berall einfach eingebunden zu werden und stellt den 
  Zugriff auf `$this->logger` bereit.

-  Die Klasse `Psr\Log\LogLevel` stellt die acht Log-Level als Konstanten zur 
  Verf&uuml;gung.

2. Paket
----------

Die Schnittstellen und Klassen die in diesem Dokument beschrieben werden, ebenso 
wie die erw&auml;hnten `Exceptions` und eine TestSuite um ihre Implementierung zu
pr&uuml;fen sind Teils des pakets [psr/log] (https://packagist.org/packages/psr/log).

3. - 5. Quellcode
----------

Um hier keine veralten Quellcodes anzuzeigen wird an dieser Stelle auf das Repository:
[psr/log] verwiesen. Das Repository beinhaltet alle Quellen aus diesem Dokument.