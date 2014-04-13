PSR-4 meta dokument
===================

1. Povzetek
-----------

Namen je določiti pravila za interoperabilni PHP avtomatski nalagalnik, ki
preslika imenske prostore v poti datotečnega sistema in da lahko so-obstoja s katerim koli ostalim SPL
registriranim avtomatskim nalagalnikom. To bi bil dodatek in ne zamenjava za
PSR-0.

2. Zakaj se truditi?
--------------------

### Zgodovina PSR-0

PSR-0 poimenovanja razreda in standard avtomatskega nalagalnika so zrastla iz širokega
sprejetja konvencij Horde/PEAR pod omejitvami PHP 5.2 in
prejšnjih. S to konvencijo je bila težnja dati vse PHP izvorne razrede
v en glavni direktorij, uporabljati podčrtaje v imenih razredov za navedbo
pseudo imenskih prostorov, takole:

    /path/to/src/
        VendorFoo/
            Bar/
                Baz.php     # VendorFoo_Bar_Baz
        VendorDib/
            Zim/
                Gir.php     # Vendor_Dib_Zim_Gir

Z izdajo PHP 5.3 in razpoložljivostjo pravilnih imenskih prostorov, je bil
predstavljen PSR-0, ki omogoča tako stari način Horde/PEAR *in* uporabo
novih zapisov imenskih prostorov. Podčrtaji so bili še vedno dovoljeni v imenu
razreda za poenostavitev prehoda iz starih imen imenskih prostorov v novo poimenovanje
in s čimer se spodbuja širše sprejetje.

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

Ta struktura je zelo informirana z dejstvom, da je PEAR namestitveni program prestavljal
izvorne datoteke iz PEAR paketov v en centralni direktorij.

### Tu prihaja Composer

S Composer-jem viri paketov niso več kopirani na eno globalno
lokacijo. Uporabljeni so iz njihovih namestitvenih lokacij in niso prestavljeni
naokrog. To pomeni, da s Composer-jem ni "enega glavnega direktorija" za
PHP vire kot pri PEAR. Namesto tega je več direktorijev; vsak
paket je v ločenem direktoriju za vsak projekt.

Za zadostitev zhtev PSR-0, to vodi, da paketi Composer-ja izgledajo
takole:

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

Direktorija "src in "tests" morata vsebovati imeni direktorija izdelovalca in
paketa. To je artefakt skladnosti PSR-0.

Lahko se zdi, da bi bila ta struktura globlja in bolj ponovljiva kot potrebno. Ta
se predlaga, da dodatni ali nadomestni PSR bi bil uporaben, da
imamo lahko pakete, ki izgledajo sledeče:

    vendor/
        vendor_name/
            package_name/
                src/
                    ClassName.php       # Vendor_Name\Package_Name\ClassName
                tests/
                    ClassNameTest.php   # Vendor_Name\Package_Name\ClassNameTest

To bi zahtevalo implementacijo, kar je bilo na začetku imenovano
"paketno-orientirano avtomatsko nalaganje" (kot je napram tradicionalnem avtomatskem
direktnem nalaganju "razred-v-datoteko").

### Paketno-orientirano avtomatsko nalaganje

Implementirati paketno-orientirano avtomatsko nalaganje je zahtevno preko razširitve ali
predloga spremembe za PSR-0, ker PSR-0 ne dovoljuje posredovalne poti
med katerimkoli delom imena razreda. To pomeni, da implementacija
paketno-orientiranega avtomatskega nalagalnika bi bila bolj zakomplicirana kot PSR-0. Vendar
bi dovoljevala čistejše pakete.

Sprva so bila predlagana sledeča pravila:

1. Implementatorji MORAJO uporabljati vsaj dva nivoja imenskih prostorov: ime izdelovalca in
ime paketa znotraj tega izdelovalca. (Ta zgornji nivo dvo-imenske kombinacije je
v nadaljevanju sklican kot ime izdelovalec-paket ali izdelovalec-paket
imenski prostor.)

2. Implementatorji MORAJO dovoljevati vpliv poti med izdelovalec-paket imenskim prostorom
in preostankom celotno kvalificiranega imena razreda.

3. Imenski prostor izdelovalec-paket LAHKO preslikuje na katerikoli direktorij. Preostali
del celotno kvalificiranega imena razreda MORA preslikati ime imenskega prostora v
identično-poimenovane direktorije in MORA preslikati ime razreda v
identično-poimenovano datoteko s končnico .php.

Bodite pozorni, saj to pomeni konec podčrtaj-kot-direktorij-ločila v imenu
razreda. Lahko si mislite, da morajo biti te podčrtaji počaščeni, saj so pod
PSR-0, vendar kot se gleda njihovo prisotnost v tistem dokumentu je v referencu
k premiku stran od PHP 5.2 in predhodnih pseudo-imenskih prostorov, tako da jih je
sprejemljivo odstraniti tudi tukaj.


3. Obseg
--------

### 3.1 Cilji

- Ohranitev pravila PSR-0, da implementatorji MORAJO uporabljati vsaj dva nivoja imenskega
  prostora: ime izdelovalca in ime paketa znotraj tega izdelovalca.

- Dovoliti pot vpliva med izdelovalec-paket imenskim prostorom in preostankom
  celotno kvalificiranega imena razreda.

- Dovoliti izdelovalec-paket imenski prostor, da LAHKO preslika v katerikoli direktorij, verjetno
  več direktorijev.

- Končati čaščenje podčrtajev v imenih razredov kot ločila direktorijev

### 3.2 Niso cilji

- Ponuditi splošni pretvorbeni algoritem za vire ne-razredov


4. Pristopi
-----------

### 4.1 Izbrani pristop

Ta pristop ohranja ključne karakteristike PSR-0 med tem ko eliminira
globljo strukturo direktorijev, ki je zahtevana. Kot dodatek določa nekatera
dodatna pravila, ki delajo implementacije eksplicitno bolj interoperabilne.

Čeprav ni vezano na preslikave direktorijev, končni osnutek tudi določa, kako
bi avtomatski nalagalniki morali ravnati z napakami. Posebej prepoveduje vreči izjeme
ali dvigniti napake. Razlog je dvo-plasten.

1. Avtomatski nalagalniki v PHP so eksplicitno načrtovani, da so zložljivi tako da če nek
avtomatski nalagalnik ne more naložiti razreda, ima drug priložnost, da to naredi. Ko avtomatski nalagalnik
sproži večjo napako, pogoj krši to kompatibilnost.

2. `class_exists()` in `interface_exists()` omogočata "ni najdeno, tudi po poskusu
avtomatskega nalaganja" kot legitimen običajen primer uporabe. Avtomatski nalagalnik, ki vrže izjeme
izpiše `class_exists()` neuporabnega, kar je v celoti nesprejemljivo iz vidika
interoperabilnosti. Avtomatski nalagalniki, ki želijo ponujati dodatne razhroščevalne informacije
v primeru razred-ni-najdem, bi morali narediti to namesto tega preko dnevnikov, ali preko PSR-3
kompatibilnega dnevnika ali drugače.

Prednosti:

- Plitkejše strukture direktorijev

- Bolj fleksibilne lokacije datotek

- Končanje podčrtajev v imenih razredov, da se jih časti kot ločilo direktorijev

- Narediti implementacije bolj eksplicitno interoperabilne

Slabosti:

- Ni več mogoče pod PSR-0 se zgolj pregledati ime razreda, da
  določa, kje je v datotečnem sistemu (konvencija "razred-v-datoteko"
  podedovana iz Horde/PEAR).


### 4.2 Alternativa: Ostanite pri samo PSR-0

Ostati samo s PSR-0 je sicer razumno in nas pusti z relativno
globljo strukturo direktorijev.

Prednosti:

- Ni potrebe po spremembi navad kogarkoli ali implementacij

Slabosti:

- Pusti nas z globljo strukturo direktorijev

- Pusti nas s podčrtaji v imenu razreda, kar se časti kot ločila
  direktorijev


### 4.3 Alternativa: Razdelitev avtomatskega nalagalnika in pretvorb

Beau Simensen in ostali predlagajo, da se algoritem pretvorbe lahko
izloči iz predloga avtomatskega nalagalnika, da so pravila pretvorbe
lahko sklicana iz ostalih predlogov. Po početju izločitve,
ki ji je sledila anketa in nekaj razprave, kombinirana verzija (t.j.,
pretvorba pravil priloženih v predlog avtomatskega nalagalnika) se je pokazala kot
prednost.

Prednosti:

- Pravila pretvorbe bi bila lahko sklicana ločeno z ostalimi predlogi

Slabosti:

- Ni v skladu z željami anketirancev in nekaterih sodelavcev

### 4.4 Alternativa: Uporaba bolj nujnih in pripovednih jezikov

Ko je bilo izvedeno drugo glasovanje s strani sponzorja, ko je bilo slišano za mnoge +1
glasovalce, da podpirajo idejo, vendar se ne strinjajo (ali razumejo)
besede predloga, je bilo drugo obdobje, ki glasovalni predlog
razširi z večjo pripovednostjo in nekako bolj nujnim jezikom. Ta
pristop je dekodirala vokalna večina udeležencev. Po določenem času je Beau
Simensen pričel poiskusno revizijo z očesom na PSR-0; Urednik in
sponzorji so se zavzemali za ta bolj zgoščen pristop in so ravnali z verzijo pod
premislekom, napisanim s strani Paul M. Jones-a in mnogih ostalih, ki so prispevali.

### Opomba kompatibilnosti s PHP 5.3.2 in manjšimi

PHP verzije pred 5.3.3 ne izolirajo vodilnih ločil imenskega prostora, tako da
je odgovornost na to paziti padla na implementacijo. Neuspešna
izolacija vodilnih ločil imenskega prostora bi lahko vodila k nepričakovanim obnašanjem.


5. Ljudje
---------

### 5.1 Urednik

- Paul M. Jones, Solar/Aura

### 5.2 Sponzorji

- Phil Sturgeon, PyroCMS (Coordinator)
- Larry Garfield, Drupal

### 5.3 Prispevali so

- Andreas Hennings
- Bernhard Schussek
- Beau Simensen
- Donald Gilbert
- Mike van Riel
- Paul Dragoonis
- In mnogi ostali, ki jih je preveč, da bi jih poimensko naštevali in imenovali


6. Glasovi
----------

- **Uvodno glasovanje:** <https://groups.google.com/d/msg/php-fig/_LYBgfcEoFE/ZwFTvVTIl4AJ>

- **Glasovanje sprejetja:**

    - 1. poskus: <https://groups.google.com/forum/#!topic/php-fig/Ua46E344_Ls>,
      predstavljen pred novim načinom dela; prekinjen zaradi naključne spremembe predloga
      
    - 2. poskus: <https://groups.google.com/forum/#!topic/php-fig/NWfyAeF7Psk>,
      preklican pri diskretnosti sponzorja <https://groups.google.com/forum/#!topic/php-fig/t4mW2TQF7iE>
    
    - 3. poskus: Bo še določen


7. Ustrezne povezave
--------------------

- [Avtomatski nalagalnik, 4. krog](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/lpmJcmkNYjM)
- [ANKETA: Avtomatski nalagalnik: Razdelitev ali kombiniranje?](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/fGwA6XHlYhI)
- [PSR-X specifikacije avtomatskega nalagalnika: Vrzeli, nejasnosti](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/kUbzJAbHxmg)
- [Avtomatski nalagalnik: Združitev predlogov?](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/422dFBGs1Yc)
- [Paketno-orientirani avtomatski nalagalnik, 2. krog](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/Y4xc71Q3YEQ)
- [Avtomatski nalagalnik: ponovni pregled imenskega prostora](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/bnoiTxE8L28)
- [RAZPRAVA: Paketno-orientirani avtomatski nalagalnik - glasovanje proti](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/SJTL1ec46II)
- [GLASOVANJE: Paketno-orientirani avtomatski nalagalnik](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/Ua46E344_Ls)
- [Predlog: Paketno-orientirani avtomatski nalagalnik](https://groups.google.com/forum/#!topicsearchin/php-fig/autoload/php-fig/qT7mEy0RIuI)
- [Proti paketno orientiranemu avtomatskemu nalagalniku](https://groups.google.com/forum/#!searchin/php-fig/package$20oriented$20autoloader/php-fig/JdR-g8ZxKa8/jJr80ard-ekJ)
- [Seznam alternativnih predlogov PSR-4](https://groups.google.com/forum/#!topic/php-fig/oXr-2TU1lQY)
- [Povzetek [potega sprejetja glasov] PSR-4 razprav](https://groups.google.com/forum/#!searchin/php-fig/psr-4$20summary/php-fig/bSTwUX58NhE/YPcFgBjwvpEJ)
