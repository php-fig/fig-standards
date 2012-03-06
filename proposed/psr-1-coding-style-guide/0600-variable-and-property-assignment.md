Variable and Property Assignment
================================

Single Assignment
-----------------

Assignment looks like the following.

    $foo = 'value';

To support readability, contiguous assignments may be aligned on the equals sign:

    $short  = fooBar($baz);
    $longer = dibGir($zim);


Multi-Line Assignment
---------------------

Assignments may be split onto several lines when the line length limit is exceeded. The equal sign has to be positioned onto the following line, and indented once.

    $this->longArrayPropertyName[$this->someOtherPropertyValue]
        = $object->getFunctionResult(ClassName::CONSTANT_VALUE);

Similarly, when concatenating strings across multiple lines, align the dot operator with the equals:

    $foo = 'prefix string '
         . $object->getSomeStringResult()
         . 'suffix string';

    $bar .= 'prefix string '
          . $object->getSomeStringResult()
          . 'suffix string';


Ternary Assignment
------------------

Ternary assignments may be split onto subsequent lines when the exceed the line length limit, or when the would be more readable. Align the question mark and colon with the equals sign.

    $foo = ($condition1 && $condition2)
         ? $foo
         : $bar;

    $bar = ($condition3 && $condition4)
         ? $a_very_long_variable_name
         : $bar;


Assignment By Reference
-----------------------

When assigning by reference, the `&` should be attached to the variable, not the operator:

    // incorrect
    $foo =& $bar;
    
    // correct
    $foo = &$bar;


Array Assignment
----------------

Array assignments may be split across subsequent lines; they should be indented once per array, and should be aligned on the `=>` double arrow. The last value in each array should have a trailing comma; this is valid syntax and reduces the chance of syntax violations when adding new elements.

    $an_array = [
        'foo'      => 'bar',
        'subarray' => [
            'baz'  => 'dib',
            'zim'  => 'gir',
        ],
        'irk'      => 'doom',
    ];

