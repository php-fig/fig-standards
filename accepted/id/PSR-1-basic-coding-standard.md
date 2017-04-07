Standar Dasar Coding
=====================

Dalam standar ini terdiri atas hal yang harus dipertimbangkan tentang dasar
elemen dalam coding. Gunanya untuk memastikan agar kode PHP yang dibuat
memiliki dasar teknis sehingga dapat mudah digunakan oleh siapa pun 
yang mengikuti standar dasar coding ini.


Kata kunci "HARUS/WAJIB/HANYA BOLEH" sepadan dengan ("MUST", "REQUIRED", dan "SHALL"),
"TIDAK BOLEH" sepada dengan ("SHALL NOT" dan "MUST NOT"), "DISARANKAN/SEBAIKNYA" sepadan
dengan ("SHOULD" dan "RECOMMENDED"), "TIDAK DISARANKAN/TIDAK DIREKOMENDASIKAN" 
sepadan dengan ("SHOULD NOT"), dan "BOLEH SAJA/BISA JADI" sepadan dengan 
("MAY" dan "OPTIONAL") seperti yang dideskripsikan dalam [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


1. Tinjauan Singkat
-----------

- Berkas HARUS hanya boleh menggunakan tag `<?php` dan `<?=`.

- Berkas HARUS hanya boleh menggunakan UTF-8 tanpa BOM untuk kode PHP.

- Berkas DISARANKAN agar baik mendeklarasikan simbol (kelas, fungsi, konstanta,
  dsb.) maupun efek samping seperti menghasilkan output, mengubah 
  pengaturan berkas .ini, dan sebagainya, tetapi TIDAK DIREKOMENDASIKAN 
  untuk mengaplikasikan kedua cara tersebut bersamaan.

- Namespaces dan kelas HARUS mengikuti "autoloading" PSR: [[PSR-0], [PSR-4]]

- Nama kelas HARUS ditulis dalam bentuk `StudlyCaps`.

- Konstanta pada kelas HARUS ditulis dengan huruf besar dengan garis bawah
  atau *underscore* sebagai pemisah antar kata pada nama konstanta.

- Nama metode HARUS ditulis dalam bentuk `camelCase`.


2. Berkas
--------

### 2.1. Tag PHP

Kode PHP HARUS menggunakan tag `<?php ?>` secara utuh atau tag short-echo 
`<?= ?>`; dan TIDAK BOLEH menggunakan tag lain. 

### 2.2. Encoding Karakter

Kode PHP HANYA BOLEH menggunakan UTF-8 tanpa BOM.

### 2.3. Efek Samping

Sebuah berkas DISARANKAN agar mendeklarasikan simbol baru (kelas, fungsi,
konstanta, dan sebagainya) dan tidak menyebabkan efek samping lain, atau
DISARANKAN agar dapat mengeksekosi logika pemrograman dengan efek samping,
tetapi TIDAK DIREKOMENDASIKAN untuk mengaplikasikan kedua cara tersebut 
bersamaan.

A file SHOULD declare new symbols (classes, functions, constants,
etc.) and cause no other side effects, or it SHOULD execute logic with side
effects, but SHOULD NOT do both.

Frase "efek samping" mengandung arti bahwa eksekusi logika pemrograman
tidak secara langsung berhubungan dalam hal mendeklarasikan kelas, fungsi,
konstanta, dan sebagainya, atau contohnya seperti melakukan include
sebuah berkas.

"Efek samping" termasuk dan tidak hanya sebatas: menghasilkan keluaran,
penggunaan `require` atau `include` secara eksplisit, menghubungkan ke
*external services*, memodifikasi pengaturan ini, menghasilkan error dan
*exceptions*, memodifikasi variabel global atau statis, membaca atau menulis
 suatu berkas, dan sebagainya.

Berikut ini adalah contoh sebuah berkas dengan deklarasi dan efek samping
yang harus dihindari:

```php
<?php
// efek samping: mengubah pengaturan ini 
ini_set('error_reporting', E_ALL);

// efek samping: memuat berkas
include "file.php";

// efek samping: menghasilkan keluaran
echo "<html>\n";

// deklarasi
function foo()
{
    // tubuh fungsi
}
```


Berikut ini adalah contoh sebuah berkas yang mengandung deklarasi tanpa
efek samping.

```php
<?php
// deklarasi
function foo()
{
    // function body
}

// deklarasi kondisional bukanlah efek samping
if (! function_exists('bar')) {
    function bar()
    {
        // tubuh fungsi
    }
}
```


3. Namespace dan Nama Kelas
----------------------------

Namespace dan kelas HARUS mengikuti "autoloading" PSR:  [[PSR-0], [PSR-4]].

Hal ini berarti setiap kelas berada pada berkasnya sendiri, dan berada dalam
sebuah namespace paling sedikit satu tingkat: tingkatan teratas pada
nama vendor.

Nama kelas HARUS ditulis dengan bentuk `StudlyCaps`.

Kode yang ditulis untuk PHP 5.3 dan versi berikutnya HARUS menggunakan
namespace formal.

Contohnya:

```php
<?php
// PHP 5.3 and later:
namespace Vendor\Model;

class Foo
{
}
```

Kode yang ditulis untuk PHP 5.2.x dan sebelumnya SEBAIKNYA menggunakan
konvensi *pseudo-namespacing* yaitu dengan menggunakan prefix `Vendor_` pada
nama kelas.


```php
<?php
// PHP 5.2.x dan sebelumnya:
class Vendor_Model_Foo
{
}
```

4. Konstanta, Proterti, dan Metode Dalam Kelas
-------------------------------------------

Istilah "kelas" mengacu pada seluruh kelas, interface, dan traits.

### 4.1. Konstanta

Konstanta kelas HARUS dideklarasikan dalam bentuk huruf besa dengan garis 
bawah atau *underscore* sebagai pemisah.
Contohnya:

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. Properti

Panduan ini bermaksud untuk menghindarkan berbagai rekomendasi penggunaan
`$StudlyCaps`, `$camelCase`, atau `$under_score` sebagai nama properti.

Apapun konvensi penamaan yang digunakan SEBAIKNYA diaplikasikan secara 
konsisten dengan lingkup yang masuk akal. Lingkup tersebut bisa terdapat
pada tingkatan vendor, tingkatan paket, tingkatan kelas, atau tingkatan
metode.

### 4.3. Metode

Nama metode HARUS dideklarasikan `camelCase()`.
