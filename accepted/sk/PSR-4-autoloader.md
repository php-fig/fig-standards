# Autoloader

Kľúčové slová "MUSÍ", "NESMIE", "POTREBNÉ", "SMIE", "NESMIE", "MALO BY",
"NEMALO BY", "ODPORÚČANÉ", "MôŽE", and "NEPOVINNÉ" v tomto dokumente sú vo význame
ako opísané v [RFC 2119](http://tools.ietf.org/html/rfc2119).

## 1. Prehľad

Toto PSR opisuje špecifikáciu pre samonačítanie [autoloading][] tried z ciest 
súborov. Je plne nahraditeľné, a môže byť použité spolu s inou samonačítavacou
špecifikáciou, vrátane [PSR-0][]. Toto PSR tiež popisuje kde umiestniť súbory,
ktoré sa budú samonačítavať podľa tejto špecifikácie.

## 2. Špecifikácia

1. Výraz "trieda" odkazuje na triedy, rozhrania, traits a ostatné podobné 
   štruktúry.

2. Plné meno triedy má nasledujúci tvar:

        \<MenoMennehoPriestoru>(\<MenaVnorenychMennychPriestorov>)*\<MenoTriedy>

    1. Plné meno triedy MUSÍ mať menný priestor najvyššej úrovne, tiež známy ako
       "vendor namespace" (menný priestor výrobcu).

    2. Plné meno triedy MôŽE obsahovať jedno alebo viac mien vnorených menných
       priestorov.

    3. Plné meno triedy MUSÍ byť zakončené menom triedy.

    4. Podtržítka nemajú žiadny špeciálny význam v žiadnej časťi plného mena triedy.

    5. Abecedné znaky v plnom mene triedy MôŽU byť kombináciou veľkých
       a malých pismen.

    6. Všetky mená tried MUSIA byť uvádzané s ohľadom na velkosť písmen (case-sensitive).

3. Keď načítavame súbor, ktorý sa zhoduje s plným menom triedy ...

    1. Séria jednej alebo viacerých susediacich menných a vnorených menných 
       priestorov od začiatku bez začiatočného oddelovača menných priestorov `\` 
       korešponduje aspoň s jedným koreňovým adresárom. Takúto sériu nazývame
       predponoou menného priestoru (namespace prefix)

    2. Séria mien vnorených menných priestorov po predpone menneho priestoru
       korešponduje s podadresárom v korenoňovom adresári, v ktorom oddelovače
       menných priestorov predstavujú oddelovače adresárov. Mená podadresárov sa
       MUSÍ zhodovať s menami vnorených menných priestorov s ohľadom na veľké a
       malé písmená v názvoch.

    3. Meno triedy na konci korešponduje s menom súboru a končiacim s `.php`
       príponou. Meno súboru sa MUSÍ zhodovať s menom triedy s ohľadom na veľké
       a malé písmena v názvoch.

4. Implementácia autoloaderu NESMIE vyhadzovať výnimky, NESMIE vyvolávať chyby
   žiadnej úrovne a NEMALA BY vracať hodnotu.


## 3. Príklady

Tabuľka nižšie ukazuje cestu, ktorá korešponduje s plným menom triedy, predponu menného priestoru a koreňový adresár.

| Plné meno triedy              | Namespace Prefix   | Koreňový adresár         | Konečná cesta k súboru
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

Pre príkladné implementácie autoloaderov podľa tejto špecifikácie si pozrite [súbor príkladov][]. 
Príklady implementácie NESMÚ byť považované za súčasť špecifikácie a MôŽU sa zmenit časom.

[autoloading]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[súbor príkladov]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
