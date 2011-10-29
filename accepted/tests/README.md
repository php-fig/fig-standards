=============================
PSR-0 Compatibility Testsuite
=============================

Purpose
=======
The PSR-0 compatibility test suite shall help you to easily and fast verify the compatability of your project 
directory and namespace structure to the [PSR-0 standard][1].


Installation
============
Getting the sources
-------------------
The installation is pretty simple just clone the sources to your favorite directory. 
This might be the test directory of your project.

    $> cd $PROJECT_HOME
    $> git clone git://github.com/lapistano/fig-standards.git tests/fig-standards

Due to the architecture of the test suite there is a dependency to a DirectoryScanner (see section Dependencies).
If you already installed the *DirectoryScanner* from the [*pear.netpirates.net*][2] PEAR channel you just have to
make sure your project does find it on demand. 
Otherwise change just run *install.sh* in the *accepted/tests* directory and the test suite will take care of this.

    $> /bin/sh install.sh
    
This clones the *DirectoryScanner* from github so a functional internet connection is mandatory for tihs action.
After you ran *install.sh* you'll find the sources in the newly created *accepted/tests/vendor* directory.

Setting up
----------
Once the sources are on your machine, you now have to tell PHPunit to run the suite. Therefore an example 
phpunit.xml.dist file is shipped with the sources. The following list is the set of mandatory and optional settings 
to configure the *DirectoryScanner*. Modify them to meet the requirements of your project.

* (mandatory) __Psr0_ProjectRootNamespace__
  is the root namespace of your project (e.g. Liip)..

* (mandatory) __Psr0_ScannerStartDir__
  represents the absolute or relative path to your source files (e.g. ../src/).

* (optional) __Psr0_ScannerInclude__
  is a colon separated set of patterns which directories/files are to be recognized when scanning.

* (optional) __Psr0_ScannerExclude__
  is a colon separated set of patterns which directories/files are to be ignored when scanning.
  You usually want to exclude the directories containing the tests and 3rd party source probably located in a
  vendor directory. This combines into the string shown in the floowing example:
    
      */vendor/*:*/Test/*
  
See [*DirectoryScanner*][3] on GitHub for further information.
  
To make PHPUnit aware of the PSR-0 Compataibility Testsuite you have to add the *Psr0_CompatibilityTest.php* to the 
*\<testsuite\>* section of your phpunit configuration. The examle assumes that the test file is in the original location 
after a checkout described as above.

    …
    <file>../tests/fig-standards/accepted/tests/Psr0_CompatibilityTest.php</file>
    …

Dependencies
============
Since the PSR-0 compatibility tests depend on a static code analysis it is necessary to scan each file for its 
class and namespace name. Arne Blankerts [*DirectoryScanner*][3] was the perfect library for this. Kudos to him.

Links
=====
[1]: http://groups.google.com/group/php-standards
[2]: http://pear.netpirates.net
[3]: https://github.com/theseer/DirectoryScanner