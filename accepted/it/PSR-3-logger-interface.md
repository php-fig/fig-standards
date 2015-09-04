Interfaccia Logger
================

Questo documento descrive un'interfaccia comune per le librerie di log.

L'obiettivo principale è quello di permettere alle librerie di ricevere
un oggetto `Psr\Log\LoggerInterface` e di usarlo per scrivere log in modo semplice 
ed universale. Framework e CMS che abbiano necessità specifiche POSSONO estendere 
l'interfaccia per i loro scopi, ma DOVREBBERO rimanere compatibili con questo 
documento. Questo assicura che librerie di terze parti che un'applicazione
potrebbe usare possano scrivere sui log centralizzati dell'applicazione.

Le parole "DEVE/DEVONO/NECESSARIO(I)" ("MUST", "SHALL" O "REQUIRED"),
"NON DEVE/NON DEVONO" ("MUST NOT" O "SHALL NOT"), "DOVREBBE/DOVREBBERO/RACCOMANDATO(I)"
("SHOULD") "NON DOVREBBE/NON DOVREBBERO" ("SHOULD NOT"), "PUO'/POSSONO" ("MAY") e
"OPZIONALE" ("OPTIONAL") in questo documento devono essere interpretate come
descritto nella [RFC 2119][].

La parola `implementatore` in questo documento è da interpretare come colui
che implementa l'interfaccia `LoggerInterface` in una libreria o framework di log.
Gli utilizzatori dei logger saranno indicati con il termine `utente`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specifiche
-----------------

### 1.1 Informazioni di base

- L'interfaccia `LoggerInterface` espone otto metodi per scrivere log negli otto
  livelli dell'[RFC 5424][] (debug, info, notice, warning, error, critical, alert,
  emergency).

- Un nono metodo, `log`, accetta un livello di log come primo argomento. Chiamare
  questo metodo con una delle costanti del livello di log DEVE avere lo stesso
  risultato di chiamare il metodo specifico per quel livello. Chiamare il metodo
  con un livello non definito in queste specifiche DEVE lanciare una eccezione
  `Psr\Log\InvalidArgumentException` se l'implementazione non conosce il livello.
  Gli utenti NON DOVREBBERO usare un livello personalizzato senza essere sicuri
  che l'implementazione corrente lo supporti.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Messaggio

- Ogni metodo accetta una stringa come messaggio, o un oggetto con un metodo
  `__toString()`. Gli implementatori POSSONO avere una gestione particolare
  per gli oggetti passati. In caso contrario, gli implementatori DEVONO
  convertire l'oggetto in una stringa.

- Il messaggio PUO' contenere segnaposto che gli implementatori POSSONO
  sostituire con valori dall'array di contesto.

  I nomi dei segnaposto DEVONO corrispondere alle chiavi dell'array di contesto.

  I nomi dei segnaposto DEVONO essere delimitati da una singola parentesi graffa
  aperta `{` e da una singola parentesi graffa chiusa `}`. NON DEVONO esserci
  spaziature tra i delimitatori e il nome del segnaposto.

  I nomi dei segnaposto DOVREBBERO essere composti dai soli caratteri `A-Z`, `a-z`,
  `0-9`, underscore `_`, e punto `.`. L'uso di altri caratteri è riservato per
  future modifiche alle specifiche dei segnaposto.

  Gli implementatori POSSONO usare i segnaposto per implementare varie strategie
  di escape e traduzione dei log per la visualizzazione. Gli utenti NON DOVREBBERO
  eseguire in anticipo escape sui valori dei segnaposto, poiché non possono sapere
  in quale contesto i dati saranno visualizzati.

  Il seguente è un esempio di implementazione di un'interpolazione di segnaposto,
  fornita a solo scopo di riferimento:

  ```php
  /**
   * Interpola i valori di contesto nei segnaposto del messaggio.
   */
  function interpolate($message, array $context = array())
  {
      // costruisce un array di sostituzione con le parentesi attorno alle chiavi del contesto
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // interpola i valori da sostituire nel messaggio e lo ritorna
      return strtr($message, $replace);
  }

  // un messaggio con nomi di segnaposto delimitati da parentesi
  $message = "User {username} created";

  // un array di contesto con nome di segnaposto => valore da sostituire
  $context = array('username' => 'bolivar');

  // stampa "User bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Contesto

- Ogni metodo accetta un array come dati di contesto. L'array è pensato per 
  contenere informazioni che non si adattano bene all'interno di una stringa.
  L'array può contenere qualunque cosa. Gli implementatori DEVONO assicurarsi
  di trattare i dati di contesto con la maggior clemenza possibile. Un qualunque
  valore in un contesto NON DEVE lanciare un'eccezione nè causare alcun errore, 
  warning o notice php.

- Se un oggetto `Exception` viene passato nei dati di contesto, DEVE essere
  passato con la chiave `'exception'`. Eseguire log delle eccezioni è una
  operazione comune, e questo permette agli implementatori di estrarre lo stack
  trace dall'eccezione quando il log di backend lo supporta. Gli implementatori
  DEVONO comunque verificare che la chiave `'exception'` sia effettivamente 
  una `Exception` prima di usarla in tal modo, perché potrebbe contenere
  qualunque cosa.

### 1.4 Classi di aiuto ed interfacce

- La classe `Psr\Log\AbstractLogger` permette di implementare l'interfaccia
  `LoggerInterface` con estrema facilità estendendola, ed implementando il
  metodo generico `log`.
  Gli altri otto metodi reindirizzano ad esso il messaggio e il contesto.

- Allo stesso modo, usando il trait `Psr\Log\LoggerTrait` sarà necessario
  implementare il solo metodo generico `log`. Da notare che poiché i trait non
  possono implementare interfacce, in questo caso bisogna sempre implementare
  l'interfaccia `LoggerInterface`.

- La classe `Psr\Log\NullLogger` è fornita assieme all'interfaccia. PUO' essere
  utilizzata dagli utenti dell'interfaccia per fornire un "buco nero" di riserva
  nel caso in cui nessun logger fosse necessario. In ogni caso il logging
  opzionale potrebbe essere un approccio migliore, se la creazione dei dati di 
  contesto fosse impegnativa.

- L'interfaccia `Psr\Log\LoggerAwareInterface` contiene solo un metodo
  `setLogger(LoggerInterface $logger)` e può essere usata dai framework per
  collegare in modo automatico e arbitrario istanze ad un logger.

- Il trait `Psr\Log\LoggerAwareTrait` può essere usato per implementare 
  la corrispondente interfaccia con facilità in ogni classe. Fornisce
  l'accesso a `$this->logger`.

- La classe `Psr\Log\LogLevel` contiene le costanti per gli otto livelli di log.

2. Pacchetto
----------

L'interfaccia e le classi descritte, le classi delle eccezioni corrispondenti  
e la test suite per verificare la propria implementazione sono fornite come parte del 
pacchetto [psr/log](https://packagist.org/packages/psr/log).

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
