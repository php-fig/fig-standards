Príkladné implementácie PSR-4
=============================

Nasledujúce príklady znázorňujú kódy vyhovujúce štandardu PSR-4:

Príklad Uzávierky (Closure)
---------------------------

```php
<?php
/**
 * Príklad implementácie špecifickej pre projekt.
 * 
 * Po zaregistrovaní autoloadera s SPL, sa bude nasledujúci
 * riadok snažiť načítať triedu \Foo\Bar\Baz\Qux
 * z cesty /path/to/project/src/Baz/Qux.php:
 * 
 *      new \Foo\Bar\Baz\Qux;
 *      
 * @param string $class Plné meno triedy.
 * @return void
 */
spl_autoload_register(function ($class) {
    
    // predpona menného priestoru pre daný projekt
    $prefix = 'Foo\\Bar\\';

    // koreňový adresár pre predponu menného priestoru
    $base_dir = __DIR__ . '/src/';
    
    // Používa trieda predponu menného priestoru?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // nie, tak sa presuň na ďaľší registrovaný autoloader
        return;
    }
    
    // získaj časť s meno triedy
    $relative_class = substr($class, $len);
    
    // nahraď predponu menného priestoru s koreňovým adresárom,
    // oddelovače mených priestorov nahrad s oddelovačmi adresárov,
    // pridaj časť s menom triedy
    // a nakoniec pridaj .php na koniec 
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
```

Príklad triedy
--------------

Nasledujúci príklad implementuje prácu s mnohými mennými priestormi:

```php
<?php
namespace Example;

/**
 * Príklad implementuje všeobecne použitelnú nepovinnú funkcionalitu,
 * kde povoľuje použitie viacerých koreňových adresárov 
 * pre jednu predponu menného priestoru
 * 
 * Je daný balík foo-bar s triedami v súborovom systéme s tymito cestami
 * 
 *     /cesta/ku/kniznici/foo-bar/
 *         src/
 *             Baz.php             # Foo\Bar\Baz
 *             Qux/
 *                 Quux.php        # Foo\Bar\Qux\Quux
 *         tests/
 *             BazTest.php         # Foo\Bar\BazTest
 *             Qux/
 *                 QuuxTest.php    # Foo\Bar\Qux\QuuxTest
 * 
 * ... pridanie cesty k súborom tried pre predponu menného priestoru \Foo\Bar\ 
 * je nasledovná:
 * 
 *      <?php
 *      // vytvorte inštanciu autoloadera
 *      $loader = new \Example\Psr4AutoloaderClass;
 *      
 *      // registrujte autoloader
 *      $loader->register();
 *      
 *      // registrujte koreňové adresáre pre predpony menných priestorov
 *      $loader->addNamespace('Foo\Bar', '/cesta/ku/kniznici/foo-bar/src');
 *      $loader->addNamespace('Foo\Bar', '/cesta/ku/kniznici/foo-bar/tests');
 * 
 * Na nasledujúcom riadku by sa autoloader snažil načítať triedu \Foo\Bar\Qux\Quux 
 * z /cesta/ku/kniznici/foo-bar/src/Qux/Quux.php:
 * 
 *      <?php
 *      new \Foo\Bar\Qux\Quux;
 * 
 * Na nasledujúcom riadku by sa autoloader snažil načítať triedu \Foo\Bar\Qux\QuuxTest
 * z /path/to/packages/foo-bar/tests/Qux/QuuxTest.php:
 * 
 *      <?php
 *      new \Foo\Bar\Qux\QuuxTest;
 */
class Psr4AutoloaderClass
{
    /**
     * Associatívne pole, kde kľúč je predpona menného priestoru a hodnota
     * je pole koreňových adresárov pre triedy v danom mennom priestore.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Registruj loader do zásobníka SPL autoloaderu.
     * 
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Pridá koreňový adresár pre predponu menného priestoru.
     *
     * @param string $prefix Predpona menného priestoru.
     * @param string $base_dir Koreňový adresár pre súbory tried v mennom
     * priestore.
     * @param bool $prepend Ak pravda, tak pripojí koreňový adresár na začiatok
     * zásobnika, namieto pripojenia ku koncu; to znamená, že bude 
     * hľadané skorej ako posledné
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // normalizuje predponu menného priestoru
        $prefix = trim($prefix, '\\') . '\\';
        
        // normalizuje koreňový adresár s oddeľovačom na konci
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // vytvorí pole pre predpony menných priestorov
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }
        
        // uchová koreňový adresár pre predpony menného priestoru.
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    /**
     * Načíta súbor triedy pre danú triedu.
     *
     * @param string $class Plné meno triedy.
     * @return mixed Namapované meno súboru v prípade úspechu, 
     * alebo binárne false v prípade zlyhania.
     */
    public function loadClass($class)
    {
        // predpona menného priestoru
        $prefix = $class;
        
        // choď naspäť cez mená menných priestorov s plným menom triedy
        // aby si našiel namapované meno súboru 
        while (false !== $pos = strrpos($prefix, '\\')) {
            
            // pridaj oddelovač menných priestorov na koniec predpony
            $prefix = substr($class, 0, $pos + 1);

            // zvyšok je časť s menom triedy
            $relative_class = substr($class, $pos + 1);

            // skús načítať namapovaný súbor pre predponu a časť mena triedy
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // odstráň oddelovač menného priestora z konca pre daľší cyklus
            // a pre funkciu strrpos()
            $prefix = rtrim($prefix, '\\');   
        }
        
        // nenašiel sa namapovaný súbor
        return false;
    }
    
    /**
     * Načítaj namapovaný súbor pre predponu menného priesotru a časti s menom triedy
     * 
     * @param string $prefix Predpona menného priestoru
     * @param string $relative_class časť s menom triedy
     * @return mixed Binárne false ak namapovaný súbor nebol načítaný alebo meno 
     * namapovaného súboru ktoré sa načítalo.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        // sú tam nejaké adresáre pre predponu tohto menného priestoru?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }
            
        // Pozri do koreňových adresárov pre túto predponu menného priestoru
        foreach ($this->prefixes[$prefix] as $base_dir) {

            // nahraď predponu menného priestoru s koreňovým adresárom,
            // nahraď oddeľovač menných priestorov s oddeľovačom adresárov
            // v časti s menom triedy a pripoj .php
            $file = $base_dir
                  . str_replace('\\', '/', $relative_class)
                  . '.php';

            // Ak namapovaný súbor existuje tak ho načítaj
            if ($this->requireFile($file)) {
                // áno, skončili sme
                return $file;
            }
        }
        
        // nikde sa nenašlo
        return false;
    }
    
    /**
     * Ak súbor existuje, tak ho načítaj zo súboru
     * 
     * @param string $file Súbor na načítanie.
     * @return bool Binárne True ak súbor existuje, false ak nie.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
```

### Jednotkové testovanie

Nasledujúci príklad je jedna z možností ako testovať 
vyššie uvedený načítávač tried:

```php
<?php
namespace Example\Tests;

class MockPsr4AutoloaderClass extends Psr4AutoloaderClass
{
    protected $files = array();

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    protected function requireFile($file)
    {
        return in_array($file, $this->files);
    }
}

class Psr4AutoloaderClassTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    protected function setUp()
    {
        $this->loader = new MockPsr4AutoloaderClass;
    
        $this->loader->setFiles(array(
            '/vendor/foo.bar/src/ClassName.php',
            '/vendor/foo.bar/src/DoomClassName.php',
            '/vendor/foo.bar/tests/ClassNameTest.php',
            '/vendor/foo.bardoom/src/ClassName.php',
            '/vendor/foo.bar.baz.dib/src/ClassName.php',
            '/vendor/foo.bar.baz.dib.zim.gir/src/ClassName.php',
        ));
        
        $this->loader->addNamespace(
            'Foo\Bar',
            '/vendor/foo.bar/src'
        );
        
        $this->loader->addNamespace(
            'Foo\Bar',
            '/vendor/foo.bar/tests'
        );
        
        $this->loader->addNamespace(
            'Foo\BarDoom',
            '/vendor/foo.bardoom/src'
        );
        
        $this->loader->addNamespace(
            'Foo\Bar\Baz\Dib',
            '/vendor/foo.bar.baz.dib/src'
        );
        
        $this->loader->addNamespace(
            'Foo\Bar\Baz\Dib\Zim\Gir',
            '/vendor/foo.bar.baz.dib.zim.gir/src'
        );
    }

    public function testExistingFile()
    {
        $actual = $this->loader->loadClass('Foo\Bar\ClassName');
        $expect = '/vendor/foo.bar/src/ClassName.php';
        $this->assertSame($expect, $actual);
        
        $actual = $this->loader->loadClass('Foo\Bar\ClassNameTest');
        $expect = '/vendor/foo.bar/tests/ClassNameTest.php';
        $this->assertSame($expect, $actual);
    }
    
    public function testMissingFile()
    {
        $actual = $this->loader->loadClass('No_Vendor\No_Package\NoClass');
        $this->assertFalse($actual);
    }
    
    public function testDeepFile()
    {
        $actual = $this->loader->loadClass('Foo\Bar\Baz\Dib\Zim\Gir\ClassName');
        $expect = '/vendor/foo.bar.baz.dib.zim.gir/src/ClassName.php';
        $this->assertSame($expect, $actual);
    }
    
    public function testConfusion()
    {
        $actual = $this->loader->loadClass('Foo\Bar\DoomClassName');
        $expect = '/vendor/foo.bar/src/DoomClassName.php';
        $this->assertSame($expect, $actual);
        
        $actual = $this->loader->loadClass('Foo\BarDoom\ClassName');
        $expect = '/vendor/foo.bardoom/src/ClassName.php';
        $this->assertSame($expect, $actual);
    }
}
