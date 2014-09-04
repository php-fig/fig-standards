# Auto-chargement des classes

Les mots clés "DOIT", "NE DOIT PAS", "OBLIGATOIRE", "DEVRA", "NE DEVRA PAS",
"DEVRAIT", "NE DEVRAIT PAS", "RECOMMENDÉ", "PEUT" et "OPTIONNELLE" dans ce
document doivent être interprétés comme décrit dans [RFC 2119][].


## 1. Vue d'ensemble

Ce PSR décrit une specification pour l'[auto-chargement][] des classes depuis
le lien des fichiers. Il est entièrement interopérable, et peut être utilisé en
plus de toute autre spécification d'auto-chargement, y compris [PSR-0] [].
Ce PSR décrit également où placer les fichiers qui seront chargés
automatiquement selon la spécification.


## 2. Specification

1. Le terme "classe" se réfère aux classes, interfaces, traits et autres
   structures similaires.

2. Un nom de classe entièrement qualifié a la forme suivante:

        \<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>

    1. Le nom de classe entièrement qualifié DOIT avoir un namespace de haut
       niveau, aussi connu comme un "espace de noms du fournisseur".

    2. Le nom de classe entièrement qualifié PEUT avoir un ou plusieurs noms de
       sous-namespace.

    3. Le nom de classe entièrement qualifié DOIT finir par un nom de classe.

    4. Les underscores n'ont aucune signification particulière dans quelque
       parti que ce soit du nom de classe entièrement qualifié.

    5. Les caractères alphabétiques dans le nom de classe entièrement qualifié
       PEUVENT être n'importe quelle combinaison de minuscules et majuscules.

    6. Tous les noms de classe DOIVENT être référencés en tenant compte de la
       casse.

3. Lors du chargement d'un fichier qui correspond à un nom de classe entièrement
qualifié ...

    1. Une série contiguë d'un ou plusieurs namespace et sous-namespace,
       n'incluant pas le séparateur de namespace principal, dans le nom de
       classe entièrement qualifié (un "préfixe de namespace") correspond à au
       moins un "répertoire de base".

    2. Les noms contigus de sous-namespace après le "préfixe de namespace"
       correspondent à un sous-répertoire dans un "répertoire de base", dans
       lequel les séparateurs de namespace représentent des séparateurs de
       répertoires. Le nom de sous-répertoire DOIT correspondre à la casse des
       noms de sous-namespaces.

    3. Le nom de la classe de fin correspond à un nom de fichier se terminant
       par `.php`. Le nom du fichier DOIT correspondre à la casse du nom de
       la classe de fin.

4. Les implémentations d'auto-chargement de classe NE DOIVENT PAS créer
   d'exceptions, NE DOIVENT PAS provoquer d'erreur de quelque niveau que ce
   soit, et de NE DEVRAIT PAS retourner de valeur.


## 3. Exemples

Le tableau ci-dessous montre le chemin du fichier correspondant à un nom de
classe entièrement qualifié, le préfixe du namespace, et le répertoire de base.



| Nom de Classe Entièrement Qualifié    | Préfixe du Namespace  | Répertoire de Base        | Chemin du Fichier Résultant
| --------------------------------------|-----------------------|---------------------------|----------------------------
| \Acme\Log\Writer\File_Writer          | Acme\Log\Writer       | ./acme-log-writer/lib/    | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status             | Aura\Web              | /path/to/aura-web/src/    | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request                 | Symfony\Core          | ./vendor/Symfony/Core/    | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                             | Zend                  | /usr/includes/Zend/       | /usr/includes/Zend/Acl.php

Pour des exemples d'implémentations d'auto-chargement conformes à la
spécification, voir les [fichiers d'exemple][]. Les exemples d'implémentations
NE DOIVENT PAS être considérées comme faisant partie de la spécification et
PEUVENT changer à tout moment.


[RFC 2119]: http://tools.ietf.org/html/rfc2119
[auto-chargement]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[fichiers d'exemple]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
