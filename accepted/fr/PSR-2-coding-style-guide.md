Guide pour le style d'écriture de code
==================

Ce guide prolonge et élargit [PSR-1][], la norme de codage de base.

Le but de ce guide est de réduire la friction cognitive lors de l'analyse du code de différents auteurs. Il le fait en énumérant un ensemble de règles et attentes communes quant à la façon de formater du code PHP.

Les règles de style dans ce document sont tirées de points communs entre les membres de différents projets. Lorsque plusieurs auteurs collaborent sur plusieurs projets, cela aide d'avoir un ensemble de lignes directrices qui seront utilisées dans tous ces projets. Ainsi, l'avantage de ce guide n'est pas dans les règles elles-mêmes, mais dans le partage de ces dernières.

Les mots clés "DOIT", "NE DOIT PAS", "OBLIGATOIRE", "DEVRA", "NE DEVRA PAS", "DEVRAIT", "NE DEVRAIT PAS", "RECOMMANDÉ", "PEUT" et "OPTIONNELLE" dans ce document doivent être interprétés comme décrit dans [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md

1. Vue d'ensemble
-----------

- Le code DOIT suivre les [PSR-1][].

- Le code DOIT utiliser 4 espaces pour l'indentation et aucune tabulation.

- Il NE DOIT PAS exister une limite stricte sur la longueur de la ligne, la limite acceptable DOIT être de 120 caractères; les lignes DEVRAIENT comprendre 80 caractères ou moins.

- Il DOIT y avoir une ligne vide après la déclaration de `l'espace de noms`, et il 
  DOIT y avoir une ligne vide après le bloc de déclarations `use`.

- L'ouverture des accolades pour les classes DOIT figurer sur la ligne suivante, les accolades de fermeture DOIVENT figurer sur la ligne suivante après le corps de la classe.

- L'ouverture des accolades pour les méthodes DOIT figurer sur la ligne suivante, les accolades de fermeture DOIVENT figurer sur la ligne suivante après le corps de la méthode.

- La visibilité DOIT être déclarée sur toutes les propriétés et méthodes; `abstraite` et `finale` doivent être déclarés avant la visibilité; `statique` DOIT être déclaré après la visibilité.

- La structure des mots-clés de contrôle DOIT avoir un espace après eux, les méthodes et les appels de fonction NE DOIVENT PAS en avoir.

- L'ouverture des accolades pour les structures de contrôle DOIT figurer sur la même ligne, et la fermeture des accolades DOIT figurer sur la ligne suivante après le corps.

- l'ouverture des parenthèses pour les structures de contrôle NE DOIT PAS contenir d'espace après eux, la fermeture de parenthèses pour les structures de contrôle NE DOIT PAS contenir d'espace avant.

### 1.1. Exemple

Cet exemple comprend certaines des règles citées ci-dessous comme étant un aperçu:

```php
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class Foo extends Bar implements FooInterface
{
    public function sampleFunction($a, $b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // corps de la fonction
    }
}
```

2. Général
----------

### 2.1 Standard de codage basic

Le code DOIT suivre toutes les règles énoncées dans [PSR-1][].

### 2.2 Fichiers

Tous les fichiers PHP DOIVENT utiliser la fin de ligne Unix LF (linefeed).

Tous les fichiers PHP DOIVENT se terminer par une ligne vide.

La tag de fermeture `?>` DOIT être omis de tous les fichiers contenant uniquement du PHP.

### 2.3. Lignes

Aucune limite stricte de la longueur de la ligne NE DOIT être fixée.

La limite souple de la longueur de la ligne DOIT être de 120 caractères; les outils de vérifications de styles automatisés DOIVENT prévenir, mais NE DOIVENT PAS lever d'erreur au dépassement de cette limite.

Les lignes NE DEVRAIENT PAS être plus longue que 80 caractères, les lignes plus longues que cela DEVRAIENT être scindé en plusieurs lignes de pas plus de 80 caractères chacune.

Aucun espace blanc NE DOIT figurer à la fin des lignes non vides.

Les lignes vides peuvent être ajoutées pour améliorer la lisibilité et pour indiquer des blocs de code liés.

Il NE DOIT PAS y avoir plus d'une instruction par ligne.

### 2.4. Indentation

Le code DOIT utiliser une indentation à 4 espaces, et NE DOIT PAS utiliser de tabulation pour l'indentation.

> N.b.: En utilisant seulement des espaces, et ne mélangant pas les espaces avec des tabulations,
> contribue à éviter les problèmes avec les différentiels, patchs, historiques, et les annotations.
> L'utilisation d'espaces rend également facile l'insertion des sous-indentations
> précises pour un alignement entre les lignes.

### 2.5. Mots-clés et True/False/Null

Les [mots-clés][] PHP DOIVENT être en minuscule.

Les constantes PHP `true`, `false`, et `null` DOIVENT être en minuscule.

[mots-clés]: http://php.net/manual/en/reserved.keywords.php

3. Namespace et déclarations Use
---------------------------------

Quand présent, il DOIT y avoir une ligne vide après la déclaration du `namespace`.

Quand présent, toutes les déclarations `use` DOIVENT être après la déclaration du `namespace`.

Il DOIT y avoir un seul mot-clé `use` par déclaration.

Il DOIT y avoir une ligne vide après le block de déclaration `use`.

Par exemple:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... Code PHP additionnel ...

```

4. Classes, Propriétés et Méthodes
-----------------------------------

Le terme "class" réfère à toutes les classes, interfaces, et traits.

### 4.1. Héritages et Implémentations

Les mots clés `extends` et `implements` DOIVENT être déclarés sur la même ligne 
que le nom de la classe.

L'accolade d'ouverture de la classe DOIT être sur sa propre ligne; l'accolade
 de fermeture DOIT être sur la ligne après le corps de la classe.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constantes, propriétés, méthodes
}
```

La liste d'`implements` PEUT être répartie sur plusieurs lignes, où chaque 
ligne subséquente est indentée une fois. Ce faisant, le premier élément de la liste
 DOIT être sur la ligne suivante, et il NE DOIT y avoir qu'une seule interface par ligne.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // constantes, propriétés, méthodes
}
```

### 4.2. Propriétés

La visibilité DOIT être déclarée sur toutes les propriétés.

Le mot-clé `var` NE DOIT PAS être utilisé pour déclarer une propriété.

Il NE DOIT PAS y avoir plus d'une propriété déclarée par déclaration.

Les noms de propriété NE DEVRAIENT PAS être précédés d'un sous-tiret pour indiquer la
visibilité protégée ou privée.

Une déclaration de propriété ressemble à ce qui suit.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. Méthodes

La visibilité DOIT être déclarée sur toutes les méthodes.

Les noms de méthode NE DOIT PAS être précédés d'un sous-tiret pour indiquer
 la visibilité protégée ou privée.

Les noms de méthode NE DOIVENT PAS être déclarés avec un espace après le nom de la méthode.
L'accolade d'ouverture DOIT aller sur sa propre ligne, et l'accolade de fermeture DOIT 
aller sur la ligne suivante à la suite du corps. Il NE DOIT PAS y avoir d'espace après 
l'ouverture des parenthèses, et il NE DOIT PAS y avoir d'espace avant la parenthèse de fermeture.

Une déclaration de méthode ressemble à la suivante. Notez l'emplacement des
parenthèses, virgules, espaces et accolades:

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // corps de la fonction
    }
}
```

### 4.4. Arguments des méthodes

Dans la liste des arguments, il NE DOIT PAS y avoir d'espace avant chaque virgule, et il
DOIT y avoir un espace après chaque virgule.

Les arguments de méthode avec les valeurs par défaut doivent être placés à la fin
 de la liste d'arguments.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // corps de la fonction
    }
}
```
La liste d'arguments PEUT être répartie sur plusieurs lignes, où chaque ligne 
subséquente est indentée une fois. Ce faisant, le premier élément de la liste 
DOIT figurer sur la ligne suivante, et il NE DOIT y avoir qu'un seul argument par ligne.

Lorsque la liste des arguments est répartie sur plusieurs lignes, la parenthèse 
de fermeture et d'ouverture DOIT être placée ensemble sur leur propre ligne avec un espace
entre eux.


```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // corps de la fonction
    }
}
```

### 4.5. `abstract`, `final`, et `static`

Lorsqu'elles sont présentes, les déclarations `abstract` et `final` DOIVENT précédés 
la déclaration de la visibilité.

Lorsqu'elle est présente, l'a déclaration `static` DOIT venir après la déclaration
 de la visibilité.

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // corps de la fonction
    }
}
```

### 4.6. Appels de Méthodes et Fonctions

Lorsque vous effectuez l'appel d'une méthode ou d'une fonction, il NE DOIT PAS 
y avoir d'espace entre le nom de la méthode ou de la fonction et la parenthèse ouvrante, 
il NE DOIT PAS y avoir d'espace après la parenthèse ouvrante et il NE DOIT PAS y avoir 
d'espace avant la parenthèse fermante. Dans la liste d'arguments, il NE DOIT PAS y avoir 
d'espace avant chaque virgule, et il DOIT y avoir un espace après chaque virgule.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

La listes d'arguments PEUT être répartie sur plusieurs lignes, où chaque ligne 
subséquente est indentée une fois. Ce faisant, le premier élément de la liste DOIT
 figurer sur la ligne suivante, et il NE DOIT y avoir qu'un seul argument par ligne.

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. Structures de contrôle
---------------------

Les règles de style générales pour les structures de contrôle sont les suivantes:

- Il DOIT y avoir un espace après la structure clé de contrôle
- Il NE DOIT PAS y avoir d'espace après la parenthèse ouvrante
- Il NE DOIT PAS y avoir d'espace avant la parenthèse fermante
- Il DOIT y avoir un espace entre la parenthèse fermante et de l'accolade ouvrante
- Le corps de la structure DOIT être indenté une fois
- L'accolade fermante DOIT être sur la ligne suivante après le corps

Le corps de chaque structure DOIT être entouré par des accolades. Ceci standardise l'aspect des structures et réduit la probabilité d'introduire des erreurs lors de l'ajout de nouvelles lignes au corps.

### 5.1. `if`, `elseif`, `else`

Une structure `if` ressemble à ce qui suit. Notez l'emplacement des parenthèses,
espaces et accolades, et que `else` et `elseif` sont sur la même ligne que l'accolade fermante du corps précédant.

```php
<?php
if ($expr1) {
    // corps du if
} elseif ($expr2) {
    // corps du elseif
} else {
    // corps du else;
}
```

Le mot-clé `elseif` DEVRAIT être utilisé au lieu de `else if` afin que tout les mots-clés de contrôle ressemblent à des mots simples.

### 5.2. `switch`, `case`

Une structure `switch` ressemble à ce qui suit. Notez l'emplacement des parenthèses,
espaces et accolades, La déclaration de `case` DOIT être indenté une fois
par rapport à `switch` et le mot-clé `break` (ou autre mot-clé de terminaison) DOIT être
indenté au même niveau que le corps de `case`. Il DOIT y avoir un commentaire comme
`// no break` lorsque son omission est intentionnel dans un corps de `case` non vide.

```php
<?php
switch ($expr) {
    case 0:
        echo 'Premier case, avec un break';
        break;
    case 1:
        echo 'Deuxième case, avec omission du break';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Troisième case, return à la place de break';
        return;
    default:
        echo 'case par défaut';
        break;
}
```

### 5.3. `while`, `do while`

Une structure `while` ressemble à ce qui suit. Notez l'emplacement des parenthèses,
espaces et accolades.

```php
<?php
while ($expr) {
    // corps de la structure
}
```

De même, une structure `do while` ressemble à ce qui suit. Notez l'emplacement des parenthèses,
espaces et accolades.

```php
<?php
do {
    // corps de la structure
} while ($expr);
```

### 5.4. `for`

Une structure `for` ressemble à ce qui suit. Notez l'emplacement des parenthèses,
espaces et accolades.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // corps de la structure for
}
```

### 5.5. `foreach`

Une structure `foreach` ressemble à ce qui suit. Notez l'emplacement des parenthèses,
espaces et accolades.

```php
<?php
foreach ($iterable as $key => $value) {
    // corps de la structure foreach
}
```

### 5.6. `try`, `catch`

Une structure `try catch` ressemble à ce qui suit. Notez l'emplacement des parenthèses,
espaces et accolades.

```php
<?php
try {
    // corps du try
} catch (FirstExceptionType $e) {
    // corps du  catch
} catch (OtherExceptionType $e) {
    // corps du catch
}
```

6. Closures
-----------

Les closures DOIVENT être déclarées avec un espace après le mot-clé `fonction` et un
espace avant et après le mot clé `use`.

L'accolade ouvrante DOIT aller sur la même ligne, et l'accolade fermante doit aller sur
la ligne suivante à la suite du corps.

Il NE DOIT PAS y avoir d'espace après la parenthèse ouvrante de la liste des arguments
ou des variables, et il NE DOIT PAS y avoir d'espace avant la parenthèse fermante
de la liste d'arguments ou de variables.

Dans la liste des arguments et des variables, il NE DOIT PAS y avoir d'espace avant chaque
virgule, et il DOIT y avoir un espace après chaque virgule.

Les arguments de closure avec des valeurs par défaut doivent aller à la fin de la liste des arguments.

Une déclaration de closure se présente comme suit. Notez l'emplacement des parenthèses, virgules, espaces et accolades.

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // corps
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // corps
};
```

Les listes d'arguments et de variables peuvent être réparties sur plusieurs lignes, où
chaque ligne subséquente est en retrait une fois. Ce faisant, le premier élément de la
liste doit figurer sur la ligne suivante, et il DOIT y avoir qu'un seul argument ou variable
par ligne.

Lorsque la liste de fin (ou arguments ou variables) est répartie sur
plusieurs lignes, la parenthèse fermante et accolade ouvrante DOIVENT être placé
ensemble sur leur ligne avec un espace entre eux.

Voici des exemples de closure avec et sans liste d'arguments et
de variables réparties sur plusieurs lignes.

```php
<?php
$ArgsLong_pasDeVars = function (
    $argumentLong,
    $argumentPlusLong,
    $argumentBcpPlusLong
) {
   // corps
};

$pasDArgs_varsLong = function () use (
    $varLong1,
    $varPlusLong2,
    $varBcpPlusLong3
) {
   // corps
};

$argsLong_varsLong = function (
    $argumentLong,
    $argumentPlusLong,
    $argumentBcpPlusLong
) use (
    $varLong1,
    $varPlusLong2,
    $varBcpPlusLong3
) {
   // corps
};

$argsLong_varsCourte = function (
    $argumentLong,
    $argumentPlusLong,
    $argumentBcpPlusLong
) use ($var1) {
   // corps
};

$argsCourt_varsLong = function ($arg) use (
    $varLong1,
    $varPlusLong2,
    $varBcpPlusLong3
) {
   // corps
};
```

Notez que les règles de formatage s'appliquent également lorsque la closure est utilisée directement
dans un appel de fonction ou d'une méthode en tant qu'argument.

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // corps
    },
    $arg3
);
```

7. Conclusion
--------------

Il y a de nombreux éléments de style et pratiques intentionnellement omis par ce
guide. Ceux-ci incluent, mais ne sont pas limités à :

- Déclaration des variables et constantes globales

- Déclaration de fonctions

- Les opérateurs et les affectations

- Alignement Inter-line

- Commentaires et blocs de documentation

- Préfixes et suffixes de noms de classe

- Les meilleures pratiques

Les futures recommandations pourront réviser et étendre ce guide pour répondre à ces éléments de style et pratique ou d'autres.

Appendix A. Survey
------------------

In writing this style guide, the group took a survey of member projects to
determine common practices. The survey is retained herein for posterity.

### A.1. Survey Data

    url,http://www.horde.org/apps/horde/docs/CODING_STANDARDS,http://pear.php.net/manual/en/standards.php,http://solarphp.com/manual/appendix-standards.style,http://framework.zend.com/manual/en/coding-standard.html,http://symfony.com/doc/2.0/contributing/code/standards.html,http://www.ppi.io/docs/coding-standards.html,https://github.com/ezsystems/ezp-next/wiki/codingstandards,http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html,https://github.com/UnionOfRAD/lithium/wiki/Spec%3A-Coding,http://drupal.org/coding-standards,http://code.google.com/p/sabredav/,http://area51.phpbb.com/docs/31x/coding-guidelines.html,https://docs.google.com/a/zikula.org/document/edit?authkey=CPCU0Us&hgd=1&id=1fcqb93Sn-hR9c0mkN6m_tyWnmEvoswKBtSc0tKkZmJA,http://www.chisimba.com,n/a,https://github.com/Respect/project-info/blob/master/coding-standards-sample.php,n/a,Object Calisthenics for PHP,http://doc.nette.org/en/coding-standard,http://flow3.typo3.org,https://github.com/propelorm/Propel2/wiki/Coding-Standards,http://developer.joomla.org/coding-standards.html
    voting,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,no,no,no,?,yes,no,yes
    indent_type,4,4,4,4,4,tab,4,tab,tab,2,4,tab,4,4,4,4,4,4,tab,tab,4,tab
    line_length_limit_soft,75,75,75,75,no,85,120,120,80,80,80,no,100,80,80,?,?,120,80,120,no,150
    line_length_limit_hard,85,85,85,85,no,no,no,no,100,?,no,no,no,100,100,?,120,120,no,no,no,no
    class_names,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,lower_under,studly,lower,studly,studly,studly,studly,?,studly,studly,studly
    class_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,next,next,next,next,next,next,same,next,next
    constant_names,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper
    true_false_null,lower,lower,lower,lower,lower,lower,lower,lower,lower,upper,lower,lower,lower,upper,lower,lower,lower,lower,lower,upper,lower,lower
    method_names,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,lower_under,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel
    method_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,same,next,next,next,next,next,same,next,next
    control_brace_line,same,same,same,same,same,same,next,same,same,same,same,next,same,same,next,same,same,same,same,same,same,next
    control_space_after,yes,yes,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes
    always_use_control_braces,yes,yes,yes,yes,yes,yes,no,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes
    else_elseif_line,same,same,same,same,same,same,next,same,same,next,same,next,same,next,next,same,same,same,same,same,same,next
    case_break_indent_from_switch,0/1,0/1,0/1,1/2,1/2,1/2,1/2,1/1,1/1,1/2,1/2,1/1,1/2,1/2,1/2,1/2,1/2,1/2,0/1,1/1,1/2,1/2
    function_space_after,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no
    closing_php_tag_required,no,no,no,no,no,no,no,no,yes,no,no,no,no,yes,no,no,no,no,no,yes,no,no
    line_endings,LF,LF,LF,LF,LF,LF,LF,LF,?,LF,?,LF,LF,LF,LF,?,,LF,?,LF,LF,LF
    static_or_visibility_first,static,?,static,either,either,either,visibility,visibility,visibility,either,static,either,?,visibility,?,?,either,either,visibility,visibility,static,?
    control_space_parens,no,no,no,no,no,no,yes,no,no,no,no,no,no,yes,?,no,no,no,no,no,no,no
    blank_line_after_php,no,no,no,no,yes,no,no,no,no,yes,yes,no,no,yes,?,yes,yes,no,yes,no,yes,no
    class_method_control_brace,next/next/same,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/next,same/same/same,same/same/same,same/same/same,same/same/same,next/next/next,next/next/same,next/same/same,next/next/next,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/same,next/next/next

### A.2. Survey Legend

`indent_type`:
The type of indenting. `tab` = "Use a tab", `2` or `4` = "number of spaces"

`line_length_limit_soft`:
The "soft" line length limit, in characters. `?` = not discernible or no response, `no` means no limit.

`line_length_limit_hard`:
The "hard" line length limit, in characters. `?` = not discernible or no response, `no` means no limit.

`class_names`:
How classes are named. `lower` = lowercase only, `lower_under` = lowercase with underscore separators, `studly` = StudlyCase.

`class_brace_line`:
Does the opening brace for a class go on the `same` line as the class keyword, or on the `next` line after it?

`constant_names`:
How are class constants named? `upper` = Uppercase with underscore separators.

`true_false_null`:
Are the `true`, `false`, and `null` keywords spelled as all `lower` case, or all `upper` case?

`method_names`:
How are methods named? `camel` = `camelCase`, `lower_under` = lowercase with underscore separators.

`method_brace_line`:
Does the opening brace for a method go on the `same` line as the method name, or on the `next` line?

`control_brace_line`:
Does the opening brace for a control structure go on the `same` line, or on the `next` line?

`control_space_after`:
Is there a space after the control structure keyword?

`always_use_control_braces`:
Do control structures always use braces?

`else_elseif_line`:
When using `else` or `elseif`, does it go on the `same` line as the previous closing brace, or does it go on the `next` line?

`case_break_indent_from_switch`:
How many times are `case` and `break` indented from an opening `switch` statement?

`function_space_after`:
Do function calls have a space after the function name and before the opening parenthesis?

`closing_php_tag_required`:
In files containing only PHP, is the closing `?>` tag required?

`line_endings`:
What type of line ending is used?

`static_or_visibility_first`:
When declaring a method, does `static` come first, or does the visibility come first?

`control_space_parens`:
In a control structure expression, is there a space after the opening parenthesis and a space before the closing parenthesis? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
Is there a blank line after the opening PHP tag?

`class_method_control_brace`:
A summary of what line the opening braces go on for classes, methods, and control structures.

### A.3. Survey Results

    indent_type:
        tab: 7
        2: 1
        4: 14
    line_length_limit_soft:
        ?: 2
        no: 3
        75: 4
        80: 6
        85: 1
        100: 1
        120: 4
        150: 1
    line_length_limit_hard:
        ?: 2
        no: 11
        85: 4
        100: 3
        120: 2
    class_names:
        ?: 1
        lower: 1
        lower_under: 1
        studly: 19
    class_brace_line:
        next: 16
        same: 6
    constant_names:
        upper: 22
    true_false_null:
        lower: 19
        upper: 3
    method_names:
        camel: 21
        lower_under: 1
    method_brace_line:
        next: 15
        same: 7
    control_brace_line:
        next: 4
        same: 18
    control_space_after:
        no: 2
        yes: 20
    always_use_control_braces:
        no: 3
        yes: 19
    else_elseif_line:
        next: 6
        same: 16
    case_break_indent_from_switch:
        0/1: 4
        1/1: 4
        1/2: 14
    function_space_after:
        no: 22
    closing_php_tag_required:
        no: 19
        yes: 3
    line_endings:
        ?: 5
        LF: 17
    static_or_visibility_first:
        ?: 5
        either: 7
        static: 4
        visibility: 6
    control_space_parens:
        ?: 1
        no: 19
        yes: 2
    blank_line_after_php:
        ?: 1
        no: 13
        yes: 8
    class_method_control_brace:
        next/next/next: 4
        next/next/same: 11
        next/same/same: 1
        same/same/same: 6
