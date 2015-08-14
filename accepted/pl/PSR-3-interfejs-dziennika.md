Interfejs dziennika
===================

Poniższy dokument opisuje wspólny interfejs dla bibliotek logujących.

Głównym celem jest umożliwienie bibliotekom dostarczenia obiektu implementującego 
`Psr\Log\LoggerInterface` oraz funkcjonalność zapisywania do wspomnianego obiektu 
logów w prosty i uniwersalny sposób. Frameworki i CMSy, które potrzebują własnych 
rozwiązań, MOGĄ rozszerzyć interfejs tak, aby osiągnąć własne cele, nie mniej jednak 
rezultaty ciągle POWINNY być kompatybilne z wymogami zwartymi w tym dokumencie. 
Gwarantuje to, że zewnętrzne biblioteki, których używa aplikacja, będą zapisywać 
logi do scentralizowanego dziennika aplikacji.

Następujące słowa "MUSI", "NIE WOLNO", "WYMAGANE", "POWINNO", "NIE POWINNO", 
"REKOMENDWANE", "MOŻE" oraz "OPCJONALNE" będą interpretowane tak jak opisano to w [RFC 2119][].

Wyrażenie `implementator` w poniższym dokumencie powinno być interpretowane, jako osoba implementująca interfejs `LoggerInterface` w bibliotece zorientowanej na obsługę dzienników lub frameworku. `Użytkownikami` poniżej nazwano osoby korzystające z dzienników.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specyfikacja
---------------

### 1.1 Podstawy

- Interfejs `LoggerInterface` udostępnia osiem metod do zapisywania logów 
zgodnie z ośmioma poziomami zdefiniowanymi w [RFC 5424][] (debug, info, notice, 
warning, error, critical, alert, emergency).

- Dziewiąta metoda – `log`, przyjmuje jako pierwszy argument poziom loga. Wywołanie 
tej metody wraz ze stałą zawierającą poziom loga, MUSI mieć ten sam wynik, co wywołanie 
jeden ze wcześniejszych ośmiu metod, zorientowanej tylko na jeden typ skali 
zdarzenia, które chcemy zapisać. Wywołanie tej metody z poziomem nie zdefiniowanym 
w specyfikacji, MUSI rzucić wyjątek typu `Psr\Log\InvalidArgumentException`, jeśli 
implementacja nie wie nic o przesłanym poziomie. Użytkownicy NIE POWINNI używać 
niestandardowego poziomu bez wiedzy o tym czy na pewno aktualna implementacja wspiera go.

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 Komunikaty

- Każda metoda akceptuje ciąg znaków lub obiekt z metodą `__toString()` jako komunikat. 
Implementator MOŻE posiadać specjalną obsługę dla przekazanych obiektów. Jeśli nie jest to spełnione, 
implementator MUSI wcześniej skonwertować obiekt do postaci ciągu znaków.

- Komunikat MOŻE zawierać zmienne zastępcze, które implementator MOŻE podmienić na 
podstawie wartości z tablicy kontekstu.

  Nazwy zmiennych zastępczych MUSZĄ odpowiadać kluczom w tablicy kontekstu.

  Nazwy zmiennych zastępczych MUSZĄ być otoczone pojedynczymi nawiasami klamrowymi,
  otwierającym `{` i zamykającym `}`. Między nawiasami 
  klamrowymi a nazwą zmiennej zastępczej NIE WOLNO umieszczać znaków spacji.

  Nazwy zmiennych zastępczych POWINNY być utworzone tylko ze znaków `A-Z`, `a-z`, `0-9`, 
  znaku podkreślenia `_` oraz kropki `.`. Użycie innych znaków jest zarezerwowane 
  dla przyszłych modyfikacji.

  Implementatorzy MOGĄ używać zmiennych zastępczych do implementacji różnych 
  strategii escape'owania i translacji logów, które mają zostać wyświetlone. 
  Użytkownicy NIE POWINNI escape'ować wartości zmiennych zastępczych przed przekazaniem 
  ich do metody, ponieważ nigdy nie wiadomo, z jakiej perspektywy dane będą wyświetlane.

  Poniższy przykład demonstruje implementację obsługi zmiennych 
  zastępczych - tylko w ramach prezentacji:

  ```php
  /**
   * Wstawianie danych kontekstowych do zmiennych w komunikacie.
   */
  function interpolate($message, array $context = array())
  {
	  // zbuduj tablicę translacyjną z nawiasami klamrowymi wokół kluczy tablicy z danymi kontekstu
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // podmień zmienne z komunikatu wartościami z tablicy kontekstu
      return strtr($message, $replace);
  }

  // komunikat ze zmiennymi zastępczymi
  $message = "User {username} created";

  // tablica z danymi kontekstu wraz z nazwami zmiennych zastępczych => wartościami zmiennych zastępczych
  $context = array('username' => 'bolivar');

  // wygenerowanie komunikatu "User bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 Kontekst

- Każda metoda akceptuję tablicę z danymi kontekstu. Parametr ten przyjmuje 
dodatkowe, zewnętrzne dane, które nie nadawałyby się do treści ciągu tekstowego 
komunikatu. Tablica może zawierać cokolwiek. Implementatorzy MUSZĄ zapewnić 
obróbkę danych kontekstowych w najmniejszym stopniu, jaki jest możliwy. 
Implementatorowi NIE WOLNO rzucać wyjątków ani zgłaszać wbudowanych w php 
typów raportowania błędów: error, warning lub notice.

- Jeśli obiekt klasy `Exception` jest przekazany w danych kontekstowych, 
MUSI on znaleźć się w kluczu `'exception'` tablicy. Logowanie wyjątków jest 
powszechnym wzorcem i pozwala implemetatorowi wyeksportować stack trace z wyjątku, 
jeśli jego funkcjonalność wspiera takie operacje. Implementator MUSI jednak zweryfikować czy 
klucz 'exception'` jest faktycznie obiektem klasy Exception 
przed użyciem go, ponieważ klucz ten MOŻE przechowywać cokolwiek.

### 1.4 Pomocnicze klasy i interfejsy

- Klasa `Psr\Log\AbstractLogger` pozwala zaimplementować `LoggerInterface` bardzo prosto poprzez rozszerzenie jej i implementację standardowej metody `log`. Pozostałych osiem metod przekazuje 
komunikaty i tablicę z danymi kontekstowymi do wspomnianej metody `log`.

- Podobnie jak w powyższym przypadku, użycie traita `Psr\Log\LoggerTrait` wymaga jedynie 
implementacji metody `log`. Należy zaznaczyć że z racji tego, iż traity nie mogą 
implementować interfejsów, należy zaimplementować dodatkowo `LoggerInterface` w klasie 
używającej traita.

- Klasa `Psr\Log\NullLogger` jest dostarczana razem z interfejsem. MOŻE ona być 
wykorzystywana przez użytkowników interfejsu, jako wsparcie dla implementacji 
`"/dev/null"`, jeśli nie istnieje obiekt dziennika. Jednakże logowanie na bazie 
warunków (`if ($this->logger) { }`) może być lepszą drogą, jeśli tworzenie danych 
kontekstowych jest kosztowne.

- Interfejs `Psr\Log\LoggerAwareInterface` zawiera tylko 
metodę `setLogger(LoggerInterface $logger)` i może być użyty 
przez frameworki do automatycznego tworzenia instancji obiektów z dziennikiem.

- Trait `Psr\Log\LoggerAwareTrait` może być użyty do łatwej implementacji 
równoznacznego interfejsu w jakiejkolwiek klasie. Daje dostęp do `$this->logger`.

- Klasa `Psr\Log\LogLevel` przechowuje stałe z ośmioma poziomami logów.

2. Pakiet
---------

Interfejsy i klasy opisane powyżej oraz odpowiednie klasy wyjątków i zestaw testów weryfikujący poprawność implementacji, zostały udostępnione w pakiecie o nazwie [psr/log](https://packagist.org/packages/psr/log).

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
