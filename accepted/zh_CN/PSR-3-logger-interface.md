日志接口
================

本文档用来描述日志类库的通用接口。

主要目标是让类库获得一个`Psr\Log\LoggerInterface`对象并且使用一个简单通用的方式来写日志。有自定义需求的框架和CMS`可以`根据情况扩展这个接口，但`应当`保持和该文档的兼容性，这将确保使用第三方库和应用能统一的写应用日志。

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

关键词`实现者`在这个文档被解释为：在日志相关的库和框架实现`LoggerInterface`接口的人。用这些实现的人都被称作`用户`。

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. 规范
-----------------

### 1.1 基础

- `LoggerInterface`暴露八个接口用来记录八个等级(debug, info, notice, warning, error, critical, alert, emergency)的日志。

- 第九个方法是`log`，接受日志等级作为第一个参数。用一个日志等级常量来调用这个方法的结果`必须`和调用具体等级方法的一致。如果具体的实现不知道传入的不按规范的等级来调用这个方法`必须`抛出一个`Psr\Log\InvalidArgumentException`。用户`不应`自定义一个当前不支持的未知等级。

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 消息

- 每个方法都接受字符串，或者有`__toString`方法的对象作为消息。实现者可以对传入的对象有特殊的处理。如果不是，实现者`必须`将它转换成字符串。

- 消息`可以`包含`可以`被上下文数组的数值替换的占位符。

  占位符名字`必须`和上下文数组键名对应。

  占位符名字`必须`使用使用一对花括号为分隔。在占位符和分隔符之间`不能`有任何空格。

  占位符名字`应该`由`A-Z`，`a-z`，`0-9`，下划线`_`和句号`.`。其它的字符作为以后占位符规范的保留。

  实现者可以使用占位符来实现不同的转义和翻译日志成文。用户在不知道上下文数据是什么的时候`不应`提前转义占位符。

  下面提供一个占位符替换的例子，仅作为参考：

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

  // echoes "Username bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 上下文

- 每个方法接受一个数组作为上下文数据，用来存储不适合在字符串中填充的信息。数组可以包括任何东西。实现者`必须`确保他们对上下文数据足够的掌控。在上下文中一个给定值`不可`抛出一个异常，也`不可`产生任何PHP错误，警告或者提醒。

- 如果在上下文中传入了一个`异常`对象，它必须以`exception`作为键名。记录异常轨迹是通用的模式，如果日志底层支持这样也是可以被允许的。实现者在使用它之前`必须`验证`exception`的键值是不是一个`异常`对象，因为它`可以`允许是任何东西。

### 1.4 助手类和接口

- `Psr\Log\AbstractLogger`类让你非常简单的实现和扩展`LoggerInterface`接口以实现通用的`log`方法。其他八个方法将会把消息和上下文转发给它。

- 类似的，使用`Psr\Log\LoggerTrait`只需要你实现通用的`log`方法。记住traits不能实现接口前，你依然需要`implement LoggerInterface`。

- `Psr\Log\NullLogger`是和接口一个提供的。它`可以`为使用接口的用户提供一个后备的“黑洞”。如果上下文数据非常重要，这不失为一个记录日志更好的办法。

- `Psr\Log\LoggerAwareInterface`只有一个`setLogger(LoggerInterface $logger)`方法可以用来随意设置一个日志记录器。

- `Psr\Log\LoggerAwareTrait`trait可以更简单的实现等价于接口。通过它可以访问到`$this->logger`。

- `Psr\Log\LogLevel`类拥有八个等级的常量。

2. 包
----------

作为[psr/log](https://packagist.org/packages/psr/log) 的一部分，提供接口和相关异常类的一些描述以及一些测试单元用来验证你的实现。

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
