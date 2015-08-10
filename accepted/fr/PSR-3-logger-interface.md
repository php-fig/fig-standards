Interface Logger
================

Ce document décrit une interface commune pour les bibliothèques de
journalisation.

L'objectif principal est de permettre aux bibliothèques d'obtenir un objet
`Psr\Log\LoggerInterface` et d'y écrire des journaux d'une façon simple et
universelle. Les Frameworks et CMS qui ont des besoins personnalisés peuvent
étendre l'interface dans leur propre but, mais DOIVENT rester compatible avec
le présent document. Cela garantit que les bibliothèques tierces utilisées par
une application peuvent écrire dans les journaux centralisés des applications.

Les mots clés "DOIT", "NE DOIT PAS", "OBLIGATOIRE", "DEVRA", "NE DEVRA PAS",
"DEVRAIT", "NE DEVRAIT PAS", "RECOMMENDÉ", "PEUT" et "OPTIONNELLE" dans ce
document doivent être interprétés comme décrit dans [RFC 2119][].

Le mot `implémenteur` dans ce document est à interpréter comme quelqu'un qui
implémente le `LoggerInterface` dans une bibliothèque relative à la
journalisation ou dans un framework. Les utilisateurs d'objet `loggers` sont
mentionnées comme `utilisateur`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Spécification
----------------

### 1.1 Basique

- L'interface `LoggerInterface` expose huit méthodes pour écrire les journaux
avec les huit niveaux de la [RFC 5424][] (debug, info, notice, warning, error,
critical, alert, emergency).

- Une neuvième méthode, `log`, accepte un niveau de journalisation en tant que
premier argument. L'appel de cette méthode avec l'une des constantes de niveau
de journalisation DOIT avoir le même résultat que l'appel de la méthode d'un
niveau spécifique. L'appel de cette méthode avec un niveau non défini par cette
spécification DOIT lancer un `Psr\Log\InvalidArgumentException` si l'implémentation
ne reconnaît pas le niveau. Les utilisateurs NE DEVRAIENT PAS utiliser de niveau
personnalisé sans savoir avec certitude si l'implémentation le supporte.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Message

- Chaque méthode accepte une chaîne comme message, ou un objet avec une méthode
  `__toString()`. Les implémenteurs PEUVENT avoir un traitement spécial pour
  les objets passés. Si ce n'est pas le cas, les implémenteurs DOIVENT les
  convertir en chaîne de caractères.

- Le message peut contenir des espaces réservés dont les implémenteurs PEUVENT
  les remplacer par les valeurs provenant du tableau contextuel.

  Les noms des espaces réservés DOIVENT correspondre aux clés du tableau
  contextuel.

  Les noms des espaces réservés DOIVENT être délimités avec une seule accolade
  ouvrante `{` et une seule accolade fermante `}`. Il NE DOIT PAS y avoir
  d'espace entre les délimiteurs et le nom d'espace réservé.

  Les noms des espaces réservés DEVRAIENT être seulement composés de caractères
  `A-Z`, `a-z`, `0-9`, sous-tiret `_`, et de point `.`. L'utilisation d'autres
  caractères est réservé pour des modifications futures de la spécification des
  espaces réservés.

  Les implémenteurs PEUVENT utiliser des espaces réservés pour mettre en œuvre
  diverses stratégies d'échappement et traduire le journal pour l'affichage.
  Les utilisateurs NE DEVRAIENT PAS pre-échapper les valeurs des espaces
  réservés, car ils ne peuvent pas savoir dans quel contexte les données seront
  affichées.

  Ce qui suit est un exemple d'implémentation d'interpolation d'espace réservé
  fourni seulement à titre de référence:

  ```php
  /**
   * Interpole les valeurs du contexte à la place des espaces réservés du message.
   */
  function interpolate($message, array $context = array())
  {
      // construit un tableau de remplacement en ajoutant des accolades autour
      // des clés du contexte
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // interpole les valeurs de remplacements dans le message et retourne le
      // résultat
      return strtr($message, $replace);
  }

  // un message avec des noms d'espaces réservés délimités par des accolades
  $message = "User {username} created";

  // un tableau de données contextuelles, noms => valeurs de remplacement
  $context = array('username' => 'bolivar');

  // affiche "Username bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Contexte

- Chaque méthode accepte un tableau comme données contextuelles. Celui-ci est
  destiné à contenir une information extérieure qui ne rentre pas bien dans une
  chaîne de caractères. Le tableau peut contenir n'importe quoi. Les
  implémenteurs doivent s'assurer qu'ils traitent les données contextuelles avec
  autant d'indulgence que possible. Une valeur donnée dans le contexte NE DOIT
  PAS lancer d'exception ni soulever d'erreur, alerte ou notification PHP.

- Si un objet `Exception` est passé dans les données contextuelles, Il DOIT
  être dans la clé `'exception'`. Toutes les exceptions ont une interface
  communes ce qui permet aux implementeurs d'extraire la trace de la pile de
  l'exception quand l'interpréteur du journal le supporte. Les implémenteurs
  DOIVENT vérifier que la clé `'exception'` est bien une `Exception` avant de
  l'utiliser comme telle, car elle peut contenir n'importe quoi.

### 1.4 Classes d'aide et interfaces

- La classe `Psr\Log\AbstractLogger` vous permet d'implémenter le
  `LoggerInterface` très facilement en l'étendant et en implémentant la méthode
  générique `log`. Les huit autres méthodes transmettent le message et le
  contexte à cette méthode.

- De même, l'utilisation du `Psr\Log\LoggerTrait` ne requiert que
  l'implémentation de la méthode générique `log`. À noter que puisque que les
  traits ne peuvent pas implémenter d'interface, dans ce cas vous pouvez
  implémenter le `LoggerInterface`.

- Le `Psr\Log\NullLogger` est fourni avec l'interface. Il PEUT être utilisé par
  les utilisateurs de l'interface pour fournir une solution "trou noir"
  implémentée si aucun `logger` ne lui est fourni. Cependant, les journalisations
  conditionnelles PEUTENT être une meilleure approche si la création de données
  de contexte est coûteuse.

- Le `Psr\Log\LoggerAwareInterface` ne contient que la méthode
  `setLogger(LoggerInterface $logger)` et PEUT être utilisé par les frameworks
  pour auto-connecter une instance arbitraires avec le logger.

- Le trait `Psr\Log\LoggerAwareTrait` peut être utilisé pour implémenter
  facilement l'interface équivalente dans n'importe quelle classe. Il vous donne
  accès à `$this->logger`.

- La classe `Psr\Log\LogLevel` contient des constantes pour les huit niveaux de
  journalisation.

2. Paquets
----------

Les interfaces et les classes décrites de même que les classes d'exception
pertinentes et une suite de tests pour vérifier votre implémentation sont
fournies par le paquet [psr/log](https://packagist.org/packages/psr/log).

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Décrit une instance logger
 *
 * Le message DOIT être une chaîne ou un objet qui implémente __toString().
 *
 * Le message PEUT contenir des marqueurs sous forme : {foo} où foo
 * sera remplacé par les données de contexte à la clé "foo".
 *
 * Le tableau de contexte peut contenir des données arbitraires, la seule
 * hypothèse qui peut être faite par des implémenteurs, c'est que si une instance
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
     * Exemple : Tout le site est hors service, la base de données est
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
     * Exemple : Composant d'application indisponible, exception inattendue.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Erreurs d'exécution qui ne nécessitent pas une action immédiate, mais doit
     * normalement être journalisée et contrôlée.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Événements exceptionnels qui ne sont pas des erreurs.
     *
     * Exemple : Utilisation des API obsolètes, mauvaise utilisation d'une API,
     * éléments indésirables qui ne sont pas nécessairement mauvais.
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
     * Exemple : Connexion utilisateur, journaux SQL.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Informations détaillées de débogue.
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
