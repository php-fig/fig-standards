Control Structures
==================


`include`, `include_once`, `require`, `require_once`
----------------------------------------------------

The `include` (et al.) structures are keywords, not functions. Do not use
parentheses around the filename.

    <?php
    // incorrect
    include("/path/to/file.php");
    
    // correct
    include "path/to/file.php";

Class files should not use `include` (et al.) to load other classes; let the
autoloader load the classes as needed.


`if`, `elseif`, `else`
----------------------

An `if` statement looks like the following. Note the placement of parentheses,
spaces, and braces:

    <?php
    if ($expression1 || ($expression2 && $expression3)) {
        echo "First case";
    } elseif (! $expression4 && ! $expression5) {
        echo "Second case";
    } else {
        echo "Default case";
    }

Always use braces to enclose the body of the statements. This standardizes
how they look and reduces the likelihood of introducing errors as new lines
get added to the body.

Do not perform variable assignment within `if` or `elseif` expressions. This
reduces the difficulty of determining if the intent was to assign or to check
equivalence. For example, this ...

    <?php
    if ($value = foo()) {
        echo "True";
    }

... should be replaced with this, to clarify the intent:

    <?php
    $value = foo();
    if ($value) {
        echo "True";
    }

    
`switch`, `case`
----------------    

A switch statement looks like the following. Note the placement of
parentheses, spaces, and braces; the indent levels for `case` and `break`
statements; and the presence of a `// no break` comment when a break is
intentionally omitted.

    <?php
    switch ($expression) {
        
        case 1:
            echo "First case";
        break;

        case 2:
            echo "Second case";
            // no break
        
        default:
            echo "Default case";
        break;
        
    }

Do not perform variable assignment within `switch` expressions. This reduces
the difficulty of determining if the intent was to assign or to check
equivalence. For example, this ...

    <?php
    switch ($value = foo()) {
        // ...
    }

... should be replaced with this, to clarify the intent:

    $value = foo();
    switch ($value) {
        // ...
    }


`while`, `do while`
-------------------

A `while` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

    <?php
    while ($expression) {
        echo "Expression is true";
    }

Similarly, a `do while` statement looks like the following. Note the placement
of parentheses, spaces, and braces.

    <?php
    do {
        echo "Expression is true";
    } while ($expression);

Always use braces to enclose the body of the statement. This standardizes how
it looks and reduces the likelihood of introducing errors as new lines get
added to the body.

It is acceptable to perform assignment within `while` expressions.


`for`
-----

A `for` statement looks like the following. Note the placement of parentheses,
spaces, and braces.

    <?php
    for ($i = 0; $i < 10; $i++) {
        echo $i;
    }
    
It is acceptable to perform assignment within `for` expressions.


`foreach`
---------
    
A `foreach` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

    <?php
    foreach ($iterable as $key => $value) {
        echo $key;
    }


`try`, `catch`
--------------

A `try catch` block looks like the following. Note the placement of
parentheses, spaces, and braces.

    <?php
    try {
        // try body
    } catch (FirstExceptionType $e) {
        // catch body
    } catch (OtherExceptionType $e) {
        // catch body
    }

Always use braces to enclose the body of the statements. This standardizes
how they look and reduces the likelihood of introducing errors as new lines
get added to the body.

Interweaving Non-PHP Output
---------------------------

Interweaving PHP and non-PHP using braces is discouraged:

    <?php if ($is_true) { ?>
        direct output
    <?php } ?>

Instead, use the alternative syntax for control structures:

    <?php if ($is_true): ?>
        direct output
    <?php endif; ?>
    
<http://php.net/manual/en/control-structures.alternative-syntax.php>
