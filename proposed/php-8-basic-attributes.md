PHP 8 basic attributes
=======================

TODO: summary

#### `@@Final`

`final` is a very debatable thing in the community because it makes no possible
to use tools for mocking finalized classes and proxying
as both use inheritance feature.

On other hand, leaving class open for inheritance often lead to misusages
and frameworks/libraries become limited to changed for keeping BC.

Some frameworks like Symfony introduced `@Final` (aka "soft `final`) annotation to make sure that with `DebugClassLoader`
developer will get a warning.
Some people suggests the same 
IDE like PhpStorm can show error when one is trying to extend `@@Final` class.

```php
@@Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)
final class Final
{
    public function __construct(public string $annotation = null)
    {
    }
}
```

Usage:

```php
@@Final('It will become final in 42.0') 
class User
{
    // ...
}
```

#### `@@Immutable`

Value objects and other immutable structures have to provide
getters for each property, but in most cases it creates
[visual debt (noise)](#rbg-color-example).

```php
namespace Psr\Attributes;

@@Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)
final class Immutable
{
}
```

##### Mutable objects inside

There is an edge case: some immutable at first glance classes
may have internal references on mutable objects like notorious `\DateTime`.

It's possible to add special flag like `allowNonPublicMutable`,
but it makes no sense to properties.

So far it's suggested to allow mutable objects if they are `private`/`protected`
and are not mutated in any case. However, there is a risk that passed through 
constructor instance of `\DateTime` will be modified outside. 

##### Example

<a name="rbg-color-example"></a>

```php
final class RbgColor
{
    private int $red;
    private int $green;
    private int $blue;

    public function __construct(int $r, int $g, int $b) {
        assertThat($r >= 0 && $r <= 255)
            ->that($g >= 0 && $r <= 255)
            ->that($b >= 0 && $b <= 255);

        $this->red = $r;
        $this->green = $g;
        $this->blue = $b;
    }

    public function getRed(): int
    {
        return $this->red;
    }

    public function getGreen(): int
    {
        return $this->green;
    }

    public function getBlue(): int
    {
        return $this->blue;
    }
}
```

... code above becomes 

```
final class RbgColor
{
    public int $red;
    public int $green;
    public int $blue;

    public function __construct(int $r, int $g, int $b) {
        assertThat($r >= 0 && $r <= 255)
            ->that($g >= 0 && $r <= 255)
            ->that($b >= 0 && $b <= 255);

        $this->red = $r;
        $this->green = $g;
        $this->blue = $b;
    }
}
```

#### `@@ReadOnly`

The idea is to have public read-only properties is very old.
But for some reasons PHP has very poor ways (`__get` + phpdoc) 
for achieving similar behaviour for far. 

```php
namespace Psr\Attributes;

@@Attribute(Attribute::TARGET_PROPERTY)
final class ReadOnly
{
    public function __construct(public bool $strict = false)
    {
    }
}
```

Usage:

```php
@@ReadOnly(strict: true)
public UserId $userId;
```

`strict` just means where you can modify value inside of the class or not.

#### `@@Internal`

The [phpdoc](https://docs.phpdoc.org/latest/references/phpdoc/tags/internal.html) `@internal` has very vague meaning:

> The @internal tag is used to denote that associated Structural Elements are elements internal to this application or library

But what is "application"? "Library"? PhpStorm just use strikethrough when you use this tag ouside of namespace where the class/function was defined.

I propose something like:

```php
namespace Psr\Attributes;

@@Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::TARGET_ATTRIBUTE)
final class Internal
{
    public function __construct(public string $namespace = null)
    {
    }
}
```

Usage:


```php
namespace Acme\Foo\Parser\Utils;

@@Internal('Acme\\Foo\\Parser\\') // It's a shame that PHP has no reference like Acme\Foo\Parser::namespace
final class PrimitiveLexer
{
    // ...
}
```

Using `PrimitiveLexer` within `Acme\Foo\Parser` namespace is OK, but when you use it outside of that namespace, IDE should warning user.
