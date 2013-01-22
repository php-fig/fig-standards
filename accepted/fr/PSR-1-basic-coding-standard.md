La norme de codage de base
==========================

Cette section de la norme comprend ce qu'il convient de prendre en compte des
éléments standards de codage nécessaires pour assurer un niveau élevé
d'interopérabilité technique pour le partage du Code PHP.

Les mots clés "DOIT", "NE DOIT PAS", "REQUIS", "DEVRA", "NE DEVRA PAS", "DEVRAIT", "NE DEVRAIT PAS", "RECOMMENDER", "POUVOIR" et "OPTIONNEL" dans ce document doivent être interprétés comme décrit dans [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/lesmyrmidons/fig-standards/accepted/fr/PSR-0.md

1. Overview
-----------

- Les fichiers DOIVENT utiliser seulement les tag's `<?php` et `<?=`.

- Les fichiers de code PHP DOIVENT être encodé uniquement en UTF-8 sans BOM.

- Files SHOULD *either* declare symbols (classes, functions, constants, etc.) *or* cause side-effects (e.g. generate output, change .ini settings, etc.) but SHOULD NOT do both. //TODO

- Les espaces de noms et les classes DOIVENT suivre [PSR-0][].

- Les noms des classes DOIVENT être déclaré comme `StudlyCaps`.

- Les constantes de classe DOIVENT être déclarée en majuscules avec un sous-tiret en séparateurs.

- Les noms des méthodes DOIVENT être déclaré comme `camelCase`.

2. Files
--------

### 2.1. Les tag's PHP

Tous le code PHP DOIT uniquement utiliser les tag's long `<?php ?>` ou bien uniquement les tag's court `<?= ?>`. On NE DOIT PAS utiliser les deux variantes.

### 2.2. Encodage des caractères

Le code PHP DOIT utiliser uniquement UTF-8 sans BOM.

### 2.3. Les effets secondaires

// TODO

```php
<?php
// side effect: change ini settings
ini_set('error_reporting', E_ALL);

// side effect: loads a file
include "file.php";

// side effect: generates output
echo "<html>\n";

// declaration
function foo()
{
    // function body
}
```

L'exemple suivant est un fichier qui contient des déclarations sans
effets secondaires, c'est à dire, un exemple à émuler :

```php
<?php
// declaration
function foo()
{
    // function body
}

// conditional declaration is *not* a side effect
if (! function_exists('bar')) {
    function bar()
    {
        // function body
    }
}
```

3. Espaces de Nom et Noms des Classes
-------------------------------------

Les espaces de nom et les classes DOIVENT suivre [PSR-0][].

Cela signifie que chaque classe se trouve dans un fichier en lui-même, et se dans un espace de nom d'au moins un niveau : le nom d'un vendor de plus haut niveau.

Les noms de classes DOIVENT être déclarées comme `StudlyCaps`.

Le code écrit pour PHP 5.3 et après DOIT utiliser les espaces de noms formel.

Par exemple :

```php
<?php
// PHP 5.3 and later:
namespace Vendor\Model;

class Foo
{
}
```
Le code écrit pour 5.2.x et avant DEVRAIT utiliser la convention pseudo-espace de nom `Vendor_` préfixés par les noms de classe.

```php
<?php
// PHP 5.2.x and earlier:
class Vendor_Model_Foo
{
}
```

4. Class Constants, Properties, and Methods
-------------------------------------------

Le terme « classe » se réfère à toutes les classes, les interfaces et les traits.

### 4.1. Les Constantes

Les constantes de classe DOIVENT être déclarée en majuscules avec un tiret bas séparateurs.
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

Ce guide évite intentionnellement toute recommandation concernant l'utilisation des noms de propriétés `$StudlyCaps`, `$camelCase` ou `$under_score`

Quelle que soit la convention de nommage est utilisé, elle DOIT être appliquées de manière cohérente dans un cadre raisonnable. Cette portée peut être au vendor-level, package-level, class-level ou method-level.

### 4.3. Les Méthodes

Les noms de méthodes DOIVENT être déclarées comme `camelCase()`.

