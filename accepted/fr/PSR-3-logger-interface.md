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

- Toutes les méthodes acceptent de prendre en paramètre le message sous la forme 
  d'une chaine de caractère ou d'un objet avec une méthode `__toString()`. 
  Les développeurs PEUVENT appliquer un traitement particulier sur les objets 
  passés en paramètre. Si ce n'est pas le cas, les développeurs DOIVENT
  les convertir en chaine de caractères.

- Le message PEUT contenir des éléments de substitution que les développeurs 
  PEUVENT remplacer avec des valeurs issues du tableau de contexte.

  Le nom des éléments de substitutions DOIVENT correspondre avec les clés du 
  tableau de contexte.

  Les noms d'éléments de substitution DOIVENT être délimités avec une accolade
  ouvrante `{` et une accolade fermante `}`. Il ne DOIT PAS y avoir d'espace 
  entre le délimiteur et le nom de l'élément.

  Les noms d'éléments de substitution DEVRAIENT être composés uniquement des 
  caractères `A-Z`, `a-z`, `0-9`, underscore `_`, et point `.`. L'utilisation
  d'autres caractères est réservée aux modifications futures de la spécification
  des éléments de substitution.

  Les développeurs PEUVENT utiliser des éléments de substitution pour mettre en 
  place différentes stratégies d'échappement et traduire les logs pour l'affichage.
  Les utilisateurs ne DEVRAIENT PAS pré-échapper les valeurs des éléments de substitution
  parce qu'ils ne peuvent pas savoir dans quel contexte elles seront affichées.

  Ce qui suit est un exemple d'implémentation d'interpolation des éléments de substitution.
  Il est uniquement fournit à titre de référence :

  ```php
  /**
   * Interpolation des valeurs du contexte dans les éléments de substitution du message.
   */
  function interpolate($message, array $context = array())
  {
      // construction d'un tableau de remplacement avec les accolades 
      // autour des clés du contexte
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // interpoler les valeurs dans le message et le retourner
      return strtr($message, $replace);
  }

  // un message avec un élément de substituion délimité par des accolades
  $message = "User {username} created";

  // un tableau de contexte avec noms des éléments de substitution => valeurs de remplacement
  $context = array('username' => 'bolivar');

  // affiche "Username bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Contexte

- Toutes les méthodes acceptent de prendre en paramètre un tableau de données de contexte. 
  Ce tableau contient toutes les informations qui ne peuvent pas être contenues dans une 
  chaine de caractères. Ce tableau peut contenir n'importe quoi. Les développeurs DOIVENT
  s'assurer de traiter les données de contexte avec autant d'indulgence que possible. Une
  valeur dans le contexte ne DOIT PAS lancer une exception ni soulever aucune erreur de php, 
  warning ni notice.

- Si un objet `Exception` est passé dans le tableau de contexte, il DOIT être placé dans la clé
  `exception`. La journalisation des exceptions est une pratique commune ce qui permet aux 
  développeurs d'extraire la pile d'appel de l'exception quand l'outil de journalisation le permet.
  Les développeurs DOIVENT toujours vérifier que la clé `exception` est vraiment une `Exception` avant
  de l'utiliser parce qu'elle PEUT contenir n'importe quoi.

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
 * hypothèse qui peut être faite par des développeurs, c'est que si une instance
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
