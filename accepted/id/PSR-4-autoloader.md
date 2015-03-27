# Pemuatan Otomatis (Autoloader)

Kata kunci "HARUS/WAJIB" sepadan dengan ("MUST", "REQUIRED", dan "SHALL"),
"TIDAK BOLEH" sepada dengan ("SHALL NOT" dan "MUST NOT"), "DISARANKAN" sepadan
dengan ("SHOULD" dan "RECOMMENDED"), "TIDAK DISARANKAN/TIDAK DIREKOMENDASIKAN" 
sepadan dengan ("SHOULD NOT"), dan "BOLEH SAJA/BISA JADI" sepadan dengan 
("MAY" dan "OPTIONAL") seperti yang dideskripsikan dalam [RFC 2119](http://tools.ietf.org/html/rfc2119).

## 1. Tinjauan Singkat

PSR ini mendeskripsikan spesifikasi untuk berbagai kelas [autoloading][]
dari berbagai path. PSR sepenuhnya dapat bekerja satu sama lain, dan dapat 
digunakan sebagai tambahan terhadap spesifikasi autoloading yang lain, termasuk
 [PSR-0][]. PSR ini juga menggambarkan tempat berkas berada untuk dimuat secara
otomatis (autoloaded) sesuai dengan spesifikasi yang telah diatur.

## 2. Spesifikasi

1. Kata "kelas" ("class") merujuk kepada kelas-kelas (classes), antarmuka 
   (interfaces), sifat (traits), dan struktur lain yang memiliki kemiripan.

2. Nama kelas yang memenuhi syarat memiliki bentuk path sebagai berikut:

        \<NamespaceName>(\<SubNamespaceNames>)*\<ClassName>

    1. Nama kelas yang memenuhi syarat HARUS memiliki nama namespace tertinggi,
       atau biasa disebut sebagai *"vendor namespace"*.

    2. Nama kelas yang memenuhi syarat BOLEH SAJA memiliki lebih dari satu
       nama *sub-namespace*.

    3. Nama kelas yang memenuhi syarat HARUS memiliki sebuah nama kelas 
       pembatas (*terminating class name*)

    4. Garis bawah (*underscores*) tidak memiliki arti khusus pada penamaan
       kelas yang memenuhi syarat.

    5. Huruf alfabet dalam kelas yang memenuhi syarat BOLEH SAJA terdiri dari
       kombinasi huruf kecil dan huruf besar

    6. Seluruh nama kelas harus direferensikan dalam bentuk *case-sensitive*

3. Ketika memuat sebuah berkas yang cocok dengan persyaratan nama kelas di
   di atas ...

    1. Sederetan rangkaian satu atau lebih namespace utama dan nama 
       sub-namespace, tidak termasuk pemisah (*separator*) namespace utama, 
       pada nama kelas yang memenuhi syarat (*"namespace prefix"*) sesuai dengan
       setidaknya satu "direktori dasar".

    2. Sederetan rangkaian nama sub-namespace setelah *"namespace prefix"*
       menyesuaikan terhadap subdirektori di dalam direktori dasar
       (*"base directory"*), dengan syarat pemisah namespace menunjukkan 
       pemisah direktori. Nama subdirektori HARUS sesuai dengan besar kecilnya 
       huruf pada nama sub-namespace.

    3. Nama kelas pembatas ditunjukkan dalam bentuk nama berkas yang berakhiran
       dengan ekstensi `.php`. Nama berkas HARUS sesuai  dengan besar kecilnya 
       huruf pada nama kelas pembatas.

4. Implementasi autoloader TIDAK BOLEH menghasilkan eksepsi, TIDAK BOLEH 
   menimbulkan error pada tahap apapun, dan TIDAK DISARANKAN untuk 
   mengembalikan nilai.


## 3. Contoh

Tabel di bawah ini menunjukkan kesesuaian path berkas pada nama kelas, prefiks 
namespace, dan *base directory* yang memenuhi syarat.

| Nama Kelas Yang Memenuhi Syarat   | Prefiks Namespace  | Direktori Dasar          | Hasil path berkas
| --------------------------------- |--------------------|--------------------------|-------------------------------------------
| \Acme\Log\Writer\File_Writer      | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status         | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request             | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                         | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php

Sebagai contoh implementasi autoloder yang telah memenuhi spesifikasi silakan
melihatnya di [contoh berkas][]. Contoh implementasi TIDAK BOLEH dianggap
sebagai bagian dari spesifikasi dan BISA SAJA berubah kapan saja.

[contoh berkas]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
[autoloading]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[examples file]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
