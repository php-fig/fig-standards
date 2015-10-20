# Autoloader

Le parole "DEVE/DEVONO", "NON DEVE/NON DEVONO",  "DOVREBBE/DOVREBBERO", "NON 
DOVREBBE/NON DOVREBBERO", "RACCOMANDATO(I)", "PUÒ/POSSONO" e "OPZIONALE" in 
questo documento devono essere interpretate come descritto nella 
[RFC 2119](http://tools.ietf.org/html/rfc2119).

## 1. Panoramica

Questa PSR descrive una specifica per eseguire l'[autoloading][] delle classi 
da file. È completamente interoperabile, e può essere usata in aggiunta ad ogni 
altra specifica di autoloading, inclusa la [PSR-0][]. Questa PSR inoltre descrive 
dove posizionare i file che saranno caricati in automatico in modo conforme 
alla specifica.

## 2. Specifica

1. Il termine "classe" si riferisce alle classi, interfacce, trait e altre 
   strutture simili.

2. Un nome di classe completamente qualificato segue questo formato:

        \<NomeNamespace>(\<NomeSottoNamespace>)*\<NomeClasse>

    1. Il nome di classe completamente qualificato DEVE aver un nome di 
       namespace di primo livello, detto anche "namespace del vendor".

    2. Il nome di classe completamente qualificato PUÒ avere uno o più
       nomi di sotto-namespace.

    3. Il nome di classe completamente qualificato DEVE terminare con il
       nome della classe.

    4. Gli underscore non hanno nessun significato speciale in alcuna parte
       del nome di classe completamente qualificato.

    5. I caratteri alfabetici all'interno del nome di classe completamente 
       qualificato POSSONO essere una qualunque combinazione di caratteri
       maiuscoli o minuscoli.

    6. Tutti i nomi di classe DEVONO essere riportati tenendo conto delle 
       maiuscole o minuscole.

3. Quando si carica un file che corrisponde ad un nome di classe completamente
   qualificato...

    1. Una serie contigua di uno o più nomi di namespace e sotto-namespace 
       posti all'inizio, escludendo il separatore di namespace posto in testa, 
       prensenti nel nome di classe completamente qualificato (un "prefisso di 
       namespace"), corrisponde ad almeno una "cartella di base".
    
    2. I nomi di sotto-namespace contigui che seguono il "prefisso di namespace"
       corrispondono ad una sotto-cartella all'interno della "cartella di base",
       in cui i separatori di namespace rappresentano i separatori di cartella.
       I nomi delle sotto-cartelle DEVONO corrispondere ai nomi dei sotto-namespace
       in termini di maiuscole e minuscole.
    
    3. Il nome di classe finale deve corrispondere ad un nome di file che
       termina con `.php`. Il nome del file DEVE corrispondere al nome della
       classe in termini di maiuscole e minuscole.

4. Le implementazioni dell'autoloader NON DEVONO lanciare eccezioni, NON DEVONO
   causare errori di qualunque livello, e NON DOVREBBERO ritornare alcun valore.

## 3. Esempi

La tabella sottostante mostra il percorso completo corrispondente ad un dato 
nome di classe completamente qualificato, prefisso di namespace e cartella di base.
 
| Nome completamente qualif.    | Pref. di Namespace | Cartella di base         | Percorso completo corrispondente
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

Per implementazioni di esempio di autoloader conformi alle specifiche, vedere il 
[file di esempio][]. Le implementazioni di esempio NON DEVONO essere considerate
come parte delle specifiche e POTREBBERO cambiare in ogni momento.

[autoloading]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[file di esempio]: https://github.com/php-fig/fig-standards/blob/master/accepted/it/PSR-4-autoloader-examples.md
