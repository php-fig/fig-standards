Panduan Gaya Coding
==================

Panduan ini memperluas cakupan dari standar dasar coding yaitu [PSR-1].

Tujuan panduan ini adalah untuk mengurangi kesalahpahaman dalam memahami kode
yang ditulis oleh penulis lain. Cara untuk mencapai hal tersebut yaitu dengan
memilah-milah sekelompok aturan dan memperkirakan cara untuk menulis kode PHP
dengan suatu format.

Aturan gaya penulisan di sini diperoleh dari persamaan antar berbagai anggota 
proyek. Ketika berbagai anggota proyek tersebut berkolaborasi, akan sangat
membantu apabila ada sebuah rangkaian panduan yang dapat digunakan di seluruh
proyek yang mereka kerjakan. Oleh karena itu, keuntungan panduan ini bukanlah
pada aturan yang terdapat panduan itu sendiri melainkan pada saling dipakainya
aturan yang tersebut.  

Kata kunci "HARUS/WAJIB/HANYA BOLEH" sepadan dengan ("MUST", "REQUIRED", dan "SHALL"),
"TIDAK BOLEH" sepada dengan ("SHALL NOT" dan "MUST NOT"), "DISARANKAN/SEBAIKNYA" sepadan
dengan ("SHOULD" dan "RECOMMENDED"), "TIDAK DISARANKAN/TIDAK DIREKOMENDASIKAN" 
sepadan dengan ("SHOULD NOT"), dan "BOLEH SAJA/BISA JADI" sepadan dengan 
("MAY" dan "OPTIONAL") seperti yang dideskripsikan dalam [RFC 2119][].


[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md


1. Overview
-----------

- Kode HARUS mengikuti "panduan gaya coding" PSR [[PSR-1]].

- Kode HARUS menggunakan 4 spasi sebagai indentasi, bukan tab.

- TIDAK BOLEH ada batas keras pada banyaknya baris; batasasn lunak HARUS
  120 karakter; jumlah baris SEBAIKNYA 80 karakter atau kurang dari itu

- HARUS ada satu baris kosong setelah pendeklarasian `namespace`, dan 
  HARUS ada satu baris kosong setelah satu blok penggunaan `use`.

- Kurung kurawal pembuka untuk kelas HARUS terletak di bawah baris 
  pendeklarasian nama kelas, dan penutupnya harus di bawah baris akhir
  dari isi kelas.

- Kurung kurawal pembuka untuk metode HARUS terletak di bawah baris 
  pendeklarasian nama kelas, dan penutupnya harus di bawah baris akhir
  isi kelas.

- Visibilitas HARUS dideklarasikan pada seluruh properti dan metode; 
  `abstract` dan `final` HARUS dideklarasikan sebelum visibilitas;
  `static` HARUS dideklarasikan setelah visibilitas.

- Kata kunci struktur kontrol HARUS memiliki satu spasi setelahnya;
  sedangkan pemanggilan metode dan fungsi TIDAK BOLEH.

- Kurung kurawal pembuka untuk struktur kontrol HARUS terletak di 
  pada baris yang sama, dan penutupnya harus di bawah baris akhir
  isi struktur kontrol.

- TIDAK BOLEH ada spasi setelah kurung buka pada struktur kontrol, dan
  TIDAK BOLEH ada spasi sebelum kurung tutup pada struktur kontrol.

### 1.1. Contoh

Contoh di bawah mengandung beberapa aturan yang terdapat pada
bagian tinjauan singkat:

```php
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class Foo extends Bar implements FooInterface
{
    public function sampleFunction($a, $b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // method body
    }
}
```

2. Umum
----------

### 2.1 Standar Dasar Coding

Kode HARUS mengikuti semua aturan yang tercantum pada [PSR-1].

### 2.2 BERKAS

Semua berkas PHP HARUS menggunakan LF (*linefeed*) pada UNIX.

Semua berkas PHP HARUS diakhiri dengan satu baris kososng

Tag penutup `?>` HARUS dihilangkan dari berkas yang hanya mengandung kode PHP

### 2.3. Baris

TIDAK BOLEH ada batasan pada panjang baris.

Batasan lunak pada panjang sebuah baris HARUS 120 karakter; pemeriksa gaya
yang diotomatisasi HARUS memperingati tetapi TIDAK BOLEH mengeluarkan
error pada tahap batasan lunak.

Baris DISARANKAN agar tidak lebih panjang dari 80 karakter; baris yang lebih
panjang DISARANKAN agar dibagi menjadi beberapa baris berikutnya yang 
masing-masing tidak lebih dari 80 karakter.

TIDAK BOLEH ada *whitespace* pada akhir dari baris yang tidak kosong.

Baris kosong BOLEH ditambahkan untuk meningkatkan daya baca dan mengindikasikan
grup suatu kode.

TIDAK BOLEH ada lebih dari satu pernyataan per baris.

### 2.4. Indentasi

Kode HARUS menggunakan 4 spasi sebagai indentasi, dan TIDAK BOLEH menggunakan
tabulasi sebagai indentasi.

> Catatan: Penggunaan spasi, dan tidak mencampuradukkannya dengan tabulasi
> dapat menghindarkan kita dari masalah dengan diffs, patch, history, dan 
> anotasi. Penggunaan spasi juga dapat mempermudah dalam menyisipi 
> sub-indentasi untuk pengaturan penjajaran antar baris.

### 2.5. Kata Kunci dan True/False/Null

PHP [kata kunci] HARUS ditulis dengan huruf kecil.

Konstanta PHP `true`, `false`, dan `null` HARUS ditulis dengan huruf kecil.

[kata kunci]: http://php.net/manual/en/reserved.keywords.php



3. Deklarasi Namespace dan Use
---------------------------------

Ketika Namespace digunakan, HARUS ada baris kosong setelah deklarasi 
`namespace`.

Ketika Namespace digunakan, seluruh penggunaan `use` HARUS di berada di
bawah deklarasi `namespace`.

HARUS ada satu kata kunci `use` setiap deklarasi

HARUS ada sebuah baris kosong setelah blok pendeklarasian `use`.

Contohnya:
```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... Kode PHP tambahan ...

```


4. Kelas, Properti, dan Metode
-----------------------------------

Istilah "class" mengacu pada seluruh kelas, interface, dan traits.

### 4.1. Extends dan Implements

Kata kunci `extends` dan `implements` HARUS dideklarasikan pada baris yang
sama dengan nama kelas.

Kurung kurawal buka pada kelas HARUS memiliki barisnya sendiri; dan
penutupnya harus di bawah baris akhir dari isi kelas.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // konstanta, properti, dan metode
}
```

Beberapa `implements` BOLEH dibagi atas beberapa baris yang masing-masing
barisnya memiliki sekali indentasi. Namun, nama interface yang pertama HARUS
terletak di bawah baris pendeklarasian, dah hanya HARUS ada satu interface
per baris.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // konstanta, properti, dan metode
}
```

### 4.2. Properti

Visibilitas HARUS dideklarasikan pada semua properti.

Kata kunci `var` TIDAK BOELH digunakan untuk mendeklarasikan properti

TIDAK BOLEH lebih dari satu properti yang dideklarasikan per barisnya.

Nama properti SEBAIKNYA TIDAK menggunakan garis bawah sebagai prefiks
untuk mengindikasikan visibilitas `protected` atau `private`. 

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. Metode

Visibilitas HARUS dideklarasikan pada semua metode.

Nama metode SEBAIKNYA TIDAK menggunakan garis bawah sebagai prefiks
untuk mengindikasikan visibilitas `protected` atau `private`. 

TIDAK BOLEH ada spasi setelah pendeklarasian nama metode. Kurung buka
HARUS memiliki barisnya sendiri, dan penutupnya harus di bawah baris akhir
isi metode. TIDAK BOLEH ada spasi setelah kurung buka dan TIDAK BOLEH ada spasi
sebelum kurung tutup sebuah metode.

Deklarasi metode terlihat seperti berikut. Perhatikan peletakan tanda kurung,
koma, spasi, dan tanda kurung kurawal.


```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```    

### 4.4. Argumen Pada Metode

Pada daftar argumen, TIDAK BOLEH ada spasi sebelum koma, dan HARUS ada satu
spasi setelah koma.

Argumen pada metode dengan nilai yang diatur HARUS ditulis diakhir daftar
argumen.
```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```

Daftar argumen BOLEH dibagi atas beberapa baris yang masing-masing
barisnya memiliki sekali indentasi. Namun, argumen pertama HARUS
terletak di bawah baris pendeklarasian, dah hanya HARUS ada satu argumen
per baris.

Ketika daftar argumen dibagi atas beberapa baris, kurung tutup dan 
kurung kurawal buka HARUS diletakkan dalam satu baris dan dipisahkan 
dengan satu spasi.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // method body
    }
}
```

### 4.5. `abstract`, `final`, dan `static`

Ketika digunakan, deklarasi `abstract` dan `final` HARUS berada sebelum
diklarasi visibilitas

Ketika digunakan, deklarasi `static` HARUS berada setelah deklarasi visibilitas

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // method body
    }
}
```

### 4.6. Pemanggilan Metode dan Fungsi

Ketika pemanggilan metode atau fungsi dilakukan, TIDAK BOLEH ada spasi di
antara nama metode atau fungsi dan kurung buka, TIDAK BOLEH ada spasi
setelah kurung buka, TIDAK BOLEH ada spasi sebelum kurung tutup. Pada daftar
argumen, TIDAK BOLEH ada spasi sebelum tiap koma, dan HARUS ada spasi
setelah tiap koma.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

Daftar argumen BOLEH dibagi atas beberapa baris yang masing-masing
barisnya memiliki sekali indentasi. Namun, argumen pertama HARUS
terletak di bawah baris pemanggilan, dah hanya HARUS ada satu argumen
per baris.

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. Struktur Kontrol
---------------------

Aturan gaya penulisan secara umum untuk struktur kontrol adalah sebagai berikut:

- HARUS ada satu spasi setelah kata kunci struktur kontrol
- TIDAK BOLEH ada spasi setelah kurung buka
- TIDAK BOLEH ada spasi sebelum kurung tutup
- HARUS ada satu spasi antara kurung tutup dan kurung kurawal buka
- Isi struktur HARUS diindentasi sebanyak satu kali
- Kurung kurawal tutup HARUS berada di baris setelah isi struktur kontrol

Isi setiap struktur HARUS dikemas dengan kurung kurawal. Hal ini menstandarisasi
penampilan struktur, dan mengurangi kemungkinan terjadinya error akibat
baris baru ditambahkan ke isi struktur.


### 5.1. `if`, `elseif`, `else`

Struktur `if` terlihat seperti di bawah ini. Perhatikan peletakan tanda kurung,
spasi, dan kurung kurawal; dan `else` dan `elseif` berada pada baris yang sama
seperti kurung kurawal penutup pada isi struktur yang terakhir.

```php
<?php
if ($expr1) {
    // if body
} elseif ($expr2) {
    // elseif body
} else {
    // else body;
}
```

Kata kunci `elseif` DISARANKAN agar lebih digunakan daripada `else if` agar
memperlihatkan kalau kata kunci pada struktur kontrol menggunakan satu kata.


### 5.2. `switch`, `case`

Struktur `switch` terlihat seperti di bawah ini. Perhatikan peletakan tanda 
kurung, spasi, dan kurung kurawal. Pernyataan `case` HARUS diindentasi
sebanyak satu kali dari `switch`, dan kata kunci `break` (atau kata kunci
penghenti proses lainnya) HARUS diindentasi pada tingkat yang sama dengan
isi `case`. HARUS ada komentar seperti 
`// tidak ada break ketika penelusuran dikehendaki pada isi `case` yang 
`//tidak kosong


```php
<?php
switch ($expr) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Third case, return instead of break';
        return;
    default:
        echo 'Default case';
        break;
}
```


### 5.3. `while`, `do while`

Pernyataan `while` terlihat seperti di bawah ini. Perhatikan peletakan tanda
kurung, spasi, dan kurung kurawal.

```php
<?php
while ($expr) {
    // isi struktur;
}
```

Pernyataan `do while` terlihat seperti di bawah ini. Perhatikan peletakan tanda
kurung, spasi, dan kurung kurawal

```php
<?php
do {
    // isi struktur;
} while ($expr);
```

### 5.4. `for`

Pernyataan `for` terlihat seperti di bawah ini. Perhatikan peletakan tanda
kurung, spasi, dan kurung kurawal.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // isi for
}
```

### 5.5. `foreach`
    
Pernyataan `foreach` terlihat seperti di bawah ini. Perhatikan peletakan tanda
kurung, spasi, dan kurung kurawal.

```php
<?php
foreach ($iterable as $key => $value) {
    // isi foreach
}
```

### 5.6. `try`, `catch`

Blok `try catch` terlihat seperti di bawah ini. Perhatikan peletakan tanda
kurung, spasi, dan kurung kurawal.

```php
<?php
try {
    // isi try
} catch (FirstExceptionType $e) {
    // isi catch
} catch (OtherExceptionType $e) {
    // isi catch
}
```

6. *Closures*
-----------

*Closure* HARUS dideklarasikan dengan menggunakan sebuah spasi setelah kata
kunci `function`, dan sebuah spasi sebelum dan sesudah kata kunci `use`.

Kurung kurawal pembuka HARUS diletakkan pada baris yang sama, dan penutupnya
HARUS diletakkan pada baris setelah isi.

TIDAK BOLEH ada spasi setelah kurung buka pada daftar argumen atau daftar
variabel, dan TIDAK BOLEH ada spasi sebelum kurung tutup dari daftar argumen
atau daftar variabel.

Pada daftar argumen dan daftar variabel, TIDAK BOLEH ada spasi sebelum tiap
koma, dan HARUS ada spasi setelah tiap koma.

Argumen pada *closure* dengan nilai yang telah ditetapkan HARUS diletakkan 
di akhir daftar argumen.

Deklarasi *closure* terlihat seperti di bawah ini. Perhatikan peletakan tanda
kurung, spasi, dan kurung kurawal:

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

Daftar argumen dan daftar variabel BOLEH dibagi atas beberapa baris yang 
masing-masing barisnya memiliki sekali indentasi. Namun, argumen pertama 
pada daftar tersebut HARUS terletak di bawah baris pendeklarasian, dah 
hanya HARUS ada satu argumen atau variabel per baris.

Ketika akhir daftar (bisa itu argumen atau variabel) dibagi atas beberapa
baris, kurung tutup kurawal dan kurung buka HARUS diletakkan pada baris
yang bersamaan dan dipisahkan dengan satu spasi.

Berikut ini adalah contoh *closure* dengan dan tanpa daftar argumen dan
daftar variabel yang dibagi atas beberapa baris.

```php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // isi
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // isi
};

$longArgs_longVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // isi
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // isi
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // isi
};
```

Perhatikan bahwa aturan dalam aturan pembentukkan juga berlaku pada saat
*closure* digunakan secara langsung pada pemanggilan fungsi atau metode
sebagai sebuah argumen.

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // isi
    },
    $arg3
);
```


7. Kesimpulan
--------------

Ada banyak elemen pada gaya dan praktek yang secara sengaja dihilangkan melalui
panduan ini. Termasuk dan tidak terbatas pada:

- Deklarasi variabel global dan konstanta global

- Deklarasi fungsi

- Operator dan *assignment*

- Penjajaran antar-baris

- Blok komentar dan dokumentasi

- Prefiks dan sufiks nama kelas

- Prosedur yang disarankan

Rekomendasi yang akan datang BISA SAJA mengubah dan memperluas panduan ini 
untuk mengatasi masalah tertentu atau menyikapi adanya gaya dan praktek dari
elemen lain. 

Apendiks A. Survei
------------------

Pada penulisan panduan gaya ini, sebuah grup melaksanakan survei terhadap
berbagai anggota proyek untuk menentukan praktek yang sama dalam pengkodean.
Survei disediakan di sini sebagai gambaran.

### A.1. Data Survei

    url,http://www.horde.org/apps/horde/docs/CODING_STANDARDS,http://pear.php.net/manual/en/standards.php,http://solarphp.com/manual/appendix-standards.style,http://framework.zend.com/manual/en/coding-standard.html,http://symfony.com/doc/2.0/contributing/code/standards.html,http://www.ppi.io/docs/coding-standards.html,https://github.com/ezsystems/ezp-next/wiki/codingstandards,http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html,https://github.com/UnionOfRAD/lithium/wiki/Spec%3A-Coding,http://drupal.org/coding-standards,http://code.google.com/p/sabredav/,http://area51.phpbb.com/docs/31x/coding-guidelines.html,https://docs.google.com/a/zikula.org/document/edit?authkey=CPCU0Us&hgd=1&id=1fcqb93Sn-hR9c0mkN6m_tyWnmEvoswKBtSc0tKkZmJA,http://www.chisimba.com,n/a,https://github.com/Respect/project-info/blob/master/coding-standards-sample.php,n/a,Object Calisthenics for PHP,http://doc.nette.org/en/coding-standard,http://flow3.typo3.org,https://github.com/propelorm/Propel2/wiki/Coding-Standards,http://developer.joomla.org/coding-standards.html
    voting,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,no,no,no,?,yes,no,yes
    indent_type,4,4,4,4,4,tab,4,tab,tab,2,4,tab,4,4,4,4,4,4,tab,tab,4,tab
    line_length_limit_soft,75,75,75,75,no,85,120,120,80,80,80,no,100,80,80,?,?,120,80,120,no,150
    line_length_limit_hard,85,85,85,85,no,no,no,no,100,?,no,no,no,100,100,?,120,120,no,no,no,no
    class_names,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,lower_under,studly,lower,studly,studly,studly,studly,?,studly,studly,studly
    class_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,next,next,next,next,next,next,same,next,next
    constant_names,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper
    true_false_null,lower,lower,lower,lower,lower,lower,lower,lower,lower,upper,lower,lower,lower,upper,lower,lower,lower,lower,lower,upper,lower,lower
    method_names,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,lower_under,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel
    method_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,same,next,next,next,next,next,same,next,next
    control_brace_line,same,same,same,same,same,same,next,same,same,same,same,next,same,same,next,same,same,same,same,same,same,next
    control_space_after,yes,yes,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes
    always_use_control_braces,yes,yes,yes,yes,yes,yes,no,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes
    else_elseif_line,same,same,same,same,same,same,next,same,same,next,same,next,same,next,next,same,same,same,same,same,same,next
    case_break_indent_from_switch,0/1,0/1,0/1,1/2,1/2,1/2,1/2,1/1,1/1,1/2,1/2,1/1,1/2,1/2,1/2,1/2,1/2,1/2,0/1,1/1,1/2,1/2
    function_space_after,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no
    closing_php_tag_required,no,no,no,no,no,no,no,no,yes,no,no,no,no,yes,no,no,no,no,no,yes,no,no
    line_endings,LF,LF,LF,LF,LF,LF,LF,LF,?,LF,?,LF,LF,LF,LF,?,,LF,?,LF,LF,LF
    static_or_visibility_first,static,?,static,either,either,either,visibility,visibility,visibility,either,static,either,?,visibility,?,?,either,either,visibility,visibility,static,?
    control_space_parens,no,no,no,no,no,no,yes,no,no,no,no,no,no,yes,?,no,no,no,no,no,no,no
    blank_line_after_php,no,no,no,no,yes,no,no,no,no,yes,yes,no,no,yes,?,yes,yes,no,yes,no,yes,no
    class_method_control_brace,next/next/same,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/next,same/same/same,same/same/same,same/same/same,same/same/same,next/next/next,next/next/same,next/same/same,next/next/next,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/same,next/next/next

### A.2. Data Legend

`indent_type`:
The type of indenting. `tab` = "Use a tab", `2` or `4` = "number of spaces"

`line_length_limit_soft`:
The "soft" line length limit, in characters. `?` = not discernible or no response, `no` means no limit.

`line_length_limit_hard`:
The "hard" line length limit, in characters. `?` = not discernible or no response, `no` means no limit.

`class_names`:
How classes are named. `lower` = lowercase only, `lower_under` = lowercase with underscore separators, `studly` = StudlyCase.

`class_brace_line`:
Does the opening brace for a class go on the `same` line as the class keyword, or on the `next` line after it?

`constant_names`:
How are class constants named? `upper` = Uppercase with underscore separators.

`true_false_null`:
Are the `true`, `false`, and `null` keywords spelled as all `lower` case, or all `upper` case?

`method_names`:
How are methods named? `camel` = `camelCase`, `lower_under` = lowercase with underscore separators.

`method_brace_line`:
Does the opening brace for a method go on the `same` line as the method name, or on the `next` line?

`control_brace_line`:
Does the opening brace for a control structure go on the `same` line, or on the `next` line?

`control_space_after`:
Is there a space after the control structure keyword?

`always_use_control_braces`:
Do control structures always use braces?

`else_elseif_line`:
When using `else` or `elseif`, does it go on the `same` line as the previous closing brace, or does it go on the `next` line?

`case_break_indent_from_switch`:
How many times are `case` and `break` indented from an opening `switch` statement?

`function_space_after`:
Do function calls have a space after the function name and before the opening parenthesis?

`closing_php_tag_required`:
In files containing only PHP, is the closing `?>` tag required?

`line_endings`:
What type of line ending is used?

`static_or_visibility_first`:
When declaring a method, does `static` come first, or does the visibility come first?

`control_space_parens`:
In a control structure expression, is there a space after the opening parenthesis and a space before the closing parenthesis? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

`blank_line_after_php`:
Is there a blank line after the opening PHP tag?

`class_method_control_brace`:
A summary of what line the opening braces go on for classes, methods, and control structures.

### A.3. Survey Results

    indent_type:
        tab: 7
        2: 1
        4: 14
    line_length_limit_soft:
        ?: 2
        no: 3
        75: 4
        80: 6
        85: 1
        100: 1
        120: 4
        150: 1
    line_length_limit_hard:
        ?: 2
        no: 11
        85: 4
        100: 3
        120: 2
    class_names:
        ?: 1
        lower: 1
        lower_under: 1
        studly: 19
    class_brace_line:
        next: 16
        same: 6
    constant_names:
        upper: 22
    true_false_null:
        lower: 19
        upper: 3
    method_names:
        camel: 21
        lower_under: 1
    method_brace_line:
        next: 15
        same: 7
    control_brace_line:
        next: 4
        same: 18
    control_space_after:
        no: 2
        yes: 20
    always_use_control_braces:
        no: 3
        yes: 19
    else_elseif_line:
        next: 6
        same: 16
    case_break_indent_from_switch:
        0/1: 4
        1/1: 4
        1/2: 14
    function_space_after:
        no: 22
    closing_php_tag_required:
        no: 19
        yes: 3
    line_endings:
        ?: 5
        LF: 17
    static_or_visibility_first:
        ?: 5
        either: 7
        static: 4
        visibility: 6
    control_space_parens:
        ?: 1
        no: 19
        yes: 2
    blank_line_after_php:
        ?: 1
        no: 13
        yes: 8
    class_method_control_brace:
        next/next/next: 4
        next/next/same: 11
        next/same/same: 1
        same/same/same: 6
