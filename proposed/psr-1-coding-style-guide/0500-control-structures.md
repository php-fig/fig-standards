Control Structures
==================

The general style rules for control structures are as follows:

- one space after the control structure keyword
- no space after the opening parenthesis
- no space before the closing parenthesis
- one space between the closing parenthesis and the opening brace
- structure body indented once
- closing brace on its own line, outdented once from the body

Always use braces to enclose the body of each structure. This standardizes how
the structures look, and reduces the likelihood of introducing errors as new
lines get added to the body.


`if`, `elseif`, `else`
----------------------

An `if` structure looks like the following. Note the placement of parentheses,
spaces, and braces, and that `else` and `elseif` are on the same line as the closing brace from the earlier body.

    <?php
    if ($expr1) {
        // if body
    } elseif ($expr2) {
        // elseif body
    } else {
        // else body;
    }

> N.b.: There appears to be no consistency between projects, and often not
> even within the same project, on the use of `else if` vs `elseif`. This
> guide encourages the use of `elseif` so that all control structures look
> like single words.


`switch`, `case`
----------------    

A `switch` structure looks like the following. Note the placement of
parentheses, spaces, and braces; the indent levels for `case` and `break`; and
the presence of a `// no break` comment when a break is intentionally omitted.

    <?php
    switch ($expr) {
        case 1:
            echo 'First case';
        break;
        case 2:
            echo 'Second case';
            // no break
        default:
            echo 'Default case';
        break;
    }


`while`, `do while`
-------------------

A `while` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

    <?php
    while ($expr) {
        // structure body
    }

Similarly, a `do while` statement looks like the following. Note the placement
of parentheses, spaces, and braces.

    <?php
    do {
        // structure body;
    } while ($expr);


`for`
-----

A `for` statement looks like the following. Note the placement of parentheses,
spaces, and braces.

    <?php
    for ($i = 0; $i < 10; $i++) {
        // for body
    }
    

`foreach`
---------
    
A `foreach` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

    <?php
    foreach ($iterable as $key => $value) {
        // foreach body
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

