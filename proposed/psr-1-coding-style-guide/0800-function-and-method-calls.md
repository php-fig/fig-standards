Function And Method Calls
=========================

A function call looks like the following. Note the placement of parentheses,
commas, and spaces:

    foo($bar, $baz, $dib);

A method call looks identical:

    $object->foo($bar, $baz, $dib);
    
To support readability, parameters in subsequent calls to the same function or
method may be aligned by parameter:

    $object->foo('value1',        'foo',    true);
    $object->foo('another value', 'barbaz', false);
    $object->foo('third',         'dib',    true);

If a function or method call exceeds the line length limit, or if it would
improve readability, split parameters onto several lines. Each line should
have only one parameter and should be indented once. The closing parenthesis
goes on its own line.

    $this->firstObject->secondObject->aFunctionWithAVeryLongName(
        $value1,
        $value2,
        $value3
    );

The same applies to nested function calls and arrays.

    $this->firstObject->secondObject->aFunctionWithAVeryLongName(
        $object->someOtherFunc(
            $another->someEvenOtherFunc(
                'A string',
                [
                    'foo'  => 'bar',
                    'baz' => 'dib',
                ],
                42
            ),
            $support->someEvenOtherFunc()
        ),
        $this->atLast(88)
    );

Using a fluent API may lead to several function calls on the same object in a
row. Split subsequent calls onto separate lines; indent the subsequent lines
to align the "->" arrows.

    $object->foo('value1', 'value2')
           ->bar(42, 88)
           ->baz();

When instantiating a class, if the constructor for that class has no
parameters, omit the `()` from the instantiation.

    $obj1 = new ClassWithConstructorParams($foo, $bar);
    $obj2 = new ClassWithoutAnyConstructorParams;

