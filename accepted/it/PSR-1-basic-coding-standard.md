Standard elementari per la scrittura del codice
================================================

Questa sezione dello standard contiene quelli che dovrebbero essere considerati
elementi standard per la scrittura del codice, necessari per garantire un alto
livello di interoperabilità tecnica tra parti di codice PHP condivise.

Le parole "DEVE/DEVONO/NECESSARIO(I)" ("MUST", "SHALL" O "REQUIRED"),
"NON DEVE/NON DEVONO" ("MUST NOT" O "SHALL NOT"), "DOVREBBE/DOVREBBERO/RACCOMANDATO(I)"
("SHOULD") "NON DOVREBBE/NON DOVREBBERO" ("SHOULD NOT"), "PUÒ/POSSONO" ("MAY") e
"OPZIONALE" ("OPTIONAL") in questo documento devono essere interpretate come
descritto nella [RFC 2119].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/it/PSR-0.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

1. Panoramica
--------------

- Nei file si DEVONO usare soltanto i tag `<?php` o `<?=`.

- I file DEVONO usare soltanto UTF-8 senza BOM per il codice PHP.

- I file DOVREBBERO *o* dichiarare i simboli (classi, funzioni, costanti, etc.)
  *o* causare effetti collaterali (es. generare output, cambiare le impostazioni .ini, etc.)
  ma NON DOVREBBERO fare entrambe le cose.

- I namespace e i nomi delle classi DEVONO seguire un PSR di "autoloading": [[PSR-0], [PSR-4]].

- I nomi delle classi DEVONO essere dichiarati in `StudlyCaps`.

- Le costanti di classe DEVONO essere dichiarate tutte maiuscole con underscore come separatore.

- I nomi dei metodi DEVONO essere dichiarati in `camelCase`.


2. I file
--------

### 2.1. Tag PHP

Nel codice PHP si DEVE usare la versione lunga dei tag `<?php ?>` o la versione
dei tag short-echo `<?= ?>`; NON DEVONO essere usate altre varianti dei tag.

### 2.2. Codifica dei caratteri

Il codice PHP DEVE usare soltanto UTF-8 senza BOM.

### 2.3. Effetti collaterali

Un file DOVREBBE dichiarare nuovi simboli (classi, funzioni, costanti,
etc.) senza causare altri effetti collaterali, oppure DOVREBBE eseguire logica
con effetti collaterali, ma NON DOVREBBE fare entrambe le cose.

Con l'espressione "effetti collaterali" si intende l'esecuzione di logica non
direttamente correlata alla dichiarazione delle classi, delle funzioni, delle
costanti, etc., *al di fuori dell'inclusione del file*.

Gli "effetti collaterali" includono, ma non sono limitati ai seguenti casi:
generazione di output, uso esplicito di `require` o `include`, connessione a
servizi esterni, modifica delle impostazioni ini, emissione di errori o eccezioni,
modifica di variabili globali o statiche, lettura o scrittura di file, e così via.

Di seguito un esempio con un file in cui sono presenti sia dichiarazioni che effetti
collaterali; ecco un esempio di quello che va evitato:

```php
<?php
// effetto collaterale: cambiare le impostazioni ini
ini_set('error_reporting', E_ALL);

// effetto collaterale: carica un file
include "file.php";

// effetto collatarale: generazione di output
echo "<html>\n";

// dichiarazione
function foo()
{
    // corpo della funzione
}
```

L'esempio che segue è un file che contiene dichiarazioni senza effetti
collaterali; ecco un esempio da imitare:

```php
<?php
// dichiarazione
function foo()
{
    // function body
}

// una dichiarazione condizionale *non* è un effetto collaterale
if (! function_exists('bar')) {
    function bar()
    {
        // corpo della funzione
    }
}
```


3. Namespace e Nomi delle Classi
----------------------------

I namespace e i nomi delle classi DEVONO seguire un PSR di "autoloading": [[PSR-0], [PSR-4]].

Questo significa che ogni classe deve essere contenuta in un singolo file
e il suo namespace deve essere di almeno un livello: il primo livello del
nome del vendor.

I nomi delle classi DEVONO essere dichiarati in `StudlyCaps`.

Il codice scritto per PHP 5.3 e versioni successive DEVE usare namespace formali.

Per esempio:

```php
<?php
// PHP 5.3 e successive:
namespace Vendor\Model;

class Foo
{
}
```

Codice scritto per le versioni 5.2.x e precedenti DOVREBBE usare la convenzione
di pseudo-namespace con il prefisso `Vendor_` nei nomi delle classi.

```php
<?php
// PHP 5.2.x e precedenti:
class Vendor_Model_Foo
{
}
```

4. Costanti di Classe, Proprietà, e Metodi
-------------------------------------------

Il termine "classe" si riferisce a tutte le classi, interfacce e trait.

### 4.1. Costanti

Le costanti di classe DEVONO essere dichiarate tutte maiuscole con undescore
come separatori.
Per esempio:

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSIONE = '1.0';
    const DATA_APPROVAZIONE = '2012-06-01';
}
```

### 4.2. Proprietà

Questa guida evita intenzionalmente qualsiasi raccomandazione a proposito
dell'uso di `$StudlyCaps`, `$camelCase`, o `$under_score` per i nomi delle
proprietà.

Qualsiasi sia la convenzione usata per i nomi, questa DOVREBBE essere applicata
in modo consistente ad un livello ragionevole di visibilità. La visibilità
potrebbe essere a livello di vendor, di pacchetto, di classe o di metodo.

### 4.3. Metodi

I nomi dei metodi DEVONO essere dichiarati in `camelCase()`.
