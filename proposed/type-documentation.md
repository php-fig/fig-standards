# Type documentation

The following describes the mandatory code documentation.

## Annotations

Every class variable MUST be documented with an `@var` annotation:

    /**
     * @var string
     */
    private $name;

Every method MUST be documented with one `@param` annotation per parameter:

    /**
     * @param string $firstname
     * @param string $lastname
     */
    public function setName($firstname, $lastname)
    {
    }

The return type of every method MUST be documented with an `@return` annotation, unless the return type is `void`, in which case the documentation MAY omit this annotation, or unless the method is a constructor, in which case the documentation MUST omit this annotation.

    /**
     * @return string
     */
    public function getName()
    {
    }

## Acceptable types

The following native types are acceptable in a variable documentation:

- `null`
- `string`
- `int`, `integer`
- `float`, `double`
- `bool`, `boolean`
- `array`
- `resource`
- `callable`
- `object`

Two additional aggregate types can be used:

- `number`: aggregates integer & float
- `mixed`: aggregates all the types

All these types are case-insensitive.

Everything else will be considered a class or interface name. If the variable is documented as a class or interface, it may be documented using the Fully Qualified Class Name starting with the `\` character, or a relative class/interface name that MUST be resolvable with the `namespace` and `use` statements of the page, with the same [rules used by PHP](http://php.net/manual/en/language.namespaces.importing.php).

    /**
     * @var \Fully\Qualified\Class\Name
     */
    private $foo;

    /**
     * @var Relative\Class\Name
     */
    private $bar;

## Mixed types

If a variable can contain several possible types, these MUST be separated with the `|` character:

    /**
     * @var string|null
     */
    private $name;

## Iterable types

In all the following examples, `type` can be any native type, or class/interface name, as mentioned in the *Acceptable types* section.

If a variable is iterable (either an `array`, or an object implementing the `\Traversable` interface), and the type of the iterated elements is known, the variable MAY be documented as such:

    /**
     * @var type[]
     */
    private $elements;

If the variable follows the same description, but is know to be an `array`, the following syntax SHOULD be used instead:

    /**
     * @var array<type>
     */
    private $elements;

If the variable follows the same description, but is known to be an instance of a specific class/interface implementing/extending the `\Traversable` interface, the following syntax SHOULD be used instead:

    /**
     * @var Collection<type>
     */
    private $elements;

In this code, `Collection` is a class implementing the `\Traversable` interface.

## Miscellaneous

The aforementioned rules can be mixed together:

    /**
     * @var int|int[]
     */
    private $foo;

    /**
     * @var string|\My\Collection<string>
     */
    private $bar;

    /**
     * @var array<string|object>
     */
    private $baz;

The iterable types can be nested:

    /**
     * @var \DOMElement[][]
     */
    private $foo;

    /**
     * @var array<array<\DOMElement>>
    private $bar;

    /**
     * @var Collection<Collection<\DOMElement>>
     */
    private $baz;
