# Sporočilo HTTP meta dokument

## 1. Povzetek

Namen tega predloga je ponuditi skupek skupnih vmesnikov za sporočila HTTP, kot
so opisana v [RFC 7230](http://tools.ietf.org/html/rfc7230) in
[RFC 7231](http://tools.ietf.org/html/rfc7231) ter URI-jev, kot so opisani v
[RFC 3986](http://tools.ietf.org/html/rfc3986) (v kontekstu sporočil HTTP).

- RFC 7230: http://www.ietf.org/rfc/rfc7230.txt
- RFC 7231: http://www.ietf.org/rfc/rfc7231.txt
- RFC 3986: http://www.ietf.org/rfc/rfc3986.txt

Vsa sporočila HTTP sestojijo iz verzije protokola HTTP, ki je uporabljen, glav in
telesa sporočila. _Zahtevek_ se zgradi na sporočilu, ki vključuje metodo HTTP,
ki je uporabljena za izdelavo zahtevka in URI-ja za katerega je narejen zahtevek.
_Odziv_ vključuje statusno kodo HTTP in frazo razloga.

V PHP so sporočila HTTP uporabljena v dveh kontekstih:

- Da pošljejo zahtevek HTTP preko razširitve `ext/curl`, PHP-jevega osnovnega nivoja toka
  itd. ter procesirajo odziv HTTP. Z drugimi besedami, sporočila HTTP
  so uporabljena, ko se uporablja PHP kot _klient HTTP_.
- Da procesirajo prihajajoči zahtevek HTTP strežniku in vrnejo odziv HTTP
  klientu, ki dela zahtevek. PHP lahko uporabi sporočila HTTP, ko so uporabljena kot
  _aplikacija strežniške strani_, da zapolni zahtevke HTTP.

Ta predlog predstavlja API za poln opis vseh delov različnih
sporočil HTTP znotraj PHP.

## 2. Sporočila HTTP v PHP

PHP nima vgrajene podpore za sporočila HTTP.

### Podpora HTTP na strani klienta

PHP podpira pošiljanje zahtevkov HTTP preko večih mehanizmov:

- [Tokovi PHP](http://php.net/streams)
- [Razširitev cURL](http://php.net/curl)
- [ext/http](http://php.net/http) (v2 tudi poskuša naslavljati podporo strežniške strani)

Tokovi PHP so najbolj priročen in vseposoten način za pošiljanje zahtevkov HTTP,
vendar predstavljajo številne omejitve, kot so ustrezno nastavljanje podpore SSL
in ponujajo nepriročen vmesnik okrog nastavitev stvari, kot so
glave. cURL ponuja celoten in razširjen skupek lastnosti, vendar ker ni
privzeta razširitev, pogostokrat ni prisotna. Razširitev http trpi za enakimi
težavami kot cURL, kot tudi dejstvo, da je imela tradicionalno veliko manj
primerov uporabe.

Večina modernih knjižnic klienta HTTP skuša abstraktirati implementacijo, da
zagotovijo delo v kateremkoli okolju, na katerem so izvršena in preko
katerega koli zgornjih nivojev.

### Podpora HTTP strežniške strani

PHP uporablja API-je strežnika (SAPI), da prevede prihajajoče zahtevke HTTP, maršalni vnos
in poda upravljanje skriptam. Originalni načrt preslikanega SAPI-ja [Common
Gateway Interface](http://www.w3.org/CGI), ki bi maršalno zahteval podatke
in jih poslal v spremenljivke okolja pred posredovanjem delegiranju skripte;
skripta bi nato potegnila iz spremenljivk okolja, da procesira
zahtevek in vrne odziv.

PHP-jev načrt SAPI abstraktira skupen vir vnosa, kot so piškotki, argumenti niza zahtevka
in url-enkodirano vsebino POST preko t.i. superglobals (pripadajoči `$_COOKIE`, `$_GET`,
in `$_POST`) ponuja nivo udobja za spletne razvijalce.

Na strani odziva enačbe je bil PHP prvotno razvit kot
jezik predloge in omogoča mešanje HTML in PHP; katerikoli HTML del
datoteke je takoj prenesen v izhodni medpomnilnik. Moderne aplikacije in
ogrodja se tej praksi izogibajo, saj lahko vodi do težav glede
opuščanja statusne vrstice in/ali glav odziva; stremijo
k združevanju vseh glav in vsebine in jih opustiti, ko je procesiranje
vseh ostalih aplikacij končano. Posebna skrb je potrebna za zagotavljanje,
da poročanje napak in ostale akcije, ki pošiljajo vsebino izhodnemu medpomnilniku,
ne izpraznijo izhodnega medpomnilnika.

## 3. Zakaj se truditi?

Sporočila HTTP so uporabljena v številnih PHP projektih -- tako klientih in
strežnikih. V vsakem primeru opazimo enega ali več od sledečih vzorcev ali
situacij:

1. Projekti uporabljajo PHP-jeve superglobals direktno.
2. Projekti bodo ustvarili implementacije od začetka.
3. Projekti lahko potrebujejo določeno knjižnico klienta/strežnika HTTP, ki ponuja
   implementacije sporočil HTTP.
4. Projekti lahko ustvarijo adapterje za pogoste implementacije sporočil HTTP.

Kot primeri:

1. Skoraj katerakoli aplikacija, ki je pričela z razvojem pred vzponom
   ogrodij, kar vključuje številne priljubljene CMS, forumske sisteme in sisteme nakupovalnih
   košaric, je zgodovinsko uporabljala superglobals.
2. Ogrodja, kot sta Symfony in Zend Framework, definirajo komponente HTTP, ki
   oblikujejo osnovo njihovih nivojev MVC; tudi majhne eno-namenske
   knjižnice, kot je oauth2-server-php, ponujajo in zahtevajo svojo lastno
   implementacijo zahtevka/odziva HTTP. Guzzle, Buzz in druge implementacije klientov
   HTTP vsake ustvarijo tudi svoje lastne implementacije sporočil HTTP.
3. Projekti, kot so Silex, Stack in Drupal 8, imajo močne odvisnosti na
   Symfony-jevo jedro HTTP. Katerikoli SDK zgrajen na Guzzle ima močno zahtevo po
   implementacijah Guzzle-ovega sporočila HTTP.
4. Projekti, kot je Geocoder, ustvarijo odvečne [adapterje iz pogostih
   knjižnic](https://github.com/geocoder-php/Geocoder/tree/6a729c6869f55ad55ae641c74ac9ce7731635e6e/src/Geocoder/HttpAdapter).

Direktna uporaba superglobalov ima mnoge skrbi. Najprej so te
spremenljive, kar naredi možnost za knjižnice in kodo, da spreminja vrednosti
in torej spreminjajo stanje za aplikacijo. Dodatno superglobal-i naredijo testiranje
enot in integracije težko in krhko, kar pelje do degradacije kvalitete
kode.

V trenutnem ekosistemu ogrodij, ki implementirajo abstrakcioj sporočil HTTP,
je neto rezultat, da projekti niso zmožni interoperabilnosti ali
navzkrižnega opraševanja. Da se uporabi koda, ki cilja na eno ogrodje iz
drugega, je prvo potrebno zgraditi nivo mosta med
implementacijami sporočila HTTP. Na strani klienta, če posebne knjižnice
nimajo adapterja, ki ga lahko uporabite, morate premostiti pare zahtevka/odziva,
če želite uporabiti adapter iz druge knjižnice.

Na koncu, ko pride do odzivov strežniške strani, PHP dobi svoj lastni način: katerakoli
vsebina oddana pred klicem `header()` bo rezultirala, da ta klic postane
ne delujoča; odvisno od nastavitev poročanja napak, to lahko pogosto pomeni, da glave
in/ali stanja odzivov niso pravilno poslane. En način kako to obiti je
uporaba PHP-jeve izhodne lastnosti medpomnilnika, vendar gnezdenje izhodnih medpomnilnikov lahko
postane problematično in težko za razhroščevanje. Ogrodja in aplikacije torej
stremijo k izdelavi abstrakcij odziva za agregacijo glav in vsebine, ki
je lahko oddana naenkrat - in te abstrakcije so pogostokrat nekompatibilne.

Torej cilj tega predloga je abstrakcija obeh strani vmesnikov zahtevkov in odzivov
klienta in strežnika, da promovirata interoperabilnost med
projekti. Če projekti implementirajo te vmesnike, je lahko razumni nivo
kompatibilnosti predpostavljen, ko se sprejme kodo iz različnih knjižnic.

Poudariti bi bilo potrebno, da cilj tega predloga ni zastarati
trenutne uporabljene vmesnike v obstoječih knjižnicah PHP. Ta predlog cilja
na interoperabilnost med paketi PHP za namen opisa sporočil
HTTP.

## 4. Obseg

### 4.1 Cilji

* Ponuditi potrebne vmesnike za opis sporočil HTTP.
* Fokusirati se na praktične aplikacije in uporabnost.
* Definirati vmesnike za modeliranje vseh elementov sporočila HTTP in specifikacij
  URI-ja.
* Zagotoviti, da API ne vsiljuje arbitrarnih omejitev na sporočilih HTTP. Na
  primer, nekatera telesa sporočil HTTP so lahko prevelika za shranjevanje v spominu, torej
  moramo to upoštevati.
* Ponuditi uporabne abstrakcije tako ravnanja prihajajočih zahtevkov za
  aplikacije strežniške strani in za pošiljanje odhajajočih zahtevkov v klientih HTTP.

### 4.2 Niso cilji

* Ta predlog ne pričakuje, da bodo vse knjižnice klientov HTTP ali ogrodij strežniške strani
  spremenili svoje vmesnike, da se to upošteva. Je striktno mišljen za
  sodelovanje.
* Medtem ko se zaznavanja vseh, kaj je in kaj ni podrobnost implementacije,
  razlikujejo, ta predlog ne bi smel vsiljevati podrobnosti implementacije. Kot
  RFC-ji 7230, 7231 in 3986 ne vsiljujejo katerihkoli določenih implementacij,
  bo potrebno določeno število izumov za opis vmesnikov sporočil HTTP
  v PHP.

## 5. Odločitve načrta

### Načrt sporočila

`MessageInterface` ponuja dostope za elemente, ki so skupni vsem sporočilom
HTTP, bodisi da so za vse zahtevke ali odzive. Te elementi vključujejo:

- verzijo protokola HTTP (npr., "1.0", "1.1")
- glave HTTP
- telo sporočila HTTP

Bolj specifični vmesniki so uporabljeni za opis zahtevkov in odzivov in bolj
specifično kontekst vsakega (stran klienta proti strani strežnika). Ta deljenja so
delno navdihnjena s strani obstoječe uporabe PHP, vendar tudi s strani ostalih jezikov kot so
Ruby-jev [Rack](https://rack.github.io),
Python-ov [WSGI](https://www.python.org/dev/peps/pep-0333/),
Go-jev [http package](http://golang.org/pkg/net/http/),
Node-ov [http module](http://nodejs.org/api/http.html) itd.

### Zakaj so metode glave na sporočilih namesto v torbi glave?

Samo sporočilo je kontejner za glave (kot tudi druge lastnosti sporočila).
Kako so te predstavljene interno, je to podrobnost implementacije,
vendar enoten dostop do glav je odgovornost sporočila.

### Zakaj so URI-ji predstavljeni kot objekti?

URI-ji so vrednosti z definirano identiteto vrednosti in bi morali biti torej modelirani
kot objekti vrednosti.

Dodatno, URI-ji vsebujejo raznolikost segmentov, ki so lahko dostopani mnogokrat
v danem zahtevku -- in ki bi zahtevali prevajanje URI-ja, da
se jih določi (npr. preko `parse_url()`). Modeliranje URI-jev kot objektov vrednosti omogočajo
prevajanje samo enkrat in poenostavljajo dostop do individualnih segmentov. Tudi
ponujajo priročnost v aplikacijah klienta z omogočanjem uporabnikom, da ustvarijo nove
instance instanc osnovnega URI-ja s samo segmenti, ki se spremenijo (npr.
posodabljanje samo poti).

### Zakaj ima vmesnik zahtevka metode za ukvarjanje z request-target IN sestavlja URI?

RFC 7230 ima podrobnosti zahtevka vrstice saj vsebuje "request-target". Od štirih
oblik request-target, je samo ena URI-skladna z RFC 3986; najbolj
pogosta uporabljena oblika je origin-form, ki predstavlja URI brez
sheme ali informacij avtoritete. Še več odkar so vsi obrazci veljavni za
razloge zahtevkov, mora predlog namestiti vsako.

`RequestInterface` ima torej metode, ki so povezane z request-target. Privzeto
bo uporabil sestavljeni URI za predstavitev origin-form request-target in v
odsotnosti instance URI vrnil niz "/". Druga metoda
`withRequestTarget()` omogoča določanje instance z določenim
request-target, kar omogoča uporabnikom izdelavo zahtevkov, ki uporabljajo eno izmed ostalih
veljavnih request-target oblik.

URI je ohranjen kot diskretni član zahtevka zaradi vrste razlogov.
Za tako kliente in strežnike je vedenje o absolutnem URI-ju običajno
zahtevano. V primeru klientov so URI in še posebno shema in
podrobnosti avtorizacije potrebni, da se naredi dejansko povezavo TCP. Za
aplikacije strežniške strani je pogostokrat polni URI zahtevan, da se potrdi
zahtevek ali usmeritev k ustreznemu krmilniku.

### Zakaj objekti vrednosti?

Predlog modelira sporočila in URI-je kot [objekte vrednosti](http://en.wikipedia.org/wiki/Value_object).

Sporočila so vrednosti, kjer je identiteta agregat vseh delov
sporočila; sprememba kateregakoli aspekta sporočila je v bistvu novo sporočilo.
To je sama opredelitev objekta vrednosti. Praksa, ki spreminja
rezultat v novi instanci je imenovana [nespremenljivost](http://en.wikipedia.org/wiki/Immutable_object)
in je načrtovana lastnost za zagotavljanje integritete date vrednosti.

Predlo tudi prepoznava, da bo večina klientov in aplikacij
strežniške strani morala biti sposobna enostavno posodobiti aspekte sporočila in
kot take ponuditi metode vmesnika, ki bodo ustvarile nove instance sporočila s
posodobitvami. Te imajo v splošnem predpono z besedičenjem `with` ali
`without`.

Objekti vrednosti ponujajo nekaj koristi, ko se modelira sporočila HTTP:

- Spremembe v stanju URI ne morejo spremeniti zahtevka, ki sestavlja instanco URI.
- Spremembe v glavah ne morejo spremeniti sporočila, ki jih sestavlja.

V osnovi modeliranje sporočil HTTP kot objektov vrednosti zagotavlja integriteto
stanja sporočila in preprečuje potrebo po dvosmernih odvisnostih, ki
se lahko pogosto neujemajo ali vodijo do težav razhroščevanja ali performančnosti.

Za kliente HTTP, omogočajo uporabnikom zgraditi osnovni zahtevek s podatki kot
so osnovni URI ali zahtevane glave, brez potrebe po gradnji novega
zahtevka ali ponastavitvi stanja zahtevka za vsako sporočilo, ki ga klient pošlje:

```php
$uri = new Uri('http://api.example.com');
$baseRequest = new Request($uri, null, [
    'Authorization' => 'Bearer ' . $token,
    'Accept'        => 'application/json',
]);;

$request = $baseRequest->withUri($uri->withPath('/user'))->withMethod('GET');
$response = $client->send($request);

// get user id from $response

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

// No need to overwrite headers or body!
$request = $baseRequest->withUri($uri->withPath('/tasks'))->withMethod('GET');
$response = $client->send($request);
```

Na strežniški strani bodo razvijalci morali:

- deserializirati telo sporočila zahtevka.
- dekriptirati piškotke HTTP.
- zapisati odziv.

Te operacije so lahko dosežene z vrednostmi objektov kot takimi s številnimi
koristmi:

- Prvotno stanje zahtevka je lahko shranjeno za pridobivanje s strani kateregakoli uporabnika.
- Privzeto stanje odziva je lahko ustvarjeno s privzetimi glavami in/ali telesom sporočila.

Večina priljubljenih PHP ogrodij ima danes polno spremenljiva sporočila HTTP. Glavne
spremembe potrebne v uporabi pravih objektov vrednosti so:

- Namesto klicanja metod nastavitev ali nastavljanja javnih lastnosti, bodo klicane metode
  mutatorja in dodeljen rezultat.
- Razvijalci morajo obvestiti aplikacijo pri spremembi stanja.

Kot primer v Zend Framework 2, namesto sledečega:

```php
function (MvcEvent $e)
{
    $response = $e->getResponse();
    $response->setHeaderLine('x-foo', 'bar');
}
```

bi se sedaj zapisalo:

```php
function (MvcEvent $e)
{
    $response = $e->getResponse();
    $e->setResponse(
        $response->withHeader('x-foo', 'bar')
    );
}
```

Zgornje kombinira dodelitev in obvestilo v enem klicu.

Ta praksa ima stransko korist izdelave eksplicitnih katerihkoli narejenih sprememb stanja
aplikacije.

### Nove instance napram vračanju $this

Ena opazka narejena na različnih metodah `with*()` je, da lahko zelo verjetno
varno naredijo `return $this;`, če predstavljeni argument ne bo rezultiral k spremembi
vrednosti. Ena smiselnost za to je uspešnost (saj to ne bo rezultiralo h
kloniranju operacije).

Različni vmesniki so bili napisani z besedičenjem, ki indicira, da
nespremenljivost MORA biti ohranjena, vendar samo indicirajo, da mora vrnjena "instanca"
vsebovati novo stanje. Ker se instance, ki predstavljajo enako vrednost,
štejejo za enake, je vračanje `$this` funkcionalno ekvivalentno in torej
dovoljeno.

### Uporaba tokov namesto X

`MessageInterface` uporablja vrednost telesa, ki mora implementirati `StreamInterface`. Ta
načrtovalska odločitev je bila sprejeta, da razvijalci lahko pošljejo in pridobijo (in/ali pridobijo
in pošljejo) sporočila HTTP, ki vsebujejo več podatkov, kot se jih lahko shrani v
spomin, medtem ko še vedno omogoča priročnost interakcije s telesi sporočil
kot niza. Medtem ko PHP ponuja abstrakcijo toka na način ovojev toka,
so lahko viri toka neprikladni za delo: viri toka lahko igrajo samo
vlogo niza z uporabo `stream_get_contents()` ali ročno branje preostalega
niza. Dodajanje obnašanj po meri toku, kot je uporabljen ali zapolnjen
zahteva registracijo filtra toka; vendar, filtri toka so lahko samo dodani
k toku za registracijo filtra s PHP (t.j. da ni na voljo nobenega mehanizma avtomatskega
nalaganja filtra).

Uporaba dobro definiranega vmesnika toka omogoča potencial
fleksibilnih dekoratorjev toka, ki so lahko dodani k zahtevku ali odzivu
pred zagonom, da se omogoči stvari, kot je enkripcija, kompresija, zagotavljanje, da
število prenesenih bajtov odseva število bajtov poročanih v
`Content-Length` glavi odziva itd. Dekoracija tokov je dobro ustaljen
vzorec v skupnostih [Java](http://docs.oracle.com/javase/7/docs/api/java/io/package-tree.html)
in [Node](http://nodejs.org/api/stream.html#stream_class_stream_transform_1),
kar omogoča zelo fleksibilne tokove.

Glavnina API-ja `StreamInterface` je osnovan na
[Python io modulu](http://docs.python.org/3.1/library/io.html), kar omogoča
praktičen in uporaben API. Namesto implementacije zmožnosti
toka, ki uporablja nekaj kot je `WritableStreamInterface` in
`ReadableStreamInterface`, so zmožnosti toka omogočene z metodami
kot sta `isReadable()` in `isWritable()` itd. Ta pristop je uporabljen v Python-u
[C#, C++](http://msdn.microsoft.com/en-us/library/system.io.stream.aspx),
[Ruby](http://www.ruby-doc.org/core-2.0.0/IO.html),
[Node](http://nodejs.org/api/stream.html) in verjetno ostalih.

#### Kaj, če želim ponuditi samo datoteko?

V nekaterih primerih boste morda želeli vrniti datoteko iz datotečnega sistema. Običajni
način, da to naredite v PHP je eden izmed sledečih:

```php
readfile($filename);

stream_copy_to_stream(fopen($filename, 'r'), fopen('php://output', 'w'));
```

Bodite pozorni, da ima zgornje opuščeno pošiljanje ustreznih glav `Content-Type` in
`Content-Lenght`; razvijalec bi moral poslati te pred
klicem zgornje kode.

Ekvivalentna uporaba sporočil HTTP bi bila uporaba implementacije `StreamInterface`,
ki sprejema ime datoteke in/ali vir toka ter ponuja
to instanci odziva. Celoten primer, vključno z nastavitvijo ustreznih
glav:

```php
// where Stream is a concrete StreamInterface:
$stream   = new Stream($filename);
$finfo    = new finfo(FILEINFO_MIME);
$response = $response
    ->withHeader('Content-Type', $finfo->file($filename))
    ->withHeader('Content-Length', (string) filesize($filename))
    ->withBody($stream);
```

Pošiljanje tega odziva bo poslalo datoteko klientu.

#### Kaj, če želim direktno pošiljati izhod?

Direktno pošiljanje izhoda (npr. preko `echo`, `printf` ali pisanje v
tok `php://output`) je v splošnem priporočljivo samo kot optimizacija uspešnosti
ali kot se pošilja večji skupek podatkov. Če je potrebno to narediti in še vedno želite
delati s paradigmo sporočila HTTP, bi bil en pristop uporabiti
implementacijo `StreamInterface` na osnovi povratnega klica, kot je [v tem
primeru](https://github.com/phly/psr7examples#direct-output). Ovijte katerokoli kodo,
ki pošilja izhod direktno v povratni klic, pošljite to k ustrezni
implementaciji `StreamInterface` in ga ponudite telesu sporočila:

```php
$output = new CallbackStream(function () use ($request) {
    printf("The requested URI was: %s<br>\n", $request->getUri());
    return '';
});
return (new Response())
    ->withHeader('Content-Type', 'text/html')
    ->withBody($output);
```

#### Kaj, če želim uporabiti iterator za vsebino?

Ruby-jeva implementacija Rack uporablja pristop na osnovi iteratorja za telesa sporočila
odziva strežniške strani. To je lahko emulirano z uporabo paradigme sporočila HTTP preko
iteratorja, ki podpira pristor `StreamInterface` kot je [podrobno opisano
v repozitoriju psr7examples](https://github.com/phly/psr7examples#iterators-and-generators).

### Zakaj so tokovi spremenljivi?

API `StreamInterface` vključuje metode, kot je `write()`, ki lahko
spremeni vsebino sporočila -- kar direktno nasprotuje imetju nespremenljivih
sporočil.

Težava, ki nastane, je zaradi dejstva, da je vmesnik namenjen
ovitju toka PHP ali podobnega. Operacija pisanje bo zato proxy za pisanje
v tok. Tudi če naredimo `StreamInterface` nespremenljiv, ko je enkrat tok
posodobljen, bo katerakoli instanca, ki ovija ta tok, tudi posodobljena --
kar naredi nespremenljivost nemogočo za vsiljanje.

Naše priporočilo je, da implementacije uporabljajo samo bralne tokove za
zahtevke strežniške strani in odzive klientne strani.

### Razlog za ServerRequestInterface

`RequestInterface` in `ResponseInterface` imata v osnovi 1:1
korelacij s sporočili zahtevka in odziva opisanih v
[RFC 7230](http://www.ietf.org/rfc/rfc7230.txt). Ponujajo vmesnike za
implementacijo objektov vrednosti, ki ustrezajo določenim tipom sporočil HTTP,
ki jih modelirajo.

Za aplikacije strežniške strani so drugi premisleki za
prihajajoče zahtevke:

- Dostop do parametrov strežnika (potencialno pridobljeni iz zahtevka, vendar tudi
  potencialno rezultat strežniške nastavitve in splošno predstavljeni
  preko `$_SERVER` superglobal-a; te so del PHP Server API-ja (SAPI)).
- Dostop do argumentov niza poizvedbe (običajno vdelani v PHP preko
  `$_GET` superglobal-a).
- Dostop do prevedenega telesa (to so deserializirani podatki iz prihajajočega zahtevka
  telesa; v PHP je to običajno rezultat POST zahtevkov z uporabo
  `application/x-www-form-urlencoded` tipa vsebine in vdelanega v
  `$_POST` superglobal-a vendar ne-POST, podatki, ki niso vkodirani v obrazec, bi lahko bili
  polje ali objekt).
- Dostop do naloženih datotek (vdelane v PHP preko `$_FILES` superglobal-a).
- Dostop do vrednosti piškotkov (vdelane v PHP preko `$_COOKIE` superglobal-a).
- Dostop do atributov pridobljenih iz zahtevka (običajno, vendar ne omejeno na
  tista ujemanja poti URL-ja).

Enoten dostop do teh parametrov poveča rentabilnost interoperabilnosti
med ogrodji in knjižnicami, saj lahko predpostavljajo, da če zahtevek
implementira `ServerRequestInterface`, lahko dobijo te vrednosti. Tudi
rešuje probleme znotraj samega jezika PHP:

- Do 5.6.0, `php://input` je bil enkrat bralen; kot tak, izdelava večih
  instanc zahtevka iz večih ogrodij/knjižnic bi lahko vodila
  do nekonsistentnega stanja, saj prvi dostop `php://input` be bil edini
  za pridobitev podatkov.
- Testiranje enot proti superglobalom (npr., `$_GET`, `$_FILES` itd.) je
  težko in običajno krhko. Njihovo zaobjemanje znotraj
  implementacije `ServerRequestInterface` poenostavlja premisleke testiranj.

### Zakaj "parsed body" v ServerRequestInterface?

Argumenti so bili izdelani za uporabo terminologije "BodyParams" in zahteva se, da je
vrednost polje s sledečimi razlogi:

- Konsistentnost z drugimi dostopi parametrov strežniške strani.
- `$_POST` je polje in v 80% primerov uporabe bi ciljali na ta superglobal.
- En tip naredi močno povezavo in poenostavljeno uporabo.

Glavni argument je, da če so parametri telesa polje, imajo razvijalci
predvidljiv dostop do vrednosti:

```php
$foo = isset($request->getBodyParams()['foo'])
    ? $request->getBodyParams()['foo']
    : null;
```

Argument za uporabo "parsed body" je bil izdelan s preučitvijo domene. Sporočilo
telesa lahko vsebuje dobesedno karkoli. Medtem ko tradicionalne spletne aplikacije uporabljajo
obrazce in pošiljajo podatke s POST, je to primer uporabe, ki je hitro
izpodbijan v trenutnih trendih spletnega razvoja, kar so pogosto API centrične in
zato uporabljajo alternativne metode zahtevka (poseben PUT in PATCH), kot tudi
vsebina, ki ni enkodirana v obrazcu (posebej JSON ali XML), ki je _lahko_ prisiljena na polja
v mnogih primerih uporabe, vendar v mnogih primerih tudi _ne more bit_ ali _ne bi smela biti_.

Če siljenje predstavitve lastnosti prevedenega telesa, da je samo polje,
razvijalci potem potrebujejo deljeno konvencijo o tem, kam dati rezultat
prevedenega telesa. To lahko vključuje:

- Poseben ključ pod parametri telesa, kot je `__parsed__`.
- Posebno imenovan atribut, kot je `__body__`.

Končni rezultat je, da mora razvijalec sedaj pogledati na več lokacij:

```php
$data = $request->getBodyParams();
if (isset($data['__parsed__']) && is_object($data['__parsed__'])) {
    $data = $data['__parsed__'];
}

// or:
$data = $request->getBodyParams();
if ($request->hasAttribute('__body__')) {
    $data = $request->getAttribute('__body__');
}
```

Predstavljena rešitev je uporaba terminologije "ParsedBody", ki implicira na to,
da so vrednosti rezultati prevedenega telesa sporočila. To tudi pomeni, da
_bo_ vrnjena vrednost dvoumna; vendar ker je to atribut
domene, je tudi to pričakovano. Kot taka bo uporaba postala:

```php
$data = $request->getParsedBody();
if (! $data instanceof \stdClass) {
    // raise an exception!
}
// otherwise, we have what we expected
```

Ta pristop odstranjuje omejitve siljenja polja na račun
dvoumnosti vrnjene vrednosti. Če upoštevamo, da druge predlagane rešitve —
potiskajo prevedene podatke v poseben ključ parametra telesa ali v atribut —
to tudi trpi za dvoumnostjo, predlagana rešitev je enostavnejša, saj ne
zahteva dodatkov k specifikaciji vmesnika. Ultimativno, dvoumnost
omogoča zahtevano fleksibilnost, ko se predstavlja rezultate prevajanja
telesa.

### Zakaj funkcionalnost za pridobivanje "base path" ni vključena?

Mnoga ogrodja ponujajo zmožnost dobit "base path", običajno mišljena
pot do in vključno s prednjim krmilnikom. Kot primer, če je
aplikacija servirana na `http://example.com/b2b/index.php` in je trenutni URI
uporabljen za zahtevek `http://example.com/b2b/index.php/customer/register`, bo
funkcionalnost za pridobivanje osnovne poti vrnila `b2b/index.php`. Ta vrednost
je lahko nato uporabljena s strani usmerjevalnikov za čiščenje tega segmenta poti pred poskusom
ujemanja.

Ta vrednost je pogosto nato tudi uporabljena za generiranje URI-ja znotraj aplikacij;
parametri bodo podani usmerjevalniku, ki bo generiral pot in
ji dodal predpono z osnovno potjo, da vrne polno-kvalificirani URI. Ostala orodja,
za reševanje poti relativno glede na osnovno pot za namen generiranja URI-ja za
povezovanje virov, kot so statična sredstva.

Pri pregledu večih različnih implementacij smo opazili sledeče:

- Logika za določanje osnovne poti se na veliko spreminja med implementacijami.
  Kot primer, primerjajmo [logiko v ZF2](https://github.com/zendframework/zf2/blob/release-2.3.7/library/Zend/Http/PhpEnvironment/Request.php#L477-L575)
  z [logiko v Symfony 2](https://github.com/symfony/symfony/blob/2.7/src/Symfony/Component/HttpFoundation/Request.php#L1858-L1877).
- Večina implementacij izgleda, da omogočajo ročno injiciranje osnovne poti
  usmerjevalniku in/ali kakršnekoli olajšave uporabljene za generiranje URI-ja.
- Primarno primer uporabe — usmerjanje in generiranje URI-ja — sta običajno edini
  uporabi funkcionalnosti; razvijalci obiačjno ne potrebujejo vedeti
  koncepta osnovne poti, saj drugi objekti poskrbijo za to podrobnost namesto njih.
  Kot primer:
  - Usmerjevalnik bo počistil osnovno pot za vas med usmerjanjem; ne potrebujete
    podajati spremenjene poti usmerjevalniku.
  - Pomočniki pogleda, filtri predlog itd. so običajno injicirani z osnovno potjo
    pred invokacijo. Včasih je to narejeno ročno, vendar bolj pogost je
    rezultat ožičenja ogrodja.
- Vsi viri potrebni za izračun osnovne poti *so že v
  instanci `RequestInterface`*, preko parametrov server in instance URI.

Naša naravnanost je, da je zaznavanje osnovne poti specifično za ogrodje in/ali aplikacijo
in rezultati zaznave so lahko enostavno injicirani v objekte, ki
jo potrebujejo in/ali izračunani, kot je potrebno z uporabo funkcij koristnosti in/ali razredov iz
same instance `RequestInterface`.

### Zakaj getUploadedFiles() vrne objekte namesto polj?

`getUploadedFiles()` vrne drevo instanc `Psr\Http\Message\UploadedFileInterface`.
To je urejeno primarno za poenostavitev specifikacije: namesto,
da se zahteva odstavke implementacije specifikacije za polje, določimo
vmesnik.

Dodatno, podatki `UploadedFileInterface` je normaliziran za delo v
obeh okoljih SAPI in ne-SAPI. To omogoča izdelavo procesov, da prevedejo
telo sporočila ročno in določijo kontekste za tokove brez pisanja
v datotečni sistem, medtem ko še vedno omogočajo ustrezno upravljanje nalaganj datotek v SAPI
okoljih.

### Kaj pa "posebne" vrednosti glave?

Številne vrednosti glave vsebujejo unikatno predstavitev zahtev, ki lahko
predstavlja probleme tako za obdelavo kot za generacijo; posebej piškotki
in glava `Accept`.

Ta predlog ne ponuja kakršnihkoli posebnih tretmajev katerihkoli tipov glav.
Osnovni `MessageInterface` ponuja metode za pridobivanje glav in nastavitev ter
vse vrednosti glavo so na koncu vrednosti nizov.

Razvijalci so spodbujeni, da napištejo udobne knjižnice za interakcijo s
temi vrednostmi glav, bodisi za namene obdelave ali generiranja. Uporabniki lahko
nato uporabijo te knjižnice, ko potrebujejo delati s temi vrednostmi.
Primeri te prakse že obstajajo v knjižnicah kot je
[willdurand/Negotiation](https://github.com/willdurand/Negotiation) in
[aura/accept](https://github.com/pmjones/Aura.Accept). Dokler ima objekt
funkcionalnost za vlogo vrednosti nizu, so te objekti lahko
uporabljeni za zapolnitev glav sporočila HTTP.

## 6. Ljudje

### 6.1 Urednik(i)

* Matthew Weier O'Phinney

### 6.2 Sponzorji

* Paul M. Jones
* Beau Simensen (coordinator)

### 6.3 Prispevali so

* Michael Dowling
* Larry Garfield
* Evert Pot
* Tobias Schultze
* Bernhard Schussek
* Anton Serdyuk
* Phil Sturgeon
* Chris Wilkinson
