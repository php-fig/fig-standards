PSR-2 Meta Document
===================

1. Panoramica
----------

L'intento di questa guida è di ridurre l'attrito cognitivo quando il codice viene esaminato da diversi autori. Tutto questo è ottenuto grazie ad una serie di regole e aspettative condivise su come formattare il codice PHP.  

Le regole di stile qui riportate derivano dalla condivisione tra i vari membri del progetto. Quando vari autori collaborano su progetti multipli, è di grande aiuto avere un insieme di linee guida da usare in tutti i progetti. Il beneficio di questa guida non è determinato dalle regole in sé, ma dalla
condivisione delle stesse.


2. Votazioni
--------

- **Voto di Approvazione:** [ML](https://groups.google.com/d/msg/php-fig/c-QVvnZdMQ0/TdDMdzKFpdIJ)


3. Errata
---------

### 3.1 - Argomenti Multi-liea (09/08/2013)

Usare uno o più argomenti multi-linea (ie: array o funzioni anonime) non implica una suddivisione della lista degli argomenti stessi, pertanto la Sezione 4.6 non viene automaticamente applicata. Array e funzioni anonime anonime sono in grado di estendersi su più righe.

Il seguente esempio è perfettamente valido in PSR-2:

```php
<?php
somefunction($foo, $bar, [
  // ...
], $baz);

$app->get('/hello/{name}', function ($name) use ($app) { 
    return 'Hello '.$app->escape($name); 
});
```

### 3.2 - Estendere Interfacce Multiple (10/17/2013)

Quando si estendono interfacce multiple, la lista di `extends` deve essere trattata allo stesso modo di una lista di `implements`, come dichiarato nella Sezione 4.1.


