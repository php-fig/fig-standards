Πρότυπο Αυτόματης Φόρτωσης
==========================

Το παρακάτω περιγράφει τις υποχρεωτικές απαιτήσεις που πρέπει να τηρούνται
για διαλειτουργικότητα με αυτόματους φορτωτές.

Υποχρεωτικά
-----------

* Ένας πλήρως προσδιορισμένος τομέας ονομάτος και κλάση πρέπει να έχουν την ακόλουθη
  δομή: `\<Vendor Name>\(<Namespace>\)*<Class Name>`
* Κάθε τομέας ονόματος πρέπει να διαθέτει έναν ανώτατο τομέα ονόματος ("Vendor Name").
* Κάθε τομέας ονόματος μπορεί να διαθέτει όσους υπο-τομείς ονομάτων επιθυμεί.
* Κάθε διαχωριστικό τομέα ονόματος μετατρέπεται σε `DIRECTORY_SEPARATOR` όταν η φόρτωση
  γίνεται από το σύστημα αρχείων.
* Κάθε χαρακτήρας `_` στο ΟΝΟΜΑ ΚΛΑΣΗΣ μετατρέπεται σε `DIRECTORY_SEPARATOR`.
  Ο χαρακτήρας `_` δεν έχει ειδικό νόημα στον τομέα ονόματος.
* Ο πλήρως προσδιορισμένος τομέας ονόματος και η κλάση καταλήγουν σε `.php` όταν η
  φόρτωση γίνεται από το σύστημα αρχείων.
* Αλφαβητικοί χαρακτήρες σε ονόματα διανομέων, τομείς ονομάτων και ονόματα κλάσεων μπορούν
  να είναι σε οποιοδήποτε συνδυασμό πεζών και κεφαλαίων.

Παραδείγματα
------------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Κάτω παύλα σε Ονόματα Τομέων και Ονόματα Κλάσεων
------------------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

Τα πρότυπα που ορίζουμε εδώ θα πρέπει να έχουν το μικρότερο κοινό παρονομαστή για
ανώδυνη διαλειτουργικότητα με αυτόματους φορτωτές. Μπορείτε να ελέγξετε ότι ακολουθείτε
αυτά τα πρότυπα χρησιμοποιώντας αυτό το δείγμα υλοποίησης SplClassLoader που μπορεί
να φορτώνει PHP 5.3 κλάσεις.

Παράδειγμα Υλοποίησης
---------------------

Παρακάτω είναι ένα παράδειγμα συνάρτησης που απλά επιδεικνύει πώς τα παραπάνω
προτεινόμενα πρότυπα φορτώνονται αυτόματα.

```php
<?php

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
```

Υλοιποίηση SplClassLoader
-------------------------

Το παρακάτω gist είναι ένα παράδειγμα υλοποίησης SplClassLoader που μπορεί να
φορτώσει τις κλάσεις σας αν ακολουθείτε τα πρότυπα διαλειτουργικότητας για αυτόματο
μεταφορτωτή που προτάθηκαν παρπάνω. Είναι ο σύγχρονος ενδεδειγμένος τρόπος να
φορτώνονται οι PHP 5.3 κλάσεις που ακολουθούν αυτά τα πρότυπα.

* [http://gist.github.com/221634](http://gist.github.com/221634)

