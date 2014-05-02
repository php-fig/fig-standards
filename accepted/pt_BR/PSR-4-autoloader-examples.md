Implementações exemplo do PSR-4
===============================

Os exemplos a seguir ilustram códigos compatíveis com o PSR-4:

Exemplo de Closure
------------------

```php
<?php
/**
 * Um exemplo de uma implementação de um projeto específico.
 * 
 * Após registrar esta função de carregamento automático com SPL, a seguinte linha
 * faria a função tentar carregar a classe \Foo\Bar\Baz\Qux
 * de /path/to/project/src/Baz/Qux.php:
 * 
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class O nome de classe totalmente qualificado.
 * @return void
 * /
spl_autoload_register(function ($class) {
    
    // prefixo de namespace específico do projeto
    $prefix = 'Foo\\Bar\\';

    // diretório base para o prefixo de namespace
    $base_dir = __DIR__ . '/src/';
    
    // a classe usa o prefixo de namespace?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // não, passa para o próximo carregador automático registrado
        return;
    }
    
    // busca o nome de classe relativo
    $relative_class = substr($class, $len);
    
    // substitui o prefixo de namespace com o diretório base, substitui os separadores
    // de namespace com os separadores de diretório no nome de classe relativo, acrescentando
    // .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // se o arquivo existir, faz um require dele
    if (file_exists($file)) {
        require $file;
    }
});
```

Exemplo de Classe
-----------------

O exemplo a seguir é de implementação de uma classe para lidar com múltiplos
namespaces:

```php
<?php
namespace Example;

/**
 * Um exemplo de uma implementação de uso geral que inclui a funcionalidade
 * opcional de permitir vários diretórios base para um único prefixo de
 * namespace.
 * 
 * Dado um pacote foo-bar de classes no sistema de arquivos nos seguintes
 * caminhos ...
 * 
 *     /path/to/packages/foo-bar/
 *         src/
 *             Baz.php             # Foo\Bar\Baz
 *             Qux/
 *                 Quux.php        # Foo\Bar\Qux\Quux
 *         tests/
 *             BazTest.php         # Foo\Bar\BazTest
 *             Qux/
 *                 QuuxTest.php    # Foo\Bar\Qux\QuuxTest
 * 
 * ... adicionar o caminho para os arquivos de classes para o prefixo de namespace \Foo\Bar\
 * como segue:
 * 
 *      <?php
 *      // instantiate the loader
 *      $loader = new \Example\Psr4AutoloaderClass;
 *      
 *      // register the autoloader
 *      $loader->register();
 *      
 *      // register the base directories for the namespace prefix
 *      $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/src');
 *      $loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/tests');
 * 
 * A linha a seguir faria o carregador automático tentar carregar a classe
 * \Foo\Bar\Qux\Quux de /path/to/packages/foo-bar/src/Qux/Quux.php:
 * 
 *      <?php
 *      new \Foo\Bar\Qux\Quux;
 * 
 * A linha a seguir faria o carregador automático tentar carregar a classe
 * \Foo\Bar\Qux\QuuxTest de /path/to/packages/foo-bar/tests/Qux/QuuxTest.php:
 * 
 *      <?php
 *      new \Foo\Bar\Qux\QuuxTest;
 */
class Psr4AutoloaderClass
{
    /**
     * Um array associativo onde a chave é um prefixo de namespace e o valor
     * é um array de diretórios base para classes naquele namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Registra carregador com SPL autoloader stack.
     * 
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Adiciona um diretório base para um prefixo de namespace.
     *
     * @param string $prefix O prefixo de namespace.
     * @param string $base_dir Um diretório base para arquivos de classe no
     * namespace.
     * @param bool $prepend Se true, antepor o diretório base no stack
     * em vez de acrescentá-lo; isso faz com que ele seja pesquisado em primeiro lugar, em vez
     * de último.
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // normaliza o prefixo do namespace
        $prefix = trim($prefix, '\\') . '\\';
        
        // normaliza o diretório base com um separador posterior
        $base_dir = rtrim($base_dir, '/') . DIRECTORY_SEPARATOR;
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // inicializa o array de prefixo de namespace
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }
        
        // manter o diretório base para o prefixo de namespace
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    /**
     * Carrega o arquivo de classe para um determinado nome da classe.
     *
     * @param string $class O nome da classe totalmente qualificado.
     * @return mixed O nome do arquivo mapeado em caso de sucesso, ou o boleano falso em caso
     * de falha.
     */
    public function loadClass($class)
    {
        // o prefixo de namespace atual
        $prefix = $class;
        
        // trabalha ao contrário através dos nomes de namespace do nome da classe
        // totalmente qualificado para encontrar um nome de arquivo mapeado
        while (false !== $pos = strrpos($prefix, '\\')) {
            
            // mantêm o separador de namespace posterior no prefixo
            $prefix = substr($class, 0, $pos + 1);

            // o resto é o nome da classe relativo
            $relative_class = substr($class, $pos + 1);

            // tentar carregar um arquivo mapeado para o prefixo e classe relativa
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // remover o separador de namespace posterior para a próxima iteração
            // do strrpos()
            $prefix = rtrim($prefix, '\\');   
        }
        
        // nunca encontrou um arquivo mapeado
        return false;
    }
    
    /**
     * Carrega o arquivo mapeado para um prefixo de namespace e classe relativa.
     * 
     * @param string $prefix O prefixo de namespace.
     * @param string $relative_class O nome de classe relativa.
     * @return mixed Boolean false se nenhum arquivo mapeado pode ser carregado, ou o
     * nome do arquivo mapeado que foi carregado.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        // existem diretórios base para este prefixo de namespace?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }
            
        // procura por este prefixo de namespace através de diretórios base
        foreach ($this->prefixes[$prefix] as $base_dir) {

            // substitui o prefixo de namespace com o diretório base,
            // substitui os separadores de namespace com separadores de diretório
            // no nome de classe relativo, adicionando .php
            $file = $base_dir
                  . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class)
                  . '.php';
            $file = $base_dir
                  . str_replace('\\', '/', $relative_class)
                  . '.php';

            // se o arquivo mapeado existe, faz require dele
            if ($this->requireFile($file)) {
                // sim, terminamos
                return $file;
            }
        }
        
        // nunca encontrei ele
        return false;
    }
    
    /**
     * Se um arquivo existe, faz require dele do sistema de arquivos.
     * 
     * @param string $file O arquivo para require.
     * @return bool True se o arquivo existe, false se não.
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

### Testes unitários

O exemplo a seguir é uma forma de implementação de teste unitário para o carregador de classe acima:

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
