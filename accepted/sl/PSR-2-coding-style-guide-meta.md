PSR-2 meta dokument
===================

1. Povzetek
-----------

Namen tega vodiča je zmanjšanje kognitivnega trenja, ko se skenira kodo različnih avtorjev. To naredi tako,
z naštevanjem skupnega niza pravil in pričakovanj o tem, kako oblikovati PHP kodo.

Stilska pravila tu so pridobljena iz skupnih značilnosti med različnimi projekti članov. Ko različni avtorji
sodelujejo med večimi projekti, pomaga imeti en niz smernic, ki so uporabljene med vsemi temi
projekti. Tako da korist tega vodiča ni v samih pravilih, vendar v deljenju teh pravil.


2. Glasovanje
-------------

- **Sprejetje glasovanja:** [ML](https://groups.google.com/d/msg/php-fig/c-QVvnZdMQ0/TdDMdzKFpdIJ)


3. Popravki
-----------

### 3.1 - Več-vrstični argumenti (09/08/2013)

Uporaba enega ali več več-vrstičnih argumentov (t.j. polja ali anonimne funkcije) ne štejejo kot
razdelitev samo seznama argumentov, zato Sekcija 4.6 ni avtomatsko uveljavljena. Polja in anonimne
funkcije so zmožne razpenjanja v večih vrsticah.

Sledeči primeri so odlično veljavni v PSR-2:

```php
<?php
somefunction($foo, $bar, [
  // ...
], $baz);

$app->get('/hello/{name}', function ($name) use ($app) { 
    return 'Hello '.$app->escape($name); 
});
```

### 3.2 - Razširitev večih vmesnikov (10/17/2013)

Ko razširjate več vmesnikov, bi moral seznam `razširitev` bi obravnavan enako kot seznam
`implementacij`, kot je deklarirano v Sekciji 4.1.

