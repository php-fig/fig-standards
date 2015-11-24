Zápisníkové rozhranie
=====================

Tento dokument opisuje spoločné rozhraniepre záznamové knižnice.

Hlavným cieľom je dovoliť knižniciam prijímať objekt `Psr\Log\LoggerInterface`
a zapisovať záznamy do neho jednoduchým a univerzálnym spôsobom. Frameworky
a CMS systémy, špeciálne požiadavky MôŽU rozšíriť toto rozhranie pre svoje
vlastné účely, ale MALI BY zostať kompatibilné s týmto dokumentom. Tým sa zaručí,
že knižnice tretích strán, ktoré aplikácia používa, budú schopné zapisovať do
centralizovaného zápisníka.

Kľúčové slová "MUSÍ", "NESMIE", "POTREBNÉ", "SMIE", "NESMIE", "MALO BY",
"NEMALO BY", "ODPORÚČANÉ", "MôŽE", and "NEPOVINNÉ" v tomto dokumente sú vo význame
ako opísané v [RFC 2119].

Slovom `implementátor` v tomto dokumente sa myslí niekto, kto implementuje
`LoggerInterface` do knižnice alebo frameworku pracujúcim so záznamami.
Užívatelia zápisníkov sú označovaný ako `užívatelia`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Špecifikácia
---------------

### 1.1 Základy

- `LoggerInterface` odhaľuje osem metód na zápis záznamov do ôsmych úrovní
  [RFC 5424][] (debug, info, notice, warning, error, critical, alert,
  emergency).

- Deviata metóda, `log`, akceptuje úroveň zaznamenávania ako prvý parameter. 
  Volanie tejto metódy s jednou z úrovní zápisu ako parametrom MUSÍ mať rovnaký 
  výsledok ako volanie samotnej úrovne ako metódy. Volanie tejto metódy 
  bez udania úrovne zaznamenávania definovanej v tejto špecifikácii MUSÍ 
  hodiť výnimku `Psr\Log\InvalidArgumentException`, ak implementácia nepozná
  úroveň. Užívatelia BY NEMALI používať rôzne úrovne zaznamenávania 
  bez znalosti, že ich implementácia podporuje.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Správy

- Každá metóda akceptuje textový retazec ako správu alebo objekt s metódou
  `__toString()`. Implementátor MôŽE nejako špeciálne spracovať podaný objekt.
   V prípade, že implementátor nepotrebuje ďalej spracovať objekt, MUSÍ podať
   textový reťazec.

- Správa MôŽE obsahovať zástupné symboly, ktoré môžu byť implementátorom
  nahradené s hodnotami zo súvisiaceho poľa.

  Mená zástupných symbolov MUSIA korešpondovať s kľúčami zo súvisiaceho poľa.

  Mená zástupných symbolov MUSIA byť oddelené s jednou otvárajúcou hranatou 
  zátvorkou `{` a jednou zatvárajúcou hranatou zátvorkou `}`. Medzi oddelovačmi
  a zástupnými symbolmi NESMIE byť žiadna medzera.

  Mená zástupných symbolov BY MALI používať iba `A-Z`, `a-z`, `0-9`, 
  podtržítko `_`, a bodku `.`. Použitie ostatných znakov je rezervované
  pre budúce možnosti rozšírenia špecifikácie.

  Implementátori MôŽU použit zástupné symboly na implementovanie rôznych 
  escape-ových sekvencií alebo prekladanie logov pre výpis. Užívatelia 
  BY NEMALI pred escape-ovať hodnoty zástupných symbolov, pretože nemôžu
  vedieť, v akom kontexte sa dáta zobrazia.

  Nasleduje príklad implementácie nahradenia zástupnych symbolov ukazujúca
  odporúčaný spôsob:

  ```php
  /**
   * Nahradzuje zástupné symboly v správe kontextovými hodnotami.
   */
  function nahrad($message, array $context = array())
  {
      // postav náhradzujúce pole s hranatými zátvorkami okolo kontextových kľúčov
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // vlož nahradzujúce hodnoty do správy a vráť správu
      return strtr($message, $replace);
  }

  // správa s menom zástupného symbolu v hranatých zátvorkách
  $message = "Užívateľ {username} vytvorený";

  // kontextové pole s menami => hodnotami zástupných symbolov
  $context = array('username' => 'Štefan');

  // vypíše "Užívateľ Štefan vytvorený"
  echo nahrad($message, $context);
  ```

### 1.3 Kontext

- Každá metóda prijíma pole s kontextovým poľom. Toto slúži na uchovanie 
  nejakých vonkajších informácií, ktoré nezapadajú dobre v textovom reťazci.
  Pole môže obsahovať hocičo. Implementátori MUSIA zaistiť čo najvyššiu 
  bezproblémovosť s kontextovými dátami. Dané hodnoty v kontexte NESMÚ
  hodiť výnimku alebo spôsobiť php chybu, upozornenie alebo varovanie.

- Ak je do kontextových dát vložený objekt `Exception`, tak MUSÍ byť
  v kľúči `'exception'`. Zaznamenávanie výnimiek je častý postup a umožňuje
  implementátorovi vyňať z výnimky kompletný cestu zásobníka, ak to backend 
  logu podporuje. Implementátor MUSÍ stále kontrolovať že v kľúči `'exception'`
  je naozaj `Exception` pred tým ako ju použije, pretože SMIE obsahovať
  čokoľvek.

### 1.4 Pomocné triedy a rozhrania

- Trieda `Psr\Log\AbstractLogger` umožnuje implementovať `LoggerInterface`
  jednoducho jej rozšírením a implementovaním spoločnej metódy `log`.
  Zvyšných osem metód posúvajú správu a kontext do nej.

- Podobne, aj `Psr\Log\LoggerTrait` potrebuje aby ste implementovali všeobecnú
  `log` metódu. Všimnite si, že aj keď traits nemôžu implementovať rozhranie,
  v tomto prípade musíte stále implementovať `LoggerInterface`.

- `Psr\Log\NullLogger` je poskytnutá spolu s rozhraním. MôŽE byť použitá
  užívateľmi rozhrania na poskytnutie rezervnej implementácie, pokiaľ im
  nebol daný žiadny záznamník. V každom prípade záznamník vybratý podmienkou 
  môže byť lepší prístup, ak je vytvorenie kontextových dát náročné.

- `Psr\Log\LoggerAwareInterface` obsahuje iba
  `setLogger(LoggerInterface $logger)` metódu a môže byť použitý vo frameworkoch
  na automatické privinutie ľubovoľnej inštancie so záznamíkom.

- `Psr\Log\LoggerAwareTrait` trait môže byť použitý na implementáciu rovnakého 
  rozhrania jednoducho v každej triede. Potom možete k záznamníku pristupovať
  cez `$this->logger`.

- Trieda `Psr\Log\LogLevel` obsahuje konštanty pre osem úrovní záznamníka.

2. Balík
----------

Rozhrania a triedy opísané vyššie ako aj relevantné triedy Výnimiek a testov
na otestovanie implementacie je poskytnutá ako súčat balíka
[psr/log](https://packagist.org/packages/psr/log).

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Opisuje inštanciu záznamníka
 *
 * Správa MUSÍ byť textový reťazec alebo objekty implementujúce __toString().
 *
 * Správa MôŽE obsahovať symbolické znaky vo forme {foo} kde foo
 * bude nahradené hodnotou z kontextovými poľa s kľúčom "foo".
 *
 * Kontextové pole môže obsahovať ľubovolné dáta, jediné čo implementátor
 * môže predpokladať je, že ak inštancia Výnimky je daná kvôli zásobníku
 * volaní metód, tak MUSÍ byť v kľúči zvanom "exception".
 *
 * Pozri https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * pre špecifikáciu celého rozhrania.
 */
interface LoggerInterface
{
    /**
     * System je nepoužívateľný.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * Akcia musí byť vykonaná okamžite.
     *
     * Napríklad: Celý web je dole, databáza je nepristupná, atď. Toto by malo
     * poslať SMS poplach a zobudiť Vás.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Kritické stavy.
     *
     * Príklady: Časť aplikácie nefunkčná, neočakávaná výnimka.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Chyby za pochodu, ktoré nepotrebujú okamžité riešenie, 
     * ale mali by byť zaznamenané a monitorované
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Výnimočne stavy, ktoré ale nie su chyby
     *
     * Príklady: Používanie zastaralých API, zlé používanie API, nežiadúce veci
     * ktoré nie sú nevyhnutne zlé.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Bežné ale významné udalosti.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Zaujímavé udalosti
     *
     * Príklady: Záznam prihlasovania užívateľov, SQL záznamy
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Detailné informácie na odstraňovanie chýb
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * Záznamy s ľubovolnou úrovňou.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array());
}
```

4. `Psr\Log\LoggerAwareInterface`
---------------------------------

```php
<?php

namespace Psr\Log;

/**
 * Opisuje inštanciu vedomú si záznamníka 
 */
interface LoggerAwareInterface
{
    /**
     * Nastaví inšntanciu záznamníka na objekt
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger);
}
```

5. `Psr\Log\LogLevel`
---------------------

```php
<?php

namespace Psr\Log;

/**
 * Popis úrovní zaznamenávania
 */
class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
}
```
