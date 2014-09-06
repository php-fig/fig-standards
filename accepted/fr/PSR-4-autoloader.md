# Auto-chargement

Les mots clés "DOIT", "NE DOIT PAS", "OBLIGATOIRE", "DEVRA", "NE DEVRA PAS", "DEVRAIT", 
"NE DEVRAIT PAS", "RECOMMENDÉ", "PEUT" et "OPTIONNELLE" dans ce document doivent 
être interprétés comme décrit dans [RFC 2119](http://tools.ietf.org/html/rfc2119).

## 1. Résumé

Cette PSR décrit les spécifications pour l'[auto-chargement][] des classes
à partir de chemins de fichiers. Elle est entièrement inter-opérable, et peut
être utilisée en plus de tout autre spécification d'auto-chargement, y compris
[PSR-0][]. Cette recommandation décrit aussi où stocker les fichiers qui 
seront auto-chargés en accord avec cette spécification.

## 2. Spécification

1. Le terme «classe» se réfère à des classes, des interfaces, des traits, et d'autres  
   structures similaires.

2. Un nom de classe entièrement qualifié a la forme suivante:

        \<EspaceDeNoms>(\<SousEspacesDeNoms>)*\<NomDeClasse>

    1. Le nom de classe entièrement qualifié DOIT commencer par un espace de noms de premier niveau,
       aussi appelé l'"espace de noms du fournisseur".

    2. Le nom de classe entièrement qualifié PEUT avoir un ou plusieurs sous-espace(s)
       de noms

    3. Le nom de classe entièrement qualifié DOIT se terminer par un nom de classe.

    4. Les caractères "souligné" n'ont aucune signification particulière peu
       importe la portion du nom dans laquelle ils se trouvent

    5. Les caractères alphabétiques dans le nom de classe entièrement qualifié PEUVENT être toutes
        combinaisons de minuscules et majuscules.

    6. Tout nom de classe DOIT être référencé en respectant sa casse

3. Lors du chargement d'un fichier qui correspond à un nom 
   de classe entièrement qualifié ...

    1. Une série contiguë d'un ou plusieurs espace(s) ou sous-espace(s) de noms
       située au début du nom de classe entièrement qualifiée, non-compris 
       le séparateur d'espace de noms, (autrement dit le "préfixe d'espace de noms")
       correspond à au moins un "répertoire de base".  

    2. Les sous-espaces de noms contigus situés après le "préfixe d'espace de noms"
       corespondent à des sous-répertoires dans le "répertoire de base",
       dans lequel les séparateurs d'espace de noms représentent des séparateurs
       de répertoires. Le nom des sous-répertoires DOIT respecter la casse des
       sous-espaces de noms. 

    3. Le nom terminal de la classe correspond à un fichier suffixé par `.php`.
       Le nom du fichier DOIT respecter la casse du nom terminal de la classe.

4. Les implémentations de l'auto-chargement NE DOIVENT PAS lancer des exceptions,
   NE DOIVENT PAS générer des erreurs quelque soit le niveau, et NE DEVRAIENT PAS
   retourner une valeur.

## 3. Exemples

Le tableau ci-dessous montre les chemins de fichier correspondant à un nom de classe 
entièrement qualifié donné, un préfixe d'espace de nom et un répertoire de base.

| Nom de classe entièrement qualifié | Préfixe d'espace de noms | Répertoire de base       | Résultat pour le chemin vers le fichier
| ---------------------------------- |--------------------------|--------------------------|--------------------------------------------
| \Acme\Log\Writer\File_Writer       | Acme\Log\Writer          | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status          | Aura\Web                 | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request              | Symfony\Core             | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                          | Zend                     | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

Pour des exemples d'auto-chargement se conformant à cette spécification,
veuillez voir le [fichier d'exemples][]. Les exemples d'implémentations NE
DOIVENT PAS être compris comme faisant partie de cette spécification et
POURRAIENT changer à tout moment.

[auto-chargement]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[fichier d'exemples]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
