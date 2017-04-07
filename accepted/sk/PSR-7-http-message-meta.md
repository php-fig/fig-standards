# Dodatok k HTTP Správam

## 1. Zhrnutie

Účelom tohto návrhu je poskytnúť sadu spoločných rozhraní pre HTTP správy ako
je to opísané v [RFC 7230](http://tools.ietf.org/html/rfc7230) a
[RFC 7231](http://tools.ietf.org/html/rfc7231), a URI cesty ako je to opísané
 v [RFC 3986](http://tools.ietf.org/html/rfc3986) (v súvislosti s HTTP správami).

- RFC 7230: http://www.ietf.org/rfc/rfc7230.txt
- RFC 7231: http://www.ietf.org/rfc/rfc7231.txt
- RFC 3986: http://www.ietf.org/rfc/rfc3986.txt

Všetky HTTP správy sa skladajú z verzie použitého HTTP protokolu, hlavičiek
a tela správy. _Požiadavka_ navyše obsahuje HTTP metódu ktorou sa má požiadať
a URI cestu kde sa požiadavka má odoslať. _Odpoveď_ má navyše kód HTTP stavu
a frázu s dôvodom daného stavu.

PHP používa HTTP správy v dvoch súvislostiach:

- odosiela HTTP požiadavky, cez `ext/curl` rozšírenie, cez vstavaný PHP prúd a podobne, 
  a spracuváva prijaté HTTP odpovede. Inými slovami, PHP správy sa používajú
  ako _HTTP klient_.
- Spracovávanie prichádzajúcich HTTP požiadaviek na serveri a vracanie HTTP
  odpovedí klientovi, ktorí požiadavku spravil. Teda PHP správy sú používané
  ako _aplikácia na strane servera_ na vykonianie HTTP požiadaviek.

Tento návrh predstavuje API plne opisujúc všetky časti HTTP správ v rámci PHP.

## 2. HTTP Správy v PHP

PHP nemá vstavanú podporu pre HTTP správy.

### HTTP podpora na strane klienta

PHP podporuje odosielanie HTTP požiadaviek rôznymi spôsobmi:

- [PHP prúdy](http://php.net/streams)
- Rozšírenie [cURL](http://php.net/curl)
- [ext/http](http://php.net/http) (v2 sa snaží venovať aj podpore na strane 
  servera)

PHP prúdy sú najjednoduchší a všadeprítomný spôsob ako odosielať 
HTTP požiadavky, ale predstavuje rad obmädzení s ohľadom na správne 
nakonfigurovanie SSL podpory a  poskytuje ťažkopádne rozhranie okolo nastavení
ako sú hlavičky. cURL poskytuje kompletnú a rozšírenú sadu funkcií, ale
pretože nie je základným rozšírením, často chýba na roznych serveroch.
Http rozšírenie trpí rovnakým problémom ako cURL, ako aj faktom že 
zvyčajne má oveľa menej príkladov použitia.

Najmodernejšie knižnice HTTP klientov sa snažia zovšeobecniť implementáciu tak,
aby sa dali používať na čo najväčšom množstve prostredí a naprieč hociktorým
z vyššie uvedených spôsobov.

### HTTP podpora na strane servera

PHP používa Serverové API (SAPI) na prekladanie prichádzajúcich HTTP požiadaviek,
vyjadrenie vstupu a podanie spracovania skriptom. Pôvodné SAPI kopírovalo 
[Rozhranie spoločnej brány (CGI)](http://www.w3.org/CGI/), ktoré vyňalo dáta
požiadavky a odoslalo ich do premenných prostredia pred samotným spustením
skriptu; skript by si potom ťahal premenné prostredia na to aby spracovalo 
požiadavku a vrátilo odpoveď.

SAPI dizajn pre phpčko zovšeobecňuje spoločné vstupné zdroje ako sú cookies,
parametre reťazca dotazu a url-zakódované POST obsahy cez superglobálne
premenné (`$_COOKIE`, `$_GET` a `$_POST`, respektívne), poskytujúc úroveň
pohodlia pre webových vývojárov.

V tejto rovnici na strane odpovedí, treba poznamenať že PHP bol pôvodne 
vyvinutý ako šablónový jazyk a umožnovanie miešanie HTML a PHP; hocijaká
časť HTML kódu je okamžite zapísaná do výstupného zásobníka.  Moderné
aplikácie a frameworky sa vyhýbajú tejto praktike, pretože to môže smerovať
k problémom s výpisom stavového riadku a/alebo hlavičiek odpovede. Radšej
sa snažia zozbierať všetky hlavičky a obsah a vypísať naraz, keď je sú všetky
procesy aplikácie kompletné. Špeciálny pozor sa musí dávať aby chybové hlášky
a ostatné akcie ktoré posielajú normálne obsah na výpis neboli odoslané priamo
do výstupného zásobníka.

## 3. Prečo sa tým trápiť?

HTTP správy sa používajú v množstve PHP projektov -- klientských
aj serverovských. V každom prípade pozorujeme jeden alebo viacero nasledujúcich
vzorcov a situácií:

1. Projekty používajú PHP supreglobálne priamo.
2. Prejekty vytvárajú implementácie z ničoho
3. Projekty môžu požadovať určité HTTP klientské/serverské knižnice ktoré 
   poskytujú implementácie HTTP správ
4. Projekty môžu vytvárať adaptéry pre spoločné implementácie HTTP správ.

Príklady:

1. Každá aplikácia ktorá začala byť vyvíjaná pred nástupom frameworkov, ktoré 
   zahŕňajú množstvo veľmi populárnych CMS, fórumov a systémov elektronických
   obchodov používalil historicky superglobálne premenné.
2. Frameworky ako Symfony a Zend Framework definovali HTTP súčasti,
   ktoré sformovali základy ich MVC vrstiev; dokonca aj malé a jednoúčelové
   knižnice ako je oauth2-server-php poskytujú a potrebujú ich vlastnú 
   implementáciu HTTP požiadaviek a odpovedí. Guzzle, Buzz a ostatné
   implementácie HTTP klientov, každý vytvára takisto ich vlastné implementácie
   HTTP správ.
3. Projekty ako Silex, Stack, and Drupal 8 sú ťažko závislé na HTTP jadre 
   Symfony. Každé SDK postavené na Guzzle je ťažko závislé na implementácii
   HTTP správ v Guzzle.
4. Projekty ako Geocoder vytvárajú nepotrebné [adaptéry pre spoločné knižnice](https://github.com/geocoder-php/Geocoder/tree/6a729c6869f55ad55ae641c74ac9ce7731635e6e/src/Geocoder/HttpAdapter).

Priame použitie superglobálnych premenných má množstvo znepokojojúcich vecí.
Za prvé, sú premenlivé, čo znamená že knižnice a koód s nimi môžu manipulovať a 
meniť ich hodnoty a tým meniť stav aplikácie. Ďalej superpremenné zťažujú unit
a integračné testovanie a tým sa kvalita kódu degraduje a stáva krehkým.

V súčasnom eko systéme frameworkov ktoré implementujú všeobecne HTTP správy,
sú výsledkom projekty ktoré nie sú schopné spolupracovať alebo sa spolu
obohacovať. V prípadoch kedy jeden framework má využiť kód toho druhého,
prvým krokom vývoja je postavenie mostovej vrstvy medzi implementáciami HTTP
správ. Ak daná knižnica nemá adaptér ktorý by sa dal využiť, tak na strane
klienta musíte premostiť páry požiadavka/odpoveď ak chcete použiť adapter
z inej knižnice.

Nakoniec keď už prídeme k odpovediam zo servera, PHP to spraví svojím spôsobom: 
hocijaký obsah vypísaný pred odoslaním hlavičky `header()` spôsobí že volanie
zastaví beh. Záleží od nastavenia vypisovania chýb ale toto často znamená
že hlavičky a stav odpoveďe nie sú odoslané správne. Jeden zo spôsobov ako
tomuto predisť je zabalenie výpisu do PHP zásobníku, ale zabalenie
výstupného zásobníka môže byť ťažké a náročné na ľadenie chýb. Frameworky a
a aplikácie sa tak snažia vytvoriť všeobecné odpovede zozbieraním hlavičiek a 
obsahu, ktorý je potom odoslaný naraz - a tieto abstrakcie sú potom často
nekompatibilné.

Takže, cieľom tohto návrhu je zovšeobecniť požiadavky na strane klienta a
servera a tiež zovšeobecniť rozhrania odpovedí aby sa umožnila spolupráca
medzi projektami. Ak projekty implementujú tieto rozhrania, môžeme
predpokladať rozumnú úroveň kompatibility keď použijeme kód z iných knižníc.

Malo by byť tiež poznamenané, že cieľom tohoto návrhu nie je zastarať
momentálne rozhrania existujúcich PHP knižníc. Tento návrh je zameraný
na spoluprácu medzi PHP balíkmi pre účely opísania HTTP správ.

## 4. Rozsah

### 4.1 Ciele

* Poskytnúť rozhrania potrebné pre opis HTTP správ.
* Sústrediť sa na praktickosť a použiteľnosť.
* Definovať rozhrania na načrtnutie všetkych elementov HTTP správ a
  URI špecifikácií.
* Zaistiť, že API nevytvorí svojvoľné limity na HTTP správy. Napríklad 
  niektoré telá HTTP správ môžu byť príliš veľké na uloženie do pamäti, takže
  to musíme brať do úvahy.
* Poskytnúť užitočné abstrakcie pre prichádzajúce požiadavky pre serverovú
  časť aplikácií a pre odosielanie odchadzajúcich požiadaviek v HTTP klientoch.

### 4.2 Nie ciele

* Účelom tohto návrhu nie je očakávanie, že všetky HTTP klientské knižnice alebo
  frameworky na strane servera zmenia svoje rozhrania aby vyhovovali. Je to
  čiste určený pre spoluprácu.
* Zatiaľčo každý vníma trošku odlišne čo je detailom implementácie, tento návrh
  by nemal predpisovať detaily implementácií.Tak ako
  RFCs 7230, 7231, a 3986 nenútia do žiadnej presnej implementácie, bude tu 
  potrebné určité množstvo vynaliezavosti na opísanie rozhraní HTTP správ v PHP.

## 5. Návrhové rozhodnutia

### Návrh správy

Rozhranie `MessageInterface` poskytuje prístupové metódy pre elementy spoločné
všetkym HTTP správam, či už sú požiadavkami alebo odpoveďami. Tieto elementy
sú:

- verzia HTTP protokolu (napr. "1.0", "1.1")
- HTTP hlavičky
- telo HTTP správy

Špecifickejšie rozhrania opisujú zvlášte požiadavky a odpovede a osobitne
ich obsah (klientský aj serverovský). Tieto rozdelenia sú čiastočne inšpirované
existujúcim používaním v PHP ako aj v iných jazykoch ako Ruby-ne [Rack](https://rack.github.io),
Python-ov [WSGI](https://www.python.org/dev/peps/pep-0333/),
[http balík](http://golang.org/pkg/net/http/) pre Go,
[http modul](http://nodejs.org/api/http.html) pre Node, atď.

### Prečo sú v správach metódy hlavičiek, namiesto aby boli v hlavičke?

Správa o sebe je kontajner pre hlavičky (ako aj iné vlastnosti správy). Ako sú
tieto reprezentované vnútorne je detail implementácie, ale jednotný prístup 
k hlavičkám je zodpovednosťou správy.

### Prečo sú URI cesty representované ako objekty?

URI sú hodnoty, s identitou definovanou v hodnote a teda by mali byť 
predstavované ako hodnoty objektov.

Dodatočne, URI obsahujú rôzne segmenty ktoré môžu byť prístupné veľa krát
v danej požiadavke -- a ktoré by potrebovali parsovanie URI za účelom
zistenia (napr. cez `parse_url()`). Sformovaním URI ako hodnty objektov
umožnuje parsovanie iba raz a zjednodušuje prístup k jednotlivým častiam.
V klientských aplikáciach tiež poskytuje užívateľom pohodlné vytvorenie
nových inštancií zo základnej URI inštancie iba s časťami ktoré chceme
zmeniť (napr. zmena URL cesty).

### Prečo má rozhranie požiadavky metódy pre prácu s cieľom požiadavky a na vytvorenie URI?

RFC 7230 opisuje riadok požiadavky ako obsahujúci cieľ požiadavky. Zo štyroch
tvarov cieľa požiadavky iba jedna vyhovuje s RFC 3986; najčastejšie používaný
je tvar pôvodu, ktorý reprezentuje URI cestu bez schémy alebo autority. Navyše, 
keďže všetky tvary sú platné pre účely požiadaviek, návrh musí vyhovieť všetkým.

`RequestInterface` teda má metódy vzťahujúce sa na cieľ požiadavky. Predvolene
bude používať zostrojené URI aby ukázalo tvar pôvodu cieľu požiadavky a 
v neprítomnosti URI inštancie, vráti reťazec "/".  Ďaľšia metóda,
`withRequestTarget()`, dovoľuje špecifikovať inštanciu so špecifickým cieľom 
požiadavky a tým povoľujú užívateľom vytvoriť požiadavky, ktoré používajú jednu
z ostatných validných foriem cieľa požiadavky. 

URI je držané ako oddelený člen požiadavky pre rôzne dôvody. Pre obe, klientov
aj servere, znalosť absolútnej URI je zvyčajne potrebné. V prípade klientov je
URI a špecificky schéma a autorita potrebná, aby sa dalo vytvoriť samotné TCP
spojenie. Pre aplikácie na strane servera je plné URI často potrebné aby sa
dala overiť požiadavka alebo cesta k správnemu spracovateľovi požiadavky.

### Prečo objekty hodnôt?

Návrh predpisuje správu a URI ako [objekty hodnôt](http://en.wikipedia.org/wiki/Value_object).

Správy su hodnoty, kde identita je množinou všetkých častí správy; zmena
hociktorej časti správy je v podstate nová správa. Toto je veľmi presná
definícia objektu hodnôt. Postup pri ktorom sa mení výsledok na novú inštanciu
sa volá [nemeniteľnosť](http://en.wikipedia.org/wiki/Immutable_object),
a je rysom navrhnutým na zaistenie integrity danej hodnote.

Návrh tiež rozoznáva že väčšina klientov a aplikácií na strane servera bude
potrebovať jednoducho meniť časti správy a ako také poskytuje metódy
rozhrania, ktoré vytvoria inštanciu novej správy s pozmenenými údajmi. Tieto
metódy sú všeobecne s predponou `with` alebo `without`.

Objekty hodnôt poskytujú rôzne výhody keď sa modelujú HTTP správy:

- Zmeny v URI nemôžu zmeniť požiadavku pre ktorú vytvárajúcu URI inštanciu
  s odpoveďou.
- Zmeny v hlavičkách nemôžu zmeniť správu pre ktorú ju vytvárajú.

V podstate, modelovanie HTTP správ ako objektov hodnôt zaisťuje integritu
stavu správy a chráni pred obojstrannými závislosťami, ktoré nemusia byť
práve synchronizované a viesť k problémom s ľadením chýb alebo výkonom.

Pre HTTP klientov, umožňujú užívaťelom vytvárať základné požiadavky
s dátami ako základné URI a potrebnými hlavičkami bez potreby vytvárať
úplne nové požiadavky alebo zrušiť stav požiadavky pre kažú správu ktorú
klient pošle:

```php
$uri = new Uri('http://api.example.com');
$baseRequest = new Request($uri, null, [
    'Authorization' => 'Bearer ' . $token,
    'Accept'        => 'application/json',
]);;

$request = $baseRequest->withUri($uri->withPath('/user'))->withMethod('GET');
$response = $client->send($request);

// získaj user Id z odpovede $response

$body = new StringStream(json_encode(['tasks' => [
    'Code',
    'Coffee',
]]));;
$request = $baseRequest
    ->withUri($uri->withPath('/tasks/user/' . $userId))
    ->withMethod('POST')
    ->withHeader('Content-Type', 'application/json')
    ->withBody($body);
$response = $client->send($request)

// Nie je potrebné prepisovať hlavičky alebo telo!
$request = $baseRequest->withUri($uri->withPath('/tasks'))->withMethod('GET');
$response = $client->send($request);
```

Na strane servera budú developeri potrebovať:

- Deserializovať telo správy požiadavky.
- Odkryptovať HTTP koláčik.
- Napísať odpovedi.

Tieto operácie môžu byť vykonané tiež s objektami hodnôt, s množstvom výhod:

- Pôvodný stav požiadavky môže byť uložený aby ho užívateľ mohol opätovne 
  vytiahnúť.
- Môžeme vytvoriť predvolený stav odpovede s predvolenou hlavičkou a telom
  správy.

Najpoužívanejšie PHP frameworky majú dnes plne zameniteľné HTTP správy. Hlavné
zmeny potrebné pre používanie objektov hodnôt sú:

- Namiesto volania "setter"-a pre nastavenie hodnoty alebo nastavovania hodnoty 
  verejne, sa budú volať "mutator" metódy a výsledky sa priradia.
- Vývojári musia upozorniť aplikáciu na zmenu v stave.

Pre príklad v Zend Framework 2, by namiesto tohto:

```php
function (MvcEvent $e)
{
    $response = $e->getResponse();
    $response->setHeaderLine('x-foo', 'bar');
}
```

bolo napísané radšej toto:

```php
function (MvcEvent $e)
{
    $response = $e->getResponse();
    $e->setResponse(
        $response->withHeader('x-foo', 'bar')
    );
}
```

Príklad kombinuje priradenie hodnoty s notifikáciou v jedinom volaní.

Tento postup má vedľajšiu výhodu v tom, že je jasné, kedy sa robí nejaká
zmena do stavu aplikácie.

### Nové inštancie vezus vracajúce $this

Pri pozorovaní rôznych metód `with*()` vidíme že môžu pravdepodobne bezpečne
vracať `return $this;`, ak parameter metódy nespôsobí zmenu hodnoty. Jedným
z dôvodov tohoto je výkon (pretože výsledkom nebude operácia klonovania).

Rôzne rozhrania boli napísané s táraninami naznačujúcimi že nemeniteľnosť 
MUSÍ byť zachovaná, avšak bez naznačenia že vrátená musí byť inštancia 
s novým stavom. Keďže inštancie ktoré reprezentujú rovnaké hodnoty sú považované
za rovnaké, vracanie `$this` je funkčne zhodné a teda povolené.

### Pužívanie prúdov (tokov) namiesto X

`MessageInterface` používa hodnotu teľa, ktoré musí implementovať 
`StreamableInterface`. Toto dizajnové rozhodnutie bolo urobené preto, aby
vývojári mogli posielať a prijímať (a naopak) HTTP správy ktoré obsahujú oveľa 
viac dát ako je prakticky možné uložiť do pamäti a stále mať pohodlie 
interaktovať s telami správy ako s textovým reťazcom. Dokial PHP poskytuje
abstrakciu prúdu vďaka obaľovačom prúdu (stream wrappers), môže byť ťažkopádne
pracovať s nimi: prúd môže byť zmenený na textový reťazec IBA 
so `stream_get_contents()` alebo manuálne čítaním zvyšku reťazca. 
Pridávanie rôznych funkcií prúdu tak ako je koznumovaný alebo tvorený vyžaduje
zaznamenávanie filtra prúdu; avšak filtre prúdu môžu byť pridané do prúdu iba 
po zaznamenaní s PHP. (prúd nemá autoloadovací mechanizmus na filtre).

Dobre definované rozhrania prúdov zlepšujú potenciál flexibilných dekorátov
prúdov ktoré môžu byť pridané do požiadavky alebo odpovede pred odoslaním a
umožňujú také veci ako zakódovanie, kompresiu, zaistenie že počet bytov súhlasí
s počtom bytov nahlásených v `Content-Length` odpovede atď. Dekorovanie prúdov
je zabehnutý
[vzor v Jave](http://docs.oracle.com/javase/7/docs/api/java/io/package-tree.html)
a v [Node](http://nodejs.org/api/stream.html#stream_class_stream_transform_1)
komunitách a povoľujú veľmi flexibilné prúdy.

Väčšina `StreamableInterface` API je založená na
[Python-ovom io module](http://docs.python.org/3.1/library/io.html), ktorý
poskytuje praktické a konzumovateľné API. Namiesto implementovania schopností
prúdu použitím `WritableStreamInterface` a
`ReadableStreamInterface`, sú schopnosti prúdu poskytované metódami ako 
`isReadable()`, `isWritable()`, atď. Tento prístup sa používa v Python-e,
[C#, C++](http://msdn.microsoft.com/en-us/library/system.io.stream.aspx),
[Ruby](http://www.ruby-doc.org/core-2.0.0/IO.html),
[Node](http://nodejs.org/api/stream.html) a pravdepodobne ďaľších.

#### Čo keď chcem naspäť iba súbor?

V niektorých prípadoch, by Ste mohli chcieť naspäť súbor zo súborobého systému.
Štandardne by Ste to v PHP uroblili takto:

```php
readfile($filename);

stream_copy_to_stream(fopen($filename, 'r'), fopen('php://output', 'w'));
```

Všimnite si, že vyššie vynechalo odosielanie správnych hlavičiek `Content-Type`
a `Content-Length`; vývojár by ich musel vypísať pred volaním daného kódu.

Porovnateľné s použitím HTTP správ by bolo cez `StreamableInterface`
implementáciu, ktorá akceptuje súbor a/alebo zdroj prúdu a poskytuje ho inštancii
odpovede. Kompletný príklad, vrátane nastavenia vhodných hlavičiek:

```php
// kde stream je konkrétne StreamableInterface:
$stream   = new Stream($filename);
$finfo    = new finfo(FILEINFO_MIME);
$response = $response
    ->withHeader('Content-Type', $finfo->file($filename))
    ->withHeader('Content-Length', (string) filesize($filename))
    ->withBody($stream);
```

Vyslanie tejto odpovede vyšle súbor ku klientovi.

#### Čo keď potrebujem priamo vypísať výstup?

Priame vypísanie výstupu (napr. cez `echo`, `printf`, alebo vypísaním do
`php://output` prúdu) je všeobecne iba odporúčané ako optimalizácia výkonu
alebo keď vypisujeme obrovské sety dát. Ak to potrebujete spraviť a stále
chcete použit vzor HTTP správ, jeden z postupov by bolo použitie implementácie
založeneje na callbacku `StreamableInterface` opísaný [v tomto 
príklade](https://github.com/phly/psr7examples#direct-output). Zabaľ hocijaký kód
ktorý vysiela výstup priamo do callbacku a pošli ho do správnej 
implementácie `StreamableInterface`, a poskytni ho do tela správy:

```php
$output = new CallbackStream(function () use ($request) {
    printf("The requested URI was: %s<br>\n", $request->getUri());
    return '';
});
return (new Response())
    ->withHeader('Content-Type', 'text/html')
    ->withBody($output);
```

#### Čo keď chcem použiť opakovanie obsahu?

Implementácia Ruby-ho Racku používa prístup založený na opakovaní pre telá
správ odpovedí na strane servera. Toto môže byť emulované so vzorom HTTP
správ cez prístup založený na `StreamableInterface`, ako je [popísané v
ropozitári psr7 príkladov](https://github.com/phly/psr7examples#iterators-and-generators).

### Prečo sú prúdy menlivé?

`StreamableInterface` API zahŕňa metódy ako je `write()`, ktoré môžu meniť
obsah správy -- to priamo odporuje tvrdeniu o nemenlivých správach.

Problém ktorý vzniká je spôsobneý faktom že rozhranie je určené k zabaleniu
PHP prúdu a podobne. Operácia zápisu teda zastupuje zapisovanie do prúdu. 
Aj keď sme urobili `StreamableInterface` nemenným, inštancia ktorá zabalí prúd 
bude zmenená, keď zmeníme prúd -- a toto znemožní dodržanie nemeniteľnosti.

Naše odporúčanie je že implementácie budú používať iba na čítanie (read-only)
prúdy pre požiadavky na strane servera a odpovede na strane klienta.

### Zdvôvodnenie pre ServerRequestInterface

`RequestInterface` a `ResponseInterface` majú v podstate vzájomné vzťahy 1:1
s požiadavkou a odpoveďou správy opísanej 
v [RFC 7230](http://www.ietf.org/rfc/rfc7230.txt). Poskytujú rozhrania pre
implementovanie objektov hodnôt korešpondujúce s danýn modelom HTTP správy.

Pre aplikácie na strane servera treba brať v úvahu ďaľšie fakty
pre prichádzajúce požiadavky:

- Prístup k parametrom servera (odvodené od požiadavky ale tiež z nastavenia
  servera a všeobecne reprezentované superglobálnou premennou `$_SERVER`; 
  tieto sú časťou PHP Server API (SAPI)).
- Prístup k reťazcu dotazu (zvyčajne zabalené v superglobálnej premennej `$_GET`).
- Prístup k naparsovanému telu (napr., dáta deserializované z tela prichádzajúcej
  požiadavky; v PHP je toto zvyčajne výsledkom požiadavky POST s typom obsahu
  `application/x-www-form-urlencoded`, a zabaleným do superglobálnej premennej
  `$_POST`, ale nezakódované dáta poslané inou ako POST metódou by mohli byť
  pole alebo objekt).
- Prístup k nahratým súborom (zabalené do superglobálnej premennej `$_FILES`).
- Prístup ku cookie hodnotám (zabalené do superglobálnej premennej `$_COOKIE`).
- Prístup k atribútom odvodeným z požiadavky (zvyčajne tie v URL ceste ale nemusia
  byť len tie).

Zjednotený prístup k týmto parametrom zvyšuje životaschopnosť spolupráce medzi 
frameworkami a knižnicami, keďže teraz môžu predpokladať, že ak požiadavka 
implementuje `ServerRequestInterface`, tak môžu získať tieto hodnoty. Tiež rieši
problémy so samotným jazykom PHP:

- Do 5.6.0, `php://input` bol iba na čítanie; ako také, inicializovanie
  viacerých inštancií požiadaviek z viacerých frameworkou a knižníc mohlo
  viesť k nekonzistentnosti, keďže prvý prístup do `php://input` by bol jediný
  ktorý by prijímal dáta.
- Unit testovanie so superglobálnymi premennými (napr., `$_GET`, `$_FILES`, atď.)
  je zložité a krehké. Ich zabalenie do `ServerRequestInterface` implementácie
  uľahčuje testovanie.

### Prečo "parsované telo" v ServerRequestInterface?

Argumenty boli urobené aby používali terminológiu "BodyParams" a potrebujú aby
hodnoty boli polia s nasledovným vysvetlením:

- Konzistencia prístupu s ostatnými parametrami na strane servera.
- `$_POST` je pole a 80% prípadov by sa zameralo na túto superglobálnu premennú.
- Jeden typ vytvára silnú dohodu a tak zjednodušuje používanie.

Hlavným dôvodom je, že ak parametre tela sú v poli, tak vývojári vedia
predpovedať prístup k hodnotám:

```php
$foo = isset($request->getBodyParams()['foo'])
    ? $request->getBodyParams()['foo']
    : null;
```

Dôvod pre používanie "parsovaného tela" bol urobený skúmaním okruhu pôsobnosti.
Telo správy môže doslovne obsahovať čokoľvek. Tradičné webové aplikácie
používajú formuláre a odosielajú dáta cez POST a toto je prípad ktorý sa 
v momentálnych trendoch webového vývoja stáva spochybňovaným. Moderné trendy
sú založené okolo API a teda používajú ďaľšie metódy (napr. PUT a PATCH) ako
aj obsah nezakódovaný formulárom (hlavne JSON alebo XML) ktorý _môže_ byť
vložený do pola, alebo aj _nemôže_ či dokonca _nemal by_.

Ak nútime vlastnosť predstavujúcu parsované telo aby bolo iba poľom, tak
budú vývojári potrebovať zdielanú dohodu o tom, tak sa uložia výsledky
parsovania tela. Tieto môžu obsahovať:

- Špeciálny kľúč pod parametrami tela, napríklad `__parsed__`.
- Špeciálne pomenovaný atribút, napríklad `__body__`.

Konečný výsledok je že vývojár sa teraz musí pozrieť na viaceré miesta:

```php
$data = $request->getBodyParams();
if (isset($data['__parsed__']) && is_object($data['__parsed__'])) {
    $data = $data['__parsed__'];
}

// alebo:
$data = $request->getBodyParams();
if ($request->hasAttribute('__body__')) {
    $data = $request->getAttribute('__body__');
}
```

Predstavené riešenie je použiť názov "ParsedBody", ktoré nám naznačuje
že hodnoty sú naparsované výsledky z tela správy. To tiež znamená, že
vracajúca sa hodnota _bude_ dvojznačná; ale pretože je toto atribút rozsahu,
tak je to aj očakávané. Použite bude asi takéto:

```php
$data = $request->getParsedBody();
if (! $data instanceof \stdClass) {
    // zavolaj výnimku
}
// v opačnom prípade, máme čo sme chceli
```

Tento prístup odstraňuje obmädzenia nútených polí, za cenu dvojzmyselnosti
vrátenej hodnoty. Vzhľadom k ďaľším navrhovaným riešeniam - tlačenie parsovaných
dát do špeciálneho kľúča parametra tela alebo do atribútu - ktoré tiež trpia
dvojzmyselnosťou, je navrhované riešenie jednoduchšie pretože nepotrebuje
ďaľšie špecifikácie rozhrania. A nakoniec, flexibilita dvojzmyselnosťi je 
potrebná pri znázorňovaní výsledkov parsovania tela.

### Prečo nie je pridaná funkcionalita na získanie "základnej cesty"?

Mnoho frameworkov poskytuje schoponosť na získanie "základnej cesty", zvyčajne
sa ňou považuje cesta po controller vrátane kontroléra. Ako príklad, ak je 
aplikácia obsluhovaná z `http://example.com/b2b/index.php`, a momentálne použité 
URI na požiadavku je `http://example.com/b2b/index.php/customer/register`, 
tak schopnosť na získanie základnej cesty by vrátilo `/b2b/index.php`. Táto
hodnota môže byť smerovačmi potom očistená od časti URI cesty pred tým ako
sa nájde zhoda.

Táto hodnota je potom často tiež používaná na generovanie URI v rámci aplikácie;
parametre sa posunú routeru, ktorý vygeneruje cestu a pridá sa základná cesta
ako predpona a takto sa vráti celá cesta URI. Ostatné nástroje - zvyčajne pomocné
views, filtre template-ov alebo funkcie template-ov - sa používajú na rozuzlenie
cesty relatívnej k základnej ceste za účelom generovania URI adries pre statické 
zdroje ako sú obrázky atď.

Pozorovaním rôznych implementácií sme zistili nasledovné:

- Logika na určovanie základnej cesty sa dosť líši medzi implementáciami.
  Ako príklad, porovnajte [logiku v ZF2](https://github.com/zendframework/zf2/blob/release-2.3.7/library/Zend/Http/PhpEnvironment/Request.php#L477-L575)
  s [logikou v Symfony 2](https://github.com/symfony/symfony/blob/2.7/src/Symfony/Component/HttpFoundation/Request.php#L1858-L1877).
- Väčšina implementácií povoľuje vsunutie základnej cesty do smerovača manuálne
  a/alebo služieb generujúcich URI adresu.
- Primárne využitia — smerovanie a generovanie URI — sú typicky jediný konzumenti
  tejto funkcionality. Vývojári nemusia mať vedomosť o prevedení získania
  základnej cesty, keďže iné objekty to urobia za nich.
  Ako príklad:
  - Smerovač odstrihne základnú cestu počas smerovania, teda vývojár nemusí
    smerovaču posúvať modifikovanú cestu.
  - Pomocník pre View, filtre template-ov, atď. sú zvyčajne vsunuté so základnou
    cestou pred volaním. Niekedy je toto spravené manuálne, hoci najčastejšie
    ma toto na starosti framework.
- Všetky zdroje potrebné pre výpočet základnej cesty *sú už v inštancii
  `RequestInterface`* cez parametre servera a URI inštanciu.

Náš postoj je že detekcia základnej cesty je špecifická pre každy framework
alebo aplikáciu a výsledky detekcie sa dajú ľahko vsunúť do objektov ktoré 
ich potrebujú. Tiež môžu byť vypočítané cez pomocné funkcie a triedy
zo samotnej inštancie `RequestInterface`.

### Prečo vracia getUploadedFiles() objekty namiesto polí?

`getUploadedFiles()` vracia strom `Psr\Http\Message\UploadedFileInterface`
inštancií. Toto je spravené pre zjednodušenie špecifikácie: teda namiesto
odstavcov s textom implementácie špecifikácie poľa sme špecifikovali rozhranie.

Dodatočne sú dáta v `UploadedFileInterface` normalizované pre prácu so SAPI 
a aj nie-SAPI prostrediami. Týmto sa dajú vytvárať manuálne procesy 
na parsovanie tela správy a priraďovať obsah do prúdu bez zápisu do súborového
systému a zároveň dovoľuje správne ovládať nahrávanie súborob v SAPI 
prostrediach.

### Čo so "special" hodnotami hlavičiek?

Množstvo hodnôt hlavičiek obsahuje unikátne požiadavky zobrazenia, ktoré môžu
spôsobiť problémy pri spracovaní ako aj pri generovaní. Konkrétne cookies
a hlavička `Accept`.

Tento návrh neposkytuje žiadne špeciálne zaobchádzanie s typmi hlavičiek. 
Základne rozhranie `MessageInterface` poskytuje metódy pre získanie a 
nastavenie hlavičky. Napokon všetky hodnoty hlavičiek sú textové reťazce.

Vývojári sú vítaný aby tvorili užitočné knižnice spolupracujúce s týmito
hodnotami hlavičiek za účelom parsovania alebo generovania. Tieto knižnice
potom môžu používať užívatelia ktorý potrebujú pracovať s týmito hodnotami.
Takéto príklady existujú v knižniciach ako sú 
[willdurand/Negotiation](https://github.com/willdurand/Negotiation) a
[aura/accept](https://github.com/pmjones/Aura.Accept). Pokiaľ objekt
bude mať funkcionalitu aby zmenil hodnotu na textový reťazec, tak takýto 
objekt môže byť použitý na tvorenie hlavičiek HTTP správ.

## 6. Ľudia

### 6.1 Vedúci návrhu

* Matthew Weier O'Phinney

### 6.2 Navrhovateľia

* Paul M. Jones
* Beau Simensen (coordinator)

### 6.3 Prispievatelia

* Michael Dowling
* Larry Garfield
* Evert Pot
* Tobias Schultze
* Bernhard Schussek
* Anton Serdyuk
* Phil Sturgeon
* Chris Wilkinson
