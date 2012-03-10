Control Structures
==================

The general rules for control structures are as follows:

- one space after the control structure keyword
- no space after the opening parenthesis
- no space before the closing parenthesis
- one space between operators and expressions
- one space after the closing parenthesis
- opening brace on the same line as the keyword
- body is indented once
- closing brace on its own line, outdented once from the body


`if`, `elseif`, `else`
----------------------

An `if` statement looks like the following. Note the placement of parentheses,
spaces, and braces:

    <?php
    if ($expression1 || ($expression2 && $expression3)) {
        echo 'First case';
    } elseif (! $expression4 && ! $expression5) {
        echo 'Second case';
    } else {
        echo 'Default case';
    }

N.b.: There appears to be no consistency between projects, and often not even
within the same project, on the use of `else if` vs `elseif`. This guide
encourages the use of `elseif` so that all control structures look like single
words.

Always use braces to enclose the body of the statements. This standardizes
how they look and reduces the likelihood of introducing errors as new lines
get added to the body.

Do not perform variable assignment within `if` or `elseif` expressions. This
reduces the difficulty of determining if the intent was to assign or to check
equivalence. For example, this ...

    <?php
    if ($value = foo()) {
        echo 'True';
    }

... should be replaced with this, to clarify the intent:

    <?php
    $value = foo();
    if ($value) {
        echo 'True';
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
            echo 'First case';
        break;
        case 2:
            echo 'Second case';
            // no break
        default:
            echo 'Default case';
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
        echo 'Expression is true';
    }

Similarly, a `do while` statement looks like the following. Note the placement
of parentheses, spaces, and braces.

    <?php
    do {
        echo 'Expression is true';
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
