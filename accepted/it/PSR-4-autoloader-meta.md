Meta Documento PSR-4
===================

1. Riassunto
----------

Il proposito è quello di specificare le regole per un autoloader PHP interoperabile
che mappi dai namespace ai path di sistema dei file, e che possa coesistere con
ogni altro autoloader SPL registrato. Questo sarebbe un'aggiunta alla PSR-0,
e non un sostituto.

2. Perché preoccuparsi?
--------------

### Storia di PSR-0

Lo standard PSR-0 per la nomenclatura delle classi e l'autoloading nacque dal
largo consenso delle convenzioni Horde/PEAR sotto i vincoli di PHP 5.2 e precedenti
versioni. Con quella convenzione, la tendenza era quella di mettere tutte le classi
sorgente di PHP in una sola cartella principale, ed usare gli underscore nel
nome della classe per indicare degli pseudo-namespace, in questa maniera:

    /path/to/src/
        VendorFoo/
            Bar/
                Baz.php     # VendorFoo_Bar_Baz
        VendorDib/
            Zim/
                Gir.php     # Vendor_Dib_Zim_Gir

Con il rilascio di PHP 5.3 e la disponibilità di veri namespace, PSR-0 fu introdotto
per permettere *sia* la vecchia modalità underscore Horde/PEAR, *sia* l'uso della
nuova modalità di uso dei namespace. Gli underscore erano ancora permessi nel nome
delle classi per facilitare la transizione dalla vecchia nomenclatura dei namespace
alla nuova, e quindi per incoraggiare una più ampia adozione.

    /path/to/src/
        VendorFoo/
            Bar/
                Baz.php     # VendorFoo_Bar_Baz
        VendorDib/
            Zim/
                Gir.php     # VendorDib_Zim_Gir
        Irk_Operation/
            Impending_Doom/
                V1.php
                V2.php      # Irk_Operation\Impending_Doom\V2

Questa struttura è pesantemente a conoscenza del fatto che l'installatore PEAR
ha spostato i file sorgente dai pacchetti PEAR in un unica cartella centrale.

### L'arrivo di Composer

Con Composer, i codici sorgente dei pacchetti non vengono più copiati in una
singola cartella globale. Sono usati a partire dalla loro cartella di installazione
e non vengono più spostati. Questo comporta che con Composer non esiste più una
"singola cartella centrale" per i sorgenti PHP come con PEAR. Al contrario, ci
sono cartelle multiple; ogni pacchetto si trova in una cartella separata per
ogni progetto.

Per essere conformi ai requisiti di PSR-0, questo porta i pacchetti di Composer
ad apparire in questo modo:

    vendor/
        vendor_name/
            package_name/
                src/
                    Vendor_Name/
                        Package_Name/
                            ClassName.php       # Vendor_Name\Package_Name\ClassName
                tests/
                    Vendor_Name/
                        Package_Name/
                            ClassNameTest.php   # Vendor_Name\Package_Name\ClassNameTest

Le cartelle "src" e "tests" devono includere le cartelle col nome del vendor
e del pacchetto. Questo è un artefatto dell'aderenza allo standard PSR-0.

Molti trovano questa struttura più profonda e ripetitiva del necessario. Questa
proposta suggerisce che una PSR addizionale o sostitutiva sarebbe utile per
ottenere dei pacchetti che somiglino più ad una struttura di questo genere:

    vendor/
        vendor_name/
            package_name/
                src/
                    ClassName.php       # Vendor_Name\Package_Name\ClassName
                tests/
                    ClassNameTest.php   # Vendor_Name\Package_Name\ClassNameTest

Questo richiederebbe l'implementazione di quello che è stato chiamato
"autoloading orientato ai pacchetti" (in contrasto col tradizionale "autoloading
diretto da classe a file").

### Autoloading orientato ai pacchetti

È difficile implementare un autoloading orientato ai pacchetti attraverso una
estensione o un emendamento alla PSR-0, perché la PSR-0 non permette un percorso
intermedio in mezzo a qualunque porzione del nome della classe. Questo significa
che l'implementazione di un autoloading orientato ai pacchetti sarebbe più complicato
della PSR-0. D'altronde, renderebbe possibile avere pacchetti più ordinati.

Inizialmente, sono state proposte le seguenti regole:

1. Gli implementatori DEVONO usare almeno due livelli di namespace: un nome
del vendor, e un nome del pacchetto all'interno di questo vendor. (Questa 
combinazione di due nomi di primo livello è da ora in poi indicata come 
nome vendor-pacchetto o namespace vendor-pacchetto)

2. Gli implementatori DEVONO permettere l'inserzione di un percorso tra il  
namespace vendor-pacchetto e il resto del nome di classe completamente qualificato.

3. Il namespace vendor-pacchetto PUÒ mappare una qualunque cartella. La
porzione restante del nome di classe completamente qualificato DEVE
mappare i nomi dei namespace a cartelle con lo stesso nome, e DEVE mappare
il nome della classe a file con lo stesso nome che terminano con .php.

Da notare che questo implica la fine dell'uso dell'underscore come indicatore
del separatore delle cartelle nel nome della classe. Si potrebbe pensare che gli
underscore debbano essere considerati come nella PSR-0, ma visto come la loro
presenza in quel documento fa riferimento alla transizione da PHP 5.2 e altre
precedenti tecniche di pseudo-namespace, è accettabile rimuoverlo del tutto.


3. Portata
--------

### 3.1 Obiettivi

- Mantenere la regola della PSR-0 che impone che gli implementatori DEVONO 
  usare almeno due livelli di namespace: un nome del vendor e un nome del 
  pacchetto all'interno di quel vendor.

- Permettere una inserzione di percorso tra il namespace vendor-pacchetto e
  il resto del nome di classe completamente qualificato. 

- Permettere che il namespace vendor-pacchetto POSSA mappare una qualunque
  cartella.

- Smettere di utilizzare gli underscore nel nome delle classi come separatori
  di cartelle.

### 3.2 Non-obiettivi

- Fornire un algoritmo di trasformazione per risorse che non siano classi.


4. Approcci
-------------

### 4.1 Approccio scelto

Questo approccio mantiene le caratteristiche chiave della PSR-0 ed elimina
le strutture delle cartelle eccessivamente profonde che venivano da esso richieste.
In aggiunta, specifica alcune regole addizionali per rendere le implementazioni 
maggiormente interoperabili in maniera esplicita.

Anche se non direttamente correlato col mapping delle cartelle, la bozza finale
specifica anche come gli autoloader debbano gestire gli errori. Nello specifico,
vieta il lancio di eccezioni o la creazione di errori. Il motivo è duplice.

1. Gli autoloader in PHP sono specificatamente progettati per essere impilabili
   in modo che se un autoloader non riesce a caricare una classe, un altro possa
   avere l'occasione di provarci. Avere un autoloader che crea una condizione di
   errore impedisce questa compatibilità.
   
2. `class_exists()` e `interface_exists()` permettono "non trovato, anche dopo aver
   provato l'autoload" come un normale e legittimo caso d'uso. Un autoloader che 
   lancia un'eccezione rende `class_exists()` inutilizzabile, cosa assolutamente
   inaccettabile da un punto di vista dell'interoperabilità. Autoloader che vogliano
   fornire informazioni aggiuntive per il debug nel caso in cui non riescano a 
   trovare una classe dovrebbero farlo attraverso i log, con un logger che
   rispetti la PSR-3 o altrimenti.

Vantaggi:

- Strutture delle cartelle meno profonde

- Più flessibilità nella distribuzione dei file

- Eliminare l'uso dell'underscore nei nomi delle classi come separatore delle cartelle

- Rendere le implementazione interoperabili in maniera più esplicita

Svantaggi:

- Non è più sufficiente, come con la PSR-0, semplicemente esaminare il nome
  di una classe per determinare dove si possa trovare nel file system
  (la convezione "da classe a file" ereditata da Horde/PEAR).


### 4.2 Alternativa: Rimanere con la sola PSR-0

Rimanere con la sola PSR-0, per quanto ragionevole, ci fa rimanere con 
strutture di cartelle relativamente più profonde.

Vantaggi:

- Non c'è bisogno di cambiare le abitudini o le implementazioni di nessuno

Svantaggi:

- Rimaniamo con una struttura di cartelle più profonde

- Rimaniamo con gli underscore nei nome delle classi ancora considerati come
  separatori delle cartelle


### 4.3 Alternativa: Dividere autoloading e trasformazione

Beau Simensen e altri suggerirono che l'algoritmo di trasformazione potrebbe
essere separato dalla proposta di autoloading, in modo che le regole di
trasformazione possano essere riferite da altre proposte. Dopo aver lavorato
per separarli, e successivamente aver fatto un sondaggio e un po' di discussioni,
la versione combinata (ovvero le regole di trasformazione integrate nella proposta
per l'autoloader) si è rivelata quella preferita.

Vantaggi:

- Le regole di trasformazione potrebbero essere riferite separatamente da un'altra proposta

Svantaggi:

- Non in linea con le volontà espresse nel sondaggio e di alcuni dei collaboratori

### 4.4 Alternativa: usare un linguaggio più imperativo e narrativo

Dopo che la seconda votazione è stata ritirata da uno sponsor dopo aver saputo
da più di un votante a favore che avevano supportato l'idea ma non erano d'accordo
(o non avevano compreso) la formulazione della proposta, c'è stato un periodo di
tempo durante il quale la proposta sotto votazione è stata espansa con una narrativa
più ampia e un linguaggio in qualche modo più imperativo. Questo approccio è stato
denigrato da una minoranza significativa di partecipanti. Dopo qualche tempo,
Beau Simensen ha cominciato una revisione sperimentale con un occhio alla PSR-0;
l'Editor e gli Sponsor hanno favorito questo approccio più conciso e guidato
verso la versione ora in considerazione, scritta da Paul M. Jones e a cui molti
hanno contribuito.
 

### Note di compatibilità con PHP 5.3.2 e inferiori

Le versioni di PHP prima della 5.3.3 non rimuovono il separatore di namespace
all'inizio del nome, perciò la responsabilità di verificare questa problematica
ricade sulle implementazioni. Dimenticarsi di rimuovere il separatore di namespace
all'inizio del nome può portare a comportamenti non previsti.


5. Persone
---------

### 5.1 Editor

- Paul M. Jones, Solar/Aura

### 5.2 Sponsor

- Phil Sturgeon, PyroCMS (Coordinator)
- Larry Garfield, Drupal

### 5.3 Collaboratori

- Andreas Hennings
- Bernhard Schussek
- Beau Simensen
- Donald Gilbert 
- Mike van Riel
- Paul Dragoonis
- Tanti altri per poterli nominare e contare


6. Votazioni
--------

- **Voto di entrata:** <https://groups.google.com/d/msg/php-fig/_LYBgfcEoFE/ZwFTvVTIl4AJ>

- **Voto di accettazione:**

    - primo tentativo: <https://groups.google.com/forum/#!topic/php-fig/Ua46E344_Ls>,
      presentato prima del nuovo flusso di lavoro; annullato per una modifica accidentale della proposta
      
    - secondo tentativo: <https://groups.google.com/forum/#!topic/php-fig/NWfyAeF7Psk>,
      annullata a discrzione dello sponsor <https://groups.google.com/forum/#!topic/php-fig/t4mW2TQF7iE>
    
    - terzo tentativo: da decidere


7. Link rilevanti
-----------------

- [Autoloader, round 4](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/lpmJcmkNYjM)
- [POLL: Autoloader: Split or Combined?](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/fGwA6XHlYhI)
- [PSR-X autoloader spec: Loopholes, ambiguities](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/kUbzJAbHxmg)
- [Autoloader: Combine Proposals?](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/422dFBGs1Yc)
- [Package-Oriented Autoloader, Round 2](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/Y4xc71Q3YEQ)
- [Autoloader: looking again at namespace](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/bnoiTxE8L28)
- [DISCUSSION: Package-Oriented Autoloader - vote against](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/SJTL1ec46II)
- [VOTE: Package-Oriented Autoloader](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/Ua46E344_Ls)
- [Proposal: Package-Oriented Autoloader](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/qT7mEy0RIuI)
- [Towards a Package Oriented Autoloader](https://groups.google.com/forum/#!searchin/php-fig/package$20oriented$20autoloader/php-fig/JdR-g8ZxKa8/jJr80ard-ekJ)
- [List of Alternative PSR-4 Proposals](https://groups.google.com/forum/#!topic/php-fig/oXr-2TU1lQY)
- [Summary of [post-Acceptance Vote pull] PSR-4 discussions](https://groups.google.com/forum/#!searchin/php-fig/psr-4$20summary/php-fig/bSTwUX58NhE/YPcFgBjwvpEJ)
