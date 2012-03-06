Control Structures
==================

`include`, `include_once`, `require`, `require_once`
----------------------------------------------------

The `include` (et al.) structures are keywords, not functions. Do not use parentheses around the filename.

    <?php
    // incorrect
    include("/path/to/file.php");
    
    // correct
    include "path/to/file.php";

Class files should not use `include` (et al.) to load other classes; let the autoloader load the classes as needed.


`if`, `elseif`, `else`
----------------------

An `if` statement looks like the following. Note the placement of parentheses, spaces, and braces:

    <?php
    if ($condition1 || ($condition2 && $condition3)) {
        echo "First case";
    } elseif (! $condition4 && ! $condition5) {
        echo "Second case";
    } else {
        echo "Default case";
    }

Always use braces to enclose the body of the `if` statement. This standardizes how they look and reduces the likelihood of introducing errors as new lines get added to the body.

Do not perform variable assignment within `if` or `elseif` conditions. This reduces the difficulty of determining if the intent was to assign or to check equivalence.  E.g., this ...

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

A switch statement looks like the following.  Note the placement of parentheses, spaces, and braces; the indent levels for `case` and `break` statements; and the presence of a `// no break` comment when a break is intentionally omitted.

    <?php
    switch ($condition) {
        
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

Do not perform variable assignment within `switch` conditions. This reduces the difficulty of determining if the intent was to assign or to check equivalence.  E.g., this ...

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

A `while` statement looks like the following.  Note the placement of parentheses, spaces, and braces.

    <?php
    while ($condition) {
        echo "Condition is true";
    }

Similarly, a `do while` statement looks like the following.  Note the placement of parentheses, spaces, and braces.

    <?php
    do {
        echo "Condition is true";
    } while ($condition);

Always use braces to enclose the body of the `while` statement. This standardizes how they look and reduces the likelihood of introducing errors as new lines get added to the body.

It it acceptable to perform assignment within `while` conditions.


`for`
-----

A `for` statement looks like the following.  Note the placement of parentheses, spaces, and braces.

    <?php
    for ($i = 0; $i < 10; $i++) {
        echo $i;
    }
    
It is acceptable to perform assignment within `for` conditions.


`foreach`
---------
    
A `foreach` statement looks like the following.  Note the placement of parentheses, spaces, and braces.

    <?php
    foreach ($iterable as $key => $value) {
        echo $key;
    }


Formatting of Conditions
------------------------

Conditon sets for `if`, `switch`, etc. statements should fit on one line.  If the set of conditions cannot fit on one line, extract the conditions into an explaining variable and use that variable as the condition.  For example, the following long condition set ...

    if (($condition1 && $condition2 && very_long_function_name())
        || $condition3 || $condition4) {
        // perform some action
    }

... should be rewritten as:

    $set1 = $condition1 && $condition2 && veryLongFunctionName();
    if ($set1 || $condition3 || $condition4) {
        // perform some action
    }

If the set of conditions still cannot fit on one line, or if it would improve readability, split the condition onto several subsequent lines at the `&&` and `||` operators.  Operators should align with the equals sign so that the conditions themselves are also aligned.

    $set1 = $condition1
         && $condition2
         && veryLongFunctionName();
    
    $set2 = $condition3
         || $condition4;
    
    if ($set1 || $set2) {
        // perform some action;
    }
    
The `!` operator should have a space between it and the condition it is negating.

    if (! $condition) {
        // ...
    }


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
