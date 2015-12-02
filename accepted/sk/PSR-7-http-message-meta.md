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

### Prečo objekty hodnôť?

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

- Changes in URI state cannot alter the request composing the URI instance.
- Changes in headers cannot alter the message composing them.

In essence, modeling HTTP messages as value objects ensures the integrity of
the message state, and prevents the need for bi-directional dependencies, which
can often go out-of-sync or lead to debugging or performance issues.

For HTTP clients, they allow consumers to build a base request with data such
as the base URI and required headers, without needing to build a brand new
request or reset request state for each message the client sends:

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

On the server-side, developers will need to:

- Deserialize the request message body.
- Decrypt HTTP cookies.
- Write to the response.

These operations can be accomplished with value objects as well, with a number
of benefits:

- The original request state can be stored for retrieval by any consumer.
- A default response state can be created with default headers and/or message body.

Most popular PHP frameworks have fully mutable HTTP messages today. The main
changes necessary in consuming true value objects are:

- Instead of calling setter methods or setting public properties, mutator
  methods will be called, and the result assigned.
- Developers must notify the application on a change in state.

As an example, in Zend Framework 2, instead of the following:

```php
function (MvcEvent $e)
{
    $response = $e->getResponse();
    $response->setHeaderLine('x-foo', 'bar');
}
```

one would now write:

```php
function (MvcEvent $e)
{
    $response = $e->getResponse();
    $e->setResponse(
        $response->withHeader('x-foo', 'bar')
    );
}
```

The above combines assignment and notification in a single call.

This practice has a side benefit of making explicit any changes to application
state being made.

### New instances vs returning $this

One observation made on the various `with*()` methods is that they can likely
safely `return $this;` if the argument presented will not result in a change in
the value. One rationale for doing so is performance (as this will not result in
a cloning operation).

The various interfaces have been written with verbiage indicating that
immutability MUST be preserved, but only indicate that "an instance" must be
returned containing the new state. Since instances that represent the same value
are considered equal, returning `$this` is functionally equivalent, and thus
allowed.

### Using streams instead of X

`MessageInterface` uses a body value that must implement `StreamableInterface`. This
design decision was made so that developers can send and receive (and/or receive
and send) HTTP messages that contain more data than can practically be stored in
memory while still allowing the convenience of interacting with message bodies
as a string. While PHP provides a stream abstraction by way of stream wrappers,
stream resources can be cumbersome to work with: stream resources can only be
cast to a string using `stream_get_contents()` or manually reading the remainder
of a string. Adding custom behavior to a stream as it is consumed or populated
requires registering a stream filter; however, stream filters can only be added
to a stream after the filter is registered with PHP (i.e., there is no stream
filter autoloading mechanism).

The use of a well- defined stream interface allows for the potential of
flexible stream decorators that can be added to a request or response
pre-flight to enable things like encryption, compression, ensuring that the
number of bytes downloaded reflects the number of bytes reported in the
`Content-Length` of a response, etc. Decorating streams is a well-established
[pattern in the Java](http://docs.oracle.com/javase/7/docs/api/java/io/package-tree.html)
and [Node](http://nodejs.org/api/stream.html#stream_class_stream_transform_1)
communities that allows for very flexible streams.

The majority of the `StreamableInterface` API is based on
[Python's io module](http://docs.python.org/3.1/library/io.html), which provides
a practical and consumable API. Instead of implementing stream
capabilities using something like a `WritableStreamInterface` and
`ReadableStreamInterface`, the capabilities of a stream are provided by methods
like `isReadable()`, `isWritable()`, etc. This approach is used by Python,
[C#, C++](http://msdn.microsoft.com/en-us/library/system.io.stream.aspx),
[Ruby](http://www.ruby-doc.org/core-2.0.0/IO.html),
[Node](http://nodejs.org/api/stream.html), and likely others.

#### What if I just want to return a file?

In some cases, you may want to return a file from the filesystem. The typical
way to do this in PHP is one of the following:

```php
readfile($filename);

stream_copy_to_stream(fopen($filename, 'r'), fopen('php://output', 'w'));
```

Note that the above omits sending appropriate `Content-Type` and
`Content-Length` headers; the developer would need to emit these prior to
calling the above code.

The equivalent using HTTP messages would be to use a `StreamableInterface`
implementation that accepts a filename and/or stream resource, and to provide
this to the response instance. A complete example, including setting appropriate
headers:

```php
// where Stream is a concrete StreamableInterface:
$stream   = new Stream($filename);
$finfo    = new finfo(FILEINFO_MIME);
$response = $response
    ->withHeader('Content-Type', $finfo->file($filename))
    ->withHeader('Content-Length', (string) filesize($filename))
    ->withBody($stream);
```

Emitting this response will send the file to the client.

#### What if I want to directly emit output?

Directly emitting output (e.g. via `echo`, `printf`, or writing to the
`php://output` stream) is generally only advisable as a performance optimization
or when emitting large data sets. If it needs to be done and you still wish
to work in an HTTP message paradigm, one approach would be to use a
callback-based `StreamableInterface` implementation, per [this
example](https://github.com/phly/psr7examples#direct-output). Wrap any code
emitting output directly in a callback, pass that to an appropriate
`StreamableInterface` implementation, and provide it to the message body:

```php
$output = new CallbackStream(function () use ($request) {
    printf("The requested URI was: %s<br>\n", $request->getUri());
    return '';
});
return (new Response())
    ->withHeader('Content-Type', 'text/html')
    ->withBody($output);
```

#### What if I want to use an iterator for content?

Ruby's Rack implementation uses an iterator-based approach for server-side
response message bodies. This can be emulated using an HTTP message paradigm via
an iterator-backed `StreamableInterface` approach, as [detailed in the
psr7examples repository](https://github.com/phly/psr7examples#iterators-and-generators).

### Why are streams mutable?

The `StreamableInterface` API includes methods such as `write()` which can
change the message content -- which directly contradicts having immutable
messages.

The problem that arises is due to the fact that the interface is intended to
wrap a PHP stream or similar. A write operation therefore will proxy to writing
to the stream. Even if we made `StreamableInterface` immutable, once the stream
has been updated, any instance that wraps that stream will also be updated --
making immutability impossible to enforce.

Our recommendation is that implementations use read-only streams for
server-side requests and client-side responses.

### Rationale for ServerRequestInterface

The `RequestInterface` and `ResponseInterface` have essentially 1:1
correlations with the request and response messages described in
[RFC 7230](http://www.ietf.org/rfc/rfc7230.txt). They provide interfaces for
implementing value objects that correspond to the specific HTTP message types
they model.

For server-side applications there are other considerations for
incoming requests:

- Access to server parameters (potentially derived from the request, but also
  potentially the result of server configuration, and generally represented
  via the `$_SERVER` superglobal; these are part of the PHP Server API (SAPI)).
- Access to the query string arguments (usually encapsulated in PHP via the
  `$_GET` superglobal).
- Access to the parsed body (i.e., data deserialized from the incoming request
  body; in PHP, this is typically the result of POST requests using
  `application/x-www-form-urlencoded` content types, and encapsulated in the
  `$_POST` superglobal, but for non-POST, non-form-encoded data, could be
  an array or an object).
- Access to uploaded files (encapsulated in PHP via the `$_FILES` superglobal).
- Access to cookie values (encapsulated in PHP via the `$_COOKIE` superglobal).
- Access to attributes derived from the request (usually, but not limited to,
  those matched against the URL path).

Uniform access to these parameters increases the viability of interoperability
between frameworks and libraries, as they can now assume that if a request
implements `ServerRequestInterface`, they can get at these values. It also
solves problems within the PHP language itself:

- Until 5.6.0, `php://input` was read-once; as such, instantiating multiple
  request instances from multiple frameworks/libraries could lead to
  inconsistent state, as the first to access `php://input` would be the only
  one to receive the data.
- Unit testing against superglobals (e.g., `$_GET`, `$_FILES`, etc.) is
  difficult and typically brittle. Encapsulating them inside the
  `ServerRequestInterface` implementation eases testing considerations.

### Why "parsed body" in the ServerRequestInterface?

Arguments were made to use the terminology "BodyParams", and require the value
to be an array, with the following rationale:

- Consistency with other server-side parameter access.
- `$_POST` is an array, and the 80% use case would target that superglobal.
- A single type makes for a strong contract, simplifying usage.

The main argument is that if the body parameters are an array, developers have
predictable access to values:

```php
$foo = isset($request->getBodyParams()['foo'])
    ? $request->getBodyParams()['foo']
    : null;
```

The argument for using "parsed body" was made by examining the domain. A message
body can contain literally anything. While traditional web applications use
forms and submit data using POST, this is a use case that is quickly being
challenged in current web development trends, which are often API centric, and
thus use alternate request methods (notably PUT and PATCH), as well as
non-form-encoded content (generally JSON or XML) that _can_ be coerced to arrays
in many cases, but in many cases also _cannot_ or _should not_.

If forcing the property representing the parsed body to be only an array,
developers then need a shared convention about where to put the results of
parsing the body. These might include:

- A special key under the body parameters, such as `__parsed__`.
- A special named attribute, such as `__body__`.

The end result is that a developer now has to look in multiple locations:

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

The solution presented is to use the terminology "ParsedBody", which implies
that the values are the results of parsing the message body. This also means
that the return value _will_ be ambiguous; however, because this is an attribute
of the domain, this is also expected. As such, usage will become:

```php
$data = $request->getParsedBody();
if (! $data instanceof \stdClass) {
    // raise an exception!
}
// otherwise, we have what we expected
```

This approach removes the limitations of forcing an array, at the expense of
ambiguity of return value. Considering that the other suggested solutions —
pushing the parsed data into a special body parameter key or into an attribute —
also suffer from ambiguity, the proposed solution is simpler as it does not
require additions to the interface specification. Ultimately, the ambiguity
enables the flexibility required when representing the results of parsing the
body.

### Why is no functionality included for retrieving the "base path"?

Many frameworks provide the ability to get the "base path," usually considered
the path up to and including the front controller. As an example, if the
application is served at `http://example.com/b2b/index.php`, and the current URI
used to request it is `http://example.com/b2b/index.php/customer/register`, the
functionality to retrieve the base path would return `/b2b/index.php`. This value
can then be used by routers to strip that path segment prior to attempting a
match.

This value is often also then used for URI generation within applications;
parameters will be passed to the router, which will generate the path, and
prefix it with the base path in order to return a fully-qualified URI. Other
tools — typically view helpers, template filters, or template functions — are
used to resolve a path relative to the base path in order to generate a URI for
linking to resources such as static assets.

On examination of several different implementations, we noticed the following:

- The logic for determining the base path varies widely between implementations.
  As an example, compare the [logic in ZF2](https://github.com/zendframework/zf2/blob/release-2.3.7/library/Zend/Http/PhpEnvironment/Request.php#L477-L575)
  to the [logic in Symfony 2](https://github.com/symfony/symfony/blob/2.7/src/Symfony/Component/HttpFoundation/Request.php#L1858-L1877).
- Most implementations appear to allow manual injection of a base path to the
  router and/or any facilities used for URI generation.
- The primary use cases — routing and URI generation — typically are the only
  consumers of the functionality; developers usually do not need to be aware
  of the base path concept as other objects take care of that detail for them.
  As examples:
  - A router will strip off the base path for you during routing; you do not
    need to pass the modified path to the router.
  - View helpers, template filters, etc. typically are injected with a base path
    prior to invocation. Sometimes this is manually done, though more often it
    is the result of framework wiring.
- All sources necessary for calculating the base path *are already in the
  `RequestInterface` instance*, via server parameters and the URI instance.

Our stance is that base path detection is framework and/or application
specific, and the results of detection can be easily injected into objects that
need it, and/or calculated as needed using utility functions and/or classes from
the `RequestInterface` instance itself.

### Why does getUploadedFiles() return objects instead of arrays?

`getUploadedFiles()` returns a tree of `Psr\Http\Message\UploadedFileInterface`
instances. This is done primarily to simplify specification: instead of
requiring paragraphs of implementation specification for an array, we specify an
interface.

Additionally, the data in an `UploadedFileInterface` is normalized to work in
both SAPI and non-SAPI environments. This allows creation of processes to parse
the message body manually and assign contents to streams without first writing
to the filesystem, while still allowing proper handling of file uploads in SAPI
environments.

### What about "special" header values?

A number of header values contain unique representation requirements which can
pose problems both for consumption as well as generation; in particular, cookies
and the `Accept` header.

This proposal does not provide any special treatment of any header types. The
base `MessageInterface` provides methods for header retrieval and setting, and
all header values are, in the end, string values.

Developers are encouraged to write commodity libraries for interacting with
these header values, either for the purposes of parsing or generation. Users may
then consume these libraries when needing to interact with those values.
Examples of this practice already exist in libraries such as
[willdurand/Negotiation](https://github.com/willdurand/Negotiation) and
[aura/accept](https://github.com/pmjones/Aura.Accept). So long as the object
has functionality for casting the value to a string, these objects can be
used to populate the headers of an HTTP message.

## 6. People

### 6.1 Editor(s)

* Matthew Weier O'Phinney

### 6.2 Sponsors

* Paul M. Jones
* Beau Simensen (coordinator)

### 6.3 Contributors

* Michael Dowling
* Larry Garfield
* Evert Pot
* Tobias Schultze
* Bernhard Schussek
* Anton Serdyuk
* Phil Sturgeon
* Chris Wilkinson
