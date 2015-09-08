Interface Logger
================

Ce document décrit une interface commune pour les bibliothèques de
journalisation.

L'objectif principal est de permettre aux bibliothèques d'obtenir un objet
`Psr\Log\LoggerInterface` et d'y écrire des logs d'une façon simple et
universelle. Les Frameworks et CMS qui ont des besoins personnalisés peuvent
étendre l'interface dans leur propre but, mais DOIVENT rester compatible avec
le présent document. Cela garantit que les bibliothèques tierces utilisées par
une application peuvent écrire dans les journaux centralisés des applications.

Les mots clés "DOIT", "NE DOIT PAS", "OBLIGATOIRE", "DEVRA", "NE DEVRA PAS",
"DEVRAIT", "NE DEVRAIT PAS", "RECOMMENDÉ", "PEUT" et "OPTIONNELLE" dans ce
document doivent être interprétés comme décrit dans [RFC 2119][].

Le mot `implementor` dans ce document est à interpréter comme quelqu'un qui
implémente le `LoggerInterface` dans une bibliothèque relative à de la
journalisation ou dans un framework.
Les utilisateurs d'objet `loggers` sont mentionnés comme `utilisateur`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Spécification
-----------------

### 1.1 Basique

- L'interface `LoggerInterface` expose huit méthodes pour écrire les logs pour
les huit [RFC 5424][] niveaux (debug, info, notice, warning, error, critical,
alert, emergency).

- Une neuvième méthode, `log`, accepte un niveau de journalisation en tant que
premier argument.
L'appel de cette méthode avec l'une des constantes du niveau de journalisation
DOIT avoir le même résultat que l'appel de la méthode de niveau spécifique.
L'appel de cette méthode avec un niveau non défini par cette spécification
DOIT lancer un `Psr\Log\InvalidArgumentException` si l'implémentation ne
reconnaît pas le niveau. Les utilisateurs NE DEVRAIENT PAS utiliser de niveau
personnalisé sans savoir avec certitude si l'implémentation le supporte.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Message

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

  // echoes "Username bolivar created"
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

### 1.4 Classes d'aide et interfaces

- La classe `Psr\Log\AbstractLogger` vous permet d'implémenter le
  `LoggerInterface` très facilement en l'étendant et en implémentant la méthode
  générique `log`. Les huit autres méthodes sont la transmission du message et
  du contexte à ce message.

- De même, l'utilisation du `Psr\Log\LoggerTrait` ne requiert que
  l'implémentation de la méthode générique `log`. A noter que puisque que les
  traits ne peuvent pas implémenter d'interfaces, dans ce cas vous pouvez
  `implémenter le LoggerInterface`.

- Le `Psr\Log\NullLogger` est fourni avec l'interface. Il PEUT être utilisé par
  les utilisateurs de l'interface pour fournir une solution "trou noir"
  implémentée si aucun logger ne lui est fournit. Cependant les journalisations
  conditionnelles PEUT être une meilleure approche si la création de données de
  contexte est couteuse.

- Le `Psr\Log\LoggerAwareInterface` ne contient que la méthode
  `setLogger(LoggerInterface $logger)` et peut être utilisé par les frameworks
  pour auto-connecter une instance arbitraire avec le logger.

- Le trait `Psr\Log\LoggerAwareTrait` peut être utilisé pour implémenter
  facilement l'interface équivalente dans n'importe quelle classe. Il vous donne
  accès à `$this->logger`.

- La classe `Psr\Log\LogLevel` contient des constantes pour les huit niveaux de
  journal.

2. Paquets
----------

Les interfaces et les classes décrites ainsi que les classes d'exception
pertinentes et une suite de tests pour vérifier votre mise en œuvre sont fournies par
[psr/log](https://packagist.org/packages/psr/log) package.

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Décrit une instance logger
 *
 * Le message DOIT être une chaîne ou un objet qui implémente __ toString ().
 *
 * Le message PEUT contenir des marqueurs à la forme: {foo} où foo
 * sera remplacé par les données de contexte à clé "foo".
 *
 * Le tableau de contexte peut contenir des données arbitraires, la seule
 * hypothèse qui peut être faite par des réalisateurs, c'est que si une instance
 * de Exception est donnée pour produire une trace de la pile, il DOIT être dans
 * une clé nommée "exception".
 *
 * Voir https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * pour la spécification d'interface complète.
 */
interface LoggerInterface
{
    /**
     * Le système est inutilisable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * Des mesures doivent être prises immédiatement.
     *
     * Exemple: Tout le site est hors service, la base de données est
     * indisponible, etc. Cela devrait déclencher des alertes par SMS et vous
     * réveiller.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Conditions critiques.
     *
     * Exemple: Composant d'application indisponible, exception inattendue.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Erreurs d'exécution qui ne nécessitent pas une action immédiate 
     * mais qui doivent normalement être journalisées et contrôlées.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Événements exceptionnels qui ne sont pas des erreurs.
     *
     * Exemple: Utilisation des API obsolètes, mauvaise utilisation d'une API,
     * indésirables élements qui ne sont pas nécessairement mauvais.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Événements normaux mais significatifs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Événements intéressants.
     *
     * Exemple: Connexion utilisateur, journaux SQL.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Informations détaillées de débogage.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * Logs avec un niveau arbitraire.
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
 * Décrit une instance logger-aware
 */
interface LoggerAwareInterface
{
    /**
     * Définit une instance logger sur l'objet
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
 * Décrit les niveaux de journalisation
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
