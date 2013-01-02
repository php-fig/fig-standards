La section suivante décrit les exigences obligatoires qui doivent être respectées pour l'interopérabilité avec un chargeur de classes.

Obligatoire
-----------

* Classes et espaces de noms entièrement qualifiés doivent disposer de la structure suivante
  `\<Nom du Vendor>\(<Espace de noms>\)*<Nom de la Classe>`.
* Chaque espace de noms doit avoir un espace de noms racine. ("Nom du Vendor").
* Chaque espace de noms peut avoir autant de sous-espaces de noms qu'il le souhaite.
* Chaque séparateur d'un espace de noms est converti en  `DIRECTORY_SEPARATOR` lors du chargement à partir du système de fichiers.
* Chaque "\_" dans le nom d'une CLASSE est converti en `DIRECTORY_SEPARATOR`. Le caractère "\_" n'a pas de signification particulière dans un espace de noms.
* Les classes et espaces de noms complètement qualifiés sont suffixés avec ".php" lors du chargement à partir du système de fichiers.
* Les caractères alphabétiques dans les noms de vendors, espaces de noms et noms de classes peuvent contenir n'importe quelle combinaison de minuscules et de majuscules.

Exemples
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/chemin/vers/projet/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/chemin/vers/projet/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/chemin/vers/projet/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/chemin/vers/projet/lib/vendor/Zend/Mail/Message.php`

Underscore dans les Espaces de Noms et Noms de Classes
------------------------------------------------------

* `\espace de noms\package\Class_Name` => `/chemin/vers/projet/lib/vendor/espace de noms/package/Class/Name.php`
* `\espace de noms\package_name\Class_Name` => `/chemin/vers/projet/lib/vendor/espace de noms/package_name/Class/Name.php`

Les standards établis ici doivent avoir le plus petit dénominateur commun pour assurer une bonne interopérabilité des chargeurs de classes. Vous pouvez vérifier que vous respectez ces standards via l'utilisation de l'implémentation d'exemple de SplClassLoader qui est capable de charger les classes PHP 5.3.

Exemple d'Implémentation
------------------------

Le code ci-dessous est une fonction d'exemple afin de montrer comment les standards proposés ci-dessus peuvent être chargés automatiquement.

```php
<?php

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
```

Implémentation SplClassLoader
-----------------------------

Le gist suivant est une implémentation d'exemple de SplClassLoader qui permet de charger vos classes si vous respectez les standards d'interopérabilité proposés ci-dessus pour automatiquement charger des classes. C'est la façon actuelle recommandée pour charger des classes PHP 5.3 qui respectent ces standards.

* [http://gist.github.com/221634](http://gist.github.com/221634)

