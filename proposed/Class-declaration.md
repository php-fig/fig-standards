# Coding Style Guide

This guide extends and expands on [PSR-2][], the coding style guide.

The intent of PSR-2 was to introduce a coding standard that makes it easier for
authors from different projects to read code, however, it leaves some ambiguity on how 
class methods and properties are defined.  The style defined here is intended 
to create a unifying template for class declarations that will be easily recognized 
by all developers 

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

## 1 Class Declaration 

- Class MUST follow [PSR-2][] section 4 Classes, Properties, Methods.

### 1.1 Properties

- It is RECOMMENDED when declaring static properties the order follows private then protected
- Order of property Declaration MUST be as follows:

    1. Class constants
    2. Static properties 
    3. Public properties  
    4. Protected properties 
    5. Private properties

### 1.2 Methods

- Magic methods MUST follow property declaration 
- Static methods MUST follow magic methods and follow Private Protected Public order
- Final methods MUST follow static methods
- Interface methods MUST be declared at the end of the class declration before Abstract Methods 
- Abstract methods MUST be declared at the end of the class declration


### 1.3 Setters and Getters

- Setters and Getters MUST NOT have private visibility
- Getters and Setters SHOULD be declared in serial or grouped with each but MUST NOT be both 

## 2 Examples:

### 2.1 Good Example:
```php
<?php
class OrderExample
{
    const VERSION = '1.4';

    private static $privateFoo;

    protected static $protectedFoo;

    public static $publicFoo;

    private $privateBar;

    protected $protectedBar;

    public $publicBar;
    
    public function __construct()
    {
        $this->privateBar = false;
    }
    
    public function __set(
}

```

### 2.2 Grouped Setters Example:
```php
<?php
class GroupedExample 
{
    protected $foo;

    protected $bar;

    public function setFoo($value)
    {
        $this->foo = $value;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function setBar($value)
    {
        $this->bar = $value;
    }

    public function getBar()
    {
        return $this->bar;
    }
}
```

### 2.3 Serial Example:
```php
<?php
class SerialExample 
{
    protected $foo;

    protected $bar;

    public function setFoo($value)
    {
        $this->foo = $value;
    }

    public function setBar($value)
    {
        $this->bar = $value;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function getBar()
    {
        return $this->bar;
    }
}
```

### 2.4 Bad Example (Mixing Grouped and Serial):
```php
<?php
class BadExample 
{
    protected $foo;

    protected $bar;

    protected $baz;

    public function setFoo($value)
    {
        $this->foo = $value;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function setBar($value)
    {
        $this->bar = $value;
    }

    public function setBaz($value)
    {
        $this->bar = $value;
    }

    public function getBar()
    {
        return $this->bar;
    }

    public function getBaz()
    {
        return $this->baz;
    }
}
```