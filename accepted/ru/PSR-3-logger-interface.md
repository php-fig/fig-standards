Logger Interface
================

В этом документе описывается общий интерфейс для библиотеки протоколирования.

Главная цель позволить библиотекам получать обьект `Psr\Log\LoggerInterface`
и запись журналов через него простым и универсальным способом. Фреймворки и CMS,
могут расширить интерфейс для своих собственных целей, но он должен оставаться 
совместимым с этим документом. Это гарантирует, что сторонние библиотеки, 
которые использует приложение, могут записывать в централизованных журналах приложения. 

Ключевые слова «НЕОБХОДИМО»/«ДОЛЖНО» («MUST»), «НЕДОПУСТИМО»/«НЕ ДОЛЖНО» («MUST NOT»), «ТРЕБУЕТСЯ»
(«REQUIRED»), «НУЖНО» («SHALL»), «НЕ ПОЗВОЛЯЕТСЯ» («SHALL NOT»), «СЛЕДУЕТ»
(«SHOULD»), «НЕ СЛЕДУЕТ» («SHOULD NOT»), «РЕКОМЕНДУЕТСЯ» («RECOMMENDED»),
«ВОЗМОЖНО» («MAY») и «НЕОБЯЗАТЕЛЬНО» («OPTIONAL»)
в этом документе должны расцениваться так, как описано в [RFC 2119].

Слово «исполнитель» в настоящем документе должно толковаться как реализация «LoggerInterface»
в связанных с журналом библиотеке или платформе.
Пользователи loggers, называются «пользователь».

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt

1. Спецификации
-----------------

### 1.1 Базовая

- `LoggerInterface` предоставляет восемь методов для записи журналов восьми уровней  
[RFC 5424][] (Отладка, информация, уведомление, предупреждение, ошибка, критическое,
тревога, чрезвычайное).

[RFC 5424]: http://tools.ietf.org/html/rfc5424

- Девятый метод, `log»`, принимает уровень журнала в качестве первого аргумента.
Вызов этого метода с одной из констант уровня журнала должен иметь
тот же результат вызова как и метода конкретного уровня. Вызов этого метода с уровнем,
не определенным в спецификации должен выбрасывать исключение `Psr\Log\InvalidArgumentException`, 
если уровень не осуществлен. Пользователи не должны использовать свой уровень не зная наверняка
что данная реализация поддерживает его.

### 1.2 Сообщения

- Каждый метод принимает строку как сообщение, или объект с помощью метода `__toString()`.
Разработчики могут специально обрабатывать переданные обьекты. Если это не так, 
разработчики должны привести его в строку.

- Сообщение может содержать значения, которыми разработчики могут заменить
значения из массива контекста.

Вводимые имена должны соответствовать ключам в массиве контекста.

Имена значений, должны быть с одной открывающей фигурной скобкой ' {' и
друной закрывающей фигурной скобкой '}'. Между разделителями и именами значений не должно быть пробелов.

Имена значений должны состоять только из знаков `A-Z, a-z, 0-9`, подчеркивания `_` и точки `.`.

Использование других символов зарезервировано для будущих модификаций спецификации значений.

Разработчики могут использовать значения для реализации различных экраннирований и переводов журналов для отображения.

Пользователи не должны предварительно экраннировать значения заполнителей, так как они не могут знать,
где будет отображаться контекст данных.

Ниже приведен пример реализации экранирования значений, предусмотренный только для справочных целей:

 ```php
  /**
   * Экранирует значения в контексте сообщения.
   */
  function interpolate($message, array $context = array())
  {
      // Создание и замена массива с фигурными скобками вокруг ключей контекста
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      //Заменить значения в сообщении и вернуть их
      return strtr($message, $replace);
  }

  // Сообщение с заменяемыми именами значений
  $message = "User {username} created";

  // Массив контекста с именами и значениями
  $context = array('username' => 'bolivar');

  // Выводит "User bolivar created"
  echo interpolate($message, $context);
  ```
### 1.3 Контекст

- Каждый метод принимает массив в качестве контекста данных. Это означает что в строке не должно быть
ни какой посторонней информации. Массив может быть пустым. Разработчики должны убедиться что они обрабатывают
контекст данных легко как это возможно. Заданное в контексте значение не должно ни вызывать ни исключения, ни любой php ошибки, предуреждения, уведомления. 
- Если обьект `Exception` передаеться в качестве значения он должен быть с ключом `exception`.
Вход исключения является общим шаблоном, это позволяет разработчикам извлечь трассировки стека из исключения в случаях, когда журнал бэкэнд поддерживает его. Разработчики должны убедиться, что ключ `exception` на самом деле является исключением, прежде чем использовать его в качестве такового, поскольку она может содержать что-нибудь другое.

### 1.4 Вспомогательные классы и интерфейсы

- Класс `Psr\Log\AbstractLogger` позволяет очень легко реализовать `LoggerInterface`, распространив реализации метода `log`. Остальные восемь методов пересылают сообщения и контекст к нему.
- Аналогично использование `Psr\Log\LoggerTrait` для реализации общего журнала. Отметим, что трейты не могут реализовывать интерфейсы, Вы ДЛОЖНЫ реализовать остальные методы `LoggerInterface`.
- `Psr\Log\LoggerAwareInterface` содержит только метод  `setLogger(LoggerInterface $logger)` который может быть задействован в фреймворках для автоматического сбора журналов.
- `Psr\Log\LoggerAwareTrait` может быть использован для реализации эквивалентного интерфейса в любом классе. Он дает доступ к `$this->logger`.
- `Psr\Log\LogLevel` содержит константы восьми уровней журнала.

1. Пакет
-----------------

Интерфейсы и классы, описанные выше а также соответствующие классы исключений
и набор тестов для проверки вашей реализации предоставляется как часть [psr/log](https://packagist.org/packages/psr/log) пакета.

3. `Psr\Log\LoggerInterface`
----------------------------

```php
<?php

namespace Psr\Log;

/**
 * Описывает экземляр журнала
 *
 * Сообщение должно быть строкой или обьектом реализующим метод __toString().
 *
 * Сообщение может содержать заполнители вида : {foo} которые будут заменены
 * ключами из контекста "foo".
 *
 * Массив контекста МОЖЕТ содержать произвольные данные, но если передается 
 * экземпляр Exception для получения трассировки стека, он ДОЛЖЕН  быть помечен ключом "exception".
 *
 * Посмотрите https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * полную спецификацию интерфейса.
 */
interface LoggerInterface
{
    /**
     * Система не может использоваться.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * Действие должно быть принято немедленно.
     *
     * Например: Загрузка сайта, базы данных не доступна. 
     * Нужно отправитьь смс уведомление и разбудить вас.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Критические условия.
     *
     * Например: Компонет приложения не доступен, непредвиденное исключение.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Ошибки выполнения, которые не требуют немедленных действий, но, как правило, должны быть зарегистрированы и 
     * котролироваться
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
