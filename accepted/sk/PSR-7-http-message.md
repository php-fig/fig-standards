# Rozhrania HTTP správ

Tento dokument opisuje spoločné rozhrania pre znázornenie HTTP správ ako
sú opísané v [RFC 7230](http://tools.ietf.org/html/rfc7230) a
[RFC 7231](http://tools.ietf.org/html/rfc7231), a URI cesty pre použitie 
s HTTP správami, popísané v [RFC 3986](http://tools.ietf.org/html/rfc3986).

HTTP správy sú základom vývoja webu. Webové prehliadače a HTTP klienti, 
ako napríklad cURL, vytvárajú HTTP správu s požiadavkou, ktorá je následne
odoslaná na web server a tento poskytne HTTP spravu s odpoveďou. Kód na
strane servera prijme HTTP správu s požiadavkou a vráti HTTP správu 
s odpoveďou.

HTTP správy sú zvyčajne nepozorované koncovým užívateľom. Na druhej strane 
my vývojári potrebujeme zvyčajne vedieť, ako sú tieto správy štrukturované 
a ako k nim pristupovať alebo s nimi manipulovať, aby sme dosiahli našu úlohu,
či už je to vytvorenie požiadavky na HTTP API server alebo spracovanie
prichádzajúcej požiadavky.

Každá HTTP správa s požiadavkou má špecifický tvar:

```http
POST /path HTTP/1.1
Host: example.com

foo=bar&baz=bat
```

Prvý riadok požiadavky je "riadok požiadavky" a obsahuje v tomto poradí, metódu
HTTP požiadavky, cieľ požiadavky (zvyčajne buď absolútne URI alebo cesta na 
web serveri) a nakoniec verzia HTTP protokolu. Toto je nasledované jednou alebo
viac HTTP hlavičkami, prázdnym riadkom a teľom správy.

HTTP správa s odpoveďou má podobný tvar:

```http
HTTP/1.1 200 OK
Content-Type: text/plain

Toto je telo odpoveďe
```

Prvý riadok je "stavový riadok" a obsahuje v tomto poradí: verziu HTTP protokolu,
HTTP kód stavu a "frázu dôvodu" ktorá je človekom čitateľný opis kódu stavu.
Podobne, ako správa požiadavky, je tento riadok nasledovaný jedným alebo 
viacerými HTTP hlavičkami, prázdnym riadkom a telom správy.

Rozhrania opísané v tomto dokumente sú zhrnutia okolo HTTP správ a prvkov
z ktorých sa skladajú.

Kľúčové slová "MUSÍ", "NESMIE", "POTREBNÉ", "SMIE", "NESMIE", "MALO BY",
"NEMALO BY", "ODPORÚČANÉ", "MôŽE", and "NEPOVINNÉ" v tomto dokumente sú vo význame
ako opísané v [RFC 2119](http://tools.ietf.org/html/rfc2119).

### Referencie

- [RFC 2119](http://tools.ietf.org/html/rfc2119)
- [RFC 3986](http://tools.ietf.org/html/rfc3986)
- [RFC 7230](http://tools.ietf.org/html/rfc7230)
- [RFC 7231](http://tools.ietf.org/html/rfc7231)


## 1. Špecifikácia

### 1.1 Správy

HTTP správa je buď požiadavka od klienta na server alebo odpoveď zo servera 
ku klientovi. Toto rozčlenenie vymedzuje rozhrania pre HTTP správy
`Psr\Http\Message\RequestInterface` a `Psr\Http\Message\ResponseInterface`.

Obe rozhrania `Psr\Http\Message\RequestInterface` aj `Psr\Http\Message\ResponseInterface`
rozširujú `Psr\Http\Message\MessageInterface`. Hoci `Psr\Http\Message\MessageInterface` 
MôŽE byť implementované priamo, implementátori BY MALI implementovať aj rozhrania
`Psr\Http\Message\RequestInterface` a `Psr\Http\Message\ResponseInterface`.

Odtialto nižšie bude menný priestor `Psr\Http\Message` vynechaný, keď budeme
odkazovať na tieto rozhrania.

#### 1.2 HTTP Hlavičky

##### Polia mien hlavičiek bez dôrazu na veľkosť písmen

HTTP správy zahŕňajú polia mien hlavičiek, ktoré akceptujú malé aj veľké písmená
a na ich veľkosti nezáleží. Hlavičky sú získavané poďla mena z tried, ktoré
implementujú `MessageInterface` spôsobom, pri ktorom nezáleží na veľkosti písmen.
Napríklad hlavička `foo` vráti rovnaký výsledok ako hlavička `FoO`. Podobne,
nastavenie hlavičky `Foo` prepíše hocijakú predošle nastavenú hodnotu 
hlavičky `foo`.

```php
$message = $message->withHeader('foo', 'bar');

echo $message->getHeaderLine('foo');
// Vypíše: bar

echo $message->getHeaderLine('FOO');
// Vypíše: bar

$message = $message->withHeader('fOO', 'baz');
echo $message->getHeaderLine('foo');
// Vypíše: baz
```

Napriek tomu že hlavičky sa dajú čítať bez ohladu na veľkosť písmen, pôvodná
veľkosť písmen MUSÍ byť zachovaná implementáciou, zvlášte keď si ju pýtame
s `getHeaders()`.

Neprispôsobené HTTP aplikácie môžu byť závislé na určitej veľkosti písmen,
takže je užitočné pre užívatela, aby bol schopný nastaviť veľkosť písmen 
pre HTTP hlavičky keď sa vytvára požiadavka alebo odpoveď.

##### Hlavičky s viacerými hodnotami

Aby sa dalo vyhovieť hlavičkám s mnohými hodnotami a zároveň poskytovať
pohodlie práce s hlavičkami ako textovými reťazcami, hlavičky môžu byť 
vytiahnuté z inštancie `MessageInterface` ako pole alebo ako textový reťazec.
Použite metódu `getHeaderLine()` na vytiahnutie hodnoty hlavičky ako
textového reťazca obsahujúceho všetky hodnoty mena hlavičky bez ohľadu na veľkosť 
písmen oddelené s čiarkou.
Použite `getHeader()` na vytiahnutie pola so všetkými hodnotami hlavičiek
pre určité meno hlavičky bez ohľadu na veľkosť písmen.

```php
$message = $message
    ->withHeader('foo', 'bar')
    ->withAddedHeader('foo', 'baz');

$header = $message->getHeaderLine('foo');
// $header obsahuje: 'bar, baz'

$header = $message->getHeader('foo');
// ['bar', 'baz']
```

Poznámka: Nie všetky hodnoty hlavičiek môžu byť spojené s čiarkou (napr.
`Set-Cookie` obsahuje rôzne znaky) a keď pracujeme s takými hlavičkami, kód 
implementujúci `MessageInterface` BY MAL použiť radšej metódu `getHeader()`
na vyťahovanie takých viac hodnotových hlavičiek.

##### Hlavička hostiteľa

Pri posielaní požiadavky, hlavička hostiteľa zvyčajne odzrkadluje hostiteľa
z celkovej URI, takisto ako aj hostiteľa použitého pri nadväzovaní TCP spojenia.
Napriek tomu, špecifikácia HTTP povoľuje, aby sa hlavička `Hostiteľa` líšila
od obidvoch.

Pri vytváraní hostiteľa sa implementácia MUSÍ snažiť nastaviť hlavičku 
`Hostiteteľa` z poskytnutej URI, ak nie je poskytnutá žiadna hlavička hostiteľa.

`RequestInterface::withUri()` predvolene nahradí vrátenú hlavičku `hostiteľa`
z požiadavky s hlavičkou `hostiteľa` z časti hostiteľ podaného rozhraním 
`UriInterface`.

Ak chcete zachovať pôvodný stav hlavičky `Hostiteľ`, nastavte hodnotu `true` 
druhému parametru `$preserveHost`. Keď je tento parameter nastavený ako
`true`, tak vrátená požiadavka neaktualizuje hlavičku `Hostiteľ` vrátenej
správe, ale len vtedy, keď správa neobsahuje žiadnu hlavičku `Hostiteľa`.

Táto tabuľka znázorňuje rôzne požiadavky a URI, a tiež čo bude 
`getHeaderLine('Host')`  vracať pre požiadavky vrátené s `withUri()` a 
s parametrom `$preserveHost` nastaveným na `true`.

Hlavička hostiteľa požiadavky <sup>[1](#rhh)</sup> | časť hostiteľa požiadavky<sup>[2](#rhc)</sup> | časť URI hostiteľa<sup>[3](#uhc)</sup> | Výsledok
---------------------------------------------------|-----------------------------------------------|----------------------------------------|---------
''                                                 | ''                                            | ''                                     | ''
''                                                 | foo.com                                       | ''                                     | foo.com
''                                                 | foo.com                                       | bar.com                                | foo.com
foo.com                                            | ''                                            | bar.com                                | foo.com
foo.com                                            | bar.com                                       | baz.com                                | foo.com

- <sup id="rhh">1</sup> Hodnota hlavičky `Hostiteľa` pred operáciu.
- <sup id="rhc">2</sup> Časť hostiteľa v zloženej URI v požiadavke pred operáciou.
- <sup id="uhc">3</sup> Časť hostiteľa v URI vložená cez `withUri()`.

### 1.3 Prúdy (Streams)

HTTP správy sa skladajú zo začiatočného riadku, hlavičiek a tela. Telo HTTP
správy môže mať akúkoľvek veľkosť. Pokus znázorniť telo správy ako textového
reťazca môže veľmi ľahko spotrebovať viac pamäte ako bolo zamýšlané, pretože
celé telo sa musí uložiť do pamäte. Takéto načítanie a uloženie tela
do pamäte by bránilo využitiu takejto implementácie pri práci s extrémne 
veľkými telami správ. Rozhranie `StreamInterface` sa používa na skrytie
detailov implementácie keď je prúd dát čítaný alebo zapisovaný.
V situáciách, kde je vhodný textový reťazec sa môže použiť vstavaný prúd
`php://memory` a `php://temp`.

`StreamInterface` obsahuje niekoľko metód, ktoré umožňujú aby boli prúdy 
čítané z, zapisované do a pretínajúce sa efektívne.

Prúdy vystavujú svoje schopnosti tromi metódami: `isReadable()`,
`isWritable()`, a `isSeekable()`. Týmito metódami môžeme zistiť či prúd
spĺňa požiadavky nejakej inštancie.

Každá inštancia prúdu bude mať rôzne schopnosti: iba na čítanie, iba na zápis
alebo na čitanie a zápis. Tiež môže mať vlastný prístup (hľadanie dopredu 
alebo dozadu na hociktoré miesto), alebo iba prístup ku sekvencii (napríklad
keď ide o socket, pipe alebo prúd založený na callbacku).

Rozhranie `StreamInterface` definuje `__toString()` metódu na zjednodušenie
získania alebo vyslania celého tela obsahu naraz.

`StreamInterface` narozdiel od rozhraní požiadavky a odpovede nepredvádza 
nemennosť. V prípadoch keď je aktuálny PHP prúd zabalený, je nemožné dodržať
nemennosť, pretože hocijaký kód spolupracujúci s prúdom môže zmeniť jeho stav
vrátane pozície kurzoru, obsahu, ... Naše odporúčanie je, že implementácie
používajú iba read-only prúdy pre požiadavky na server a odpovede pre klientov.
Užívatelia prúdu by mali mať na pamäti, že inštancie sa môžu meniť a teda
meniť stav správy. Keď ste si neistí, vytvorte novú inštanciu prúdu a pripojte
ju ku správe aby ste presadili stav.

### 1.4 Ciele a URI požiadaviek

Ako je popísané v RFC 7230, správy požiadaviek obsahujú "cieľ požiadavky" v
druhej časťi riadku na riadku požiadavky. Cieľ požiadavky môže byť jeden
z nasledujúcich:

- **tvar pôvodu**, ktorá pozostáva z cesty a ak existuje, tak aj reťazec dotazu; 
  toto sa nazýva často aj ako relatívna cesta. Správy presúvané cez TCP sú
  zvyčajne tohto typu; Metóda a autorita sú zvyčajne prítomné iba cez CGI
  premenné.
- **absolútny tvar**, ktorý pozostáva z metódy, autority,
  ("[user-info@]host[:port]", kde položky v hranatých zátvorkách nie sú povinné),
  cesty (ak existuje), reťazec dotazu (ak existuje), a fragment (ak existuje).
  Toto je často nazývané ako absolútna cesta, a je to jediný tvar cesty detailne
  popísaný v RFC 3986. Tento tvar je často používaný keď sa posiela požiadavka
  na HTTP proxy servre.
- **tvar autority**, ktorý pozostáva iba z autority. Tento tvar je zvyčajne
  používaný iba v CONNECT požiadavkách, keď sa vytvára spojenie medzi 
  HTTP klientom a proxy servrom.
- **hviezdičkový tvar**, ktorý pozostáva čisto iba zo znaku `*`, a ktorý sa 
  používa s OPTIONS metódou na zistenie všeobecných schopností web servra.

Bokom týchto cieľov požiadavky je ešte často 'efektívna cesta' ktorá je
oddelená od cieľa požiadavky. Efektívna cesta sa neprenáša v HTTP správe, ale
sa používa na určenie protokolu (http/https), portu a mena hostiteľa kam sa
pošle požiadavka.

Efektívna cesta je reprezentovaná pomocou `UriInterface`. `UriInterface` 
vytvorí HTTP a HTTPS cestu podľa špecifikácie RFC 3986 (základné využitie). 
Rozhranie poskytuje metódy pre pôsobobenie s rôznými časťami cesty,
ktoré predchádza potrebe opätovného parsovania cesty. Taktiež obsahuje
`__toString()` metódu získanie vytvorenej cesty vo forme textového reťazca.

Metóda na získanie cieľa požiadavaky `getRequestTarget()` predvolene použije
URI objekt a extrahuje z neho všetko potrebné na zostrojenie _origin-form_,
ktorý je načastejšie používaným cieľom požiadavky.

Ak užívateľ chce použiť jeden z ostatných troch tvarov, alebo ak chce výslovne
prepísať cieľ požiadavky, môže tak urobiť s metódou `withRequestTarget()`.

Volanie tejto metódy nezmeni URI cesta, napr pri volaní metódy `getUri()`.

Napríklad, ak chce užívateľ urobiť požiadavku na server v hviezdičkovom tvare:

```php
$request = $request
    ->withMethod('OPTIONS')
    ->withRequestTarget('*')
    ->withUri(new Uri('https://example.org/'));
```

Výsledkom tohto príkladu môže nakoniec byť HTTP požiadavka ktorá vyzerá takto:

```http
OPTIONS * HTTP/1.1
```

HTTP klient bude schopný použiť efektívnu cestu (z `getUri()`),
aby zistil protokol, meno hostiteľa a TCP port.

HTTP klient MUSÍ ignorovať hodnoty `Uri::getPath()` a `Uri::getQuery()`,
a namiesto nich použiť hodnotu vrátenú metódou `getRequestTarget()`, ktorá 
je rovnaká ako spojenie týchto dvoch hodnôt.

Klienti, ktorí sa rozhodnú neiplementovať niektorú z tvarov cieľov požiadaviek
MUSIA stále použiť `getRequestTarget()`. Títo klienti MUSIA odmietnúť
požiadavky cieľov ktoré nepodporujú a NESMÚ použiť hodnoty z `getUri()`.

`RequestInterface` poskytuje metódy na získanie cieľu požiadavky alebo 
vytvoriť novú inŠtanciu s poskytnutým cielom požiadavky. Ak cieľ požiadavky
nie je špecifikovaný v inštancii rozhrania, metóda `getRequestTarget()` 
vráti tvar pôvodu z vytvorenej URI cesty (alebo "/" ak URI cesta nie je 
zostrojená), `withRequestTarget($requestTarget)` vytvorí novú inštanciu 
zo zadaného cieľa požiadavky a tak povoľí vývojárom vytvoriť správy 
požiadaviek, ktoré reprezentujú ostatné tri tvary cieľu požiadavky 
(absolútny tvar, tvar autority a hviezdičkový tvar). Inštancia zostrojenej
URI cesty môže byť stále užitočná, napríklad v klientoch, kde ju môžme 
využit na vytvorenie spojenia k serveru. 

### 1.5 Požiadavky na strane servera

Rozhranie `RequestInterface` poskytuje všeobecné znázornenie o správe požiadavky.
Napriek tomu požiadavky na strane servera potrebujú ďaľšiu úpravu kvôli povahe
prostredia na strane servera. Spracovanie na strane servera musí brať v úvahu 
Rozhranie Spoločnej Brány (Common Gateway Interface alebo CGI), a špecifickejšie
oddelenie PHP a rozšírenia Brány (CGI) cez Rozhranie aplikácie programu 
na servri (Server APIs alebo SAPI). PHP poskytlo zjednodušenie okolo zaraďovania
vstupu cez superglobálne premenné ako sú:

- `$_COOKIE`, ktoré deserializuje a poskytuje zjednodušený prístup 
  k HTTP cookies.
- `$_GET`, ktoré deserializuje a poskytuje zjednodušený prístup ku parametrom
  dotazu.
- `$_POST`, ktoré deserializuje a poskytuje zjednodušený prístup parametrom 
  dotazu poslaných cez HTTP POST. Môže byť považované za telo správy.
- `$_FILES`, ktoré poskytujú serializované metadata pri nahrávaní súborov.
- `$_SERVER`, ktorý poskytuje prístup k CGI/SAPI premmeným prostredia a vrátane
  typu požiadavky, schémy požiadavky, URI cesty požiadavky a hlavičiek.

`ServerRequestInterface` rozširuje `RequestInterface`  a poskytuje abstrakciu
okolo týchto rôznych superglobálnych premenných. Táto praktika pomáha znížiť
väzbu superglobálov a užívateľov a zlepšuje schopnosť testovať požiadavky
používateľov.

Požiadavka servera poskytuje ešte jednu vlastnosť, "atribúty", ktorá dáva
užívateľom schopnosť požiadavku skúmať, rozložiť alebo porovnať s nejakým
špecifickým pravidlom danej aplikácie (napríklad porovnanie cesty, porovnanie
schémy, porovnanie hostiteľa, atď.). Požiadavka servera ako taká, môže
tiež poskytnúť správy medzi rôznymi požiadavkami užívateľov.

### 1.6 Nahrané súbory

`ServerRequestInterface` špecifikuje spôsob k získaniu stromu nahraných súborov
v normalizovanej štruktúre, a každý súbor ako inštancia `UploadedFileInterface`.

Superglobálna premenná `$_FILES` má určité známe problémy keď pracuje s poľom 
vstupných s´uborov. Ako príklad, ak máte formulár, ktorý odošle pole súborov, 
názov poľa "files", odosielajúce `files[0]` a `files[1]` — PHP ich znázorní:

```php
array(
    'files' => array(
        'name' => array(
            0 => 'file0.txt',
            1 => 'file1.html',
        ),
        'type' => array(
            0 => 'text/plain',
            1 => 'text/html',
        ),
        /* etc. */
    ),
)
```

namiesto očakávaného:

```php
array(
    'files' => array(
        0 => array(
            'name' => 'file0.txt',
            'type' => 'text/plain',
            /* etc. */
        ),
        1 => array(
            'name' => 'file1.html',
            'type' => 'text/html',
            /* etc. */
        ),
    ),
)
```

Výsledkom je, že užívatelia potrebujú poznať detaily implementácie jazyka
a naprogramovať kód, ktorý správne nahrá dané súbory.

Dodatočne, existujú scenáre kde `$_FILES` nie je vytvorené, pri nahrávaní súborov:

- a HTTP metóda nie je `POST`.
- keď sa unit testuje.
- keď kód nebeží v SAPI prostredí, napríklad [ReactPHP](http://reactphp.org).

V takých prípadoch, dáta budú musieť byť posielané iným spôsobom. Napríklad:

- proces môže naparsovať telo správy aby objavilo, že obsahuje súbory. V takých
  prípadoch, by implementácia nemala zapisovať súbory do súborového systému,
  ale zabaliť ich do prúdu aby sa znížil strop pamäti, vstupy a výstupy a 
  úložisko.
- pri unit testovaní, vývojári musia byť schopný podvrhnúť falošné metadáta 
  nahrávaných súborov aby overili funkčnosť rôznych variánt.

`getUploadedFiles()` poskytuje normalizovanú štruktúru pre užívateľov.
Od implementácií sa očakáva:

- zgrupiť všetky informácie pre dané nahrávanie súborov a použiť ich
  na vytvorenie inštancie`Psr\Http\Message\UploadedFileInterface`.
- znovu vytvoriť odoslanú stromovú štruktúru s každým súborom ako inštanciou
  `Psr\Http\Message\UploadedFileInterface` v danom mieste stromu.

Štruktúra stromu by mala odkazovať na mennú štruktúru v ktorej boli súbory 
odoslané.

V najjednoduchšom príklade, toto môže byť jednoducho nazvaný element formulára:

```html
<input type="file" name="avatar" />
```

V tomto prípade štruktúra v `$_FILES` by vyzerala takto:

```php
array(
    'avatar' => array(
        'tmp_name' => 'phpUxcOty',
        'name' => 'my-avatar.png',
        'size' => 90996,
        'type' => 'image/png',
        'error' => 0,
    ),
)
```

Normalizovaný formulár vrátený cez `getUploadedFiles()` by bol:

```php
array(
    'avatar' => /* inštancia UploadedFileInterface */
)
```

V prípade vstupu používajúce pomenované polia:

```html
<input type="file" name="my-form[details][avatar]" />
```

`$_FILES` by vyzeralo takto:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatar' => array(
                'tmp_name' => 'phpUxcOty',
                'name' => 'my-avatar.png',
                'size' => 90996,
                'type' => 'image/png',
                'error' => 0,
            ),
        ),
    ),
)
```

A korenšpondujúci strom vrátený s `getUploadedFiles()` by bol:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatar' => /* inštancia UploadedFileInterface */
        ),
    ),
)
```

V niektorých prípadoch môžete špecifikovať pole súborob:

```html
Upload an avatar: <input type="file" name="my-form[details][avatars][]" />
Upload an avatar: <input type="file" name="my-form[details][avatars][]" />
```

(Ako príklad, JavaScript môže vytvoriť viacej vstupných polí pre nahratie
viacerých súborov naraz.)

V takom prípade, implementácia špecifikácie musí zhrnúť všetky informácie
ohľadne súboru v danom indexe. Príčinou je `$_FILES` ktorý sa odchyľuje
od svojej normálnej štruktúry v takých prípadoch:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatars' => array(
                'tmp_name' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
                'name' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
                'size' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
                'type' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
                'error' => array(
                    0 => '...',
                    1 => '...',
                    2 => '...',
                ),
            ),
        ),
    ),
)
```

Vyššie pole `$_FILES` by korešpondovalo s nasledujúcou štruktúrou vrátenou
metódou `getUploadedFiles()`:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatars' => array(
                0 => /* UploadedFileInterface instance */,
                1 => /* UploadedFileInterface instance */,
                2 => /* UploadedFileInterface instance */,
            ),
        ),
    ),
)
```

Užívatelia by pristupovali do vnoreného poľa k indexu `1` takto:

```php
$request->getUploadedFiles()['my-form']['details']['avatars'][1];
```

Pretože nahraté dáta súborov sú odvodené (z `$_FILES` alebo tela požiadavky),
rozhranie obsahuje aj "setter" metódu `withUploadedFiles()` ktoré dovoľuje
prenesenie normalizáciena iný proces.

V prípade pôvodného príkladu, sa použitie podobá na toto:

```php
$file0 = $request->getUploadedFiles()['files'][0];
$file1 = $request->getUploadedFiles()['files'][1];

printf(
    "Prijali sa súbory %s a %s",
    $file0->getClientFilename(),
    $file1->getClientFilename()
);

// "Prijali sa súbory file0.txt a file1.html"
```

Toto riešenie tiež počíta s implementáciami mimo SAPI prostredia a v takých
prípadoch poskytuje `UploadedFileInterface` metódy na zaistenie, že operácie
budú pracovať nezávisle na prostredí. Špecificky:

- `moveTo($targetPath)` je bezpečná a odporúčaná alternatíva namiesto volania
  `move_uploaded_file()` priamo na dočasný nahratý súbor. Implementácie
  zisťia a použijú správnu operáciu v danom prostredí.
- `getStream()` vráti `StreamInterface` inštanciu. V prostrediach mimo SAPI
  je jednou z navrhnutých možností parsovať individuálne nahrávané súbory
  do prúdov `php://temp` namiesto priamo do súborov; v takýchto prípadoch
  nie je prítomný dočasný nahratý súbor a preto metóda `getStream()` 
  garantuje že bude pracovať v hocijakom prostredí.

Príklad:

```
// Presuň súbor do upload adresára
$filename = sprintf(
    '%s.%s',
    create_uuid(),
    pathinfo($file0->getClientFilename(), PATHINFO_EXTENSION)
);
$file0->moveTo(DATA_DIR . '/' . $filename);

// Prúd súboru do Amazonu S3.
// Predpokladajme že $s3wrapper je PHP prúd ktorý bude zapisovať do S3 a že
// Psr7StreamWrapper je trieda ktorá bude dekorovať StreamInterface ako PHP
// StreamWrapper.
$stream = new Psr7StreamWrapper($file1->getStream());
stream_copy_to_stream($stream, $s3wrapper);
```

## 2. Balík

Opísané rozhrania a triedy sú poskytnuté ako časť balíka
[psr/http-message](https://packagist.org/packages/psr/http-message).

## 3. Rozhrania

### 3.1 `Psr\Http\Message\MessageInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * HTTP správy pozostávajú z požiadaviek od klienta na server a z odpovedí
 * zo servera ku klientovi. Toto rozhranie definuje metódy spoločné pre obe
 * správy
 *
 * Správy sú považované za nemeniteľné; všetky metódy ktoré by mohli zmeniť
 * stav MUSIA byť implementované tak aby zachovali interný stav správy a
 * vrátili inštanciu ktorá obsahuje ktorá obsahuje zmenený stav.
 *
 * @see http://www.ietf.org/rfc/rfc7230.txt
 * @see http://www.ietf.org/rfc/rfc7231.txt
 */
interface MessageInterface
{
    /**
     * Získa verziu HTTP protokolu ako textový reťazec.
     *
     * Text MUSÍ obsahovať iba číslo HTTP verzie (napr., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion();

    /**
     * Vráti inštanciu so špecifikovanou verziou HTTP protokolu
     *
     * Text MUSÍ obsahovať iba číslo HTTP verzie (napr., "1.1", "1.0").
     *
     * Táto metóda MUSÍ byť implementovaná tak aby nezmenila pôvodnú správu
     * a MUSÍ vrátiť inštanciu ktorá bude mať novú verziu protokolu.
     *
     * @param string $version HTTP protocol version
     * @return self
     */
    public function withProtocolVersion($version);

    /**
     * Získa všetky hodnotu hlavičiek správy.
     *
     * Kľúče reprezentujú meno hlavičky v poradí v ktorom boli prijaté a každá 
     * hodnota je pole textových reťazcov priradené k danej hlavičke.
     *
     *     // Representuje hlavičky ako reťazec
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Vypisuje hlavičky iteratívne:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * Hoci nezáleží na veľkosti písmen v názvoch hlavičiek, getHeaders() 
     * zachová písmená hlavičiek v presnej veľkosti písmen ako boli špecifikované.
     *
     * @return string[][] Vráti asociatívne pole s hlavičkami správy.
     *     Každý kľúč MUSÍ byť názvom hlavičky a každá hodnota MUSÍ byť pole
     *     textových reťazcov pre danú hlavičku.
     */
    public function getHeaders();

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($name);

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     *
     * If the header does not appear in the message, this method MUST return an
     * empty array.
     *
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader($name);

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    public function getHeaderLine($name);

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * While header names are case-insensitive, the casing of the header will
     * be preserved by this function, and returned from getHeaders().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated header and value.
     *
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return self
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value);

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new header and/or value.
     *
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     * @return self
     * @throws \InvalidArgumentException for invalid header names.
     * @throws \InvalidArgumentException for invalid header values.
     */
    public function withAddedHeader($name, $value);

    /**
     * Return an instance without the specified header.
     *
     * Header resolution MUST be done without case-sensitivity.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     *
     * @param string $name Case-insensitive header field name to remove.
     * @return self
     */
    public function withoutHeader($name);

    /**
     * Gets the body of the message.
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody();

    /**
     * Return an instance with the specified message body.
     *
     * The body MUST be a StreamInterface object.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     *
     * @param StreamInterface $body Body.
     * @return self
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body);
}
```

### 3.2 `Psr\Http\Message\RequestInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Representation of an outgoing, client-side request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * During construction, implementations MUST attempt to set the Host header from
 * a provided URI if no Host header is provided.
 *
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * In most cases, this will be the origin-form of the composed URI,
     * unless a value was provided to the concrete implementation (see
     * withRequestTarget() below).
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget();

    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @see http://tools.ietf.org/html/rfc7230#section-2.7 (for the various
     *     request-target forms allowed in request messages)
     * @param mixed $requestTarget
     * @return self
     */
    public function withRequestTarget($requestTarget);

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod();

    /**
     * Return an instance with the provided HTTP method.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request method.
     *
     * @param string $method Case-sensitive method.
     * @return self
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod($method);

    /**
     * Retrieves the URI instance.
     *
     * This method MUST return a UriInterface instance.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri();

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * over to the returned request.
     *
     * You can opt-in to preserving the original state of the Host header by
     * setting `$preserveHost` to `true`. When `$preserveHost` is set to
     * `true`, this method interacts with the Host header in the following ways:
     *
     * - If the the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the returned
     *   request.
     * - If the Host header is missing or empty, and the new URI does not contain a
     *   host component, this method MUST NOT update the Host header in the returned
     *   request.
     * - If a Host header is present and non-empty, this method MUST NOT update
     *   the Host header in the returned request.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new UriInterface instance.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return self
     */
    public function withUri(UriInterface $uri, $preserveHost = false);
}
```

#### 3.2.1 `Psr\Http\Message\ServerRequestInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Representation of an incoming, server-side HTTP request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * Additionally, it encapsulates all data as it has arrived to the
 * application from the CGI and/or PHP environment, including:
 *
 * - The values represented in $_SERVER.
 * - Any cookies provided (generally via $_COOKIE)
 * - Query string arguments (generally via $_GET, or as parsed via parse_str())
 * - Upload files, if any (as represented by $_FILES)
 * - Deserialized body parameters (generally from $_POST)
 *
 * $_SERVER values MUST be treated as immutable, as they represent application
 * state at the time of request; as such, no methods are provided to allow
 * modification of those values. The other values provide such methods, as they
 * can be restored from $_SERVER or the request body, and may need treatment
 * during the application (e.g., body parameters may be deserialized based on
 * content type).
 *
 * Additionally, this interface recognizes the utility of introspecting a
 * request to derive and match additional parameters (e.g., via URI path
 * matching, decrypting cookie values, deserializing non-form-encoded body
 * content, matching authorization headers to users, etc). These parameters
 * are stored in an "attributes" property.
 *
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */
interface ServerRequestInterface extends RequestInterface
{
    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    public function getServerParams();

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The data MUST be compatible with the structure of the $_COOKIE
     * superglobal.
     *
     * @return array
     */
    public function getCookieParams();

    /**
     * Return an instance with the specified cookies.
     *
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     *
     * This method MUST NOT update the related Cookie header of the request
     * instance, nor related values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated cookie values.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     * @return self
     */
    public function withCookieParams(array $cookies);

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * Note: the query params might not be in sync with the URI or server
     * params. If you need to ensure you are only getting the original
     * values, you may need to parse the query string from `getUri()->getQuery()`
     * or from the `QUERY_STRING` server param.
     *
     * @return array
     */
    public function getQueryParams();

    /**
     * Return an instance with the specified query string arguments.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's parse_str() would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * Setting query string arguments MUST NOT change the URI stored by the
     * request, nor the values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated query string arguments.
     *
     * @param array $query Array of query string arguments, typically from
     *     $_GET.
     * @return self
     */
    public function withQueryParams(array $query);

    /**
     * Retrieve normalized file upload data.
     *
     * This method returns upload metadata in a normalized tree, with each leaf
     * an instance of Psr\Http\Message\UploadedFileInterface.
     *
     * These values MAY be prepared from $_FILES or the message body during
     * instantiation, or MAY be injected via withUploadedFiles().
     *
     * @return array An array tree of UploadedFileInterface instances; an empty
     *     array MUST be returned if no data is present.
     */
    public function getUploadedFiles();

    /**
     * Create a new instance with the specified uploaded files.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param array An array tree of UploadedFileInterface instances.
     * @return self
     * @throws \InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles);

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, this method MUST
     * return the contents of $_POST.
     *
     * Otherwise, this method may return any results of deserializing
     * the request body content; as parsing returns structured content, the
     * potential types MUST be arrays or objects only. A null value indicates
     * the absence of body content.
     *
     * @return null|array|object The deserialized body parameters, if any.
     *     These will typically be an array or object.
     */
    public function getParsedBody();

    /**
     * Return an instance with the specified body parameters.
     *
     * These MAY be injected during instantiation.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, use this method
     * ONLY to inject the contents of $_POST.
     *
     * The data IS NOT REQUIRED to come from $_POST, but MUST be the results of
     * deserializing the request body content. Deserialization/parsing returns
     * structured data, and, as such, this method ONLY accepts arrays or objects,
     * or a null value if nothing was available to parse.
     *
     * As an example, if content negotiation determines that the request data
     * is a JSON payload, this method could be used to create a request
     * instance with the deserialized parameters.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param null|array|object $data The deserialized body data. This will
     *     typically be in an array or object.
     * @return self
     * @throws \InvalidArgumentException if an unsupported argument type is
     *     provided.
     */
    public function withParsedBody($data);

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return mixed[] Attributes derived from the request.
     */
    public function getAttributes();

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute($name, $default = null);

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $value The value of the attribute.
     * @return self
     */
    public function withAttribute($name, $value);

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @return self
     */
    public function withoutAttribute($name);
}
```

### 3.3 `Psr\Http\Message\ResponseInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Representation of an outgoing, server-side response.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - Status code and reason phrase
 * - Headers
 * - Message body
 *
 * Responses are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode();

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @return self
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '');

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase();
}
```

### 3.4 `Psr\Http\Message\StreamInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Describes a data stream.
 *
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 */
interface StreamInterface
{
    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString();

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close();

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach();

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize();

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws \RuntimeException on error.
     */
    public function tell();

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof();

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Seek to a position in the stream.
     *
     * @see http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see seek()
     * @see http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public function rewind();

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public function write($string);

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws \RuntimeException if an error occurs.
     */
    public function read($length);

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws \RuntimeException if unable to read.
     * @throws \RuntimeException if error occurs while reading.
     */
    public function getContents();

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @see http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata($key = null);
}
```

### 3.5 `Psr\Http\Message\UriInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Value object representing a URI.
 *
 * This interface is meant to represent URIs according to RFC 3986 and to
 * provide methods for most common operations. Additional functionality for
 * working with URIs can be provided on top of the interface or externally.
 * Its primary use is for HTTP requests, but may also be used in other
 * contexts.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 *
 * Typically the Host header will be also be present in the request message.
 * For server-side requests, the scheme will typically be discoverable in the
 * server parameters.
 *
 * @see http://tools.ietf.org/html/rfc3986 (the URI specification)
 */
interface UriInterface
{
    /**
     * Retrieve the scheme component of the URI.
     *
     * If no scheme is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     *
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string The URI scheme.
     */
    public function getScheme();

    /**
     * Retrieve the authority component of the URI.
     *
     * If no authority information is present, this method MUST return an empty
     * string.
     *
     * The authority syntax of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority();

    /**
     * Retrieve the user information component of the URI.
     *
     * If no user information is present, this method MUST return an empty
     * string.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo();

    /**
     * Retrieve the host component of the URI.
     *
     * If no host is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string The URI host.
     */
    public function getHost();

    /**
     * Retrieve the port component of the URI.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard port
     * used with the current scheme, this method SHOULD return null.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     *
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return null|int The URI port.
     */
    public function getPort();

    /**
     * Retrieve the path component of the URI.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * Normally, the empty path "" and absolute path "/" are considered equal as
     * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
     * do this normalization because in contexts with a trimmed base path, e.g.
     * the front controller, this difference becomes significant. It's the task
     * of the user to handle both "" and "/".
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     *
     * As an example, if the value should include a slash ("/") not intended as
     * delimiter between path segments, that value MUST be passed in encoded
     * form (e.g., "%2F") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string The URI path.
     */
    public function getPath();

    /**
     * Retrieve the query string of the URI.
     *
     * If no query string is present, this method MUST return an empty string.
     *
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     *
     * As an example, if a value in a key/value pair of the query string should
     * include an ampersand ("&") not intended as a delimiter between values,
     * that value MUST be passed in encoded form (e.g., "%26") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string The URI query string.
     */
    public function getQuery();

    /**
     * Retrieve the fragment component of the URI.
     *
     * If no fragment is present, this method MUST return an empty string.
     *
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string The URI fragment.
     */
    public function getFragment();

    /**
     * Return an instance with the specified scheme.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     *
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     * @return self A new instance with the specified scheme.
     * @throws \InvalidArgumentException for invalid schemes.
     * @throws \InvalidArgumentException for unsupported schemes.
     */
    public function withScheme($scheme);

    /**
     * Return an instance with the specified user information.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     *
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return self A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null);

    /**
     * Return an instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return self A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host);

    /**
     * Return an instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     *
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *     removes the port information.
     * @return self A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port);

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If the path is intended to be domain-relative rather than path relative then
     * it must begin with a slash ("/"). Paths not starting with a slash ("/")
     * are assumed to be relative to some base path known to the application or
     * consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     * @return self A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path);

    /**
     * Return an instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     *
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return self A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
    public function withQuery($query);

    /**
     * Return an instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     *
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return self A new instance with the specified fragment.
     */
    public function withFragment($fragment);

    /**
     * Return the string representation as a URI reference.
     *
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters:
     *
     * - If a scheme is present, it MUST be suffixed by ":".
     * - If an authority is present, it MUST be prefixed by "//".
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     * - If a query is present, it MUST be prefixed by "?".
     * - If a fragment is present, it MUST be prefixed by "#".
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     * @return string
     */
    public function __toString();
}
```

### 3.6 `Psr\Http\Message\UploadedFileInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * Value object representing a file uploaded through an HTTP request.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 */
interface UploadedFileInterface
{
    /**
     * Retrieve a stream representing the uploaded file.
     *
     * This method MUST return a StreamInterface instance, representing the
     * uploaded file. The purpose of this method is to allow utilizing native PHP
     * stream functionality to manipulate the file upload, such as
     * stream_copy_to_stream() (though the result will need to be decorated in a
     * native PHP stream wrapper to work with such functions).
     *
     * If the moveTo() method has been called previously, this method MUST raise
     * an exception.
     *
     * @return StreamInterface Stream representation of the uploaded file.
     * @throws \RuntimeException in cases when no stream is available.
     * @throws \RuntimeException in cases when no stream can be created.
     */
    public function getStream();

    /**
     * Move the uploaded file to a new location.
     *
     * Use this method as an alternative to move_uploaded_file(). This method is
     * guaranteed to work in both SAPI and non-SAPI environments.
     * Implementations must determine which environment they are in, and use the
     * appropriate method (move_uploaded_file(), rename(), or a stream
     * operation) to perform the operation.
     *
     * $targetPath may be an absolute path, or a relative path. If it is a
     * relative path, resolution should be the same as used by PHP's rename()
     * function.
     *
     * The original file or stream MUST be removed on completion.
     *
     * If this method is called more than once, any subsequent calls MUST raise
     * an exception.
     *
     * When used in an SAPI environment where $_FILES is populated, when writing
     * files via moveTo(), is_uploaded_file() and move_uploaded_file() SHOULD be
     * used to ensure permissions and upload status are verified correctly.
     *
     * If you wish to move to a stream, use getStream(), as SAPI operations
     * cannot guarantee writing to stream destinations.
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     * @param string $targetPath Path to which to move the uploaded file.
     * @throws \InvalidArgumentException if the $path specified is invalid.
     * @throws \RuntimeException on any error during the move operation.
     * @throws \RuntimeException on the second or subsequent call to the method.
     */
    public function moveTo($targetPath);

    /**
     * Retrieve the file size.
     *
     * Implementations SHOULD return the value stored in the "size" key of
     * the file in the $_FILES array if available, as PHP calculates this based
     * on the actual size transmitted.
     *
     * @return int|null The file size in bytes or null if unknown.
     */
    public function getSize();

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * The return value MUST be one of PHP's UPLOAD_ERR_XXX constants.
     *
     * If the file was uploaded successfully, this method MUST return
     * UPLOAD_ERR_OK.
     *
     * Implementations SHOULD return the value stored in the "error" key of
     * the file in the $_FILES array.
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     * @return int One of PHP's UPLOAD_ERR_XXX constants.
     */
    public function getError();

    /**
     * Retrieve the filename sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious filename with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "name" key of
     * the file in the $_FILES array.
     *
     * @return string|null The filename sent by the client or null if none
     *     was provided.
     */
    public function getClientFilename();

    /**
     * Retrieve the media type sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious media type with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "type" key of
     * the file in the $_FILES array.
     *
     * @return string|null The media type sent by the client or null if none
     *     was provided.
     */
    public function getClientMediaType();
}
```
