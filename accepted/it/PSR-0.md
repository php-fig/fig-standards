Quanto segue descrive i requisiti necessari ai quali ci si deve uniformare
per garantire l'interoperabilità tra gli autoloader.

Obblighi
---------

* Il fully-qualified namespace e la classe devono avere la seguente
  struttura `\<Nome Vendor>\(<Namespace>\)*<Nome Classe>`
* Ogni namespace deve avere un namespace di primo livello ("Nome Vendor").
* Ogni namespace può avere una quantità arbitraria di sotto-namespace.
* Ogni separatore di namespace deve essere convertito in un `DIRECTORY_SEPARATOR` al
  caricamento da file system.
* Ogni carattere `_` nel NOME DELLA CLASSE deve essere convertito in un
  `DIRECTORY_SEPARATOR`. Il carattere `_` non ha nessun significato particolare nel
  namespace.
* Al fully-qualified namespace e alla classe viene apposto il suffisso `.php` al
  caricamento da file system.
* I caratteri alfabetici nei nomi dei vendor, nei namespace, e nei nomi delle classi possono
  formare una qualsiasi combinazione di caratteri minuscoli e caratteri maiuscoli.

Esempi
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/percorso/del/mio/progetto/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/percorso/del/mio/progetto/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/percorso/del/mio/progetto/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/percorso/del/mio/progetto/lib/vendor/Zend/Mail/Message.php`

Underscore nei Namespace e nei Nomi delle Classi
-------------------------------------------------

* `\namespace\package\Nome_Classe` => `/percorso/del/mio/progetto/lib/vendor/namespace/package/Nome/Classe.php`
* `\namespace\package_name\Nome_Classe` => `/percorso/del/mio/progetto/lib/vendor/namespace/package_name/Nome/Classe.php`

Gli standard che abbiamo fissato dovrebbero essere il minimo comune
denominatore per l'interoperabilità indolore tra gli autoloader. Puoi
verificare l'aderenza del tuo codice agli standard utilizzando questa
implementazione esemplificativa dell'SplClassLoader, in grado di caricare
classi PHP 5.3.

Esempio di implementazione
---------------------------

Di seguito una funzione per dimostrare im modo semplice come gli standard
proposti in precedenza permettano il caricamento automatico delle classi.

```php
<?php

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
```

Implementazione dell'SplClassLoader
----------------------------------

Il seguente gist è un esempio di implementazione dell'SplClassLoader
che sarà in grado di caricare automanticamente le tue classi se
segui gli standard di interoperabilità proposti in precedenza. È il
modo attualmente raccomandato per caricare le classi PHP 5.3 che adottano
questi standard.

* [http://gist.github.com/221634](http://gist.github.com/221634)
