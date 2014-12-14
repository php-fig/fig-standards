日志接口
================

本文档为日志类库描述了通用的日志接口。

主要目标是允许类库接收一个`Psr\Log\LoggerInterface`对象并且以一种简单和
通用的方式将日志信息写入到该对象中。 有自定义需要的框架和CMS可以为了他们
的目标扩展该接口，但是应该保持与本文档的兼容性。这样确保了应用程序使用的
第三方的类可以将日志写入到中心化的应用程序日志中。

本文档中的关键字“必须”， “不允许”，“必需”，“将会”，“将不会”，“应该”，“不应该”，
“推荐”，“可以”和“可选”遵循[RFC 2119]中的描述。

单词 `implementor` 在本文档中指的是实现`LoggerInterface`接口的相关日志类库
或者框架。日志的用户我们叫做`user`。

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. 规范
-----------------

### 1.1 基础

- 接口 `LoggerInterface` 暴露出八个方法用于为[RFC 5424][]规范的八个
  日志级别（debug, info, notice, warning, error, critical, alert, emergency)提供日志写入功能。

- 第九个方法，`log`，接收日志级别作为第一个参数。使用日志级别常量
  作为参数调用这个方法必须和直接调用级别特定的方法产生同样的结果。
  使用规范没有定义的日志级别调用这个方法，如果日志实现不知道这个日志
  级别的话，必须抛出一个`Psr\Log\InvalidArgumentException`异常。
  用户不应该在不知道当前实现是否支持该日志级别的情况下使用自定义的日志级别。

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 消息

- 每个方法都接收一个字符串，或者一个包含`__toString()`方法的对象作为消息。
  实现者可以对传递的对象做特殊的处理。如果事实并非如此的话，实现者必须将其
  作为一个字符串处理。

- 消息可以包含占位符，实现者可以将其替换为上下文数组中的值。

  占位符的名称必须对应上下文数组中的键名。

  占位符的名称必须包含在一个单个的`{`开始，`}`结束的分隔符之间。
  在占位符和大括号之间不允许出现任何空格。

  占位符的名称应该仅包含字符`A-Z`, `a-z`,
  `0-9`, 下划线 `_`, 和句点 `.`。 其它字符预留给未来占位符规范的修改使用.

  实现者可以使用占位符实现各种转义策略和翻译日志以供显示。用户不应该预先对占位符的值
  进行转义，因为他们并不知道这些数据将会在哪个上下文中显示。

  下面是一个插入占位符的例子，仅供参考：

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

### 1.3 上下文

- 每一个方法都接受一个数组作为上下文数据。这样是为了能够接受任意不能转换为
  字符串的外来信息。这个数组可以包含任何内容。是限制必须确保尽可能宽容的对待
  这些上下文数据。上下文中一个给定的值不允许抛出异常或者产生任何PHP error，
  warning或者notice。

- 如果在上下文数据中传递了一个`Exception`对象，它必须以`'exception'` 为键名。
  日志记录异常是一个常见的模式，如果后端支持的话，这允许实现者去解析异常调用堆栈信息。
  实现者在使用`'exception'`键下的值作为一个Exception对象的时候必须先验证它是否是
  一个合法的`Exception`对象，因为它可能包含任何值。

### 1.4 助手类和接口

- 类 `Psr\Log\AbstractLogger` 让你可以通过扩展它来更加容易的实现 `LoggerInterface`
  接口的`log`方法。其它八个方法转发消息和上下为到该方法中。

- 同样的，使用`Psr\Log\LoggerTrait`只需要实现通用的`log`方法。注意的是，
  Trait不能够实现接口，在这种情况下，你依然必须实现`LoggerInterface`接口。

- 同接口一起，提供了`Psr\Log\NullLogger`，接口的使用者在没有提供日志功能时，
  可以使用它提供一种“黑洞”实现。然而，如果上下文数据的创建是非常耗费资源的话，
  使用有条件的日志是一个不错的办法。

- 接口 `Psr\Log\LoggerAwareInterface` 值包含了一个 `setLogger(LoggerInterface $logger)`
  方法，框架可以使用它自动加载任意日志实现的实例。

- Trait `Psr\Log\LoggerAwareTrait` 可以在任何类中方便的实现接口相同的方法，
  它使得你可以访问 `$this->logger`。

- 类 `Psr\Log\LogLevel` 包含了八个日志级别的常量。

2. 包
----------

上面描述的接口、类和相关的异常类，和用来验证你的日志实现的测试用例都已作为 [psr/log](https://packagist.org/packages/psr/log) 包的一部分提供。

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
