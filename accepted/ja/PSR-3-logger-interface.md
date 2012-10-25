ロガーインターフェイス
================

このドキュメントでは、ロギングライブラリの為の共通インターフェイスについて記述します。

主な目標は、ライブラリで`Psr\Log\LoggerInterface`オブジェクトを受け、シンプルかつ普遍的にログを書き込めるようにすることです。カスタマイズ需要のあるフレームワークやCMSは、それぞれの目的にあったインターフェイスを拡張をすることができますが、本文書との互換性を維持すべきです。これによりサードパーティのライブラリが、アプリケーションログを集約的に書き込めることを保証することになります。

原文書内で記載されている "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY" 及び "OPTIONAL" は、[RFC 2119][]で説明される趣旨で解釈してください。

原文書内で記載されている`implementor`は、ライブラリやフレームワークにおいてログに関する`LoggerInterface`を実装した開発者、と解釈してください。
対して`user`は、ロガー利用者を指します。

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. 仕様 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#1-specification)
-----------------

### 1.1 基本 [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#11-basics)

- `LoggerInterface`は[RFC 5424][]にある8つのレベル（debug, info, notice, warning, error, critical, alert, emergency）毎に8つのメソッドを公開しています。

- 9番目のメソッドとして`log`があります。これは第一引数にログレベルを受け付けます。いずれかのログレベルをもってこのメソッドを呼び出した際には、レベル固有のメソッドを呼び出したときと同じ結果が得られなければなりません。本仕様により定義されていないレベルを指定して呼び出した際には、レベル不明の例外`Psr\Log\InvalidArgumentException`を投げます。サポートの有無が把握できていないカスタムレベルは使用するべきではありません。

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 メッセージ [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message)

- 全てのメソッドはメッセージの文字列、または`__toString()`を伴うオブジェクトを受け付けます。開発者によっては渡されたオブジェクトに特殊な処理を持たせているかもしれませんが、そうでない場合は、文字列にキャストする必要があります。

- メッセージには、連想配列から値を置換するプレースホルダを含めることができます。

  プレースホルダ名は、連想配列のキーに対応していなければなりません。

  プレースホルダ名は、開き括弧`{`と閉じ括弧`}`で区切られていなければなりません。またデリミタとプレースホルダ名の間に空白があってはなりません。

  プレースホルダ名は`A-Z`、`a-z`、`0-9`、アンダースコア`_`及びピリオド`.`といった文字列のみで構成されるべきです。その他の文字を使用することについては、プレースホルダの仕様が今後変更されることを考慮して控えます。

  開発者は変数のエスケープ対応や表示向けにログを変換するためにプレースホルダを使用することができます。利用者は、表示される情報が不明なうちからプレースホルダの値を事前にエスケープするべきではありません。

  以下は、プレースホルダ置換に対する実装例です。

  ```php
  /**
   * Interpolates context values into the message placeholders.
   */
  function interpolate($message, array $context = array())
  {
      // キーを括弧で囲った連想配列に置き換えます
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // メッセージに置換すべき値を差し込み、返します
      return strtr($message, $replace);
  }

  // 括弧で区切られたプレースホルダ名を含んだメッセージ
  $message = "User {username} created";

  // プレースホルダ名とプレースホルダ値の連想配列
  $context = array('username' => 'bolivar');

  // "Username bolivar created"を出力します
  echo interpolate($message, $context);
  ```

### 1.3 コンテキスト [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#13-context)

- 全てのメソッドはコンテキスト情報としての配列を受け入れます。配列にはあらゆる情報を含むことができるため、文字列に限らず保持することを意味します。開発者は可能な限りの寛大さをもって利用者が取り扱うコンテキスト情報について保証しなければなりません。コンテキストで渡された値は、例外をスローしたり、任意のPHPエラーを起こしたり、警告・通知を出してはいけません。

- コンテキスト情報に例外オブジェクト`Exception`が渡された場合は、キーを`'exception'`としなければなりません。例外ロギングは一般的であり、バックエンドがサポートされている場合に例外からスタックトレースを抽出することができます。開発者は`'exception'`キーに含まれている値が実際に例外`Exception`であることを確認する必要があります。

### 1.4 ヘルパークラスとインターフェイス [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#14-helper-classes-and-interfaces)

- `Psr\Log\AbstractLogger`クラスを拡張し、汎用的な`log`メソッドを実装することで、非常に簡単に`LoggerInterface`に対する実装が可能です。8つのメソッドは、メッセージとコンテキストを送ります。

- 同様にして、`Psr\Log\LoggerTrait`を使用するにも`log`メソッドを実装する必要があります。トレイトはインターフェイスを実装することができないので、この場合はさらに`implement LoggerInterface`としなければならないことに注意してください。

- `Psr\Log\NullLogger`はインターフェイスと共に提供されます。まだロガーが渡されていない際に、フォールバックである"black hole"な実装を提供するインターフェースとして使用することができます。コンテキスト情報生成が高コストである場合に、条件付きロギングとしては良いアプローチかもしれません。

- `Psr\Log\LoggerAwareInterface`は`setLogger(LoggerInterface $logger)`メソッドのみを同梱しており、任意のロガーインスタンスを繋ぐことでフレームワークで使用することができます。

- `Psr\Log\LoggerAwareTrait`トレイトは、任意のクラスで相応するインタフェースを実装することで、`$this->logger`にアクセスできます。

- `Psr\Log\LogLevel`クラスは8つのログレベル定数を保持しています。

2. パッケージ [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#2-package)
----------

インタフェースとクラス説明と同様に、関連する例外クラスおよび実装検証用のテストスイートは、[psr/log](https://packagist.org/packages/psr/log)パッケージの一部として提供されます。

3. `Psr\Log\LoggerInterface` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#3-psrlogloggerinterface)
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

4. `Psr\Log\LoggerAwareInterface` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#4-psrlogloggerawareinterface)
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

5. `Psr\Log\LogLevel` [原文](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#5-psrlogloglevel)
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
