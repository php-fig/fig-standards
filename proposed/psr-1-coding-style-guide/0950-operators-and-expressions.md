Operators and Expressions
=========================

Operators
---------

Operators should have one space on either side.

    <?php
    $a = $b + $c;
    $d = $e . $f;
    $g = $h && $i;

Similarly, `!` operator should have a space between it and the
expression it is negating.

    if (! $expression) {
        // ...
    }

Increment/decrement operators should have no space separation:

    <?php
    $i--;
    ++$k;

Casts should have a space between the type and the variable.

    $foo = (bool) $bar;


Expressions
-----------

Expressions for `if`, `switch`, etc. statements should fit on one line. If the
expression cannot fit on one line, extract the expression into an explaining
variable and use that variable as the expression. For example, the following
long expression ...

    if (($expression1 && $expression2 && veryLongFunctionName())
        || $expression3 || $expression4) {
        // perform some action
    }

... should be rewritten as:

    $set1 = $expression1 && $expression2 && veryLongFunctionName();
    if ($set1 || $expression3 || $expression4) {
        // perform some action
    }

If the set of expressions still cannot fit on one line, or if it would improve
readability, split the expression onto several subsequent lines at the `&&`
and `||` operators. Operators should align with the equals sign so that the
expressions themselves are also aligned.

    $set1 = $expression1
         && $expression2
         && veryLongFunctionName();
    
    $set2 = $expression3
         || $expression4;
    
    if ($set1 || $set2) {
        // perform some action;
    }
    

