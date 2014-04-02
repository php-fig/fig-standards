Pregled poteka dela PSR
=======================

Ta dokument opisuje potek dela za predlog PSR-ja in njegove objave s strani PHP-FIG.

**Opomba:** Skozi ta članek, ko vidite "PSR-N", se "N" sklicuje na katerokoli številko, ki je bila
dodeljena PSR-ju v vprašanju.

## 1. Vloge

**Urednik:** Urednik PSR-ja je aktivno vključen in upravlja in sledi PSR-ju, ko se ga piše.
Predlog lahko nima več kot dva urednika sočasno in en urednik je v prednosti. Urednik
je odgovoren za upravljanje in razvoj PSR; za predstavljanje PSR-ja in razprave na
PHP-FIG e-poštnem seznamu; za koordinacijo ostalih, ki prispevajo; in za delo s koordinatorjem,
da vidi PSR tekom procesa pregledovanja. Za urednika ni obvezno, da je član glasovanja
PHP-FIG. Če urednik predloga manjka za več kot 60 dni brez obvestila potem
se lahko sponzorji strinjajo o novem uredniku. Za urednika se predvideva, da tudi prispeva PSR-ju.

**Sponzor:** Katerikoli od enega ali dveh članov glasovanja, ki sta se strinjala, da sponzorirata predlagani PSR.
Vsak PSR mora imeti dva sponzorja. Sponzor lahko ni urednik, vendar lahko drugače prispeva
na običajen način PSR-ju. Sponzor lahko odstopi, da postane urednik za PSR z objavo
sporočila e-poštnem seznamu. V tem primeru mora biti najden novi zamenjani sponzor, da se PSR
nadaljuje. Glasovanje bi moralo biti na poti in posneti sponzor za te PSR objekte na osnovi,
da ni aktiven ali ni veljaven sponzor, BI MORAL biti ta ugovor narejen na e-poštnem seznamu
in glasovanje za ta PSR BO takoj preklicano dokler ni podan zamenjani
sponzor. Predlog ni nikoli napredek, razen če nista dva sponzorja, ki aktivno
sponzorirata predlagani PSR. Vsak sponzor mora potrditi svojo sponzorstvo PSR-ja preko individualne
e-pošte na e-poštni seznam in PSR se ne šteje za sponzorja dokler niso te e-pošte dostavljene.

Sponzor lahko ni urednik ali napisan na seznamu ljudi, ki prispevajo, vendar seveda sponzorja nič ne ustavlja,
da ne prispeva. Sponzor lahko odstopi, da postane urednik ali nekdo, ki prispeva za PSR
z oddanim sporočilom na e-poštni seznam. V tem primeru mora biti najden novi sponzor. Če bi moralo biti glasovanje
v teku s sponzorjem, za katerega se ne smatra, da je aktivno napisan v meta dokumentu, potem
bi morali ugovarjati na e-poštnem seznamu. Glas bo potem neveljaven dokler ni določen novi
sponzor.

> Zahteva po dveh sponzorjih namesto samo enem preprečuje posameznemu sponzorju, da dela pomembne
> odločitve sam.

**Koordinator:** Eden izmed dveh zahtevanih sponzorjev je koordinator in to mora biti določeno med
sponzorji pred tem. Koordinator je odgovoren za proces glasovanja. Zabeleži začetne in
končne datume, število članov glasovanja na začetku glasovanja in potrebno število za sklepčnost.
Odpošilja opomnike, če misli, da je to ustrezno, da vodi glasovanje. Na koncu glasovalnega
obdobja, ujema glasove, opombe, če je bila sklepčnost ustvarjena in če je bila aplikacija
sprejeta.

> **Opomba:** Kopirano iz [Paul M. Jonesove e-pošte](https://groups.google.com/d/msg/php-fig/I0urcaIsEpk/uqQMb4bqlGwJ)

**Kdor prispeva:** Kdorkoli, ki je bistveno prispeval PSR-ju. To lahko vključuje pošiljanje v zahtevku
potegov (pull request) med fazo pred-osnutka ali osnutka, ponudil bistvene in smiselne preglede, bivši uredniki
itd. V primeru sporov, sta urednik in koordinator odgovorna za določanje ali je določen
posameznik kvalificiran kot nekdo, ki prispeva. Pomembna je diskretnost urednika in
sponzorjev. Če se nekdo počuti, da so njegovi prispevki izvedeni brez pripisovanja, bi morali
kontaktirati urednik(e) ali sponzorja in to opustiti kot zadnjo možnost poslati temo na e-poštnem seznamu,
ki to pravi.

## 2. Faze

### 2.1 Pred-osnutek

Cilj faze pred-osnutka je določitev ali je glavnina PHP-FIG zainteresirana v
objavi PSR za predlagani koncept.

Zainteresirane strani lahko razpravljajo o možnih predlogih, kar vključuje možne implementacije, s
katerimikoli sredstvi mislijo, da je ustrezno. To vključuje neformalne razprave na PHP-FIG
e-poštnem seznamu ali IRC kanalu, če ideja ima ali nima prednosti in je znotraj obsega
ciljev PHP-FIG.

Enkrat, ko so se te strani odločite premakniti naprej, morajo izbrati urednika in pripraviti dokument
predloga. Predlog mora biti objavljen kot t.i. "fork" [uradnega PHP-FIG "fig-standards" repozitorija][repo].
Vsebina predloga mora biti postavljena znotraj direktorija `/proposed` z enostavnim imenom datoteke, kot je
"autoload.md". Skupaj s tem dokumentom mora biti meta dokument s pripono "-meta" pred
končnico (npr. "autoload-meta.md"). Uporabljeno mora biti GitHub Markdown oblikovanje za oba dokumenta.
Nobena PSR številka ni določena predlogu na tej točki.

Urednik mora potem locirati dva člana glasovanja, da sponzorirata predlog, in kdor se od njiju strinja, da bo
koordinator. Urednik, sponzorji in obstoječi dodatni uporabniki, ki prispevajo, če katerikoli formirajo delujočo skupino
za predlog.

Za predlog se ne zahteva, da je v celoti razvit na tej točki, čeprav je to dovoljeno. Vključevati
mora vsaj izjavo problema, ki ga rešuje in osnovne informacije na
splošnem pristopu, ki se ga bo uporabilo. Nadaljnje revizije in razširitve so pričakovane med fazo osnutka.

Koordinator mora sprožiti vstopni glas, da pridobi ali člani PHP-FIG so splošno
zainteresirani v objavi PSR-ja za predlagani predmet, tudi če se ne strinjajo s podrobnostmi
predloga. Koordinator mora objaviti glasovanje na e-poštnem seznamu v temi z naslovom
"[VOTE][Entrance] Title of the proposal". Glasovanje se mora držati [glasovalnega protokola][voting].

Če gre glasovanje skozi, predlog uradno vstopi v fazo osnutka. Predlog dobi PSR številko
povečano od najvišje številke PSR-ja, ki je šel skozi vstopno glasovanje, ne glede na status
tega PSR-ja. Seznam PSR-jev bo vdrževan na wiki strani [indeks PHP standardnih priporočil][wikiindex]
[uradnega PHP-FIG "fig-standards" repozitorija][repo], kjer bo vpis PSR vzdrževan s strani
koordinatorja.

Delujoča skupina lahko nadaljuje delo na predlogu med celotnim obdobjem glasovanja.

### 2.2 Osnutek

Cilj faze osnutka je razprava in poliranje predloga PSR do točke, da je lahko
smatran za pregled s strani glasovalnih članov PHP-FIG.

V fazi osnutka uredniki in morebitni uporabniki, ki prispevajo lahko naredijo katerekoli spremembe, kjer vidijo, da ustrezajo preko zahtevkov potegov,
komentarjev na GitHub-u, temah e-poštnega seznama, IRC-u in podobnih orodjih. Spremembe tu niso omejene na kakršnakoli striktna
pravila in fundamentalni prepisi so možni, če so podprti s strani urednikov. Alternativni pristopi so lahko
predlagani in predebatirani kadarkoli. Če sta urednik in koordinator prepričana, da je alternativni predlog
bolji od originalnega predloga, potem lahko alternativni zamenja originalnega. Če je alternativen zgrajen
nad originalnim, potem bodo uredniki originalnega predloga in nove alternative napisani na seznamu kot
uporabniki, ki so prispevali. Drugače bi morali biti uredniki alternativnega predloga biti napisani na tem seznamu.

Vso znanje pridobljeno med fazo osnutka, kot so možni alternativni pristopi, njihove implementacije, prednosti
in slabosti itd kot tudi razlogi za izbiro predlaganega pristopa morajo biti povzeti v meta
dokumentu. Razlog tega pravila je preprečiti kroženje razprav ali alternativnih predlogov iz
ponovnega prikaza enkrat, ko je bilo na tem odločeno.

Ko se urednik in sponzorji strinjajo, da je predlog pripravljen in da je meta dokument cilj in
dokončan, koordinator lahko spodbudi predlog v fazo pregleda. Spodbuda mora biti objavljena v
temi na e-poštnem seznamu z naslovom "[REVIEW] PSR-N: Title of the proposal". Na tej točki
mora biti predlog združen v "master" vejo [uradnega PHP-FIG "fig-standards" repozitorija][repo].

> Na tej točki uredniki prenesejo pooblastitev nad predlogom k sponzorjem. Namen tega je [preprečitev
> urednikov iz gradnje sprememb](https://groups.google.com/d/msg/php-fig/qHOrincccWk/HrjpQMAW4AsJ)
> da se ostali PHP-FIG člani strinjajo.
>
> Če uredniki še niso pripravljeni prenesti pooblaščenosti, bi morali nadaljevati na delu predloga in
> meta dokumenta dokler niso prepričani, da to naredijo.

### 2.3 Pregled

Cilj faze pregleda je vključiti glavnino članov PHP-FIG, da se seznanijo s
predlogom in odločijo ali je pripravljen za sprejetje glasovanja. V tej fazi je koordinator
odgovoren za katerekoli odločitve, da prestavi predlog naprej ali nazaj.

Cilj tudi *ni nujno*, da se vsak član PHP-FIG strinja z izbranim pristopom
predloga. Cilj pa vseeno *je*, da se vsi člani PHP-FIG strinjajo na popolnosti ali objektivnosti
meta dokumenta.

> Posamezni člani PHP-FIG ne bi smeli preprečiti objave PSR-ja.

Med pregledom, spremembe v obeh predlogih in meta dokumentu so omejene na besede, tipkarske napake, pojasnila
itd. Sponzorji bi morali uporabiti njihovo svojo presojo za kontrolo obsega teh sprememb in morajo blokirati
karkoli se začuti, da je fundamentalna sprememba. Sponzorji morajo narediti spremembe, da se glavnina
članov PHP-FIG z njimi strinja, tudi če se osebno ne strinjajo.

> Sponzorji ne smejo blokirati razvoja predloga.

V tej fazi je glavnina sprememb v meta dokumentu striktno prepovedana. Če so alternativni pristopi
odkriti, da še niso napisani na seznamu v meta dokumentu, mora koordinator preklicati pregled z
objavo teme z naslovom "[CANCEL REVIEW] PSR-N: Title of the proposal" na e-poštnem seznamu, razen če
se je glasovanje sprejetja že začelo. Vendar sponzorji lahko izberejo, da prekličejo glasovanje (z objavo
teme na e-poštnem seznamu) in pregled tudi po tem, če se strinjajo, da je to potrebno. Razlog
tega pravila je dati PHP-FIG članom možnost, da premislijo *vse* znane alternative med
fazo pregleda.

Razen, če je predlog ponovno premaknjen v fazo osnutka, mora ostati v fazi pregleda za vsaj dva tedna
preden se skliče glasovanje sprejetja. To da vsakemu članu PHP-FIG dovolj časa, da se seznani
z njim in vpliva na predlog preden se skliče končno glasovanje.

Ko se uredniki in sponzorji strinjajo, da je predlog pripravljen, da postane PSR, je sklicano glasovanje
sprejetja. Koordinator mora objaviti temo na e-poštnem seznamu z naslovom "[VOTE][Accept] PSR-N:
Title of the proposal", da naznani glasovanje. Glasovanje se mora držati [the voting protocol][voting].

### 2.4 Sprejetje

Če gre glasovanje sprejetja skozi, potem predlog uradno postane sprejeti PSR. Sam predlog
je premaknjen iz `/proposed` v `/accepted` s strani člana PHP-FIG z GitHub dostopom in dobi predpono s
svojo PSR številko, kot je "PSR-3-logger-interface.md". Komentarji morajo biti odstranjeni iz tega dokumenta, vendar
kopija komentiranega predloga mora biti ohranjena v `/accepted/meta`, ki vsebuje pripono "-commented" (npr.
"PSR-3-logger-interface-commented.md"). Komentirana verzija je lahko uporabljena, da razlaga pravila
PSR-ja v primeru dvoma.

> Razlog imeti tako komentirani PSR in meta dokument:
>
> Meta dokument ponuja perspektivo visokega nivoja, zakaj je bil pristop
> izbran in kateri ostali pristopi obstajajo.
>
> Komentarji v PSR v nasprotju, ponujajo dodatne informacije o
> določenih pravilih v PSR ali razlagajo namen pravila v enostavnih besedah
> (kot so dokumentacijskih bloki in izvorna koda). Komentarji so največkrat uporabni med fazama osnutka in
> pregleda. Z njihovimi dodatnimi informacijami, ostali ljudje, ki berejo predlog
> lahko sodijo bolj enostavno ali se v osnovi ne strinjajo s pravilom ali
> se strinjajo, vendar urednik je lahko samo slučajno formuliral pravilo slabo.

Meta dokument predloga mora biti tudi prestavljen v `/accepted/meta` in imeti predpono z PSR številko,
na primer "PSR-3-logger-interface-meta.md".

## 3. Meta dokument

Razlog meta dokumenta je ponuditi visoki nivo perspektive predloga za glasovalce
in jim dati objektivne informacije o tako izbranem pristopu kot tudi o katerihkoli alternativnih pristopih,
da naredi informirano odločitev.

### 3.1 Povzetek

Povzeta razlog in veliko sliko predloga, verjetno z nekaj enostavnimi primeri, kako si
uporabniki, ki prispevajo, zamišljajo implementacijo PSR-ja, da bo uporabljen v praksi.

### 3.2 Zakaj se truditi?

Argument zakaj bi morala biti predlagana tema sploh določena v PSR. Moral bi vključevati seznam
pozitivnih in negativnih posledic izdaje tega PSR-ja. Razlog te sekcije je prepričati
glasovalce, da sprejmejo predlog kot osnutek med vstopnim glasovanjem.

### 3.3 Obseg

Seznam tako ciljev kot ne-ciljev, ki jih bi PSR moral doseči. Cilji/ne-cilji bi morali biti specifični
in merljivi.

**Slabo:** Narediti dnevnik enostavnejši.

**Boljše:** Ponuditi interoperabilen vmesnik dnevnika.

### 3.4 Pristopi

Opisuje odločitve oblike, ki so bile narejene v predlogu in *zakaj*, so bile izbrane. Bolj pomembno,
ta sekcija mora objektivno navesti tako pozitibne in negativne posledice teh odločitev. Če je možno,
povezave na individualne, relevatne objave na e-poštnem seznamu, IRC dnevnike ali podobne bi morale biti vključene.

Tudi seznami vseh znanih alternativnih pristopov za PSR predlog. Za vsakega of njih, bi moral dokument opisovati
objektivni seznam prednosti in slabosti ter razlog, zakaj ta pristop ni smatran za dovolj dobrega. Tudi bi moral
vključevati povezave do zahtevkov potegov, individualnih objav na e-poštnem seznamu, IRC dnevnikov ali podobnih, če so na voljo.

### 3.5 Ljudje

Imena ljudi, ki so vkjučeni v izdelavo PSR predloga, priimki razporejeni po abecednem
redu. Dokument bi moral razlikovati med sledečimi skupinami:

* Uredniki
* Sponzorji (navaja, kateri od njih je bil koordinator)
* Tisti, ki so prispevali (kot je opisano v sekciji 1)

Če se nekdo smatra, da je uporabnik, ki prispeva, vendar ni naveden tu, mora kontaktirati
urednike in sponzorje vključno z nekim dokazilom o njihovem prispevanju. Če je dokazilo veljavno, mora
uporabnik, ki je prispeval, biti dodan na ta seznam s strani enega od urednikov ali sponzorjev.

### 3.6 Popravek

Popravek je lahko uporabljen, da doda pojasnitev na spornih točkah, ki nastanejo po oblikovanju dokumentov.
To je omejeno na ne-gradnjo, razlage združljivosti za nazaj in ne sme vključevati novih pravil.

Popravek je lahko dodan samo k meta dokumentu. Za dodajanje novega popravka meta dokumentu, je potrebno glasovati
na e-poštnem seznamu in to glasovanje se mora držati [protokola glasovanja][voting].

### 3.7 Predloga

To je primer predloge, ki je lahko uporabljen za gradnjo meta dokumenta.

    PSR-N Meta Document
    ===================

    1. Summary
    ----------

    The purpose of X is to bla bla bla. More description than might go into the
    summary, with potential prose and a little history might be helpful.

    2. Why Bother?
    --------------

    Specifying X will help libraries to share their mechanisms for bla bla...

    Pros:

    * Frameworks will use a common algorithm

    Cons:

    * Most of the frameworks don't use this algorithm yet

    3. Scope
    --------

    ## 3.1 Goals

    * Autoload namespaced classes
    * Support an implementation capable of loading 1000 classes within 10ms

    ## 3.2 Non-Goals

    * Support PEAR naming conventions

    4. Approaches
    -------------

    ### 4.1 Chosen Approach

    We have decided to build it this way, because we have noticed it to be common practice withing member
    projects to do X, Y and Z.

    Pros:

    * Simple solution
    * Easy to implement in practice

    Cons:

    * Not very efficient
    * Cannot be extended

    ### 4.2 Alternative: Trent Reznor's Foo Proposal

    The idea of this approach is to bla bla bla. Contrary to the chosen approach, we'd do X and not Y etc.

    We decided against this approach because X and Y.

    Pros:

    * ...

    Cons:

    * ...

    ### 4.3 Alternative: Kanye West's Bar Proposal

    This approach differs from the others in that it bla bla.

    Unfortunately the editor disappeared mid-way and no-one else took over the proposal.

    Pros:

    * ...

    Cons:

    * ...

    5. People
    ---------

    ### 5.1 Editor(s)

    * John Smith

    ### 5.2 Sponsors

    * Jimmy Cash
    * Barbra Streisand (Coordinator)

    ### 5.3 Contributors

    * Trent Reznor
    * Jimmie Rodgers
    * Kanye West

    6. Votes
    --------

    * **Entrance Vote: ** http://groups.google.com...
    * **Acceptance Vote:** http://groups.google.com...

    7. Relevant Links
    -----------------

    _**Note:** Order descending chronologically._

    * [Formative IRC Conversation Gist]
    * [Mailing list thread poll to decide if Y should do Z]
    * [IRC Conversation Gist where everyone decided to rewrite things]
    * [Relevant Poll of existing method names in voting projects for new interface]

    8. Errata
    ---------

    1. _[08/23/2013]_ This is an example of a non-binding errata rule that was originally missed 
    in the formation of the document. It can include clarification on wording, explanations, etc
    but it cannot create new rules.

[repo]: https://github.com/php-fig/fig-standards/tree/master
[wikiindex]: https://github.com/php-fig/fig-standards/wiki/Index-of-PHP-Standard-Recommendations
[voting]: https://github.com/php-fig/fig-standards/blob/master/bylaws/001-voting-protocol.md
