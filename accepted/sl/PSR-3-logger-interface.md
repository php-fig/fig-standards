Vmesnik dnevnika - Logger Interface
===================================

Ta dokument opisuje skupne vmesnike za knjižnice dnevnika.

Glavni cilj je omogočati knjižnicam, da dobijo `Psr\Log\LoggerInterface`
objekt in pišejo vanj dnevnike na enostaven in univerzalen način. Ogrodja
in CMS-i, ki imajo zahteve po meri LAHKO razširjajo vmesnik za njihove lastne
namene, vendar BI MORALI ostati kompatibilni s tem dokumentom. To zagotavlja,
da uporabe tretje-osebnih knjižnic aplikacije lahko pišejo v
centralen dnevnik aplikacije.

Ključne besede "MORA", "NE SME", "ZAHTEVANO", "SE", "SE NE", "BI",
"NE BI", "PRIPOROČLJIVO", "LAHKO" in "OPCIJSKO" se v tem dokumentu
razlaga kot je opisano v [RFC 2199][].

Besedo `implementator` se v tem dokumentu razlaga kot nekoga, ki
impementira `LoggerInterface` v knjižnico, ki se tiče dnevnika ali ogrodja.
Uporabniki dnevnikov so navedeni kot `uporabnik`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specifikacija
----------------

### 1.1 Osnove

- `LoggerInterface` izpostavlja osem metod za pisanje dnevnikov na osem
  [RFC 5424][] nivojev (debug, info, notice, warning, error, critical, alert,
  emergency).

- Deveta metoda, `log`, sprejema nivo dnevnika kot prvi argument. Klicanje te
  metode z eno izmed konstant nivoja dnevnika MORA imeti enak rezultat kot
  klicanje metoda določenega nivoja. Klicanje te metode z nivojem, ki ni definiran
  v tej implementaciji MORA vreči `Psr\Log\InvalidArgumentException`,
  če implementacija ne pozna nivoja. Uporabniki NE BI SMELI uporabljati
  nivojev po meri brez, da bi zagotovo vedeli, da ga trenutna implementacija podpira.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Sporočilo

- Vsaka metoda sprejema niz kot sporočilo ali objekt z
  metodo `__toString()`. Implementatorji LAHKO imajo posebno ravnanje za poslane
  objekte. Če to ni primer, implementors MORAJO vezati na niz.

- Sporočilo LAHKO vsebuje ograde, ki jih implementatorji LAHKO zamenjajo z
  vrednostmi iz kontekstnega polja.

  Imena ograd MORAJO biti v korespondenci s ključi konteksnega polja.

  Imena ograd MORAJO biti ločena z enim odpirajočim zavitim oklepajem `{` in
  enim zavitim zaklepajem `}`. NE SME BITI kakršnih koli praznih prostorov med
  ločili in imenom ograde.

  Imena ograd MORAJO biti sestavljena samo iz znakov `A-Z`, `a-z`,
  `0-9`, podčrtajev `_` in pike `.`. Uporaba ostalih znakov je
  rezervirana za prihajajoče spremembe specifikacij ograd.

  Implementatorji LAHKO uporabljajo ograde za implementacijo različnih strategij zatekanja
  in prevajanja dnevnikov za prikaz. Uporabniki NE BI SMELI vnaprej zatekati vrednosti
  ograd, saj morda ne vejo v katerem kontekstu bodo podatki prikazani.

  Sledi primer implementacije ogradne interpolacije
  ponujen samo za namene sklicevanja:

  ```php
  /**
   * Interpolates context values into the message placeholders.
   */
  function interpolate($message, array $context = array())
  {
      // build a replacement array with braces around the context keys
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // interpolate replacement values into the message and return
      return strtr($message, $replace);
  }

  // a message with brace-delimited placeholder names
  $message = "User {username} created";

  // a context array of placeholder names => replacement values
  $context = array('username' => 'bolivar');

  // echoes "User bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Kontekst

- Vsaka metoda sprejema polje podatkov konteksta. To je namenjeno držanju katerihkoli
  tujih informacij, ki se ne ujemajo dobro v nizu. Polje lahko
  vključuje karkoli. Implementatorji MORAJO zagotoviti, da obravnavajo podatke konteksta s
  kakorkoli zanesljivosti je možno. Dana vrednost v kontekstu NE SME vreči
  izjeme ali dvigniti katerekoli php napake, opozorila ali obvestila.

- Če je podan objekt `Exception` v kontekst podatkov, MORA biti v
  ključu `'exception'`. Beleženje izjem v dnevnikih je pogosti vzorec in to omogoča
  implementatorjem, da ekstraktirajo snop sledi iz izjeme, ko to
  ozadnje dnevnika omogoča. Implementatorji MORAJO še vedno zagotavljati, da ključ `'exception'`
  je dejansko `Exception` preden se ga uporablja kot takega, saj lahko vključuje
  karkoli.

### 1.4 Pomočniški razredi in vmesniki

- Razred `Psr\Log\AbstractLogger` vam omogoča, da implementirate `LoggerInterface`
  zelo enostavno z razširitvijo in implementacijo generične `log` metode.
  Drugih osem metod mu posreduje sporočilo in kontekst.

- Podbno z uporabo `Psr\Log\LoggerTrait` od vas samo zahteva
  implementacijo generične `log` metode. Bodite pozorni, da odkar lastnosti - traits ne morejo implementirati
  vmesnikov, morate v tem primeru še vedno narediti `implement LoggerInterface`.

- `Psr\Log\NullLogger` je ponujen skupaj z vmesnikom. LAHKO
  ga uporabljajo uporabniki vmesnika, da podajo fall-back "črno luknjo"
  implementacije, če noben dnevnik ni podan njim. Vendar pogojno beleženje dnevnika
  je lahko boljši pristop, če je izdelava podatkov konteksta draga.

- `Psr\Log\LoggerAwareInterface` samo vključuje
  `setLogger(LoggerInterface $logger)` metodo in jo ogrodja lahko uporabljajo za
  avtomatsko povezovanje samovoljne instance z dnevnikom.

- `Psr\Log\LoggerAwareTrait` lastnost je lahko uporabljena za implementacijo ekvivalentnega
  vmesnika enostavno v kateremkoli razredu. Da vam dostop do `$this->logger`.

- `Psr\Log\LogLevel` razred zadržuje konstante za osem nivojev dnevnika.

2. Paket
--------

Vmesniki in razredi opisani in tudi pomembni razredi izjem
in komplet testov za pregledovanje vaše implementacije so ponujeni kot del
paketa [psr/log](https://packagist.org/packages/psr/log).

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Describes a logger instance
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data, the only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * for the full interface specification.
 */
interface LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * Logs with an arbitrary level.
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
 * Describes a logger-aware instance
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object
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
 * Describes log levels
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
