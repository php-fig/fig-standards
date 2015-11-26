PSR-4 Dodatok
=============

1. Zhrnutie
----------

Účelom je špecifikovanie pravidiel pre spoluprácu PHP autoloadera, ktorý 
mapuje menné priestory do ciest súboroých systémov a ktorý môže existovať spolu
s ostatnými registrovanými SPL autoloadermi. Týmto vlastne doplňuje PSR-0 a nie
ho nahrádza.

2. Prečo sa unúvať?
--------------

### Minulosť PSR-0

Štandard o pomenúvaní a autoloadovaní PSR-0 vyrástol po širšom prijatí Horde/PEAR
dohody a pod tlakom PHP 5.2 a predošlých. Podľa tej dohody, zámerom bolo položiť
všetky zdrojové triedy PHP do jedného hlavného adresára a používaním podtržítka
v menách tried sa určovali pseudo menné priestory, napríklad takto:

    /path/to/src/
        VendorFoo/
            Bar/
                Baz.php     # VendorFoo_Bar_Baz
        VendorDib/
            Zim/
                Gir.php     # Vendor_Dib_Zim_Gir

S vydaním PHP 5.3 a s dostupnosťou skutočných menných priestorov bolo predstavené
PSR-0 aby bolo možné používať oba spôsoby, starý podtržítkový Horde/PEAR mód *a* 
nový spôsob s mennými priestormi. Podtržítka boli stále povolené v mene triedy aby
sa uľahčil prechod zo starého spôsobu pomenovávania menných prirestorov na novší
a tým sa tak podporilo širšie prijatie štandardu.

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

Táto štruktúra je dobre informovaná o fakte že inštalátor PEARu presunul súbory
z adresára PEAR balíkov do jedného centrálneho adresára.

### V tom prichádza Composer

Súbory balíkov už nie sú kopírované do jedného globálneho adresára. Používajú sa
z adresára v ktorom sú inštalované a nepresúvajú sa sem a tam. To znamená že
s Composerom nemáme jeden hlavný adresár pre PHP súbory ako s PEARom. Namiesto toho
sú v mnohých adresároch, každý balík je vo vlastnom adresári pre každý jeden projekt.

Aby sa zároveň splnili požiadavky PSR-0, tak balíky Composera vypadajú takto:

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

Adresáre "src" a "tests" musia zahrnúť aj mená adresárov vendora a balíka.
Toto je predmetom dodržiavania PSR-0.

Mnohý považujú toto členenie za hlbšie ako potrebné a viacej sa opakujúce. Tento
návrh navrhuje, že dodatočné alebo nahradzujúce PSR by bolo užitočné tak,
že môžme mať balíky, ktoré budú vypadať takto:

    vendor/
        vendor_name/
            package_name/
                src/
                    ClassName.php       # Vendor_Name\Package_Name\ClassName
                tests/
                    ClassNameTest.php   # Vendor_Name\Package_Name\ClassNameTest

Toto by potrebovalo implementáciu pôvodne nazývanú ako *balíkovo-orientované 
autoloadovanie* (oproti tradičnému *priame autoloadovanie triedy na súbor*).

### Balíkovo-orientované Autoloadovanie

Je ťažké implementovať balíkovo-orientované autoloadovanie rozšírením alebo
zmenením PSR-0, pretože PSR-0 nedovoľuje skracovanie adresárovej cesty 
žiadnej časti z plného mena triedy. To znamená že implementovanie 
balíkovo-orientovaného autoloadovania by bolo komplikovanejšie ako PSR-0,
aj napriek tomu, že by nám povolilo jednoduchšie balíky.

Pôvodne boli navrhnuté tieto pravdilá:

1. Implementétor MUSÍ použiť aspoň dve úrovne menných priestorov: meno 
poskytovateľa a meno balíka od daného poskytovateľa. Táto dvojúrovňová
kombinácia je potom označovaná ako poskytovateľ-balík alebo menný priestor
poskýtovateľ-balík

2. Implementátor MUSÍ dodržať cestu medzi menným priestorom poskytovateľ-balík
a zvyškom plného mena triedy.

3. Menný priestor poskytovateľ-balík MôŽE byť namapovaný do hociktorého 
adresára. Zvyšná časť plného mena triedy MUSÍ mapovať mená menných priestorov
do zhodne nazvaných adresárov a MUSÍ mapovať meno triedy do zhodne nazvaného
súboru s príponou .php.

Všimnite si, že toto znamená koniec podtržítkových oddeľovačov adresárov
v menách tried. Mohli by sme predpokladať, že kvôli zachovaniu spätnej
kompatibility s PSR-0 sa podtržítka budú naďalej akceptovať, ale kvôli
odklonu od PHP 5.2 a predošlých pseudo menných priestorov sa rozhodlo,
že bude prijateľné ak sa odstránia tu.


3. Rámec
--------

### 3.1 Ciele

- Zachovať pravidlo z PSR-0, podľa ktorého implementátori MUSIA použiť aspoň
  dve úrovne menných priestorov a to: meno poskytovateľa a meno balíka v ňom.

- Umožniť pevnú cestu medzi mennyćh priestorom poskytovateľ-balík a zvyškom
  plného mena triedy.

- Umožniť aby menný priestor poskytovateľ-balík MOHOL byť namapovaný do 
  hociktorého adresára, možno aj do viacerých adresárov.

- Ukončiť rešpektovanie podtržítka v mene triedy ako oddeľovača adresárov.

### 3.2 Nie Ciele

- Poskytnúť všeobecnú transformačnú sadu pravidiel pre zdrojové kódy, ktoré
  nie sú v triedach.


4. Postupy
----------

### 4.1 Vybratý postup

Tento prístup zachová kľúčové charakteristiky PSR-0 a zároveň odstráni hlbšie 
štruktúry adresárov ktoré potrebuje. Zároveň, špecifikuje určité dodatočné 
pravidlá, vďaka ktorým budú implementácie výslovne lepšie použiteľné.

Hoci konečný návrh nie je prepojený s mapovaním adresárov, buďe tiež
špecifikovať ako autoloader spracúvava chyby. Osobitne, zakazuje vyhadzovanie
výnimiek a tvorenie akýchkoľvek chýb. Dôvod je dvojaký.

1. Autoloadery v PHP sa skladajú na seba, to znamená že ak jeden autoloader nevie
načítať triedy, tak ďaľší v poradí má šancu to urobiť. Ak by autoloader vyhadzoval
chyby, tak by sa narušila táto zľúčiteľnosť.

2. `class_exists()` a `interface_exists()` povoľujú "nenájdené, aj po vyskúšaní
autoloadu" ako platný a normálny stav. Autoloader, ktorý hádže výnimky spôsobí 
`class_exists()` nepoužiteľným, čo je absolútne neprijateľné z pohľadu 
použiteľnosti. Autoloadery ktoré chcú poskytovať dodatočné informácie 
na ľadenie chýb pre prípady nenajdených tried by tak mali robiť zapisovaním
do záznamov (logov), buď do PSR-3 kompatibilných záznamov alebo iných.

Pre:

- Menej hlboká štruktúra adresárov

- Flexibilnejšie umiestnenie súborob

- Ukončenie podpory podtržítka v mene triedy ako oddeľovača adresára

- Implementácie sú výslovne lepšie spolupracujúce

Proti:

- Už viac nie je možné vďaka PSR-0 určiť vďaka menu triedu, kde sa fyzicky
  daný súbor nachádza v súborovom systéme (konvencia "trieda-subor" konvencia)
  zdedená z Horde/PEAR).


### 4.2 Alternatíva: Zostať iba pri PSR-0

Ak zostaneme iba pri PSR-0, tak nám ostane pomerne hlbšia adresárová štruktúra.

Pre:

- Nie je potreba zmeniť nikoho zvyky a implementácie

Proti:

- Ostane nám hlbšia adresárová štruktúra

- Zostanú nám podtržítka v menách tried, ktoré sa budú počítať ako oddelovače
  adresárov


### 4.3 Alternatíva: Rozdeliť Autoloadovanie a transformovať

Beau Simensen a ostatní navrhovali že sada pravidiel na transformácia by mala 
byť odčlenená z návrhu na nový autoloader tak, že pravidlá transformácie by
mali byť udané v iných návrhoch. Po tom ako táto sada oddelila, nasledovalo
hlasovanie  a diskusia, sa rozhodlo, že preferovaná verzia bude kombinovaná.
To znamená, pravidlá pre transformáciu budu súčasťou návrhu autoloader.

Pre:

- Transformačné pravidlá by mohli byť odkazované osobitne inými návrhmi.

Proti:

- Nie celkom v súlade so želaniami respondentov ankety a spolupracovníkov

### 4.4 Alternatíva: Používat viacej prikazujúci a vodiaci jazyk

Po druhom hlasovaní, a po tom ako navrhovateľ počul od mnohých hlasujúcich, že
podporili návrh, ale nerozumeli alebo nesúhlasili celkom zneniu návrhu, tu
bolo obdobie, kedy odhlasovaný návrh bol prepísaný s viacej prikazujúcim tónom
a širším vysvetlovaním. Tento prístup bol kritizovaný hlasnou menšinou 
zúčastnených. Po nejakom čase začal Beau Simensen s experimentálnou opravou
s prihliadaním na PSR-0. Navrhovateľ a pozmenovatelia si obľúbili tento stručný
prístup a doviedli túto verziu k rozhodovaniu, spísanú Paulom M. Jones-eom 
s prispením mnohých ďaľších.

### Poznámky ku kompatibilite s PHP 5.3.2 a nižšie

PHP verzie pre 5.3.3 neodoberajú začiatočný oddelovač menného priestoru, takže
je povinnosťou implementácie sa postarať o toto. Bez odobratia začiatočného
oddelovača by mohlo prísť k neočakávanému chovaniu.


5. People
---------

### 5.1 Editor

- Paul M. Jones, Solar/Aura

### 5.2 Sponsors

- Phil Sturgeon, PyroCMS (Coordinator)
- Larry Garfield, Drupal

### 5.3 Contributors

- Andreas Hennings
- Bernhard Schussek
- Beau Simensen
- Donald Gilbert 
- Mike van Riel
- Paul Dragoonis
- Too many others to name and count


6. Votes
--------

- **Entrance Vote:** <https://groups.google.com/d/msg/php-fig/_LYBgfcEoFE/ZwFTvVTIl4AJ>

- **Acceptance Vote:**

    - 1st attempt: <https://groups.google.com/forum/#!topic/php-fig/Ua46E344_Ls>,
      presented prior to new workflow; aborted due to accidental proposal modification
      
    - 2nd attempt: <https://groups.google.com/forum/#!topic/php-fig/NWfyAeF7Psk>,
      cancelled at the discretion of the sponsor <https://groups.google.com/forum/#!topic/php-fig/t4mW2TQF7iE>
    
    - 3rd attempt: TBD


7. Relevant Links
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
