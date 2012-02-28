The following document describes the guidelines for code formatting in
modern PHP projects.

Rules
-----

* PHP code should be delimited by standard PHP tags. Short tags, ASP tags and script tags are not allowed.
* Files that contain only PHP code should omit the closing tag "?>" in order to avoid accidental injection of escape characters.
* Indentation consists of 4 spaces, tabs are not allowed.
* Don't put spaces after opening parenthesis and before closing parenthesis.
* After language keywords (if, else, while, switch, etc.) add a single space before the opening parenthesis.
* Don't add trailing spaces after braces, parenthesis or line endings.
* Native PHP types should be lowercase (false, null, true, array, etc.).
* Use camelCase for variable, function and method names. Don't use underscores.
* Use uppercase for constants, separate words with underscores.
* Class, method and function declarations should have braces on a new line.
* Conditional statements should have braces on the same line, with a single space before the brace.
* When there are no parameters for the constructor method, omit the parenthesis.
* Wrap operators with a single space (==, !=, &&).
* A string that does not contain variable substitution should use single quotes.
* A string that contains variable substitution should use double quotes. If the variable to be substituted is an array index, braces should be used. Never concatenate for this.
* Don't concatenate string and function calls.
* Inside switch statements, the break keyword is aligned with the case keyword indentation.

Example implementation
----------------------

    <?php

    class Example
    {
        const CLASS_CONSTANT = 1;

        private $var;

        public function executeTest($foo, $bar, $item)
        {
            if ($foo == 'crazy') {
                $foobar = "Hey! I'm $foo and this is my {$item['weapon']}";
            }
            
            switch ($bar) {
                case 'people':
                    return;
                break;

                default:
                    $bar = true;
                break;
            }

            return $foobar;
        }
    }

    $obj = new Example;
    $obj->executeTest('crazy', 'nothing', array('weapon' => 'BFG'));
