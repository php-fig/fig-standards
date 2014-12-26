La norme de codage de base
==========================

Cette section de la norme comprend ce qu'il convient de prendre en compte des
éléments standard de codage nécessaires pour assurer un niveau élevé
d'interopérabilité technique pour le partage du code PHP.

Les mots clés "DOIT", "NE DOIT PAS", "OBLIGATOIRE", "DEVRA", "NE DEVRA PAS",
"DEVRAIT", "NE DEVRAIT PAS", "RECOMMANDÉ", "PEUT" et "OPTIONNELLE" dans ce
document doivent être interprétés comme décrit dans [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/lesmyrmidons/fig-standards/accepted/fr/PSR-0.md

1. Vue d'ensemble
-----------

- Les fichiers DOIVENT utiliser seulement les tags `<?php` et `<?=`.

- Les fichiers de code PHP DOIVENT être encodés uniquement en UTF-8 sans BOM.

- Les fichiers DEVRAIENT *soit* déclarer des symboles (classes, fonctions,
  constantes, etc.) *soit* causer des effets secondaires (par exemple, générer
  des sorties, modifier paramètres .ini), mais NE DOIVENT PAS faire les deux.

- Les espaces de noms et les classes DOIVENT suivre [PSR-0][].

- Les noms des classes DOIVENT être déclarés comme `CamelCase`.

- Les constantes de classe DOIVENT être déclarées en majuscules avec un
  sous-tiret en séparateurs.

- Les noms des méthodes DOIVENT être déclarés comme `lowerCamelCase`.

2. Fichiers
--------

### 2.1. Les tags PHP

Le code PHP DOIT utiliser les tags longs <?php ?> ou bien les tags courts
<?= ?>. On NE DOIT PAS utiliser d'autres variantes.

### 2.2. Encodage des caractères

Le code PHP DOIT utiliser uniquement UTF-8 sans BOM.

### 2.3. Les effets secondaires

Un fichier DEVRAIT déclarer des nouveaux symboles (classes, fonctions,
constantes, etc.) et ne pas causer d’effets secondaires, où il DEVRAIT exécuter
de la logique avec effets secondaires, mais NE DEVRAIT PAS faire les deux.

La phrase "effets secondaires" signifie l’exécution de la logique qui n’est pas
liée directement à la déclaration de classes, fonctions, constantes, etc.,
*simplement par l’inclusion du fichier.*

Les "effets secondaires" comprennent, mais ne sont pas limités à : générer une
sortie, utilisation explicite de `require` ou `include`, connexion à des
services externes, modification de paramètres ini, émission d'erreurs ou
d'exceptions, modification de variables globales ou statiques, lecture ou
écriture dans un fichier et ainsi de suite.

Le code suivant est un exemple d’un fichier avec déclarations et effets
secondaires ; c’est-à-dire, un exemple de ce qu’il faut éviter :

```php
<?php
// Effet secondaire: change ini settings
ini_set('error_reporting', E_ALL);

// Effet secondaire: loads a file
include "file.php";

// Effet secondaire: generates output
echo "<html>\n";

// déclaration
function foo()
{
    // corps de la fonction
}
```

L'exemple suivant est un fichier qui contient des déclarations sans
effets secondaires, c’est-à-dire, un exemple à émuler :

```php
<?php
// déclaration
function foo()
{
    // corps de la fonction
}

// une déclaration conditionnelle n'est pas un effet secondaire
if (! function_exists('bar')) {
    function bar()
    {
        // corps de la fonction
    }
}
```

3. Espaces de Nom et Noms des Classes
-------------------------------------

Les espaces de noms et les classes DOIVENT suivre [PSR-0][].

Cela signifie que chaque classe devra se trouver seule dans un fichier, et dans
un espace de nom d'au moins un niveau : le nom d'un vendor de plus haut niveau.

Les noms de classes DOIVENT être déclarés en `StudlyCaps`.

Le code écrit pour PHP 5.3 et après, DOIT utiliser les espaces de noms formels.

Par exemple :

```php
<?php
// PHP 5.3 et supérieur:
namespace Vendor\Model;

class Foo
{
}
```

Le code écrit pour 5.2.x et avant DEVRAIT utiliser la convention pseudo-espace
de nom `Vendor_` préfixée par les noms de classes.

```php
<?php
// PHP 5.2.x and earlier:
class Vendor_Model_Foo
{
}
```

4. Constantes de Classe, Propriétés et Méthodes
-------------------------------------------

Le terme « classe » se réfère à toutes les classes, les interfaces et les
traits.

### 4.1. Les Constantes

Les constantes de classe DOIVENT être déclarées en majuscules avec un tiret bas
séparateur.
Par exemple :

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. Les Propriétés

Ce guide évite intentionnellement toute recommandation concernant l'utilisation
des noms de propriétés `$StudlyCaps`, `$camelCase` ou `$under_score`.

Quelle que soit la convention de nommage utilisée, elle DOIT être appliquée de
manière cohérente dans un cadre raisonnable. Cette portée peut être au niveau
vendor, paquet, classe ou méthode.

### 4.3. Les Méthodes

Les noms de méthodes DOIVENT être déclarés ainsi `camelCase()`.

