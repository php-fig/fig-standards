# Avtomatski nalagalnik

Ključne besede "MORA", "NE SME", "ZAHTEVANO", "BI", "NE BI", "BI MORALO",
"NE BI SMELO", "PRIPOROČLJIVO", "LAHKO" in "OPCIJSKO" v tem dokumentu se
interpretira, kot je opisano v [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Pregled

Ta PSR opisuje specifikacijo za [avtomatsko nalaganje][autoloading] razrede iz poti
datotek. Je polno interoperabilen in je lahko uporabljen kot dodatek h katerim koli ostalim
specifikacijam avtomatskega nalagalnika, vključno s [PSR-0][]. Ta PSR tudi opisuje, kam
dati datoteke, ki bodo avtomatsko naložene glede na specifikacijo.


## 2. Specifikacija

1. Izraz "razred" se sklicuje na razrede, vmesnike, lastnosti - traits in ostale podobne
   strukture.

2. Polno kvalificirano ime razreda ima sledečo obliko:

        \<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>

    1. Polno kvalificirano ime razreda MORA imeti ime imenskega prostora najvišjega nivoja,
       znano tudi kot "ime izdelovalca" oz. "vendor namespace".

    2. Polno kvalificirano ime razreda ima LAHKO eno ali več imen pod-imenskih
       prostorov.

    3. Polno kvalificirano ime razreda MORA imeti zaključno ime razreda.

    4. Podčrtaji nimajo posebnega pomena v kateremkoli delu celotno
       kvalificiranega imena razreda.

    5. Znaki abecede v polno kvalificiranem imenu razreda so LAHKO katerakoli
       kombinacija malih in velikih črk.

    6. Vsa imena razredov MORAJO biti sklicana v stilu ločevanja malih in velikih črk.

3. Ko se nalaga datoteko, ki ustreza polno kvalificiranem imenu razreda ...

    1. Sosednje serije enega ali več vodilnih imen imenskega prostora in pod-imenskega prostora,
       kar ne vključuje vodilnega ločila imenskih prostorov v polno kvalificiranem
       imenu razreda (t.i. "predpona imenskega prostora"), ustreza vsaj enemu
       "osnovnemu direktoriju".

    2. Sosednja imena pod-imenskih prostorov za "predpono imenskega prostora"
       ustreza pod-direktoriju znotraj "osnovnega direktorija", kjer
       ločila imenskega prostora predstavljajo ločila direktorijev. Ime pod-direktorija
       se MORA ujemati z ločevanjem velikih in malih črk imen pod-imenskih prostorov.

    3. Zaključno ime razreda ustreza imenu datoteke s končnico `.php`.
       Ime datoteke se MORA ujemati z imenom zaključnega razreda, ki ločuje velike in male črke.

4. Implementacije avtomatskega nalagalnika NE SMEJO vreči izjem, NE SMEJO dvigniti napak
   katerega koli nivoja in NE BI SMELE vrniti vrednosti.


## 3. Primeri

Tabela spodaj prikazuje ustrezne poti datotek za dano polno kvalificirano
ime razreda, predpono imenskega prostora in osnovni direktorij.

| Polno kvalificirano ime razreda | Predpona imenskega prostora | Osnovni direktorij       | Rezultat poti datoteke
| ------------------------------- |-----------------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer    | Acme\Log\Writer             | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status       | Aura\Web                    | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request           | Symfony\Core                | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                       | Zend                        | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

Na primer, za implementacije avtomatskih nalagalnikov, ki se skladajo s specifikacijo,
prosimo, glejte [primer datoteke][]. Primeri implementacij NE SMEJO biti obravnavani
kot del specifikacije in se LAHKO spremenijo kadarkoli.

[autoloading]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[primer datoteke]: https://github.com/php-fig/fig-standards/blob/master/accepted/sl/PSR-4-autoloader-examples.md
