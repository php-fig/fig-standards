The following describes the best practice for a PHP application

Mandatory
---------

* Don't use Output-Buffers (ob_start()) except to catch output which sends a 
  PHP function (like var_dump()) direct to the browser.
  Concatinate a string and send the whole content in one step to the browser

Example Implementation
----------------------

Below is an example script to simply demonstrate how the above
proposed standards are meant to be. First the bad example afterwards the good
one.
```php
<?php

ob_start(); // or ob_start('gz_handler'); for compression, omit this

echo 'some output';

$testVar = 'hello world';

var_dump($testVar);

ob_end_clean();

echo 'end of site';

?>
```

Now the good one:

```php
<?php

$content = 'some output';

$testVar = 'hello world';

// Here it's okay to use output-buffering, because it's not possible to catch
// the output of var_dump() otherwise
ob_start();
var_dump($testVar);
$varDump = ob_get_clean(); // Turn off output-buffering here again!

$content .= $varDump;

$content .= 'end of site';

// Finally send to the browser
echo $content;

?>
```