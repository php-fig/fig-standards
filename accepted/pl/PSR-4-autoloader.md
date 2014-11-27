# Autoloader

Następujące słowa "MUSI", "NIE WOLNO", "WYMAGANE", "POWINNO", "NIE POWINNO", 
"REKOMENDWANE", "MOŻE" oraz "OPCJONALNE" będą interpretowane tak jak opisano 
to w [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Podsumowanie

Poniższy PSR opisuje specyfikację [autoloadingu][] klas na podstawie 
ścieżek plików. Autoloading jest w pełni interoperacyjny i może być użyty 
jako dodatek do wszelkich innych specyfikacji autoloadingu, włączając [PSR-0][]. 
Ten PSR opisuje także to gdzie umieszczać pliki, które będę automatycznie 
ładowane stosownie do specyfikacji.


## 2. Specyfikacja

1. Termin "klasa" odnosi się poniżej do wszystkich klas, interfejsów, 
traitów oraz innych podobnych struktur.

2. W pełni poprawna nazwa klasy posiada poniższą formę:

        \<NazwaNamespacea>(\<NazwaPodNamespacea>)*\<NazwaKlasy>

    1. W pełni poprawna nazwa klasy MUSI posiadać namespace bazowy, 
	zwany także "namespace'm vendora".

    2. W pełni poprawna nazwa klasy MOŻE posiadać jeden lub wiele podnamespace'ów.

    3. W pełni poprawna nazwa klasy MUSI posiadać nazwę klasy na końcu.

    4. Znak podkreślenia nie ma żadnego specjalnego znaczenia w pełni 
	poprawnej nazwie klasy.

    5. W pełni poprawna nazwa klasy MOŻE posiadać dowolny porządek znaków 
	alfabetu różnej wielkości (małe/duże litery).

    6. Wszystkie nazwy klas MUSZĄ być wywoływane z uwzględnieniem małych i wielkich liter.

3. Kiedy wczytujemy plik, który odpowiada w pełni poprawnej nazwie klasy...

    1. Sąsiadujące ze sobą nazwy namespace'a bazowego oraz podnamespace'ów 
	(bez uwzględnienia początkowego separatora namespace'a bazowego) w pełni poprawnej 
	nazwie klasy zwane są "prefiksem przestrzeni nazw" i odpowiadają przynajmniej jednemu 
	"katalogowi bazowemu".

    2. Sąsiadujące ze sobą nazwy podnamespace'ów występujące po "prefiksie przestrzeni nazw" 
	odpowiadają podkatalogowi w "katalogu bazowym", gdzie separatory namespace'ów reprezentują 
	separatory katalogów. Nazwa (ścieżka) podkatalogu MUSI być zgodna z nazwami podnamespace'ów.

    3. Końcowa nazwa klasy odpowiada plikowi kończącemu się na `.php`. 
	Nazwa pliku MUSI być zgodna z ostatnim segmentem w pełni poprawnej nazwy klasy.

4. Implementacji autoloadera NIE WOLNO rzucać wyjątków, NIE WOLNO zgłaszać 
jakichkolwiek błędów, implementacja NIE POWINNA także zwracać wartości.


## 3. Przykłady

Poniższa tabela przedstawia w pełni poprawne nazwy klas, prefiksy przestrzeni 
nazw oraz bazowy katalog, które wskazują na ścieżkę do pliku.

| W pełni poprawna nazwa klasy  | Prefiks przestrzeni nazw | Bazowy katalog          | Wynikowa ścieżka pliku
| ----------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer  | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status     | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request         | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                     | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

Aby przejrzeć przykładową implementację autloadera zgodnego ze specyfikacja, 
można przejść do [pliku przykładu][]. NIE WOLNO uważać przykładowej implementacji 
za część specyfikacji, MOŻE się ona zmienić w każdej chwili.

[autoloadingu]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[pliku przykładu]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
