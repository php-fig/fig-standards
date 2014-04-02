Predlogi sprememb
=================

Pri sledenju pravil [akta poteka dela][bylaw], ko je enkrat PSR bil "sprejet", se pomen PSR-ja
ne more spremeniti, združljivost za nazaj mora ostati 100% in kakršna koli zmeda, ki nastane iz
originalnega pomena, je lahko razjasnjena preko popravka.

Pravila za popravke so pokrita v [aktu poteka dela][bylaw] in samo dovoljujejo združjive ne za nazaj
razjasnitve, da se dodajo k meta dokumentu. Včasih bodo spremembe potrebne v samem PSR
dokumentu in ta dokument obriše te primere.

## 1. Opustitev in zamenjava

Če se za PSR ugotovi, da potrebuje vsebinske posodobitve ali popravke, ni več zmožen razjasniti zmedo,
zato mora biti PSR zamenjan in slediti poteku dela določenega v [aktu poteka dela][bylaw].

Originalni PSR je lahko na določeni točki obdobja opuščen in novi PSR postane priporočljiv
dokument. Opuščanje in priporočene spremembe morajo biti narejene z glasovanjem glede na pravila
[glasovalnega protokola], z naslovom kot je "[VOTE] Deprecate PSR-X", kjer
bi moral nadomestni PSR biti specificiran kot priporočilo.

Ko je enkrat glasovanje opravljeno za opustitev PSR-ja in se ga nadomesti z drugim PSR, mora opuščeni PSR
biti označen kot tak v originalnem dokumentu in povezava bi morala biti postavljena v telo.

Na primer, sledeči Markdown je postavljen na sam vrh pomembne standardne datoteke v
uradnem PHP-FIG GitHub repozitoriju `fig-standards`.

> **Deprecated** - As of 2014-12-30 PSR-0 has been marked as deprecated. [PSR-4] is now recommended
as an alternative.
> [PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


## 2. Odvisnosti

Kot se za dokumente pričakuje, da se jih zamenja namesto spremeni, bi se moralo odvisnostim
na ostale PSR-je izogibati, kadarkoli je možno. Na primer, sledeče
ni več dovoljeno:

> - Imenski prostori in razredi MORAJO slediti PSR-0.

Namesto - če se smatra za odvisnost nujno s strani delovne skupine, ki jo je ustvarila - potem je sledeči
primer lahko uporabljen:

> - Namespaces and classes MUST follow an autoloading PSR: [ [PSR-0] ].

Zunanji set oglatih oklepajev označuje "seznam odvisnosti", ki je seznam PSR-jev,
ki štejejo za združljivo odvisnost.

Ko je več PSR-jev dodanih k "seznamu odvisnosti", bi enak primer izgledal takole:

> - Namespaces and classes MUST follow an autoloading PSR: [ [PSR-0], [PSR-4] ].

Nove PSR-je se lahko doda k "seznamu odvisnosti", vendar starih PSR-jev se nikoli ne odstranja, saj bi to polomilo
združljivost za nazaj.

## 3. Sprejemljive spremembe

Drugače kot posodabljanje "seznama odvisnosti", obstajata dva druga potencialno sprejemljiva scenarija predloga sprememb,
ki ne zahtevata njihove lastnega posebnega glasovanja.

### 3.1. Zaznamki

Če so dodani popravki, ki štejejo za dovolj pomembne za kogarkoli, ki začne glasovanje za popravke,
se zaznamke lahko doda v ali blizu kršene vrstice, da bralci vedo pogledati popravke za
več informacij s povezavo, ki vključuje sidro na tisti določeni del popravka.

> - Nekaj zmedenega o tem, kam morajo iti zaviti oklepaji. [cf. [errata](foo-meta.md#errata-1-foo)]

To bo urejeno kot del glasovanja o popravkih in ne na svojem.

### 3.2. Oblikovanje & tipkarske napake

Če je oblikovanje polomljeno zaradi katerega koli razloga, potem spreminjanje oblikovanja ne sme biti smatrano za
spremembo dokumenta. Te so lahko združeni ali poslani brez oklevanja, dokler
ne spremenijo ničesar kateregakoli pomena ali sintakse.

Nekaj trivialnih tipkarskih napak, kot je napačno postavljena vejica imajo subtilen vpliv na pomen. Bodite posebej pozorni, da
ne spremenite združljivosti za nazaj in izdelate glasovanje, če niste prepričani. Razum bo tam pomagal.

Primeri:

1. HTML tabele so trenutno zlomljene na php-fig.org zaradi sintakse, ki je uporabljena.
2. Nekdo je nekaj napačno črkoval in nihče ni tega opazil leto dni.
3. Problemi z GitHub Markdown

[bylaw]: https://github.com/philsturgeon/fig-standards/blob/master/bylaws/004-psr-workflow.md
[glasovalnega protokola]: https://github.com/philsturgeon/fig-standards/blob/master/bylaws/001-voting-protocol.md
