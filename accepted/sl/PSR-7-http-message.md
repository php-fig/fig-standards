# Vmesniki sporočil HTTP

Ta dokument opisuje skupne vmesnike za predstavitev sporočil HTTP, kot
so opisani v [RFC 7230](http://tools.ietf.org/html/rfc7230) in
[RFC 7231](http://tools.ietf.org/html/rfc7231) ter URI-je za uporabo s sporočili HTTP,
kot so opisani v [RFC 3986](http://tools.ietf.org/html/rfc3986).

Sporočila HTTP so osnova spletnega razvoja. Spletni brskalniki in klienti HTTP,
kot je cURL, ustvarijo sporočila zahtevka HTTP, ki so poslana spletnemu strežniku,
ki ponuja sporočilo odziva HTTP. Koda strežniške strani prejme sporočilo zahtevka HTTP
in vrne sporočilo odziva HTTP.

Sporočila HTTP so običajno izvzeta od končnega uporabnika, vendar kot
razvijalci moramo običajno vedeti, kako so strukturirana in kako
do njih dostopati ali z njimi manipulirati, da opravimo naša opravila, bodisi naj bo to
ustvarjanje zahtevka k API-ju HTTP ali upravljanje prihajajočega zahtevka.

Vsako sporočilo zahtevka HTTP ima določeno obliko:

```http
POST /path HTTP/1.1
Host: example.com

foo=bar&baz=bat
```

Prva vrstica zahtevka je t.i. "vrstica zahtevka" in vsebuje v vrstnem redu
metodo zahtevka HTTP, cilj zahtevka (običajno bodisi absolutni URI ali
pot na spletnem strežniku) in verzijo protokola HTTP. Temu sledi ena
ali več glav HTTP, prazna vrstica in telo sporočila.

Sporočila odziva HTTP imajo podobno strukturo:

```http
HTTP/1.1 200 OK
Content-Type: text/plain

This is the response body
```

Prva vrstica je "vrstica stanja" in vsebuje v vrstnem redu verzijo protokola
HTTP, kodo stanja HTTP in "frazo razloga", berljivi
opis kode stanja. Kot sporočilo zahtevka temu nato
sledi ena ali več glav HTTP, prazna vrstica in telo sporočila.

Vmesniki opisani v tem dokumentu so abstrakcije okoli sporočil HTTP
in elementi, ki jih sestavljajo.

Ključne besede "MORA", "NE SME", "ZAHTEVA", "PRIPOROČA", "LAHKO" in "NEOBVEZNO"
v tem dokumentu se razlagajo, kot je navedeno v
[RFC 2119](http://tools.ietf.org/html/rfc2119).

### Reference

- [RFC 2119](http://tools.ietf.org/html/rfc2119)
- [RFC 3986](http://tools.ietf.org/html/rfc3986)
- [RFC 7230](http://tools.ietf.org/html/rfc7230)
- [RFC 7231](http://tools.ietf.org/html/rfc7231)


## 1. Specifikacija

### 1.1 Sporočila

HTTP sporočilo je bodisi zahtevek klienta strežniku ali odziv
strežnika klientu. Ta specifikacija definira vmesnike za sporočila HTTP
`Psr\Http\Message\RequestInterface` in `Psr\Http\Message\ResponseInterface` v tem zaporedju.

Tako `Psr\Http\Message\RequestInterface` kot `Psr\Http\Message\ResponseInterface` razširjata
`Psr\Http\Message\MessageInterface`. Medtem ko `Psr\Http\Message\MessageInterface` je LAHKO
implementiran direktno, implementatorji BI MORALI implementirati
`Psr\Http\Message\RequestInterface` in `Psr\Http\Message\ResponseInterface`.

Od tu naprej bo imenski prostor `Psr\Http\Message` opuščen,
ko se sklicuje na te vmesnike.

#### 1.2 Glave HTTP

##### Imena polj glave z neobčutljivo velikostjo črk

Sporočila HTTP vključujejo imena polj glave z neobčutljivo velikostjo črk. Glave so vzpostavljene
z imeni iz razredov, ki implementirajo `MessageInterface` na način neobčutljivih velikosti črk.
Na primer, vzpostavitev glave `foo` bo vrnilo enak rezultat kot
vzpostavitev glave `FoO`. Podobno, nastavitev glave `Foo` bo prepisalo
katerokoli prej nastavljeno vrednost glave `foo`.

```php
$message = $message->withHeader('foo', 'bar');

echo $message->getHeaderLine('foo');
// Outputs: bar

echo $message->getHeaderLine('FOO');
// Outputs: bar

$message = $message->withHeader('fOO', 'baz');
echo $message->getHeaderLine('foo');
// Outputs: baz
```

Klub temu, da so glave lahko vzpostavljene z neobčutljivo velikostjo črk, prvotna velikost črk
MORA biti ohranjena v implementaciji, še posebej ko je vzpostavljena z
`getHeaders()`.

Neskladne aplikacije HTTP so lahko odvisne na določeno velikost črk, tako da je uporabno
za uporabnika, da je sposoben diktirati velikost črk za glave HTTP, ko se ustvarja
zahtevek odziva.

##### Glave z večimi vrednostmi

Za namestitev glav z večimi vrednostmi še vedno ponujajo
udobje dela z glavami kot nizi, glave so lahko pridobljene iz
instance `MessageInterface` kot polje nizov. Uporabite
`getHeaderLine()` metodo za vzpostavitev vrednosti glave saj niz vsebuje vse
vrednosti glave od glave z neobčutljivo velikostjo črk z imenom združenim z vejico.
Uporabite `getHeader()` za vpostavitev polja vseh vrednosti glav za
določeno glavo z neobčutljivo velikostjo črk po imenu.

```php
$message = $message
    ->withHeader('foo', 'bar')
    ->withAddedHeader('foo', 'baz');

$header = $message->getHeaderLine('foo');
// $header contains: 'bar, baz'

$header = $message->getHeader('foo');
// ['bar', 'baz']
```

Opomba: Ne vse vrednosti glav so lahko združene z uporabo vejice (npr.,
`Set-Cookie`). Ko delate s takimi glavami, se BI MORALI uporabniki
`MessageInterface`-osnovanih razredov zanašati na metodo `getHeader()`
za pridobivanje takih več-vrednostnih glav.

##### Glava gostitelj

V zahtevkih glava `Host` običajno preslika komponento gostitelja URI-ja kot
tudi uporabljenega gostitelja, ko vzpostavlja TCP povezavo. Vendar specifikacija HTTP
omogoča, da je glava `Host` drugačna od vsake od teh dveh.

Med ustvarjanjem MORAJO implementacije poskusiti nastaviti glavo `Host` iz
ponujenega URI-ja, če glava `Host` ni ponujena.

`RequestInterface::withUri()` bo privzeto zamenjal vrnjeno glavo zahtevka
`Host` z glavo `Host`, ki se ujema s komponento gostitelja podanega
`UriInterface`.

Lahko poskusite opt-in, da obdržite prvotno stanje glave `Host` s podajanjem
`true` kot drugi argument (`$preserveHost`). Ko je ta argument nastavljen na
`true`, vrnjeni zahtevek ne bo posodobil glave `Host` vrnjegega
sporočila -- razen, če sporočilo vsebuje glavo `Host`.

Ta tabela ponazarja, kaj bo `getHeaderLine('Host')` vrnil za zahtevek
vrnje z `withUri()` z argumentom `$preserveHost` nastavljenim na `true` za
različne začetne zahtevke in URI-je.

Glava zahtevka gostitelja<sup>[1](#rhh)</sup> | Komponenta zahtevka gostitelja<sup>[2](#rhc)</sup> | Komponenta gostitelja URI<sup>[3](#uhc)</sup> | Rezultat
----------------------------------------------|----------------------------------------------------|-----------------------------------------------|---------
''                                            | ''                                                 | ''                                            | ''
''                                            | foo.com                                            | ''                                            | foo.com
''                                            | foo.com                                            | bar.com                                       | foo.com
foo.com                                       | ''                                                 | bar.com                                       | foo.com
foo.com                                       | bar.com                                            | baz.com                                       | foo.com

- <sup id="rhh">1</sup> vrednost glave `Host` pred operacijo.
- <sup id="rhc">2</sup> Komponenta gostitelja URI-ja sestavljena v zahtevku pred
  operacijo.
- <sup id="uhc">3</sup> Komponenta gostitelja URI-ja injicirana preko
  `withUri()`.

### 1.3 Tokovi

HTTP sporočila so sestavljena iz začetne vrstice, glav in telesa. Telo HTTP
sporočila je lahko zelo majhno ali izjemno veliko. Poskušanje predstavitve telesa
sporočila kot niz lahko enostavno porabi več spomina, kot je namenjen, ker
mora telo biti shranjeno v celoti v spomin. Poskušanje shraniti telo iz zahtevka
ali odziva v spomin bi izključilo uporabo te implementacije, da je zmožna
delati z velikimi telesi sporočil. `StreamInterface` je uporabljen,
da skrije podrobnosti implementacije, ko je tok podatkov bran ali
pisan. Za situacije, kjer bi bil niz ustrezna implementacija sporočila
vgrajeni tokovi, kot sta lahko uporabljena `php://memory` in `php://temp`.

`StreamInterface` izpostavlja več metod, ki omogočajo, da so tokovi brani
ali zapisani in efektivno prečkani.

Tokovi izpostavljajo zmožnosti z uporabo treh metod: `isReadable()`,
`isWritable()` in `isSeekable()`. Te metode so lahko uporabljene s
sodelujočimi tokovi za določitev, če je tok zmožen njihovih zahtev.

Vsaka instanca toku bo imela različne zmožnosti: lahko je samo za branje,
samo za pisanje ali branje in pisanje. Lahko tudi omogoča arbitrarno naključen dostop (iskanje
naprej ali nazaj po kateri koli lokaciji), ali samo sekvenčni dostop (za
primer priključka, cevi ali na osnovi povratno klicanega toka).

Končno, `StreamInterface` definira `__toString()` metodo za enostavnejše
pridobivanje ali oddajanje velotnega telesa vsebin na enkrat.

Z razliko vmesnikov zahtevka in odziva, `StreamInterface` ne modelira
nespremenljivosti. V situacijah, kjer je dejanski PHP tok ovit, je nespremenljivost
nemogoča za uveljavitev saj lahko katerakoli koda, ki ima interakcijo z virom,
potencialno spremeni svoje stanje (vključno s pozicijo kurzorja, vsebinami in več).
Naše priporočilo je, da implementacije uporabljajo tokove samo za branje za
zahtevke strežniške strani in odzive klientne strani. Uporabniki bi se morali zavedati
dejstva, da so instance tokov lahko spremenljive in kot take lahko spremenijo
stanje sporočila; ko ste v dvomih, ustvarite novo instanco toka in jo prilepite
sporočilu, da vsilite stanje.

### 1.4 Tarče zahtevka in URI-ji

Glede na RFC 7230 sporočilo zahtevka vsebuje "request-target" kot drugi segment
vrstice zahtevka. Tarča zahtevka je lahko ena izmed sledečih oblik:

- **origin-form**, ki sestoji iz poti in, če je prisoten, niza poizvedbe;
  to je pogosto sklicano kot relativni URL. Sporočila kot so posredovana
  preko TCP imajo običajno origin-form; shema in podatki avtoritete so običajno
  samo prisotni preko CGI spremenljivk.
- **absolute-form**, ki sestoji iz sheme, avtoritete
  ("[user-info@]host[:port]", kjer so elementi v oglatih oklepajih opcijski), poti (če
  je prisotna), niza poizvedbe (če je prisoten) in fragmenta (če je prisoten). To je pogosto
  sklicano kot absolutni URI in je edina oblika za določanje URI-ja kot je
  podrobno opisan v RFC 3986. Ta oblika je pogosto uporabljena, ko se dela zahtevke za
  proksije HTTP.
- **authority-form**, ki sestoji samo iz avtoritete. Ta je običajno
  uporabljena samo v zahtevkih CONNECT za vzpostavitev povezave med klientom
  HTTP in strežnikom proxy.
- **asterisk-form**, ki sestoji izključno iz niza `*` in ki je uporabljen
  z metodo OPTIONS za določitev splošnih zmožnosti spletnega strežnika.

Na stran od teh request-targets je pogostokrat 'efektivni URL', ki je
ločen od tarče zahtevka. Efektivni URL ni posredovan znotraj
sporočila HTTP, vendar je uporabljen za določitev protokola (http/https), porta
in imena gostitelja za izdelavo zahtevka.

Efektivni URL je predstavljen z `UriInterface`. `UriInterface` modelira URI-je HTTP
in HTTPS, kot so določeni v RFC 3986 (primarni primer uporabe). Vmesnik
ponuja metode za interakcijo z različnimi deli URI-ja, ki bo odpravil
potrebo po ponavljanju prevajanja URI-ja. Tudi določa metodo `__toString()`
za igranje vloge modeliranjega URI-ja v njegovo predstavitev z nizom.

Ko se pridobiva request-targe z `getRequestTarget()`, bo privzeto ta
metoda uporabila objekt URI in izvlekla vse potrebne komponente, da sestavi
_origin-form_. _origin_form_ je najbolj pogosti
request-target.

Če je zaželjeno s strani končnega uporabnika uporabiti eno izmed treh oblik ali če
uporabnik želi eksplicitno prepisati request-targe, je to možno narediti
z `withRequestTarget()`.

Klicanje te metode ne vpliva na URI kot je vrnjen iz `getUri`().

Na primer, uporabnik želi narediti zahtevek asterisk-form na strežnik:

```php
$request = $request
    ->withMethod('OPTIONS')
    ->withRequestTarget('*')
    ->withUri(new Uri('https://example.org/'));
```

Ta primer lahko ultimativno rezultira v zahtevek HTTP, ki izgleda takole:

```http
OPTIONS * HTTP/1.1
```

Vendar klient HTTP bo zmožen uporabiti efektivni URL (iz `getUri()`)
za določitev protokola, imena gostitelja in porta TCP.

Klient HTTP MORA ignorirati vrednosti `Uri::getPath()` in `Uri::getQuery()`
in namesto tega uporabiti vrednost vrnjeno od `getRequestTarget()`, ki je privzeto
združevanje teh dveh vrednosti.

Klienti, ki ne izberejo implementirati 1 ali več od 4 request-target oblik,
MORAJO še vedno uporabiti `getRequestTarget()`. Te klienti MORAJO zavrniti request-target-e,
ki jih ne podpirajo in se NE SMEJO povrniti na vrednosti iz `getUri()`.

`RequestInterface` ponuja metode za pridobitev request-target ali
izdelavo nove instance s ponujenim request-target. Privzeto, če
request-target ni specifično sestavljen v instanci, bo `getRequestTarge()`
vrnila origin-form sestavljenega URI-ja (ali "/", če URI ni sestavljen).
`withRequestTarget($requestTarget)` ustvarja novo instanvo z
določeno tarčo zahtevka in tako omogoča razvijalcem, da ustvarijo sporočila zahtevka,
ki predstavljajo ostale tri oblike request-target (absolute-form,
authority-form in asterisk-form). Ko je uporabljeno, je sestavljena instanca URI-ja
še vedno uporabljena, posebno v klientih, kjer je lahko uporabljena za izdelavo
povezave s strežnikom.

### 1.5 Zahtevki strežniške strani

`RequestInterface` ponuja splošno predstavitev zahtevka sporočila
HTTP. Vendar zahtevki strežniške strani potrebujejo dodatno obdelavo zaradi
naravi okolja strežniške strani. Procesiranje strežniške strani morajo vzeti v
obzir Common Gateway Interface (CGI) in natančneje PHP-jevo
abstrakcijo in razširitev CGI preko njegovih strežniških API-jev (SAPI). PHP ponuja
poenovstavitev okrog ranžiranja vhodov preko superglobals kot so:

- `$_COOKIE`, ki deserizaliza in ponuja poenostavljen dostop za piškotke
  HTTP.
- `$_GET`, ki deserializira in ponuja poenostavljen dostop za argumente niza
  poizvedbe.
- `$_POST`, ki deserializira in ponuja poenostavljen dostop za url-enkodirane
  parametre poslane preko HTTP POST; generično je lahko smatran kot
  rezultat prevedenega telesa sporočila.
- `$_FILES`, ki ponuja serializirane meta podatke okrog nalaganja datotek.
- `$_SERVER`, ki ponuja dostop do CGI/SAPI spremenljivk okolja, ki
  skupno vključujejo metodo zahtevka, shemo zahtevka, URI zahtevka in
  glave.

`ServerRequestInterface` razširja `RequestInterface`, da ponuja abstrakcijo
okrog teh različnih superglobals. Ta praksa pomaga zmanjšati sklapljanje
superglobals s strani uporabnikov in spodbuja in promovira zmožnost testirati
zahtevke uporabnikov.

Zahtevek strežnika ponuja dodatno lastnost, "attributes", da omogoča
uporabnikom zmožnost za introspekcijo, dekompozicijo in ujemanje zahtevka proti
pravilom specifik aplikacije (kot so ujemanje poti, ujemanje sheme, ujemanje
gostitelja itd.). Kot tak zahtevek strežnika lahko tudi ponuja sporočanje med
večimi zahtevki uporabnikov.

### 1.6 Naložene datoteke

`ServerRequestInterface` določa metodo za pridobivanje drevesa naloženih
datotek v normalizirani strukturi, z vsakim listom instance
`UploadedFileInterface`.

Superglobal `$_FILES` ima nekaj dobro znanih težav, ko se dela s polji
vnosnih datotek. Kot primer, če imate obrazec, ki pošlje polje datotek
— npr. ime vnosa "files", pošiljanje `files[0]` in `files[1]` — PHP bo
predstavil to kot:

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

namesto pričakovanega:

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

Rezultat je, da morajo uporabniki vedeti to podrobnost implementacije jezika,
in pisati kodo za pridobivanje podatkov za dano nalaganje.

Dodatno, scenariji obstajajo, kjer `$_FILES` ni zapolnjen, ko se zgodi nalaganje
datoteke:

- Ko metoda HTTP ni `POST`.
- Ko se dela teste enot.
- Ko se operira pod okoljem ne-SAPI, kot je [ReactPHP](http://reactphp.org).

V takih primerih bodo podatki morali biti sejani drugačno. Kot primer:

- Proces lahko prevede telo sporočila, da odkrije nalaganje datotek. V takih
  primerih lahko implementacija izbere, da *ne* zapiše naloženih datotek
  v datotečni sistem, vendar jih namesto tega ovije v tok, da zmanjša spomin,
  I/O in preveliko uporabo shrambe.
- V scenarijih testiranja enot, morajo biti razvijalci zmožni potrgati in/ali oponašati
  metapodatke nalaganja datotek, da preverijo in potrdijo različne scenarije.

`getUploadedFiles()` ponuja normalizirano strukturo za uporabnike.
Implementacije so pričakovane, da:

- Sestavljajo vse informacije za dano nalaganje datoteke in jo uporabijo za polnjenje
  instance `Psr\Http\Message\UploadedFileInterface`.
- Ponovno ustvarijo drevesno strukturo, ki je z vsakim listom ustrezna
  instanca `Psr\Http\Message\UploadedFileInterface` za dano lokacijo v
  drevesu.

Sklicana struktura drevesa bi morala oponašati strukturo poimenovanja v kateri so datoteke
poslane.

V najenostavnejšem primeru je to lahko eno poimenovanje elementa obrazca poslanega kot:

```html
<input type="file" name="avatar" />
```

V tem primeru bi struktura v `$_FILES` izgledala takole:

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

Normalizirana oblika vrnjega od `getUploadedFiles()` bi bila:

```php
array(
    'avatar' => /* UploadedFileInterface instance */
)
```

V primeru vnosa z uporabo zapisa polja za ime:

```html
<input type="file" name="my-form[details][avatar]" />
```

`$_FILES` na koncu izgleda takole:

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

In pripadajoče drevo vrnjeno z `getUploadedFiles()` bi moralo biti:

```php
array(
    'my-form' => array(
        'details' => array(
            'avatar' => /* UploadedFileInterface instance */
        ),
    ),
)
```

V nekaterih primerih lahko določite polje datotek:

```html
Upload an avatar: <input type="file" "name="my-form[details][avatars][]" />
Upload an avatar: <input type="file" "name="my-form[details][avatars][]" />
```

(Kot primer, JavaScript kontrole lahko dodajo dodatne vnose nalaganja datotek, da
omogočijo nalaganje večih datotek naenkrat.)

V takem primeru, mora biti implementacije specifikacije sestavljena iz vseh informacij,
ki se tičejo datoteke na danem indeksu. Razlog je, ker `$_FILES` odstopa
od svoje normalne strukture v takih primerih:

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

Zgornje polje `$_FILES` bi ustrezalo sledeči strukturi kot
je vrnjena z `getUploadedFiles()`:

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

Uporabniki bi dostopali do indeksa `1` vgnezdenega polja z uporabo:

```php
$request->getUploadedFiles()['my-form']['details']['avatars'][1];
```

Ker so podatki naloženih datotek derivati (pridobljeni iz `$_FILES`) ali
telesa zahtevka), je metoda mutatorja `ẁithUploadedFiles()` tudi prisotna v
vmesniku, kar omogoča delegiranje normalizacije drugemu procesu.

V primeru prvotnih primerov, uproaba odraža sledeče:

```php
$file0 = $request->getUploadedFiles()['files'][0];
$file1 = $request->getUploadedFiles()['files'][1];

printf(
    "Received the files %s and %s",
    $file0->getClientFilename(),
    $file1->getClientFilename()
);

// "Received the files file0.txt and file1.html"
```

Ta predlog tudi prepoznava, da implementacije lahko operirajo v ne-SAPI
okoljih. Kot take `UploadedFileInterface` ponujajo metode za zagotavljanje
operacij, ki bodo delovale ne glede na okolje. Še posebej:

- `moveTo($targetPath)` je ponujen kot varnost in priporočena alternativa klicanju
  `move_uploaded_file()` direktno na začasno naloženi datoteki. Implementacije
  bodo zaznale ustrezno operacijo za uporabo na osnovi okolja.
- `getStream()` bo vrnil instanco `StreamInterface`. V ne-SAPI
  okoljih je ena predlagana možnost prevajanje individualnih naloženih datotek
  v `php://temp` tokove namesto direktno v datoteke; v takih primerih
  ni prisotna nobena datoteka. `getStream()` torej garantirano deluje
  ne glede na okolje.

Kot primeri:

```
// Move a file to an upload directory
$filename = sprintf(
    '%s.%s',
    create_uuid(),
    pathinfo($file0->getClientFilename(), PATHINFO_EXTENSION)
);
$file0->moveTo(DATA_DIR . '/' . $filename);

// Stream a file to Amazon S3.
// Assume $s3wrapper is a PHP stream that will write to S3, and that
// Psr7StreamWrapper is a class that will decorate a StreamInterface as a PHP
// StreamWrapper.
$stream = new Psr7StreamWrapper($file1->getStream());
stream_copy_to_stream($stream, $s3wrapper);
```

## 2. Paket

Vmesniki in opisani razredi so ponujeni kot del
paketa [psr/http-message](https://packagist.org/packages/psr/http-message).

## 3. Vmesniki

### 3.1 `Psr\Http\Message\MessageInterface`

```php
<?php
namespace Psr\Http\Message;

/**
 * HTTP messages consist of requests from a client to a server and responses
 * from a server to a client. This interface defines the methods common to
 * each.
 *
 * Messages are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @see http://www.ietf.org/rfc/rfc7230.txt
 * @see http://www.ietf.org/rfc/rfc7231.txt
 */
interface MessageInterface
{
    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion();

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new protocol version.
     *
     * @param string $version HTTP protocol version
     * @return self
     */
    public function withProtocolVersion($version);

    /**
     * Retrieves all message header values.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * While header names are not case-sensitive, getHeaders() will preserve the
     * exact case in which headers were originally specified.
     *
     * @return string[][] Returns an associative array of the message's headers.
     *     Each key MUST be a header name, and each value MUST be an array of
     *     strings for that header.
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
